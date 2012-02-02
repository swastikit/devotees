<?php

class Application_Model_DbTable_MstAstCounselor extends Zend_Db_Table_Abstract
{

    protected $_name = 'mst_astcounselor';
    
        public function getMentor(){
        $select = $this->_db->select();
        $select->from(array('ment'=>'mst_astcounselor'),array('ment.id'))
               ->joinLeft(array('d'=>'devotee'),'ment.id=d.did',array('mentorname'=>'d.search_name','mentordid'=>'d.did'));
        $mentor = $this->getAdapter()->fetchAll($select);
        return $mentor;       
    }
    
    public function getKeyValues(){
        
        $select=$this->_db->select();
        $select->from(array('c'=>'mst_astcounselor'),array('c.id'))
                ->joinLeft(array('d'=>'devotee'),'c.did=d.did',array())
                ->joinLeft(array('cn'=>'mst_center'),'cn.id=d.center_id',array('name'=> new Zend_Db_Expr("CONCAT(d.search_name, '(', cn.name, ')')")));
               
        $result=$this->_db->fetchPairs($select);
        return $result;
        
    }
}

