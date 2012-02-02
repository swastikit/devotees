<?php

class Application_Model_DbTable_SysVariables extends Zend_Db_Table_Abstract
{
    protected $_name = 'sys_variables';
    public function get($variableName)
    {
        $row=$this->find($variableName);
        if ($row) {
            $r=current($row);
            return $r[0]['value'];
        }
        return false;
    }
}

