<?php
class Admin_Model_User extends Zend_Db_Table_Abstract
{
    //Update the basic info of user
    /*
    $data = array(
        'login'     =>  'x',
        'role_id'   =>  'x',        --Compare with old one and if different whole action list to be changed.
        'email'     =>  'x',        --Same to be updated in the devotee table
        'mobile'    =>  'x',        --Same to be updated in the devotee table
        'is_temporary_pwd'    =>  'x',
        'country_id'    =>  'x',
        'remarks'    =>  'x',
        'is_active'    =>  'x',
        'is_blocked'    =>  'x',
        'blocked_reason'    =>  'x',--Derived (applicable if is_blocked = true)
        'blocked_date'    =>  'x',  --Derived (applicable if is_blocked = true)
        
    );
    
    1. get row from mstuser with id=id
    2. 
    */
    public function updateBasicInfo($id,$data){
        $mstuser = new Application_Model_DbTable_Mstuser();
        $uRowSet = $mstuser->find($id);
        $return="";
        if($uRowSet){
            //Get bootstrap
            $bootstrap = Zend_Controller_Front::getInstance()->getParam("bootstrap");
            $db = $bootstrap->getPluginResource('db')->getDbAdapter();
            $db->beginTransaction();
            $mstUser=new Application_Model_DbTable_Mstuser();
            $prevUser=$mstUser->getBasicInfo($id);
            $logObj = Zend_Json::encode($prevUser);
            try {
                $uRow = $uRowSet->getRow(0);
                $uRow->login=$data['login'];
                $uRow->role_id=$data['role_id'];
                $uRow->is_temporary_pwd=$data['is_temporary_pwd'];
                $uRow->remarks=$data['remarks'];
                $uRow->is_active=$data['is_active'];
                $uRow->is_blocked=$data['is_blocked'];
                $uRow->blocked_reason=$data['blocked_reason'];
                if($data['is_blocked']=='Y'){
                    $uRow->blocked_date=date('Y-m-d H:i:s');
                }
                $uRow->dolm = date('Y-m-d H:i:s');
                $auth = Zend_Auth::getInstance();
                if($auth->hasIdentity()){
                    $authArray=$auth->getIdentity();
                    $userid = $authArray['user_id'];
                    $uRow->modi_by_uid = $userid;
                }
                $uRow->save();
                //--------------Update The Devotee-----------------
                $d=new Application_Model_DbTable_Devotee();
                $did=$uRow->did;
                $dRowSet= $d->find($did);
                if($dRowSet){
                    $dRow=$dRowSet->getRow(0);
                    $dRow->email=$data['email'];
                    $dRow->mobile=$data['mobile'];
                    $dRow->country_id=$data['country_id'];
                    $dRow->dolm = date('Y-m-d H:i:s');
                    $auth = Zend_Auth::getInstance();
                    if($auth->hasIdentity()){
                        $authArray=$auth->getIdentity();
                        $userid = $authArray['user_id'];
                        $dRow->modibyuid = $userid;
                    }
                    $dRow->save();
                }
                
                //--------------------UPDATE THE RESOURCES------------------------
                //Check If the Role Has been changed and if changed reassign the resources as per role.
                if($prevUser['role_id'] != $data['role_id']){
                    //MstUserVsDatabase.php
                    //-Delete Database-------------
                    $uVsDb=new Application_Model_DbTable_MstUserVsDatabase();
                    $where = $uVsDb->getAdapter()->quoteInto('user_id = ?', $id);
                    $uVsDb->delete($where);
                    
                    //---------------------------------------------------------------
                    //MstUserMethodVsUser.php
                    //-Delete prev methods
                    $uVsMethod=new Application_Model_DbTable_MstUserMethodVsUser();
                    $where = $uVsMethod->getAdapter()->quoteInto('user_id = ?', $id);
                    $uVsMethod->delete($where);

                    //insert new actions from MstUserRoleVsMethod.php
                    $rVsMethod=new Application_Model_DbTable_MstUserRoleVsMethod();
                    $select = $rVsMethod->select();
                    $select->from($rVsMethod, array('method_id'))
                           ->where('role_id = ?', $data['role_id']);
                    $rows = $rVsMethod->fetchAll($select);
                    foreach($rows as $r){
                        $data = array(
                            'user_id'      => $id,
                            'method_id' => $r->method_id
                        );
                        $uVsMethod->insert($data);                 
                    }
                    //------------------------------------------------------------------
                    
                    //MstUserVsGuru.php.php
                    //-Delete prev gures
                    $uVsGuru=new Application_Model_DbTable_MstUserVsGuru();
                    $where = $uVsGuru->getAdapter()->quoteInto('user_id = ?', $id);
                    $uVsGuru->delete($where);

                    //MstUserVsDevotee.php
                    //-Delete prev devotees
                    $uVsDev=new Application_Model_DbTable_MstUserVsDevotee();
                    $where = $uVsDev->getAdapter()->quoteInto('user_id = ?', $id);
                    $uVsDev->delete($where);
                    
                    //MstUserVsCounselor.php
                    //-Delete prev counselors
                    $uVsCon=new Application_Model_DbTable_MstUserVsCounselor();
                    $where = $uVsCon->getAdapter()->quoteInto('user_id = ?', $id);
                    $uVsCon->delete($where);
                    
                    //MstUserVsCounselor.php
                    //-Delete prev centers
                    $uVsCen=new Application_Model_DbTable_MstUserVsCenter();
                    $where = $uVsCen->getAdapter()->quoteInto('user_id = ?', $id);
                    $uVsCen->delete($where);
                    
                    //MstUserVsAstcounselor.php
                    //-Delete prev mentors
                    $uVsMen=new Application_Model_DbTable_MstUserVsAstcounselor();
                    $where = $uVsMen->getAdapter()->quoteInto('user_id = ?', $id);
                    $uVsMen->delete($where);
                }
                Rgm_UserServices::log($id,'mst_user','Primary Info Updated',$logObj);
                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                $return=$e->getMessages();
            }            
        }else{
            $return="User not found in the database.";
        }
        return $return;
    }
    /*
    ----------------------------------------------------------------------------------------------------------
    $data format = array(0 =>array('id' =>'73','checked' => ''), 1 =>array('id' => '30', 'checked' => 'checked'))
    ----------------------------------------------------------------------------------------------------------
    */
    public function updateAceessList($id,$data){
        $bootstrap = Zend_Controller_Front::getInstance()->getParam("bootstrap");
        $db = $bootstrap->getPluginResource('db')->getDbAdapter();
        $db->beginTransaction();
        $mstUser=new Application_Model_DbTable_Mstuser();
        $prevUser=$mstUser->getActionList($id,'CHECKED');
        $logObj = Zend_Json::encode($prevUser);
        try {
            if($data){
                //-Delete Database-------------
                $uVsMethod=new Application_Model_DbTable_MstUserMethodVsUser();
                $where = $uVsMethod->getAdapter()->quoteInto('user_id = ?', $id);
                $uVsMethod->delete($where);
                //Insert Now
                foreach($data as $r){
                    if($r['checked']=='checked'){//insert
                        $data = array(
                            'user_id'      => $id,
                            'method_id' => $r['id']
                        );
                        $uVsMethod->insert($data); 
                    }
                }
                Rgm_UserServices::log($id,'mst_user','Access list changed',$logObj);
                $db->commit();
                $return="";
            }
        } catch (Exception $e) {
            $db->rollBack();
            $return=$e->getMessages();
        }
        return $return;
    }
    
    /*
    ----------------------------------------------------------------------------------------------------------
    $data format = array(0 =>array('id' =>'73','checked' => ''), 1 =>array('id' => '30', 'checked' => 'checked'))
    ----------------------------------------------------------------------------------------------------------
    */
    public function updateCenterList($id,$data){
        $bootstrap = Zend_Controller_Front::getInstance()->getParam("bootstrap");
        $db = $bootstrap->getPluginResource('db')->getDbAdapter();
        $db->beginTransaction();
        $mstUser=new Application_Model_DbTable_Mstuser();
        $prevUser=$mstUser->getCenterList($id,'CHECKED');
        $logObj = Zend_Json::encode($prevUser);
        try {
            if($data){
                //-Delete Centers-------------
                $uVsCenter=new Application_Model_DbTable_MstUserVsCenter();
                $where = $uVsCenter->getAdapter()->quoteInto('user_id = ?', $id);
                $uVsCenter->delete($where);
                //Insert Now
                foreach($data as $r){
                    if($r['checked']=='checked'){//insert
                        $data = array(
                            'user_id'      => $id,
                            'center_id' => $r['id']
                        );
                        $uVsCenter->insert($data); 
                    }
                }
                Rgm_UserServices::log($id,'mst_user','Center list changed',$logObj);
                $db->commit();
                $return="";
            }
        } catch (Exception $e) {
            $db->rollBack();
            $return=$e->getMessages();
        }
        return $return;
    }
    
    /*
    ----------------------------------------------------------------------------------------------------------
    $data format = array(0 =>array('id' =>'73','checked' => ''), 1 =>array('id' => '30', 'checked' => 'checked'))
    ----------------------------------------------------------------------------------------------------------
    */
    public function updateCounselorList($id,$data){
        $bootstrap = Zend_Controller_Front::getInstance()->getParam("bootstrap");
        $db = $bootstrap->getPluginResource('db')->getDbAdapter();
        $db->beginTransaction();
        $mstUser=new Application_Model_DbTable_Mstuser();
        $prevUser=$mstUser->getCounselorList($id,'CHECKED');
        $logObj = Zend_Json::encode($prevUser);
        try {
            if($data){
                //-Delete Counselors-------------
                $uVsCounselor=new Application_Model_DbTable_MstUserVsCounselor();
                $where = $uVsCounselor->getAdapter()->quoteInto('user_id = ?', $id);
                $uVsCounselor->delete($where);
                //Insert Now
                foreach($data as $r){
                    if($r['checked']=='checked'){//insert
                        $data = array(
                            'user_id'      => $id,
                            'counselor_id' => $r['id']
                        );
                        $uVsCounselor->insert($data); 
                    }
                }
                Rgm_UserServices::log($id,'mst_user','Counselor list changed',$logObj);
                $db->commit();
                $return="";
            }
        } catch (Exception $e) {
            $db->rollBack();
            $return=$e->getMessages();
        }
        return $return;
    }
    /*
    ----------------------------------------------------------------------------------------------------------
    $data format = array(0 =>array('id' =>'73','checked' => ''), 1 =>array('id' => '30', 'checked' => 'checked'))
    ----------------------------------------------------------------------------------------------------------
    */
    public function updateMentorList($id,$data){
        $return="";
        $bootstrap = Zend_Controller_Front::getInstance()->getParam("bootstrap");
        $db = $bootstrap->getPluginResource('db')->getDbAdapter();
        $db->beginTransaction();
        $mstUser=new Application_Model_DbTable_Mstuser();
        $prevUser=$mstUser->getMentorList($id,'CHECKED');
        $logObj = Zend_Json::encode($prevUser);
        try {
            if($data){
                //-Delete astCounselors-------------
                $uVsAstcounselor=new Application_Model_DbTable_MstUserVsAstcounselor;
                $where = $uVsAstcounselor->getAdapter()->quoteInto('user_id = ?', $id);
                $uVsAstcounselor->delete($where);
                //Insert Now
                foreach($data as $r){
                    if($r['checked']=='checked'){//insert
                        $data = array(
                            'user_id'      => $id,
                            'astcounselor_id' => $r['id']
                        );
                        $uVsAstcounselor->insert($data); 
                    }
                }
                Rgm_UserServices::log($id,'mst_user','Mentor list changed',$logObj);
                $db->commit();
                $return="";
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            $db->rollBack();
            $return=$e->getMessages();
        } catch (Zend_Exception $e) {
            $db->rollBack();
            $return=$e->getMessages();
        } catch (Exception $e) {
            $db->rollBack();
            $return=$e->getMessages();
        }
        return $return;
    }
    /*
    ----------------------------------------------------------------------------------------------------------
    $data format = array(0 =>array('id' =>'73','checked' => ''), 1 =>array('id' => '30', 'checked' => 'checked'))
    ----------------------------------------------------------------------------------------------------------
    */
    public function updateGuruList($id,$data){
        $return="";
        $bootstrap = Zend_Controller_Front::getInstance()->getParam("bootstrap");
        $db = $bootstrap->getPluginResource('db')->getDbAdapter();
        $db->beginTransaction();
        $mstUser=new Application_Model_DbTable_Mstuser();
        $prevUser=$mstUser->getGuruList($id,'CHECKED');
        $logObj = Zend_Json::encode($prevUser);
        try {
            if($data){
                //-Delete Guru-------------
                $uVsGuru=new Application_Model_DbTable_MstUserVsGuru();
                $where = $uVsGuru->getAdapter()->quoteInto('user_id = ?', $id);
                $uVsGuru->delete($where);
                //-Insert Now
                foreach($data as $r){
                    if($r['checked']=='checked'){//insert
                        $data = array(
                            'user_id'   => $id,
                            'guru_id'   => $r['id']
                        );
                        $uVsGuru->insert($data); 
                    }
                }
                Rgm_UserServices::log($id,'mst_user','Guru list changed',$logObj);
                $db->commit();
                $return="";
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            $db->rollBack();
            $return=$e->getMessages();
        } catch (Zend_Exception $e) {
            $db->rollBack();
            $return=$e->getMessages();
        } catch (Exception $e) {
            $db->rollBack();
            $return=$e->getMessages();
        }
        return $return;
    }
    
    /*
    ----------------------------------------------------------------------------------------------------------
    $data format = array(0 =>array('id' =>'73','checked' => ''), 1 =>array('id' => '30', 'checked' => 'checked'))
    ----------------------------------------------------------------------------------------------------------
    */
    public function updateDbList($id,$data){
        $return="";
        $bootstrap = Zend_Controller_Front::getInstance()->getParam("bootstrap");
        $db = $bootstrap->getPluginResource('db')->getDbAdapter();
        $db->beginTransaction();
        $mstUser=new Application_Model_DbTable_Mstuser();
        $prevUser=$mstUser->getDbList($id,'CHECKED');
        $logObj = Zend_Json::encode($prevUser);
        try {
            if($data){
                //-Delete Guru-------------
                $uVsDb=new Application_Model_DbTable_MstUserVsDatabase();
                $where = $uVsDb->getAdapter()->quoteInto('user_id = ?', $id);
                $uVsDb->delete($where);
                //-Insert Now
                foreach($data as $r){
                    if($r['checked']=='checked'){//insert
                        $data = array(
                            'user_id'   => $id,
                            'database_id'   => $r['id']
                        );
                        $uVsDb->insert($data); 
                    }
                }
                Rgm_UserServices::log($id,'mst_user','Database list changed',$logObj);
                $db->commit();
                $return="";
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            $db->rollBack();
            $return=$e->getMessages();
        } catch (Zend_Exception $e) {
            $db->rollBack();
            $return=$e->getMessages();
        } catch (Exception $e) {
            $db->rollBack();
            $return=$e->getMessages();
        }
        return $return;
    }
    /*
    ----------------------------------------------------------------------------------------------------------
    $data format = array(0 =>1,1=>122,3=>343,..=>..,..))
    ----------------------------------------------------------------------------------------------------------
    */
    public function updateDevoteesList($id,$data){
        $return="";
        $bootstrap = Zend_Controller_Front::getInstance()->getParam("bootstrap");
        $db = $bootstrap->getPluginResource('db')->getDbAdapter();
        $db->beginTransaction();
        $mstUser=new Application_Model_DbTable_Mstuser();
        $prevUser=$mstUser->getDevoteeList($id,'CHECKED');
        $logObj = Zend_Json::encode($prevUser);
        try {
            if($data){
                //-Delete Devotees-------------
                $uVsDevotees=new Application_Model_DbTable_MstUserVsDevotee;
                $where = $uVsDevotees->getAdapter()->quoteInto('user_id = ?', $id);
                $uVsDevotees->delete($where);
                //-Insert Now
                foreach($data as $r){
                    if($r!=0){
                        $data = array(
                            'user_id'   => $id,
                            'did'   => $r
                        );
                        $uVsDevotees->insert($data); 
                    }
                }
                Rgm_UserServices::log($id,'mst_user','Devotees list changed',$logObj);
                $db->commit();
                $return="";
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            $db->rollBack();
            $return=$e->getMessages();
        } catch (Zend_Exception $e) {
            $db->rollBack();
            $return=$e->getMessages();
        } catch (Exception $e) {
            $db->rollBack();
            $return=$e->getMessages();
        }
        return $return;
    }
}