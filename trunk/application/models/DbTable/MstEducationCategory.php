<?php

class Application_Model_DbTable_MstEducationCategory extends Zend_Db_Table_Abstract
{

    protected $_name = 'mst_educationcategory';
    
    public function getEduCategory(){
        
        $select = $this->_db->select();
        $select->from(array('m_ec'=>'mst_educationcategory',array('m_ec.id','m_ec.name')));
        $result = $this->getAdapter()->fetchPairs($select);
        
        return $result;  
    }

}


