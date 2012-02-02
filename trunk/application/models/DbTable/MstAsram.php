<?php

class Application_Model_DbTable_MstAsram extends Zend_Db_Table_Abstract
{
    protected $_name = 'mst_asram';
    public function getAsram(){
        $select = $this->_db->select();
        $select->from(array('m_a'=>'mst_asram'), array('asramid'=> 'm_a.id','asramname'=>'m_a.name'));
        $result = $this->getAdapter()->fetchAll($select);
        return $result;
    }
    public function listIdNames(){
        $select = $this->_db->select();
        $select->from(array('a'=>'mst_asram'), array('id'=> 'a.id','name'=>'a.name'))
        ->where('a.id <> ?',7);
        $result = $this->getAdapter()->fetchAll($select);
        return $result;
    }
    public function listPairs(){
        $select = $this->_db->select();
        $select->from(array('a'=>'mst_asram'), array('id'=> 'a.id','name'=>'a.name'))
        ->where('a.id <> ?',7);
        $result = $this->getAdapter()->fetchPairs($select);
        return $result;
    }
    public function listIds(){
        $select = $this->_db->select();
        $select->from(array('a'=>'mst_asram'), array('id'=> 'a.id'))
        ->where('a.id <> ?',7);
        $result = $this->getAdapter()->fetchCol($select);
        return $result;
    }
}

