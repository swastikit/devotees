<?php
class Application_Model_DbTable_MstCenter extends Zend_Db_Table_Abstract
{
    protected $_name = 'mst_center';
    public function getKeyValues($option=null){
        $select = $this->_db->select();
        if($option==null){
            $select->from(array('mst_center'), array('id', 'name'))
                            ->where('isactive = ?', 'Y')
                            ->order(array('name'));
        }else if($option=='counselor'){
            $select->from(array('mst_center'), array('id', 'name'))
                            ->where('isactive = ?', 'Y')
                            ->Where('id in (SELECT x.center_id FROM devotee x where x.did in (select y.did from mst_counselor y))')
                            ->order(array('name'));
        }else if($option=='mentor'){
            $select->from(array('mst_center'), array('id', 'name'))
                            ->where('isactive = ?', 'Y')
                            ->Where('id in (SELECT x.center_id FROM devotee x where x.did in (select y.did from mst_astcounselor y))')
                            ->order(array('name'));
        }
        $result=$this->_db->fetchPairs($select );
        return $result;
    }
}
