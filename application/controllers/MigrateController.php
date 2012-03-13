<?php
Class MigrateController extends Zend_Controller_Action{
    
    public function init(){
        
    }
    
    public function indexAction(){
        //$sql_query = "SELECT AUTO_INCREMENT FROM information_schema.tables WHERE TABLE_NAME='devotee' AND TABLE_SCHEMA = devotees_3_0";
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
                }            
        }
        $this->view->lastdid = $data2; 
    } 
}
?>