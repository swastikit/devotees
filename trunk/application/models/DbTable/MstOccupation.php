<?php

class Application_Model_DbTable_MstOccupation extends Zend_Db_Table_Abstract
{

    protected $_name = 'mst_occupation';

    public function listOccupation(){
        
        $select = $this->_db->select();
        $select->from(array('m_o'=>'mst_occupation',array('m_o.id','m_o.name')));
        $result = $this->getAdapter()->fetchPairs($select);
        
        return $result;
    }

}

