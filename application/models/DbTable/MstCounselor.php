<?php

class Application_Model_DbTable_MstCounselor extends Zend_Db_Table_Abstract
{

    protected $_name = 'mst_counselor';
    
    public function getCounselor(){
        $select=$this->_db->select();
        $select->from(array('mcoun'=>'mst_counselor'),array('mcoun.id','mcoun.did'))
               ->joinLeft(array('d'=>'devotee'),'mcoun.did=d.did',array('counselorname'=>'d.search_name'));
        $counselor = $this->getAdapter()->fetchAll($select);
        return $counselor;
    }
    public function listIdNames($like){
        $select=$this->_db->select();
        $select->from(array('c'=>'mst_counselor'),array('c.id'))
                ->joinLeft(array('d'=>'devotee'),'c.did=d.did',array())
                ->joinLeft(array('cn'=>'mst_center'),'cn.id=d.center_id',array('name'=> new Zend_Db_Expr("CONCAT(d.search_name, '(', cn.name, ';id-', c.id ,')')")))
                ->where('d.isactive = ?','Y')
                ->where($this->_db->quoteInto('d.encoded_search_name LIKE ?','%' . Rgm_Basics::encodeDiacritics($like) . '%'))
                ->order('d.encoded_search_name');
        $result=$this->_db->fetchAll($select);
        return $result;
    }
    public function getKeyValues(){
        $select=$this->_db->select();
        $select->from(array('c'=>'mst_counselor'),array('c.id'))
                ->joinLeft(array('d'=>'devotee'),'c.did=d.did',array())
                ->joinLeft(array('cn'=>'mst_center'),'cn.id=d.center_id',array('name'=> new Zend_Db_Expr("CONCAT(d.search_name, '(', cn.name, ')')")));

        $result=$this->_db->fetchPairs($select);
        return $result;
    }
}

