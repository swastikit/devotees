<?php
class Rgm_Validate_MobileNumber extends Zend_Validate_Abstract
{
    const LENGTH = 'length';
    const UPPER  = 'upper';
    const LOWER  = 'lower';
    const DIGIT  = 'digit';
    const SPECIAL = 'special';
    const ALL = 'all';
    
    protected $_messageTemplates = array(
        self::ALL => "Mobile number should contain only digits, length should be 7 to 12 and it should not start with 0",
    );
    public function isValid($value)
    {
        $this->_setValue($value);
        $isValid = true;
        if (strlen($value) < 7) {
            $this->_error(self::ALL);
            $isValid = false;
        }
        if(!preg_match("/^[1-9]+[0-9]+$/", trim($value))) 
        {
            $this->_error(self::ALL);
            $isValid = false;
        } 
        if (strlen($value) > 12) {
            $this->_error(self::ALL);
            $isValid = false;
        }
        return $isValid;
    }
}
    
?>

    
