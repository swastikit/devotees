<?php

class Admin_UserController extends Zend_Controller_Action
{

    public function init()
    {
        /*
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('view', 'html')
                    ->addActionContext('editbasic', 'html')
                    ->addActionContext('save', 'json')
                    ->initContext();
        */
    }

    public function indexAction()
    {
        $user=new Application_Model_DbTable_Mstuser();
        $user->listUsers(10,1);

        $page = $this->_getParam('page', 1);
        $PAGE_SIZE = 10;
        $offset = ($page - 1) * $PAGE_SIZE;
        //---------------Paging---------------
        $ids = $user->listUsersIds(array());
        $paginator = Zend_Paginator::factory($ids);
        $paginator->setItemCountPerPage($PAGE_SIZE);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;

        $userList = $user->listUsers($PAGE_SIZE, $offset,array());
        $this->view->userList = $userList;

        $num_rec_found = count($ids);
        $this->view->number_of_records_found = $num_rec_found;
    }

    public function viewAction()
    {
        $id = $this->_getParam('id', 0);
        $u=new Application_Model_DbTable_Mstuser();
        $this->view->id=$id;
        $this->view->u=$u->getBasicInfo($id);
        //ALL, CHECKED, UNCHECKED
        $this->view->dbList=$u->getDbList($id,'CHECKED');
        $this->view->centerList=$u->getCenterList($id,'CHECKED');
        $this->view->actionList=$u->getActionList($id,'CHECKED');
        $this->view->mentorList=$u->getMentorList($id,'CHECKED');
        $this->view->counselorList=$u->getCounselorList($id,'CHECKED');
        $this->view->guruList=$u->getGuruList($id,'CHECKED');
    }
    public function editmoreinfoAction()
    {
        //info=ACCESSES;MENTORS;GURUS;DBS;COUNSELORS;CENTERS;
        $id = $this->_getParam('id');
        $info = $this->_getParam('info');
        $result = new stdClass;
        $result->info = $info;
        $this->view->info=$info;
        $message='';
        $htmlStringBg='';
        $error='';
        if(!$id || !$info){
            $error='Invalid request';
            //*********************************************************************
            return 1;
        }
        $this->view->id=$id;
        $u=new Application_Model_DbTable_Mstuser();
        $htmlString='';
        /*
        *********************ACTION ASSIGNED TO THE USER*****************************
        */
        if($info=='ACCESSES'){
            $do = $this->_getParam('do');
            if($do==null){ //Display the edit actions page
                $list=$u->getActionList($id,'ALL');
                $this->view->list=$list;
                $menuTbl=new Application_Model_DbTable_MstUserMenuMain();
                $lstModules=$menuTbl->getKeyValues();
                $lstModules = array(0=>'All') + $lstModules;
                $this->view->lstModules=$lstModules;
                $htmlString = $this->view->render('user/editaccesses.phtml');
            }else if($do=='SAVE'){
                $jSelectedIds=$this->_getParam('jSelectedIds');
                $actionIds=Zend_Json::decode($jSelectedIds);
                if($actionIds != null){
                    $userModel=new Admin_Model_User();
                    $message=$userModel->updateAceessList($id, $actionIds);
                    if($message==''){//Successfull
                        $message='Successfully updated.';   
                        //Refresh the background page
                        $this->view->actionList=$u->getActionList($id,'CHECKED');
                        $htmlStringBg = $this->view->render('user/viewaccesses.ajax.phtml');
                    }
                }else{
                    $message='Error:: list of selected action found blank.';
                }
            }
        /**********************CENTER ASSIGNED TO THE USER******************************/
        }else if($info=='CENTERS'){
            $do = $this->_getParam('do');
            if($do==null){ //Display the edit actions page
                $list=$u->getCenterList($id,'ALL');
                $this->view->list=$list;
                $countryTbl=new Application_Model_DbTable_MstCountry();
                $lstCountry=$countryTbl->getKeyValues();
                $lstCountry = array(0=>'All') + $lstCountry;
                $this->view->lstCountries=$lstCountry;
                $htmlString = $this->view->render('user/editcenters.phtml');
            }else if($do=='SAVE'){
                $jSelectedIds=$this->_getParam('jSelectedIds');
                $centerIds=Zend_Json::decode($jSelectedIds);
                if($centerIds != null){
                    $userModel=new Admin_Model_User();
                    $message=$userModel->updateCenterList($id, $centerIds);
                    if($message==''){//Successfull
                        $message='Successfully updated.';   
                        //Refresh the background page
                        $this->view->centerList=$u->getCenterList($id,'CHECKED');
                        $htmlStringBg = $this->view->render('user/viewcenters.ajax.phtml');
                    }
                }else{
                    $message='Error:: list of selected centers found blank.';
                }
            }
        /**********************COUNSELORS ASSIGNED TO THE USER******************************/
        }else if($info=='COUNSELORS'){
            $do = $this->_getParam('do');
            if($do==null){ //Display the edit actions page
                $list=$u->getCounselorList($id,'ALL');
                $this->view->list=$list;
                $centerTbl=new Application_Model_DbTable_MstCenter();
                $lstCenter=$centerTbl->getKeyValues('counselor');
                $lstCenter = array(0=>'All') + $lstCenter;
                $this->view->lstCenters=$lstCenter;
                $htmlString = $this->view->render('user/editcounselors.phtml');
            }else if($do=='SAVE'){
                $jSelectedIds=$this->_getParam('jSelectedIds');
                $counselorIds=Zend_Json::decode($jSelectedIds);
                if($counselorIds != null){
                    $userModel=new Admin_Model_User();
                    $message=$userModel->updateCounselorList($id, $counselorIds);
                    if($message==''){//Successfull
                        $message='Successfully updated.';   
                        //Refresh the background page
                        $this->view->counselorList=$u->getCounselorList($id,'CHECKED');
                        $htmlStringBg = $this->view->render('user/viewcounselors.ajax.phtml');
                    }
                }else{
                    $message='Error:: list of selected counselor found blank.';
                }
            }
        /**********************MENTORS ASSIGNED TO THE USER******************************/
        }else if($info=='MENTORS'){
            $do = $this->_getParam('do');
            if($do==null){ //Display the edit MENTORS page
                $list=$u->getMentorList($id,'ALL');
                $this->view->list=$list;
                $centerTbl=new Application_Model_DbTable_MstCenter();
                $lstCenter=$centerTbl->getKeyValues('mentor');
                $lstCenter = array(0=>'All') + $lstCenter;
                $this->view->lstCenters=$lstCenter;
                $htmlString = $this->view->render('user/editmentors.phtml');
            }else if($do=='SAVE'){
                $jSelectedIds=$this->_getParam('jSelectedIds');
                $mentorIds=Zend_Json::decode($jSelectedIds);
                if($mentorIds != null){
                    $userModel=new Admin_Model_User();
                    $message=$userModel->updateMentorList($id, $mentorIds);
                    if($message==''){//Successfull
                        $message='Successfully updated.';   
                        //Refresh the background page
                        $this->view->mentorList=$u->getMentorList($id,'CHECKED');
                        $htmlStringBg = $this->view->render('user/viewmentors.ajax.phtml');
                    }
                }else{
                    $message='Error:: list of selected mentor found blank.';
                }
            }
        /**********************GURUS ASSIGNED TO THE USER******************************/
        }else if($info=='GURUS'){
            $do = $this->_getParam('do');
            if($do==null){ //Display the edit GURUS page
                $list=$u->getGuruList($id,'ALL');
                $this->view->list=$list;
                $htmlString = $this->view->render('user/editgurus.phtml');
            }else if($do=='SAVE'){
                $jSelectedIds=$this->_getParam('jSelectedIds');
                $guruIds=Zend_Json::decode($jSelectedIds);
                if($guruIds != null){
                    $userModel=new Admin_Model_User();
                    $message=$userModel->updateGuruList($id, $guruIds);
                    if($message==''){//Successfull
                        $message='Successfully updated.';   
                        //Refresh the background page
                        $this->view->guruList=$u->getGuruList($id,'CHECKED');
                        $htmlStringBg = $this->view->render('user/viewgurus.ajax.phtml');
                    }
                }else{
                    $message='Error:: list of selected spiritual master found blank.';
                }
            }
        /**********************Dbs ASSIGNED TO THE USER******************************/
        }else if($info=='DBS'){
            $do = $this->_getParam('do');
            if($do==null){ //Display the edit DB page
                $list=$u->getDbList($id,'ALL');
                $this->view->list=$list;
                $htmlString = $this->view->render('user/editdbs.phtml');
            }else if($do=='SAVE'){
                $jSelectedIds=$this->_getParam('jSelectedIds');
                $dbIds=Zend_Json::decode($jSelectedIds);
                if($dbIds != null){
                    $userModel=new Admin_Model_User();
                    $message=$userModel->updateDbList($id, $dbIds);
                    if($message==''){//Successfull
                        $message='Successfully updated.';   
                        //Refresh the background page
                        $this->view->dbList=$u->getDbList($id,'CHECKED');
                        $htmlStringBg = $this->view->render('user/viewdbs.ajax.phtml');
                    }
                }else{
                    $message='Error:: list of selected database found blank.';
                }
            }
        /**********************DEVOTEES ASSIGNED TO THE USER******************************/
        }else if($info=='DEVOTEES'){
            $do = $this->_getParam('do');
            if($do==null){ //Display the edit UserVsDevotee page
                $list=$u->getDevoteeList($id,'CHECKED');
                $this->view->list=$list;
                $htmlString = $this->view->render('user/editdevotees.phtml');
            }else if($do=='SAVE'){
                $jSelectedIds=$this->_getParam('jSelectedIds');
                $DIDs=Zend_Json::decode($jSelectedIds);
                if($DIDs != null){
                    $userModel=new Admin_Model_User();
                    $message=$userModel->updateDevoteesList($id, $DIDs);
                    if($message==''){//Successfull
                        $message='Successfully updated.';   
                        //Refresh the background page
                        $this->view->devoteeList=$u->getDevoteeList($id,'CHECKED');
                        $htmlStringBg = $this->view->render('user/viewdevotee.ajax.phtml');
                    }
                }else{
                    $message='Error:: list of selected database found blank.';
                }
            }
        }
        $result->message = $message;
        $result->htmlBody = $htmlString;
        $result->htmlBodyBg = $htmlStringBg;
        $this->_helper->json($result);
    }
    public function editbasicAction()
    {
        $do='editbasic';
        $message='';
        if($this->getRequest()->getParam('do')){
            $do=$this->getRequest()->getParam('do');
        }
        $id = $this->_getParam('id', 0);
        $u=new Application_Model_DbTable_Mstuser();
        $user=$u->getBasicInfo($id);
        $this->view->u=$user;
        $this->view->id=$id;
        $form=new Admin_Form_User_EditBasic();
        $form->setName('edituserbasic');
        $htmlStringBg='';
        if($do=='editbasic'){
            $form->getElement('id')->setValue($id);
            $form->getElement('userId')->setValue($user['login']);
            $form->getElement('role')->setValue($user['role_id']);
            $form->getElement('email')->setValue($user['email']);
            $form->getElement('country')->setValue($user['country_id']);
            $form->getElement('mobile')->setValue($user['mobile']);
            $form->getElement('active')->setValue($user['is_active']);
            $form->getElement('temporaryPwd')->setValue($user['is_temporary_pwd']);
            $form->getElement('blocked')->setValue($user['is_blocked']);
            $form->getElement('blockedReason')->setValue($user['blocked_reason']);
            
            $form->getElement('remarks')->setValue($user['remarks']);
        }else if($do=='savebasic'){
            if($form->isValid($this->getRequest()->getPost())){
                /*Save the info*/
                $data = array(
                    'login'     =>  $this->getRequest()->getPost('userId'),
                    'role_id'   =>  $this->getRequest()->getPost('role'),
                    'email'     =>  $this->getRequest()->getPost('email'),
                    'mobile'    =>  $this->getRequest()->getPost('mobile'),
                    'is_temporary_pwd'    =>  $this->getRequest()->getPost('temporaryPwd'),
                    'country_id'    =>  $this->getRequest()->getPost('country'),
                    'remarks'    =>  $this->getRequest()->getPost('remarks'),
                    'is_active'    =>  $this->getRequest()->getPost('active'),
                    'is_blocked'    =>  $this->getRequest()->getPost('blocked'),
                    'blocked_reason'    =>  $this->getRequest()->getPost('blockedReason')
                );
                $id = $this->getRequest()->getParam('id');
                $userModel=new Admin_Model_User();
                $message=$userModel->updateBasicInfo($id, $data);
                if($message==''){//Successfull
                    $message='Successfully updated.';
                    $user=$u->getBasicInfo($id);   
                    //Refresh the background page
                    $this->view->u=$user;
                    $htmlStringBg = $this->view->render('user/viewbasic.ajax.phtml');
                }
            }
        }
        $this->view->form=$form;
        $htmlString = $this->view->render('user/editbasic.phtml');
        $rs = new stdClass;
        $rs->do = $do;
        $rs->debug = $this->getRequest()->getPost();
        $rs->message = $message;
        $rs->htmlBody = $htmlString;
        $rs->htmlBodyBg = $htmlStringBg;
        $this->_helper->json($rs);
    }
    /*
    send the list of devotees assigned to a user.
    param receives 
    uid = user id
    search=text to be searched.
    jassigned=json string containing the dids already assigned but not saved. (to exclued in the assingable devotees list)
    jdeleted=json string containging the dids already deleted but not saved. (to include in the assignable devotees list)
    */
    public function devoteelistAction(){
        $search=$this->getRequest()->getParam('s');
        $dev=new Application_Model_DbTable_Devotee();
        $rs = new stdClass;
        $rs->result = $dev->listIndentificationInfo($search);
        $this->_helper->json($rs);
    }
    
}
