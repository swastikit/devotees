<?php

class Application_Model_DbTable_MstCity extends Zend_Db_Table_Abstract
{

    protected $_name = 'mst_city';

    public function getCityKeyValues(){
        $select = $this->_db->select();
        $select->from(array('mst_city'), array('id', 'name'))
               ->where('isactive = ?', 'Y')
               ->order(array('name'));
    
        $result=$this->_db->fetchPairs($select);
    
    return $result;
    }
    
}


