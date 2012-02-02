<?php

class Application_Form_Login extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setName('UserLogin');
        
        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('User Name')
                 ->setRequired(true)
                 ->addFilter('StripTags')
                 ->addFilter('StringTrim')
                 ->addValidator('NotEmpty');
        
        $pass = new Zend_Form_Element_Password('pass');
        $pass->setLabel('Password')
             ->setRequired(true)
             ->addFilter('StripTags')
             ->addFilter('StringTrim')
             ->addValidator('NotEmpty');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('class','buttons');
        $redirect = new Zend_Form_Element_Hidden('redirect');
        $submit->setAttrib('id', 'submitbutton');
        $submit->setAttrib('class','form-submit-button');
        $this->addElements(array($username, $pass,$submit));
        $this->setAttrib('class', 'form');
    }
}
