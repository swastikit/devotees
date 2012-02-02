<?php
class Application_Model_DbTable_MstUserSecurityQuestions extends Zend_Db_Table_Abstract
{
    protected $_name = 'mst_user_security_questions';
    public function getPair(){
        $select = $this->_db->select();
        $select->from(array('mst_user_security_questions'), array('id', 'question'))
                        ->where('is_active = ?', 'Y')
                        ->order(array('question'));
        $result=$this->_db->fetchPairs($select);
        return $result;
    }
}

