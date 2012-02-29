<?php

class Application_Model_DbTable_MstServices extends Zend_Db_Table_Abstract
{

    protected $_name = 'mst_services';

    public function getKeyValues(){
        $select = $this->_db->select();
        $select->from(array('ms'=>'mst_services'),array('ms.id','ms.name'));
        $result = $this->getAdapter()->fetchPairs($select);
        return $result;
    }
}

