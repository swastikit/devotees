<?php

class Application_Model_DbTable_MstUserRole extends Zend_Db_Table_Abstract
{
    protected $_name = 'mst_user_role';
    public function getPairs(){
        $sql="SELECT a.id, a.name FROM mst_user_role AS a WHERE a.active='Y'";
        $results = $this->getAdapter()->fetchPairs($sql);
        return $results;
    }
}

