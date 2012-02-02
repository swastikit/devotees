<?php
class Rgm_UserServices{
    protected $_roleId;
    protected $_userId;
    public function __construct(){
        $auth = Zend_Auth::getInstance();
        $this->_userid = 0; //System user
        $this->_roleId='Hare Krishna';
        if ($auth->hasIdentity()) {
            $authArray=$auth->getIdentity();
            $this->_userid = $authArray['user_id'];
            $this->_roleId = $authArray['role_id'];
        }
    }
    
    /*
    ------------paramenters-------------
    $message    = String
    $variables  = objects Devotee, Registrations etc.
    */
    public function log($entityId, $entityTable, $message=null, $preChangedData){
        /*
        fields in mst_user_log table
        ----------------------------
        id
        user_id
        entity_table
        entity_id
        module_name
        controller_name
        action_name
        timestamp
        message
        variables
        session_id
        ip
        */
        $auth = Zend_Auth::getInstance();
        $userid = 0; //System user        
        if ($auth->hasIdentity()) {
            $authArray=$auth->getIdentity();
            $userid = $authArray['user_id'];
        }
        $module_name=Zend_Controller_Front::getInstance()->getRequest()->getModuleName();
        $controller_name=Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $action_name=Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        $session_id = Zend_Session::getId();
        $ip = Zend_Controller_Front::getInstance()->getRequest()->getServer('REMOTE_ADDR');
        
        $table = new Application_Model_DbTable_MstUserLog();
        $data = array(
            'user_id'=>$userid,
            'entity_table'=>$entityTable,
            'entity_id'=>$entityId,
            'module_name'=>$module_name,
            'controller_name'=>$controller_name,
            'action_name'=>$action_name,
            'timestamp'=>date('Y-m-d H:i:s'),
            'message'=>$message,
            'variables'=>$preChangedData,
            'session_id'=>$session_id,
            'ip'=>$ip
        );
        $table->insert($data);        
    }
    /*
        Returns a sub query (select did from devotees <condition to retrive the list of devotees for this user>)    
    */
    public function subqueryForDidList($devoteeTableAlias){
        $sql='';
        if($this->_roleId==Rgm_Constants::ROLE_ADMIN || $this->hasAccessToMethod(Application_Model_DbTable_Mstuser::METHOD_CAN_VIEW_LIST_OF_DEVOTEES_ALL)){
            $sql='(1=1)';
            return $sql;
        }else{
			//---------devotees assigned to this user----------------
            $sql = "($devoteeTableAlias.did in (SELECT a_1.did FROM mst_user_vs_devotee AS a_1 where a_1.user_id=$this->_userId))";
              
			//---------centers assigned to this user----------------
            $sql = ($sql==''?'':' OR ') . "($devoteeTableAlias.center_id in (SELECT a_2.center_id FROM mst_user_vs_center AS a_2 where a_2.user_id=$this->_userId))";
            
            //---------guru assigned to this user----------------
            $sql = ($sql==''?'':' OR ') . "($devoteeTableAlias.ini_guru_id in (SELECT a_3.guru_id FROM mst_user_vs_guru AS a_3 where a_3.user_id=$this->_userId) OR $devoteeTableAlias.sanyas_guru_id in (SELECT a_4.guru_id FROM mst_user_vs_guru AS a_4 where a_4.user_id=$this->_userId))";

            //---------counselors assigned to this user----------------
            $sql =  ($sql==''?'':' OR ') . "($devoteeTableAlias.counselor_id in (SELECT a_5.counselor_id FROM mst_user_vs_counselor AS a_5 where a_5.user_id=$this->_userId))";

            //---------Astcounselors assigned to this user----------------
            $sql = ($sql==''?'':' OR ') . "($devoteeTableAlias.ast_counselor_id in (SELECT a_6.ast_counselor_id FROM mst_user_vs_astcounselor AS a_6 where a_6.user_id=$this->_userId))";
            
            return '(' . $sql . ')';
        }
    }
    public function subqueryForDidView(){
        Zend_Debug::dump($this->_roleId);
        
    }
    public function subqueryForDidEdit(){
        Zend_Debug::dump($this->_roleId);
        
    }
    public function hasAccessToMethod($method){
        if($this->_roleId==Rgm_Constants::ROLE_ADMIN){
            return true;
        }
        $user=new Application_Model_DbTable_Mstuser();
        return $user->hasAccessToMethod($method,$this->_userId);
    }
}
?>
