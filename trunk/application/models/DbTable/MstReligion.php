<?php

class Application_Model_DbTable_MstReligion extends Zend_Db_Table_Abstract
{

    protected $_name = 'mst_religion';
     
    public function getReligionslist(){
        
        $select = $this->_db->select();
        $select->from(array('mr'=>'mst_religion'),array('mr.id','mr.name'));
        $result = $this->getAdapter()->fetchPairs($select);
        return $result;  
    }
}


