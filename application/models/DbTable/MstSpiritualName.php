<?php

class Application_Model_DbTable_MstSpiritualName extends Zend_Db_Table_Abstract
{

    protected $_name = 'mst_spiritualname';

    public function getKeyValues(){
        
        $select = $this->_db->select();
        $select->from(array('m_sp_n'=>'mst_spiritualname'),array('m_sp_n.id','m_sp_n.name'));
        $result = $this->getAdapter()->fetchPairs($select);
        
        return $result; 
    }
}

