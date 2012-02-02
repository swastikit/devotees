<?php

class Application_Form_ForgotPwdRe extends Zend_Form
{
    public function init()
    {
        $this->setName('forgotpwdre');
        $this->setAttrib('id','forgotpwdre');
        
        //For Element Type New Password
        $newpwd = new Zend_Form_Element_Password('newPassWord');
        $newpwd->setLabel('Type New Password:')
            ->setRequired('true')
            ->addValidator(new Rgm_Validate_PasswordStrength());
            
        $newpwdR = new Zend_Form_Element_Password('newPassWordRe');
        $newpwdR->setLabel('Retype Password:')
                ->setRequired('true');
                
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('class','buttons');
        
        
        $redirect = new Zend_Form_Element_Hidden('redirect');
        $submit->setAttrib('id', 'submitbutton');
        $submit->setAttrib('class','form-submit-button');
        
        $this->addElements(array($newpwd, $newpwdR, $submit));
    }
}

