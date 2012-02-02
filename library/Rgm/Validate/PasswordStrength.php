<?php
class Rgm_Validate_PasswordStrength extends Zend_Validate_Abstract
{
    const LENGTH = 'length';
    const UPPER  = 'upper';
    const LOWER  = 'lower';
    const DIGIT  = 'digit';
    const SPECIAL = 'special';
    const ALL = 'all';
 
    protected $_messageTemplates = array(
        self::LENGTH => "Password must be at least 11 characters in length",
        self::UPPER  => "Password must contain at least one uppercase letter",
        self::LOWER  => "Password must contain at least one lowercase letter",
        self::DIGIT  => "Password must contain at least one digit character",
        self::SPECIAL  => "Password must contain at least one special character(e.g. + * # $ etc.)",
        self::ALL => "Password must be minimum 11 characters long with at least one uppercase letter, one lowercase leter, one digit and one special character (e.g. + * # $ etc.)"
    );
 
    public function isValid($value)
    {
        $this->_setValue($value);
 
        $isValid = true;
 
        if (strlen($value) < 11) {
            //$this->_error(self::LENGTH);
            $this->_error(self::ALL);
            $isValid = false;
        }
 
        if (!preg_match('/[A-Z]/', $value)) {
            //$this->_error(self::UPPER);
            $this->_error(self::ALL);
            $isValid = false;
        }
 
        if (!preg_match('/[a-z]/', $value)) {
            //$this->_error(self::LOWER);
            $this->_error(self::ALL);
            $isValid = false;
        }
 
        if (!preg_match('/\d/', $value)) {
            //$this->_error(self::DIGIT);
            $this->_error(self::ALL);
            $isValid = false;
        }
        /*
        if (!preg_match('/[!\'^£$%&*()}{@#~?><>,|=_+¬-]:;./', $value)) {
            if (!preg_match('[~!@#\$%\^&\*\(\)_\+=-`\{\[]}\\;:/\?\.>,<\|]', $value)) {
                ()-_=+\|\]}[{
        */
        if (!preg_match('/[`~!@#$%^&*()\-_=\+\|\]}[{;:?\/.,\'\"\\\<>]/', $value)) {
            $this->_error(self::SPECIAL);
            $isValid = false;
        }
        return $isValid;
    }
}
    
?>

    
