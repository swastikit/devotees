<?php
class Application_Form_AccountSetings extends Zend_Form
{
    public function init()
    {
        $this->setName('accountsetings');
        $this->setAttrib('id','accountsetings');

        $pageId = new Zend_Form_Element_Hidden('pageId');
        $pageId->setValue('accountsetings');
        
        //'d.first_name','d.middle_name','d.last_name',
        // Add First middle last name
        $fName = new Zend_Form_Element_Text('fName');
        $fName->setLabel('First Name :')
        ->addFilters(array('StripTags','StringTrim'))
        ->setRequired(true)
        ->addValidator('NotEmpty')
        ->addValidator(new Zend_Validate_Alnum(array('allowWhiteSpace' => true)))
        ->addValidator(new Zend_Validate_StringLength(array('max' => 100)));
        //Midle Name
        $mName = new Zend_Form_Element_Text('mName');
        $mName->setLabel('Middle Name :')
        ->addFilters(array('StripTags','StringTrim'))
        ->addValidator(new Zend_Validate_StringLength(array('max' => 100)));

        //Last Name
        $lName = new Zend_Form_Element_Text('lName');
        $lName->setLabel('Last Name :')
        ->addFilters(array('StripTags','StringTrim'))
        ->setRequired(true)
        ->addValidator('NotEmpty')
        ->addValidator(new Zend_Validate_Alnum(array('allowWhiteSpace' => true)))
        ->addValidator(new Zend_Validate_StringLength(array('max' => 100)));

        $userName = new Zend_Form_Element_Text('userName');
        $userName->setLabel('User Id :')
        ->addFilters(array('StripTags','StringTrim', 'StringToLower'))
        ->setRequired(true)
        ->addValidator('NotEmpty')
        ->addValidator(new Zend_Validate_Alnum(array('allowWhiteSpace' => false)))
        ->addValidator(new Zend_Validate_StringLength(array('min'=>8, 'max' => 100)));
        
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email :')
            ->addFilters(array('StripTags','StringTrim','StringToLower'))
            ->setRequired(true)
            ->addValidator('NotEmpty')
            ->addValidator(new Zend_Validate_EmailAddress());

        //Add Country
        $mstcountry=new Application_Model_DbTable_MstCountry();
        
        $country = new Zend_Form_Element_Select('country');
        $country->setLabel('Country Code :')
                ->addMultiOption('0','Select Country')
                ->addMultiOptions($mstcountry->getPairWithTelCode());
        $mobile = new Zend_Form_Element_Text('mobile');
        $mobile->setLabel('Mobile :')
            ->addFilters(array('StripTags','StringTrim','StringToLower'))
            ->setRequired(true)
            ->addValidator('NotEmpty')
            ->addValidator(new Rgm_Validate_MobileNumber()
            );
        $this->addElements(array($pageId, $fName, $mName, $lName, $userName, $email,$country, $mobile));
        $this->addDisplayGroup(array('fName','mName','lName'), 'legalname');
        $this->getDisplayGroup('legalname')
                            ->setLegend('Legal Name')
                            ->setAttrib('style','border:1px solid grey;padding:10px;');
        
        $this->addDisplayGroup(array('userName', 'email', 'country', 'mobile'), 'user');
        $this->getDisplayGroup('user')
                            ->setLegend('User Info')
                            ->setAttrib('style','border:1px solid grey;padding:10px;');

        $mstSecQ=new Application_Model_DbTable_MstUserSecurityQuestions();
        
        $securityQ01 = new Zend_Form_Element_Select('securityQ01');
        $securityQ01->setLabel('Question 01 :')
                    ->addMultiOption('0','Select One')
                    ->addMultiOptions($mstSecQ->getPair());
        $securityA01 = new Zend_Form_Element_Text('securityA01');
        $securityA01->setLabel('Answer :')
                    ->addFilters(array('StripTags','StringTrim'))
                    ->setRequired(true)
                    ->addValidator('NotEmpty')
                    ->addValidator(new Zend_Validate_Alnum())
                    ->addValidator(new Zend_Validate_StringLength(array('max' => 100)));

        $securityQ02 = new Zend_Form_Element_Select('securityQ02');
        $securityQ02->setLabel('Question 02 :')
                    ->addMultiOption('0','Select One')
                    ->addMultiOptions($mstSecQ->getPair());
        $securityA02 = new Zend_Form_Element_Text('securityA02');
        $securityA02->setLabel('Answer :')
                    ->addFilters(array('StripTags','StringTrim'))
                    ->setRequired(true)
                    ->addValidator('NotEmpty')
                    ->addValidator(new Zend_Validate_Alnum())
                    ->addValidator(new Zend_Validate_StringLength(array('max' => 100)));

        $this->addElements(array($securityQ01, $securityA01, $securityQ02, $securityA02));
        $this->addDisplayGroup(array('securityQ01', 'securityA01', 'securityQ02', 'securityA02'), 'securityQuestions');
        $this->getDisplayGroup('securityQuestions')
                            ->setLegend('Security Questions')
                            ->setAttrib('style','border:1px solid grey;padding:10px;');


        $submit = new Zend_Form_Element_Submit('save');
        $submit->setAttrib('id', 'submitbutton');
        $submit->setAttrib('class','form-submit-button');
        
        $this->addElements(array($submit));
    }

}