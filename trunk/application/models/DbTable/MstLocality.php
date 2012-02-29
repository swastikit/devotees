<?php

class Application_Model_DbTable_MstLocality extends Zend_Db_Table_Abstract
{

    protected $_name = 'mst_locality';

    public function getLocalityKeyValues(){
        $select = $this->_db->select();
            $select->from(array('mst_locality'), array('id', 'name'))
                   ->where('isactive = ?', 'Y')
                   ->order(array('name'));
     
        $result=$this->_db->fetchPairs($select );
        return $result;
  }
}