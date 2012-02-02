<?php

class Application_Form_ForgotPwdSq extends Zend_Form
{
    protected $_questions;
    public function __construct($questions = null)
    {
        $this->_questions = $questions;
        parent::__construct();
    }
    public function init()
    {
        $this->setMethod('post');
        $this->setName('forgotpwdsq');
        $this->setAttrib('id', 'forgotpwdsq');

        // Add Question01 Id Element
        $securityAns01 = new Zend_Form_Element_Text('securityAns01');
        $securityAns01->setLabel($this->_questions['Q1'])
        ->addFilters(array('StripTags','StringTrim', 'StringToLower'))
        ->setRequired(true)
        ->addValidator('NotEmpty')
        ->addValidator(new Zend_Validate_Alnum(array('allowWhiteSpace' => false)))
        ->addValidator(new Zend_Validate_StringLength(array('max' => 100)));

        // Add Question02 Id Element
        $securityAns02 = new Zend_Form_Element_Text('securityAns02');
        $securityAns02->setLabel($this->_questions['Q2'])
        ->addFilters(array('StripTags','StringTrim', 'StringToLower'))
        ->setRequired(true)
        ->addValidator('NotEmpty')
        ->addValidator(new Zend_Validate_Alnum(array('allowWhiteSpace' => false)))
        ->addValidator(new Zend_Validate_StringLength(array('max' => 100)));

        $this->addElements(array($securityAns01,$securityAns02));

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('class', 'form-submit-button');
        $this->addElements(array($submit));
    }
}
?>
