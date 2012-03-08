<?php
class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        /*
        $logger = Zend_Registry::get('logger');
        $logger->log('we are in index action',Zend_Log::INFO);
        $params = $this->getRequest()->getParams();
        $logger->log('we are in index action ID=' . $params, Zend_Log::INFO);
        $logger->log('query string   ' . $_SERVER['QUERY_STRING'], Zend_Log::INFO);

        $bootstrap = $this->getInvokeArg('bootstrap');
        $resource = $bootstrap->getPluginResource('db');
        $db = $resource->getDbAdapter();
        $select = $db->select()
        ->from(array('d' => 'devotee'),
        array('d.did', 'd.display_name'))
        ->where('d.did=100');
        $stmt = $db->query($select);
        $result = $stmt->fetchAll();            
        
        //$this->view->assign('abc',count($result));

        //-----------Authentication---------------
        $auth = Zend_Auth::getInstance();        
        $authAdapter= new Application_Model_AuthAdapter('rgmdba', 'gopinatha1728');
        $result = $auth->authenticate($authAdapter);
        $this->view->assign('abc','xyz=' . $result->getCode() . '----->' . Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID);
        if(!$result->isValid()) {
        switch ($result->getCode()) {
        case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
        $this->view->errors = 'user credentials not found';
        }
        } else {
        //Successfully logged in
        //$this->_redirect( $redirect );
        } 
        */
        $this->view->assign('title', 'Radha Gopinath Mandir-HOME');
    }

    public function loginAction()
    {
        /*
        $manager = $this->getFrontController()
                    ->getParam('bootstrap')
                    ->getPluginResource('cachemanager')
                    ->getCacheManager();
        //Zend_Debug::dump($manager);            
        $cache = $manager->getCache('database');
        
        //$cache = Zend_Controller_Front::getInstance()->getParam("bootstrap")->getPluginResource('cachemanager')->getCache('database');
        //Zend_Debug::dump($cache);
        $count=0;
        if ($count = $cache->load('count') ){
            $cache->save($count+1, 'count');
        }else{
            $cache->save($count+1, 'count');
        }
        Zend_Debug::dump($count);
        */
        $message="";
        $session = new Zend_Session_Namespace();
        $loginForm = new Application_Form_Login();
        $redirect = $this->getRequest()->getParam('redirect', 'index/index');
        $loginForm->setAttrib('redirect', $redirect);
        $loginForm->setAction('login');
        $loginForm->setMethod('post');
        
        $loginForm->setDecorators(array('FormElements',array('HtmlTag', array('tag' => 'dl', 'class' => 'formUl')),'Form'));
        $this->view->loginForm = $loginForm;
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $this->_redirect('/index/');
        }else{
            if ($this->getRequest()->isPost()) {
                if($this->getRequest()->getParam('source')){ //Redirected from Other Actions
                    return;            
                }
                if ($loginForm->isValid($this->getRequest()->getPost())) {
                    $username = $this->getRequest()->getPost('username');
                    $pwd = $this->getRequest()->getPost('pass');
                    //Check if Block
                    $user=new Application_Model_DbTable_Mstuser();
                    if($user->isBlocked($username)==1){
                        $auth = Zend_Auth::getInstance();
                        $auth->clearIdentity();
                        Zend_Session::destroy(true);
                        $this->view->errors = "Invalid username or password.";
                        return;
                    }
                    //Authenticate now
                    $authAdapter = new Application_Model_AuthAdapter($username, $pwd);
                    $result = $auth->authenticate($authAdapter);
                    if (!$result->isValid()) {
                        //Get how many times user has tried in this session and block if applicable
                        $sysVar = new Application_Model_DbTable_SysVariables(); 
                        $login_try_count_max = $sysVar->get(Rgm_Constants::SYS_VAR_BLOCK_USER_AT_NUMBER_OF_TRY_TO_LOGIN);
                        $login_try_count_max=intval($login_try_count_max);
                        $login_try_count=0;
                        $login_try_login='';
                        if(isset($session->login_try_count)) {
                            $login_try_count=$session->login_try_count;
                        }
                        $login_try_count=intval($login_try_count);
                        if(isset($session->login_try_login)) {
                            $login_try_login=$session->login_try_login;
                        }
                        if($login_try_login==$username){
                            $login_try_count = $login_try_count + 1;
                        }else{
                            $login_try_count=1;
                            $login_try_login=$username;
                        }
                        if($login_try_count >= $login_try_count_max){
                            $remarks='Blocked by system while trying to login more than ' . $login_try_count_max . ' times';
                            if($this->blockAccount($username,$remarks)){
                                Rgm_UserServices::log(0,'mst_user',$remarks . '(' . $username . ')' ,'');
                                unset($session->login_try_count);
                                unset($session->login_try_login);    
                            };
                            unset($session->login_try_count);
                            unset($session->login_try_login);
                            if($login_try_count == $login_try_count_max){
                                $message="Warning:: Your account is blocked. Please contact concerned authorities.";
                            }
                        }else{
                            if($login_try_count>1){
                                $message="Warning:: You have tried " . $login_try_count . " attempts to login. Your account will be blocked after " . ($login_try_count_max - $login_try_count) . " more attempts.";
                            }
                            $session->login_try_count=$login_try_count;
                            $session->login_try_login=$username;    
                        }
                        switch ($result->getCode()) {
                            case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                                $message = 'Error:: User credentials not found' . ($message==''?"":"<br/>") . $message;
                        }
                        $this->view->errors = $message;
                    }else{
                        //Successfully logged in
                        //Clear the login try session variable
                        if(isset($session->login_try_count)) {
                            unset($session->login_try_count);
                            unset($session->login_try_login);
                        }
                        $authArray=$result->getIdentity();
                        $userid = $authArray['user_id'];
                        Rgm_UserServices::log($userid,'mst_user','Loged in by ' . $username,'');
                        $this->_redirect($redirect);
                    }
                }
            }
        }
        
    }

    protected function blockAccount($login, $remarks)
    {
        $u=new Application_Model_DbTable_Mstuser();
        $u->block($login,$remarks);
        return true;
    }

    public function logoutAction()
    {
        $auth = Zend_Auth::getInstance();
        $userid=0;
        $username='';
        if ($auth->hasIdentity()) {
            $authArray=$auth->getIdentity();
            $userid = $authArray['user_id'];
            $username = $authArray['login'];
        }
        Rgm_UserServices::log($userid,'mst_user','Loged out by ' . $username,'');

        $auth->clearIdentity();
        Zend_Session::destroy(true);
        $this->_redirect('/');
    }

    public function forgotpasswordAction()
    {
        $this->getHelper("layout")->setLayout('layout_no_sidebar_no_menu');
        $form = new Application_Form_ForgotPwd();
        $form->setAction('forgotpassword');
        $form->setMethod('post');
        $messages='';
        if($this->getRequest()->getParam('invalid')=='T'){//It is forwarded from forgotpasswordsqAction
            $messages=$this->getRequest()->getParam('messages');    
        }else{
            if ($this->getRequest()->isPost()) {
                $goAhead=false;
                if ($form->isValid($this->getRequest()->getPost())) {
                    $goAhead=true;
                }
                if($goAhead){//process the form
                    //Retrive the user
                    $userId=$this->getRequest()->getPost('userId');
                    $email=$this->getRequest()->getPost('email');
                    
                    $userTbl=new Application_Model_DbTable_Mstuser();
                    if($user=$userTbl->verifyPrimaryInfo($userId,$email)){//
                        $session = new Zend_Session_Namespace();
                        $session->forgot_pwd_login = $user->login;
                        $this->_forward('forgotpasswordsq',null,null,array('source'=>'forgotpassword'));
                    }else{
                        $messages='Information entered are not correct';
                    }
                }else{
                    $messages='Information entered are not correct';
                }
            }
        }
        $this->view->messages=$messages;
        $this->view->form = $form;
    }

    public function forgotpasswordsqAction()
    {
        $this->getHelper("layout")->setLayout('layout_no_sidebar_no_menu');
        //Page is comming from links
        if(!$this->getRequest()->isPost()){
            $messages='Access denied.';
            $this->_forward('error','error','default',array('message'=>$messages));
            return;
        }
        $session = new Zend_Session_Namespace();
        //Session Expired redirect to login page
        if(!isset($session) || !isset($session->forgot_pwd_login)) {
            $this->_forward('login',null,null,array('source'=>'forgotpasswordsq'));
            return;
        }
        $login = $session->forgot_pwd_login;
        
        $userTbl=new Application_Model_DbTable_Mstuser();
        $user=$userTbl->findByLogin($login);
        if($user){
            //Check if the user had set all the security question. If not 'Ask to contact administrator'
            if(!isset($user->security_question01,$user->security_question02,$user->security_question03)){
                $messages='Security questions are not properly set. Contact administrator.';
                unset($this->view->form);
                $this->view->messages=$messages;
                unset($session->forgot_pwd_login);
                return;
            }
            //-----------Contruct the form----------------
            $form = new Application_Form_ForgotPwdSq(
                array(
                    'Q1' =>'1. ' . $user->security_question01,
                    'Q2' =>'2. ' . $user->security_question02
                )
            );
            $form->setAction('forgotpasswordsq');
            $form->setMethod('post');
            $form->setDecorators(array('FormElements',array('HtmlTag', array('tag' => 'dl', 'class' => 'formUl')),'Form'));
            $this->view->form = $form;
            /*
            It is forwarded from forgotpasswordAction dispatch it now
            */
            if($this->getRequest()->getParam('source')=='forgotpassword'){
                return;            
            }
            /*
            It is comming from client browser, process the form
            */
            if ($form->isValid($this->getRequest()->getPost())) {
                //Retrive the Security Answers
                $ans1=$this->getRequest()->getPost('securityAns01');
                $ans2=$this->getRequest()->getPost('securityAns02');

                //If answers are correct forward to resetpwd form                
                if(strcasecmp($user->security_answer01,$ans1)==0 && strcasecmp($user->security_answer02,$ans2)==0){
                    $this->_forward('forgotpasswordre',null,null,array('source'=>'forgotpasswordsq'));
                    return;
                }
                $messages='Information entered are not correct';
                $this->view->messages=$messages;
            }
        }else{
            $this->_forward('error','error','default',null);
            unset($session->forgot_pwd_login);
        }
    }

    public function forgotpasswordreAction()
    {
        $this->getHelper("layout")->setLayout('layout_no_sidebar_no_menu');
        //Page is comming from links
        if(!$this->getRequest()->isPost()){
            $messages='Access denied.';
            $this->_forward('error','error','default',array('message'=>$messages));
            return;
        }
        $session = new Zend_Session_Namespace();
        //Session Expired redirect to login page
        if(!isset($session) || !isset($session->forgot_pwd_login)) {
            $this->_forward('login',null,null,array('source'=>'forgotpasswordre'));
            return;
        }
        $login = $session->forgot_pwd_login;
        $form = new Application_Form_ForgotPwdRe();
        $form->setAction('forgotpasswordre');
        $form->setMethod('post');
        $form->setDecorators(array('FormElements',array('HtmlTag', array('tag' => 'dl', 'class' => 'formUl')),'Form'));
        $this->view->form = $form;
        /*
        It is forwarded from forgotpasswordsqAction dispatch it now
        */
        if($this->getRequest()->getParam('source')=='forgotpasswordsq'){
            return;            
        }
        //Validate the form now
        if (!$form->isValid($this->getRequest()->getPost()))return;
            $newPassWord = $this->getRequest()->getPost('newPassWord');
            $newPassWordRe = $this->getRequest()->getPost('newPassWordRe');
        if(!($newPassWord==$newPassWordRe)){
            $form->getElement('newPassWordRe')->addError('Password does not match');
            return;
        }
        
        //Reset the password now
        /*
        Check if the user exists
        */
        $userTbl=new Application_Model_DbTable_Mstuser();
        $user=$userTbl->findByLogin($login);
        if(!$user){
            $messages='Error occured.';
            $this->_forward('error','error','default',array('message'=>$messages));
            return;
        }
        $userId=$user->id;
        $reset = $userTbl->resetPassword($login,$newPassWord);
        if($reset['result']=='ok'){
            $messages='Successfully reset the password.';
            $this->view->messages=$messages;
            $this->_helper->viewRenderer('forgotpasswordrt');
            
            //Log
            Rgm_UserServices::log($userId,'mst_user','Successfully reset password through forgot password method for user id = ' . $login,'');
            unset($session->forgot_pwd_login);
        }else{
            $messages='Error occured while restting the password. Please try again.';
            $this->view->messages=$messages;
            $this->_helper->viewRenderer('forgotpasswordrt');
            
            //Log
            Rgm_UserServices::log($userId,'mst_user','Failed while tryed to reset password through forgot password method for user id = ' . $login,'');
            unset($session->forgot_pwd_login);
        }
    }

    public function accountsettingsAction()
    {
        $this->getHelper("layout")->setLayout('layout_no_sidebar');
        $messages='';
        $form = new Application_Form_AccountSetings();
        $form->setAction('accountsettings');
        $form->setMethod('post');
        $this->view->form = $form;

        //Save button pushed in accountsetting page        
        if($this->getRequest()->isPost() && $this->getRequest()->getPost('pageId')=='accountsetings'){
            $valid=true;            
            //Validate the form
            if (!$form->isValid($this->getRequest()->getPost()))return;
            //Validate the Security Question
            $country=$this->getRequest()->getPost('country');
            if($country==0 || $country == ''){
                $form->getElement('country')->addError('Select country');
                $valid=false;
            }
            $securityQ01=$this->getRequest()->getPost('securityQ01');
            if($securityQ01==0 || $securityQ01 == ''){
                $form->getElement('securityQ01')->addError('Select a security question');
                $valid=false;
            }
            $securityQ02=$this->getRequest()->getPost('securityQ02');
            if($securityQ02==0 || $securityQ02 == ''){
                $form->getElement('securityQ02')->addError('Select a security question');
                $valid=false;
            }
            if($securityQ01!=0 && $securityQ01 != '' && $securityQ01==$securityQ02){
                $form->getElement('securityQ01')->addError('Both security questions are identical. Select different one');
                $form->getElement('securityQ02')->addError('Both security questions are identical. Select different one');
                $valid=false;
            }
            if(!$valid) return;
            
            //Update the useraccount now
            $fName=$this->getRequest()->getPost('fName');
            $mName=$this->getRequest()->getPost('mName');
            $lName=$this->getRequest()->getPost('lName');
            $email=$this->getRequest()->getPost('email');
            $mobile=$this->getRequest()->getPost('mobile');

            $userName=$this->getRequest()->getPost('userName');
            $securityA01=$this->getRequest()->getPost('securityA01');
            $securityA02=$this->getRequest()->getPost('securityA02');
            
            $auth = Zend_Auth::getInstance();
            $authArray=$auth->getIdentity();
            $userid = $authArray['user_id'];
            $mstUser=new Application_Model_DbTable_Mstuser();
            $prevData = Zend_Json::encode($mstUser->findById($userid));
            $data=array(
                'fName'=>$fName,
                'mName'=>$mName,
                'lName'=>$lName,
                'email'=>$email,
                'country_id'=>$country,

                'login'=>$userName,
                'mobile'=>$mobile,
                'security_question_id01'=>$securityQ01,
                'security_answer01'=>$securityA01,
                'security_question_id02'=>$securityQ02,
                'security_answer02'=>$securityA02
            );
            $result = $mstUser->updatePreliminaryInfo($userid,$data);
            if($result){
                if($result['result']=='ok'){
                    $messages='Changes saved successfully.';
                }else{
                    if($result['result']=='DUPLICATE_LOGIN'){
                        $messages='User id already exists in the database.';
                        $form->getElement('userName')->addError($messages);    
                    }else{
                        $messages='Error occured while saveing the changes.';
                    }
                }
            }
            Rgm_UserServices::log($userid,'mst_user','Preliminary Information Changed',$prevData);
        }else{ //Populate the form with default values
            $auth = Zend_Auth::getInstance();
            $authArray=$auth->getIdentity();
            $userid = $authArray['user_id'];
            $mstUser = new Application_Model_DbTable_Mstuser();
            if($user=$mstUser->findById($userid)){
                $form->getElement('fName')->setValue($user->first_name);
                $form->getElement('mName')->setValue($user->middle_name);
                $form->getElement('lName')->setValue($user->last_name);
                $form->getElement('userName')->setValue($user->login);
                $form->getElement('email')->setValue($user->email);
                $form->getElement('country')->setValue($user->country_id);
                $form->getElement('mobile')->setValue($user->mobile);
                $form->getElement('securityQ01')->setValue($user->security_question_id01);
                $form->getElement('securityA01')->setValue($user->security_answer01);
                $form->getElement('securityQ02')->setValue($user->security_question_id02);
                $form->getElement('securityA02')->setValue($user->security_answer02);
            };
        }
        $this->view->messages=$messages;
    }
}








