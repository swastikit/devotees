<?php
class Admin_Form_User_EditBasic extends Zend_Form{
    public function init()
    {
        $this->setName('edituserbasic');
        $this->setAttrib('id','edituserbasic');
        
        $id = new Zend_Form_Element_Hidden('id');
        // Add userId Id Element
        $userId = new Zend_Form_Element_Text('userId');
        $userId->setLabel('User id :')
            ->addFilters(array('StripTags','StringTrim','StringToLower'))
            ->setRequired(true)
            ->addValidator('NotEmpty')
            ->addValidator(new Zend_Validate_Alnum(array('allowWhiteSpace' => false)))
            ->addValidator(new Zend_Validate_StringLength(array('max' => 100,'min'=>8)));
           
        //Role
        $role = new Zend_Form_Element_Select('role');
        $mstRole=new Application_Model_DbTable_MstUserRole();
        $role->setLabel('Role :')
                    ->setAttrib('style','width:280px;')
                    ->addMultiOption('0','Select Role')
                    ->addMultiOptions($mstRole->getPairs());
                   
        // Add an Email Id Element
        $EmailId = new Zend_Form_Element_Text('email');
        $EmailId->setLabel('Email :')
            ->setAttrib('style','width:280px;')
            ->addFilters(array('StripTags','StringTrim','StringToLower'))
            ->setRequired(true)
            ->addValidator('NotEmpty')
            ->addValidator(new Zend_Validate_EmailAddress());
            
        //Add Country
        $mstcountry=new Application_Model_DbTable_MstCountry();
        $country = new Zend_Form_Element_Select('country');
        $country->setLabel('Country Code :')
                ->setAttrib('style','width:280px;')
                ->addMultiOption('0','Select Country')
                ->addMultiOptions($mstcountry->getPairWithTelCode());
            
        //Mobile No
        $mobile = new Zend_Form_Element_Text('mobile');
        $mobile->setLabel('Mobile :')
            ->addFilters(array('StripTags','StringTrim','StringToLower'))
            ->setRequired(true)
            ->addValidator('NotEmpty')
            ->addValidator(new Rgm_Validate_MobileNumber()
            );
        
        //Is Active
        $active = new Zend_Form_Element_Select('active');
        $active->setLabel('Active :')
                ->setMultiOptions(array('Y'=>'Yes', 'N'=>'No'));

        //Is Temporary
        $temporarypwd = new Zend_Form_Element_Select('temporaryPwd');
        $temporarypwd->setLabel('Temporary Password :')
                ->setMultiOptions(array('Y'=>'Yes', 'N'=>'No'));

        //Is Blocked
        $blocked = new Zend_Form_Element_Select('blocked');
        $blocked->setLabel('Blocked :')
                ->setMultiOptions(array('Y'=>'Yes', 'N'=>'No'));
        //blockedreason
        $blockedreason = new Zend_Form_Element_Text('blockedReason');
        $blockedreason->setLabel('Reason for blocking :')->setAttrib('style','width:330px;');

        //Remarks
        $remarks = new Zend_Form_Element_Text('remarks');
        $remarks->setLabel('Remarks :')
        ->setAttrib('style','width:330px;');

        $this->addElements(array($id,$userId,$role,$EmailId,$country,$mobile,$active,$temporarypwd,$blocked,$blockedreason,$remarks));
        /*
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('class', 'form-submit-button');
        $this->addElements(array($submit));
        */
        /*
        $this->setDecorators(array(array('ViewScript', array('viewScript' => 'user/editbasic.form.phtml'))
        ));
        */
    }
}
?>