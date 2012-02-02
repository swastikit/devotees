<?php

class Application_Model_DbTable_MstLanguage extends Zend_Db_Table_Abstract
{

    protected $_name = 'mst_language';
    
    public function getLanguagelist(){
        
        $select = $this->_db->select();
        $select->from(array('ml'=>'mst_language'),array('ml.id','ml.name'));
        $result = $this->getAdapter()->fetchPairs($select);
        
        return $result;
        
    }


}

