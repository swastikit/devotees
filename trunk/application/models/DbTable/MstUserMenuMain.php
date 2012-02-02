<?php

class Application_Model_DbTable_MstUserMenuMain extends Zend_Db_Table_Abstract
{
    protected $_name = 'mst_user_menu_main';
    public function getKeyValues(){
        $select = $this->_db->select();
        $select->from(array('mst_user_menu_main'), array('id','name'))
                        ->where('is_active = ?', 'Y')
                        ->order(array('sort_order'));
        $result=$this->_db->fetchPairs($select);
        return $result;
    }
    


}

