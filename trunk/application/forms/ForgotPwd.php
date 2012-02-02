<?php

class Application_Form_ForgotPwd extends Zend_Form
{
    public function init()
    {
        $this->setName('forgotpwd');
        $this->setAttrib('id','forgotpwd');
        
        // Add userId Id Element
        $userId = new Zend_Form_Element_Text('userId');
        $userId->setLabel('User id :')
            ->addFilters(array('StripTags','StringTrim','StringToLower'))
            ->setRequired(true)
            ->addValidator('NotEmpty')
            ->addValidator(new Zend_Validate_Alnum(array('allowWhiteSpace' => false)))
            ->addValidator(new Zend_Validate_StringLength(array('max' => 100,'min'=>8)));

        // Add an Email Id Element
        $EmailId = new Zend_Form_Element_Text('email');
        $EmailId->setLabel('Email :')
            ->addFilters(array('StripTags','StringTrim','StringToLower'))
            ->setRequired(true)
            ->addValidator('NotEmpty')
            ->addValidator(new Zend_Validate_EmailAddress());

        $this->addElements(array($userId, $EmailId));
        // create captcha
        $captcha = new Zend_Form_Element_Captcha('captcha', array(
            'captcha' => 'Figlet',
            'captchaOptions' => array(
                'captcha' => 'Figlet',
                'wordLen' => 6,
                'timeout' => 300,
                //'font' => APPLICATION_PATH . '/../public/fonts/3x5.flf', '',
            ),
        ));
        $captcha->setRequired(true)
             ->addFilter('StripTags')
             ->addFilter('StringTrim')
             ->addValidator('NotEmpty');
        
        $captcha->setLabel('Verification code * :')->setOptions(array('size' => '45')); 
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('class', 'form-submit-button');
        $this->addElements(array($captcha,$submit));
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'viewscripts/forgotPwdVwScript.phtml'))
        ));
    }

}
