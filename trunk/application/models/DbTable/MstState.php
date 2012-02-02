<?php

class Application_Model_DbTable_MstState extends Zend_Db_Table_Abstract
{

    protected $_name = 'mst_state';


    public function getStateKeyValues(){
        
        $select = $this->_db->select();
        $select->from(array('m_s'=>'mst_state'),array('m_s.id','m_s.name'));
        $result = $this->getAdapter()->fetchPairs($select);
        return $result;
    } 

}

