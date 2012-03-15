<?php
Class MigrateController extends Zend_Controller_Action{
    
    public function init(){
        
    }
    
    public function indexAction(){
        $bootstrap = $this->getInvokeArg('bootstrap');
        $resource = $bootstrap->getPluginResource('multidb');
        $db1 = $resource->getDb('db1');
        $db3 = $resource->getDb('db3');
        $select = $db3->select()
                      ->from(array('t' => 'TABLES'), array('t.AUTO_INCREMENT'))
                      ->where('TABLE_NAME = ?','devotee')
                      ->where('TABLE_SCHEMA = ?', 'devotees_3_0');
        $stmt = $db3->query($select);
        $result = $stmt->fetchAll();
        
        foreach($result as $key => $value){
                foreach($value as $data1 => $data2){
        $this->view->lastdid = $data2; 
        return $result;
       } 
     }
   }
 
   public function migratecountryAction(){
        $bootstrap = $this->getInvokeArg('bootstrap');
        $resource = $bootstrap->getPluginResource('multidb');
        $db1 = $resource->getDb('db1');
        $select = $db1->select()
                      ->from(array('m_c'=>'mst_country'), array('m_c.id','m_c.name','m_c.do_modify','m_c.modified_by_login_id','m_c.isactive'));
        $stmt = $db1->query($select);
        //$result = $stmt->fetchAll();
        $bootstrap = $this->getInvokeArg('bootstrap');
        $resource = $bootstrap->getPluginResource('multidb');
        $db2 = $resource->getDb('db2');
        $row = $stmt->fetchAll();
        $counter = 0;
        foreach($row as $key => $data1){
        $data = array(
                              'name'                =>$data1['name'],
                              'entered_by_uid'      =>$data1['modified_by_login_id'],
                              'entered_date'        =>Zend_Date::now()->toString('yyyyMMddHHmmss'),
                              'modified_by_uid'     =>$data1['modified_by_login_id'],
                              'modified_date'       =>Zend_Date::now()->toString('yyyyMMddHHmmss'),
                              'is_active'           =>$data1['isactive']
                              ); 

        $db2->insert('mst_country', $data);                
         }
                

   
        echo "The data from mst_country table of devotees_3_0 database was successfully migrated 
              to mst_country table of devotees_3_1 database";
     }
     
     
  public function migratecityAction(){
       $bootstrap = $this->getInvokeArg('bootstrap');
       $resource = $bootstrap->getPluginResource('multidb');
       $db1 = $resource->getDb('db1');
       $select = $db1->select()
                     ->from(array('m_c'=>'mst_city'),
                            array('m_c.state_id','m_c.country_id','m_c.name',
                                  'm_c.do_modify','m_c.modified_by_login_id','m_c.isactive'));
       $stmt = $db1->query($select);
       $bootstrap = $this->getInvokeArg('bootstrap');
       $resource = $bootstrap->getPluginResource('multidb');
       $db2 = $resource->getDb('db2');
       $row = $stmt->fetchAll();
       $counter = 0;
       foreach($row as $key => $data1){
       $data = array ('state_id'         =>$data1['state_id'],
                      'country_id'       =>$data1['country_id'],
                      'name'             =>$data1['name'],
                      'entered_by_uid'   =>$data1['modified_by_login_id'],
                      'entered_date'     =>Zend_Date::now()->toString('yyyyMMddHHmmss'),  
                      'modified_by_uid'  =>$data1['modified_by_login_id'],
                      'modified_date'    =>Zend_Date::now()->toString('yyyyMMddHHmmss'),
                      'is_active'        =>$data1['isactive']
                     ); 
       $db2->insert('mst_city', $data);                
                 }
       echo "The data from mst_city table of devotees_3_0 database was successfully migrated to mst_city table of devotees_3_1 database";
             }
             
  public function migratecenterAction(){
       $bootstrap = $this->getInvokeArg('bootstrap');
       $resource = $bootstrap->getPluginResource('multidb');
       $db1 = $resource->getDb('db1');
       $select = $db1->select()
                     ->from(array('m_c'=>'mst_center'),
                            array('m_c.name','m_c.country_id',
                                  'm_c.do_modify','m_c.modified_by_login_id','m_c.isactive'));
       $stmt = $db1->query($select);
       $bootstrap = $this->getInvokeArg('bootstrap');
       $resource = $bootstrap->getPluginResource('multidb');
       $db2 = $resource->getDb('db2');
       $row = $stmt->fetchAll();
       $counter = 0;
       foreach($row as $key => $data1){
       $data = array (
                      'name'             =>$data1['name'],
                      'country_id'       =>$data1['country_id'],
                      'entered_by_uid'   =>$data1['modified_by_login_id'],
                      'entered_date'     =>Zend_Date::now()->toString('yyyyMMddHHmmss'),  
                      'modified_by_uid'  =>$data1['modified_by_login_id'],
                      'modified_date'    =>Zend_Date::now()->toString('yyyyMMddHHmmss'),
                      'is_active'        =>$data1['isactive']
                     ); 
       $db2->insert('mst_center', $data);                
                 }
       echo "The data from mst_center table of devotees_3_0 database was successfully migrated to mst_center table of devotees_3_1 database";
             }
             
  public function migratestateAction(){
       $bootstrap = $this->getInvokeArg('bootstrap');
       $resource = $bootstrap->getPluginResource('multidb');
       $db1 = $resource->getDb('db1');
       $select = $db1->select()
                     ->from(array('m_s'=>'mst_state'),array('m_s.name','m_s.country_id','m_s.do_modify','m_s.modified_by_login_id','m_s.isactive'));

       $stmt = $db1->query($select);
       $bootstrap = $this->getInvokeArg('bootstrap');
       $resource = $bootstrap->getPluginResource('multidb');
       $db2 = $resource->getDb('db2');
       $row = $stmt->fetchAll();
       foreach($row as $key => $data1){
       $data = array (
                      'name'             =>$data1['name'],
                      'country_id'       =>$data1['country_id'],
                      'entered_by_uid'   =>$data1['modified_by_login_id'],
                      'entered_date'     =>Zend_Date::now()->toString('yyyyMMddHHmmss'),  
                      'modified_by_uid'  =>$data1['modified_by_login_id'],
                      'modified_date'    =>Zend_Date::now()->toString('yyyyMMddHHmmss'),
                      'is_active'        =>$data1['isactive']
                     ); 
       $db2->insert('mst_state', $data);                
                 }
       Zend_Debug::dump($row);          
             }           
   }
?>