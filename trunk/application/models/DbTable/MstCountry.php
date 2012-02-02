<?php

class Application_Model_DbTable_MstCountry extends Zend_Db_Table_Abstract
{
    protected $_name = 'mst_country';
    public function getActiveList(){
        $select = $this->_db->select();
        $select->from(array(mst_country), array('id', 'name', 'tel_code' ,'name_tel'=>'concat(name,1)'))
               ->where('isactive = ?', 'Y')
               ->order(array('name'));
        $stmt = $select->query();
        $rows = $stmt->fetchAll();
        if ($rows) {
            return $rows;
        }
        return false;
    }
    public function getPairWithTelCode(){
        $select = $this->_db->select();
        $select->from(array('mst_country'), array('id', 'name_tel'=> new Zend_Db_Expr("CONCAT(name, '(+', tel_code, ')')")))
                        ->where('isactive = ?', 'Y')
                        ->order(array('name'));

        $result=$this->_db->fetchPairs($select);
        return $result;
    }
    public function getKeyValues(){
        $select = $this->_db->select();
        $select->from(array('mst_country'), array('id', 'name'))
                        ->where('isactive = ?', 'Y')
                        ->order(array('name'));

        $result=$this->_db->fetchPairs($select);
        return $result;
    }
}

