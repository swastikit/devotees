<?php
class Application_Plugin_Aclplugin extends Zend_Controller_Plugin_Abstract {
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $layout = Zend_Controller_Action_HelperBroker::getStaticHelper('Layout');
        $layout->setLayout('layout_main'); //other-layout.phtml
        if($this->isPublic()<1){
            /*---------------------Check The authentication----------------*/
            $auth = Zend_Auth::getInstance();
            if (!$auth->hasIdentity()) {
                $this->getRequest()->setControllerName("index");
                $this->getRequest()->setActionName("login");
                $layout->setLayout('layout_no_sidebar_no_menu');
            }else{
                $authVal=$auth->getIdentity();
                $userId = $authVal['user_id'];
                //Check if the your user password is temporary
                if($this->isTemporaryPwd($userId)){
                    //Only following action are allowed.
                    //1. index/accountsettings
                    //2. index/logout
                    $layout->setLayout('layout_no_sidebar');
                    
                    $this->menu();
                    $controllerName=$this->getRequest()->getControllerName();
                    $actionName=$this->getRequest()->getActionName();
                    if(strcasecmp($controllerName,'index')==0){
                        if(strcasecmp($actionName,'accountsettings')==0 || strcasecmp($actionName,'logout')==0){
                            //Don Nothing
                        }else{
                            $this->getRequest()->setControllerName("index");
                            $this->getRequest()->setActionName("accountsettings");
                        }
                    }else{
                        $this->getRequest()->setControllerName("index");
                        $this->getRequest()->setActionName("accountsettings");
                    }
                }else{
                    //No issue
                    $this->menu();
                    //Check Authorization of the user
                }
            }
        }
    }
    /*
    Check If the user has temporary password.
    i.e the user is login for the first time after creationg of the login
    */
    private function isTemporaryPwd($userId){
        $user=new Application_Model_DbTable_Mstuser();
        return $user->isTemporaryPwd($userId);
    }
    /*
    Check if the url path being accessed is set as public in mst_user_method table.
    */
    private function isPublic(){
        /*---------------------------------*/
        $req=$this->getRequest();
        if($req->getControllerName()=='error') return 1; //Error controller is public
        $urlpath= (($req->getModuleName()=='default')?'' : ('/' . $req->getModuleName())) . '/' . $req->getControllerName() . '/' . $req->getActionName();
        //Get bootstrap
        $bootstrap = Zend_Controller_Front::getInstance()->getParam("bootstrap");
        $db = $bootstrap->getPluginResource('db')->getDbAdapter();
        $select = $db->select();
        $select->from(array('a'=>'mst_user_method'),array('id' => 'a.id', 'url' => 'a.url_path'))
            ->where('a.is_public = ?', 'Y')
            ->where('a.is_active = ?', 'Y')
            ->where('a.url_path = ?', $urlpath);
        $stmt = $select->query();
        $rowcount = $stmt->fetchAll();
        return count($rowcount);
    }
    private function menu() { 
        //Get bootstrap
        $bootstrap = Zend_Controller_Front::getInstance()->getParam("bootstrap");
        $db = $bootstrap->getPluginResource('db')->getDbAdapter();
        $auth = Zend_Auth::getInstance();
        $authArray=$auth->getIdentity();
        $roleid = $authArray['role_id'];
        $userid = $authArray['user_id'];
        
        //Get The Menu Items from Database
        $select = $db->select();
        $select->from(array('sm'=>'mst_user_method'),array('submenu_id' => 'sm.id', 'submenu_name' => 'sm.name', 'submenu_url' => 'sm.url_path'))
               ->joinLeft(array('mm'=>'mst_user_menu_main') , 'sm.menu_id = mm.id', array('mainmenu_id' => 'mm.id','mainmenu_name' => 'mm.name','mainmenu_url' => 'mm.url_path'))
               ->where('sm.show_in_menu = ?', 'Y')
               ->where('sm.is_active = ?', 'Y')
               ->where('mm.is_active = ?', 'Y');
               
        if($roleid!=Rgm_Constants::ROLE_ADMIN){
            $select->where('sm.id IN (?)', new Zend_Db_Expr("select mst_user_method_vs_user.method_id from mst_user_method_vs_user where user_id=" + $userid));
        }
        $select->order('mm.sort_order ASC');
        $select->order('sm.sort_order ASC');
        $stmt = $select->query();
        $menuRows = $stmt->fetchAll();
        $container = new Zend_Navigation();
        
        //---------Get The Active Menu And Sub Menu Item----------------
        $activeIdmm=0;
        $activeIdsm=0;
        $menuIdSession = new Zend_Session_Namespace();
        if(!$this->getRequest()->isXmlHttpRequest()){
            if($this->getRequest()->getParam('mm_id')){//Main menuId
                $activeIdmm=$this->getRequest()->getParam('mm_id');
            }else{
                if(isset($menuIdSession->Active_Main_Menu_id)) {
                    $activeIdmm=$menuIdSession->Active_Main_Menu_id;    
                }
            }
            if($this->getRequest()->getParam('sm_id')){//Main menuId
                $activeIdsm=$this->getRequest()->getParam('sm_id');
            }else{
                if(isset($menuIdSession->Aactive_Sub_Menu_Id)) {
                    $activeIdsm=$menuIdSession->Aactive_Sub_Menu_Id;    
                }
            }
            $menuIdSession->Active_Main_Menu_id=$activeIdmm;      
            $menuIdSession->Aactive_Sub_Menu_Id=$activeIdsm;      
        }
                
        if($menuRows) {
            $tmpMainMenuId=0;
            $tmpMainMenuPage=null;
            $tmpSubMenuPage=null;
            $tmpActive=0;
            foreach ($menuRows as $r) {
                if($tmpMainMenuId!=$r['mainmenu_id']){//New Main Menu Item
                    $tmpMainMenuId=$r["mainmenu_id"];
                    //Add previously created main menu page to container;
                    if($tmpMainMenuPage!=null){
                        $container->addPage($tmpMainMenuPage);
                    }
                    if($r["mainmenu_id"]==$activeIdmm){
                        $tmpActive=1;  
                    }else{
                        $tmpActive=0;
                    }
                    $tmpMainMenuPage = Zend_Navigation_Page::factory(array('id' => 'mm' . $r["mainmenu_id"], 'label'  => $r["mainmenu_name"],'uri' => $r["mainmenu_url"]==null?"#":$r["mainmenu_url"].'?mm_id=' . $r["mainmenu_id"] . '&sm_id=' . $r["submenu_id"],'active' => $tmpActive));
                }
                //Create sub menu
                if($r["submenu_id"]==$activeIdsm){
                    $tmpActive=1;  
                }else{
                    $tmpActive=0;
                }
                $tmpSubMenuPage = Zend_Navigation_Page::factory(array('id' => 'sm' . $r["submenu_id"], 'label'  => $r["submenu_name"],'uri' => $r["submenu_url"]==null?"#":$r["submenu_url"].'?sm_id=' . $r["submenu_id"],'active' => $tmpActive));
                //Add it to mainMenuPage
                $tmpMainMenuPage->addPage($tmpSubMenuPage);
            }
            //Last page
            if($tmpMainMenuPage!=null){
                $container->addPage($tmpMainMenuPage);
            }
        }
        $view = $bootstrap->bootstrap('view')->getResource('view');
        $view->navigation($container);
    }
 }