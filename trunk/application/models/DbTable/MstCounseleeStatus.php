<?php

class Application_Model_DbTable_MstCounseleeStatus extends Zend_Db_Table_Abstract
{

    protected $_name = 'mst_counselee_status';
    
    public function getCounseleeStatus(){
        
        $select = $this->_db->select();
        $select->from(array('mcs'=>'mst_counselee_status'),array('mcs.id','mcs.name'));
        $result = $this->getAdapter()->fetchPairs($select);
     
     return  $result;
     
    }
}

