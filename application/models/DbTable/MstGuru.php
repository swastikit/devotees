<?php

class Application_Model_DbTable_MstGuru extends Zend_Db_Table_Abstract
{

    protected $_name = 'mst_guru';
    
    public function seekGuru(){
    $select = $this->_db->select();
    $select->from(array('mg'=>'mst_guru'),array('guru_id'=>'mg.id','guru_name'=>'mg.name'));
    $result = $this->getAdapter()->fetchPairs($select);
    return $result;
    }
    
}

