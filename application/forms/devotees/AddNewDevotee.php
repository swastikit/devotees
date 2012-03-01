<?php

class Application_Form_Devotees_AddNewDevotee extends Zend_Form
{
    public function init(){
    //parent::__construct($options);
   $this->setName('addnewdevotee')
         ->setAttrib('id','addnewdevotee')
         ->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

    //SUB FORM for Preliminary Information aboout Devotee

$SubForm_BasicInfo = new Zend_Form_SubForm();
        
        $DevPic = new Zend_Form_Element_File('uplphoto');
        $DevPic ->setLabel('Upload Your Photo Here')
                ->setName('uplphoto');
        $path     = 'photos/';
        $DevPic ->setDestination($path) //->setMaxFileSize(2097152)
                ->addValidator('Size',true,array('max'=>'4096000','messages'=>'The maximum permitted image file size is %max% selected image file size is %size%.'))
                ->addValidator('Extension',true,array('jpg,jpeg', 
                                                      'messages' => 'photo with only jpg, jpeg or gif format 
                                                                     are accepted for uploading profile.'));
                //->setRequired(true)
                //->addValidator('NotEmpty');
            
    //->setValidators(array('Size'=>array('min' => 20,'max' =>2097152),'Count' =>array('min' => 1,'max' => 3)))
    //->addValidator('IsImage');
    //to disable the viewrenderer use 
    //$this->_helper->viewRenderer->setNoRender(true);
    
    $Fname = new Zend_Form_Element_Text('first_name');
    $Fname ->setLabel('First Name*')
           ->setName('first_name')
           ->setAttrib('placeholder','First Name')
           ->addValidator(new Zend_Validate_Alnum(array('allowWhiteSpace' => true))) 
           ->setRequired(true)
           ->addValidator('NotEmpty')
           ->addFilters(array('StripTags','StringTrim'));
               
    $Mname = new Zend_Form_Element_Text('middle_name');
    $Mname ->setLabel('Middle Name*')
           ->setName('middle_name')
           ->setAttrib('placeholder','Middle Name')
           ->addValidator(new Zend_Validate_Alnum(array('allowWhiteSpace' => true))) 
           ->setRequired(true)
           ->addValidator('NotEmpty')
           ->addFilters(array('StripTags','StringTrim'));
    
    $Lname = new Zend_Form_Element_Text('last_name');
    $Lname ->setLabel('Last Name*')
           ->setName('last_name')
           ->setAttrib('placeholder','Last Name')
           ->addValidator(new Zend_Validate_Alnum(array('allowWhiteSpace' => true))) 
           ->setRequired(true)
           ->addValidator('NotEmpty')
           ->addFilters(array('StripTags','StringTrim'));
               
    $Day = new  Zend_Form_Element_Select('day');
    $Day ->setLabel('Date of Birth*')
         ->setName('day')
         ->setRequired(true)
         ->setMultiOptions(Rgm_Basics::getDates())
         ->addValidator('NotEmpty')
         ->addFilters(array('StripTags','StringTrim'));
    
    $Month = new Zend_Form_Element_Select('month');
    $Month ->setName('month')
           ->setRequired(true)
           ->setMultiOptions(Rgm_Basics::getMonths())
           ->addValidator('NotEmpty')
           ->addFilters(array('StripTags','StringTrim'));
    
    $Year = new Zend_Form_Element_Select('year');      
    $Year ->setName('year')
          ->setRequired(true)
          ->setMultiOptions(Rgm_Basics::getYears(1912,2012))
          ->addValidator('NotEmpty')
          ->addFilters(array('StripTags','StringTrim'));
       
    $Gender = new Zend_Form_Element_Radio('gender');
    $Gender ->setName('gender')
            ->setLabel('Gender*')
            ->addValidator(new Zend_Validate_Alnum(array('allowWhiteSpace' => true)))
            ->setRequired(true)
            ->setMultiOptions(array('M' => 'Male','F' => 'Female'))
            ->setSeparator('')
            ->addFilters(array('StripTags','StringTrim'));           
            
    $centr  = new Application_Model_DbTable_MstCenter();
    $CenterOptions =$centr->getKeyValues();
    $Center = new Zend_Form_Element_Select('center');
    $Center ->setName('center')
            ->setLabel('Center*')
            ->setMultiOptions($CenterOptions)
            ->setRequired(false)
            ->addValidator('NotEmpty');
    
    $con=new Application_Model_DbTable_MstCounselor();    
    $Counselor = new Zend_Form_Element_Select('counselor');
    $CounselorOptions =$con->getKeyValues();
    $Counselor ->setName('counselor')
               ->setMultiOptions($CounselorOptions)
               ->setLabel('Counselor')
               ->setRequired(false)
               ->addValidator('NotEmpty')
               ->addValidator(new Zend_Validate_Alnum(array('allowWhiteSpace' => true)))
               ->addFilters(array('StripTags','StringTrim', 'StringToLower'));
    
    $mentr=new Application_Model_DbTable_MstAstCounselor();
    $MentorOptions =$mentr->getKeyValues();
    $Mentor = new Zend_Form_Element_Select('mentor');
    $Mentor ->setName('mentor')
            ->setLabel('Mentor')
            ->setMultioptions($MentorOptions )
            ->addValidator(new Zend_Validate_Alnum(array('allowWhiteSpace' => true)))
            ->setRequired(false)
            ->addValidator('NotEmpty')
            ->addFilters(array('StripTags','StringTrim', 'StringToLower'));
                  
    $Mobile = new Zend_Form_Element_Text('mobile');
    $Mobile ->setName('mobile')
            ->setLabel('Mobile No')
            ->addFilters(array('StripTags','StringTrim'))
            ->addValidator('NotEmpty')
            ->addValidator(new Rgm_Validate_MobileNumber())
            ->addValidator(new Zend_Validate_Alnum(array('allowWhiteSpace' => true)));
            
    $CC = new Application_Model_DbTable_MstCountry();
    $CCOptions =$CC->getPairWithTelCode(); 
        
    $CountryCode = new Zend_Form_Element_Select('cc');
    $CountryCode ->setName('cc')
                 ->setLabel('Country Code*')
                 ->setMultiOptions($CCOptions)                 
                 ->setRequired(true)
                 ->addValidator('NotEmpty');
    
    $PhoneNumber = new Zend_Form_Element_Text('phone_number');
    $PhoneNumber ->setName('phone_number')
                 ->setLabel('Phone Number(R/O)')
                 ->addValidator(new Zend_Validate_Alnum(array('allowWhiteSpace' => true)))
                 ->addFilters(array('StripTags','StringTrim'))
                 ->setRequired(false);
    
    $Email = new Zend_Form_Element_Text('email');
    $Email ->setName('email')
           ->setLabel('Email Id:')
           ->addValidator('NotEmpty')
           ->addValidator('EmailAddress')
           ->addFilters(array('StripTags','StringTrim'));
                    
    $CounseleeStatus = new Application_Model_DbTable_MstCounseleeStatus();
    $CounseleeStatusOptions = $CounseleeStatus->getCounseleeStatus();
    $CounsellingStatus = new Zend_Form_Element_Select('counselee_status');
    $CounsellingStatus ->setName('counselee_status')
                       ->setLabel('Counselling Status*')
                       ->setRequired(true)
                       ->setMultiOptions($CounseleeStatusOptions);
    
    $ActiveStatus = new Zend_Form_Element_Select('active_status');
    $ActiveStatus ->setName('active_status')
                  ->setLabel('Active Status*')
                  ->setMultiOptions(array('A'=>'Active',
                                          'I'=>'Inactive',
                                          'E'=>'Deceased'))
                  ->setRequired(true);

$SubForm_BasicInfo->addElements(array($DevPic,$Fname,$Mname,$Lname,$Day,$Month,$Year,$Gender,
                                      $CountryCode,$Mobile,$PhoneNumber,$Email,$Center,
                                      $Counselor,$Mentor,$CounsellingStatus,$ActiveStatus));
/*Personal Information */

$SubForm_Personal_Info = new Zend_Form_SubForm();
    
    $langsknownOptions = new Application_Model_DbTable_MstLanguage();
    $langsknownOptions =$langsknownOptions->getLanguagelist();     
           
    $MotherTongue = new Zend_Form_Element_select('mother_tongue');
    $MotherTongue ->setName('mother_tongue')
                  ->setLabel('Mother Tongue*')
                  ->setMultiOptions($langsknownOptions)
                  ->addFilters(array('StripTags','StringTrim'))
                  ->setRequired(true);
                  
    $LanguagesKnown = new Zend_Form_Element_MultiSelect('languages_known');
    $LanguagesKnown ->setName('languages_known')
                    ->setLabel('Languages Known* ')
                    ->setMultiOptions($langsknownOptions)
                    ->setRequired(true);

    $BldGrp = new Zend_Form_Element_Select('bld_grp');
    $BldGrp->setName('bld_grp')
           ->setLabel('Blood Group')
           ->setMultiOptions(Rgm_Basics::getBloodGroupsAsoArr());
    
    $Religion = new Application_Model_DbTable_MstReligion();
    $ReligionOptions =$Religion->getReligionslist();
    
    $PrevReligion = new Zend_Form_Element_Select('previous_religion');
    $PrevReligion->setName('previous_religion')
                 ->setLabel('Previous Religion*')
                 ->setMultiOptions($ReligionOptions)
                 ->setRequired(true);
    
$SubForm_Personal_Info->addElements(array($MotherTongue,$BldGrp,$PrevReligion,$LanguagesKnown));                                           


//PRESENT AND PERMENANT ADDRESS INFORMATION
$SubForm_Address_Info = new Zend_Form_SubForm();
               
    $NativePlace = new Zend_Form_Element_Text('native_place');
    $NativePlace ->setName('native_place')
                 ->setLabel('Native Place* ')
                 ->setRequired(true)
                 ->addFilters(array('StripTags','StringTrim','StringToLower'));
                 
    $Statedb = new Application_Model_DbTable_MstState();
    $stateOptions = $Statedb->getStateKeyValues(); 
    
    $NativeState = new Zend_Form_Element_Select('native_state');
    $NativeState ->setName('native_state')
                 ->SetLabel('Native State* ')
                 ->setMultiOptions($stateOptions)
                 ->setRequired(true)
                 ->addFilters(array('StripTags','StringTrim','StringToLower'));
    
    $PresentAddLine1 = new Zend_Form_Element_Text('present_addline1');
    $PresentAddLine1 ->setName('present_addline1')
                     ->setLabel('PlotNo.\Room No.\Wing*')
                     ->setRequired(true)
                     ->addFilters(array('StripTags','StringTrim','StringToLower'));
             
    $PresentAddLine2 = new Zend_Form_Element_Text('present_addline2');
    $PresentAddLine2 ->setName('present_addline2')
                     ->setLabel('Bulding\Chawl')
                     ->setRequired(true)
                     ->addFilters(array('StripTags','StringTrim','StringToLower'));
    
    $localitydb = new Application_Model_DbTable_MstLocality();
    $localityOptions = $localitydb->getLocalityKeyValues();
          
    $PresentLocality =new Zend_Form_Element_Select('present_locality');
    $PresentLocality ->setName('present_locality')
                     ->setLabel('Locality*')
                     ->setMultiOptions($localityOptions)
                     ->setRequired(true)
                     ->addFilters(array('StripTags','StringTrim','StringToLower'));

    $citydb = new Application_Model_DbTable_MstCity();
    $cityOptions = $citydb->getCityKeyValues();
             
    $PresentCity  =new Zend_Form_Element_Select('present_city');
    $PresentCity  ->setName('present_city')
                  ->SetLabel('City')
                  ->setMultiOptions($cityOptions)
                  ->setRequired(true)
                  ->addFilters(array('StripTags','StringTrim','StringToLower'));
    
    $PresentState =new Zend_Form_Element_Select('present_state');
    $PresentState ->setName('present_state')
                  ->setLabel('State* ')
                  ->setMultiOptions($stateOptions)
                  ->setRequired(true)
                  ->addFilters(array('StripTags','StringTrim','StringToLower'));
                 
    $countrydb = new Application_Model_DbTable_MstCountry();
    $countryOptions = $countrydb->getKeyValues();
                 
    $PresentCountry =new Zend_Form_Element_Select('present_country');
    $PresentCountry ->setName('present_country')
                    ->SetLabel('Country* ')
                    ->setMultiOptions($countryOptions)
                    ->setRequired(true)
                    ->addFilters(array('StripTags','StringTrim','StringToLower'));
    
    $PresentZipCode =new Zend_Form_Element_Text('present_zip_code');
    $PresentZipCode ->setName('present_zip_code')
                    ->setLabel('Zip Code* ')
                    ->setRequired(true)
                    ->addFilters(array('StripTags','StringTrim','StringToLower'));
    
                 
    $PermenantAddLine1 = new Zend_Form_Element_Text('permenant_addline1');
    $PermenantAddLine1 ->setName('permenant_addline1')
                       ->setLabel('PlotNo.\Room No.\Wing*')
                       ->setRequired(true)
                       ->addFilters(array('StripTags','StringTrim','StringToLower'));
             
    $PermenantAddLine2 = new Zend_Form_Element_Text('permenant_addline2');
    $PermenantAddLine2 ->setName('permenant_addline2')
                       ->setLabel('Bulding\Chawl')
                       ->setRequired(true)
                       ->addFilters(array('StripTags','StringTrim','StringToLower'));
          
    $PermenantLocality =new Zend_Form_Element_Select('permenant_locality');
    $PermenantLocality ->setName('permenant_locality')
                       ->setLabel('Locality*')
                       ->setMultiOptions($localityOptions)
                       ->setRequired(true)
                       ->addFilters(array('StripTags','StringTrim','StringToLower'));
                       
    $PermenantCity  =new Zend_Form_Element_Select('permenant_city');
    $PermenantCity  ->setName('permenant_city')
                    ->SetLabel('City')
                    ->setMultiOptions($cityOptions)
                    ->setRequired(true)
                    ->addFilters(array('StripTags','StringTrim','StringToLower'));
    
    $PermenantState =new Zend_Form_Element_Select('permenant_state');
    $PermenantState ->setName('permenant_state')
                    ->setLabel('State* ')
                    ->setMultiOptions($stateOptions)
                    ->setRequired(true)
                    ->addFilters(array('StripTags','StringTrim','StringToLower'));
                 
    $PermenantCountry =new Zend_Form_Element_Select('permenant_country');
    $PermenantCountry ->setName('permenant_country')
                      ->SetLabel('Country* ')
                      ->setMultiOptions($countryOptions)
                      ->setRequired(true)
                      ->addFilters(array('StripTags','StringTrim','StringToLower'));
    
    $PermenantZipCode =new Zend_Form_Element_Text('permenant_zip_code');
    $PermenantZipCode ->setName('permenant_zip_code')
                      ->setLabel('Zip Code* ')
                      ->setRequired(true)
                      ->addFilters(array('StripTags','StringTrim','StringToLower'));
                      
$SubForm_Address_Info->addElements(array($NativePlace,$NativeState,$PresentAddLine1,$PresentAddLine2,$PresentLocality,
                                         $PresentCity,$PresentState,$PresentCountry,$PresentZipCode,
                                         $PermenantAddLine1,$PermenantAddLine2,$PermenantCity,$PermenantCountry,
                                         $PermenantLocality,$PermenantState,$PermenantZipCode));                  


//SUB FORM for Family Information

$SubForm_Family_Info=new Zend_Form_SubForm();
                 
    $Father =new Zend_Form_Element_Text('father_name');
    $Father ->setName('father_name')
            ->SetLabel('Father Name* ')
            ->setRequired(true)
            ->addFilters(array('StripTags','StringTrim','StringToLower'));
                
    $Mother =new Zend_Form_Element_Text('mother_name');
    $Mother ->setName('mother_name')
            ->setLabel('Mother Name* ')
            ->setRequired(true)
            ->addFilters(array('StripTags','StringTrim','StringToLower'));
    
    $AsramStatus = new Application_Model_DbTable_MstAsram();
    $AsramStatusOptions = $AsramStatus->listPairs();              
    $MaritalStatus = new Zend_Form_Element_Select('marital_status');
    $MaritalStatus ->setName('marital_status')
                   ->setLabel('Marital Status* ')
                   ->setMultiOptions($AsramStatusOptions)
                   ->setRequired(true)
                   ->addFilters(array('StripTags','StringTrim','StringToLower'));
    
$SubForm_Family_Info->addElements(array($Father,$Mother,$MaritalStatus));

//SUB FORM Education Info

$SubForm_Education_Info = new Zend_Form_SubForm();               
    $isgurukuli = new Zend_Form_Element_Select('gurukuli');
    $isgurukuli->setName('gurukuli')
               ->setLabel('Is the Devotee Gurukuli* ')
               ->setMultiOptions(array(
                                        array('value'=>'Yes','key'=>'y'),
                                        array('value'=>'No','key'=>'n')
                                        ))
               ->setRequired(false)
               ->addFilters(array('StripTags','StringTrim','StringToLower'));
               
    $highesteducationdb = new Application_Model_DbTable_MstEducationCategory();
    $highesteducationOptions = $highesteducationdb->getEduCategory(); 
    $HighEducation = new Zend_Form_Element_Select('highest_education');
    $HighEducation->setName('highest_education')
                  ->setLabel('Highest Education* ')
                  ->setMultiOptions($highesteducationOptions)
                  ->setRequired(true)
                  ->addFilters(array('StripTags','StringTrim','StringToLower'));
    
    $EduDescription = new Zend_Form_Element_Text('education_description');
    $EduDescription->setName('education_description')
                   ->setLabel('Education Description* ')
                   ->addValidator(new Zend_Validate_Alnum(array('allowWhiteSpace' => true)))
                   ->setRequired(true)
                   ->addFilters(array('StripTags','StringTrim','StringToLower'));
                   
    $Occupationdb = new Application_Model_DbTable_MstOccupation();
    $OccupationOptions = $Occupationdb->listOccupation();                
    $Occupation = new Zend_Form_Element_Select('occupation');
    $Occupation->setName('occupation')
               ->setLabel('Occupation* ')
               ->setMultiOptions($OccupationOptions)
               ->setRequired(true)
               ->addFilters(array('StripTags','StringTrim','StringToLower'));
               
    $PresDesignation = new Zend_Form_Element_Text('designation');
    $PresDesignation->setName('designation')
                    ->setLabel('Present Designation ')
                    ->setRequired(true)
                    ->addFilters(array('StripTags','StringTrim','StringToLower'));
                    
    $Merits_Awards = new Zend_Form_Element_Text('merits_awards');
    $Merits_Awards->setName('merits_awards')
                  ->setLabel('Merits Awards')
                  ->setRequired(true)
                  ->addFilters(array('StripTags','StringTrim','StringToLower'));
                  
    $SkillSet = new Zend_Form_Element_Text('skill_sets');
    $SkillSet->setName('skill_sets')
             ->setLabel('Skill Sets ')
             ->setRequired(true)
             ->addFilters(array('StripTags','StringTrim','StringToLower'));

$SubForm_Education_Info->addElements(array($isgurukuli,$HighEducation,$EduDescription,$Occupation,
                                           $PresDesignation,$Merits_Awards,$SkillSet));

//SUBFORM for Office  Information
$SubForm_Office_Info = new Zend_Form_SubForm();         

    $Organization = new Zend_Form_Element_Text('office_name');
    $Organization->setName('office_name')
                 ->setLabel('Company Name')
                 ->setRequired(true)
                 ->addFilters(array('StripTags','StringTrim','StringToLower'));
             
    $OffAddressLine1 = new Zend_Form_Element_Text('office_address_line1');
    $OffAddressLine1->setName('office_address_line1')
                    ->setLabel('Address')
                    ->setRequired(true)
                    ->addFilters(array('StripTags','StringTrim','StringToLower'));
               
    $OffAddressLine2 = new Zend_Form_Element_Text('office_address_line2');
    $OffAddressLine2->setName('office_address_line2')
                    ->setLabel('Address')
                    ->setRequired(true)
                    ->addFilters(array('StripTags','StringTrim','StringToLower'));
             
    $OffLocality = new Zend_Form_Element_Select('office_locality');
    $OffLocality->setName('office_locality')
                ->setLabel('Locality ')
                ->setMultiOptions($localityOptions)
                ->setRequired(true);
             
    $OffCity = new Zend_Form_Element_Select('office_city');
    $OffCity->setName('office_city')
            ->setLabel('City')
            ->setMultiOptions($cityOptions)
            ->setRequired(true);
             
    $OffState = new Zend_Form_Element_Select('office_state');
    $OffState->setName('office_state')
             ->setLabel('State')
             ->setMultiOptions($stateOptions)
             ->setRequired(true);
             
    $OffCountry = new Zend_Form_Element_Select('office_country');
    $OffCountry->setName('office_country')
               ->setLabel('Country')
               ->setMultiOptions($countryOptions)
               ->setRequired(true);
             
    $OffZip = new Zend_Form_Element_Text('office_zip_code');
    $OffZip->setName('office_zip_code')
           ->setLabel('Zip-Code')
           ->setRequired(true)
           ->addFilters(array('StripTags','StringTrim','StringToLower'));
           
    $OffPhone = new Zend_Form_Element_Text('office_phone');
    $OffPhone->setName('office_phone')
             ->setLabel('Office Phone')
             ->setRequired(true)
             ->addFilters(array('StripTags','StringTrim','StringToLower'));
                  
$SubForm_Office_Info->addElements(array($Organization,$OffAddressLine1,$OffAddressLine2,$OffLocality,$OffCity,$OffState,$OffCountry,$OffZip,$OffPhone));
       
//SUB FORM for Devotional Information
    
$SubForm_Devotional_Info = new Zend_Form_SubForm();

    $BeganChantingFromDay = new  Zend_Form_Element_Select('bgn_chan_from_day');
    $BeganChantingFromDay->setName('bgn_chan_from_day')
                         ->setLabel('Started Chanting From')
                         ->setMultiOptions(Rgm_Basics::getDates())
                         ->addValidator('NotEmpty')
                         ->addFilters(array('StripTags','StringTrim'));
    
    $BeganChantingFromMonth = new  Zend_Form_Element_Select('bgn_chan_from_month');
    $BeganChantingFromMonth->setName('bgn_chan_from_month')
                           ->setMultiOptions(Rgm_Basics::getMonths())
                           ->addValidator('NotEmpty')
                           ->addFilters(array('StripTags','StringTrim'));
                      
    $BeganChantingFromYear = new  Zend_Form_Element_Select('bgn_chan_from_year');
    $BeganChantingFromYear->setName('bgn_chan_from_year')
                          ->setMultiOptions(Rgm_Basics::getYears(1965,2012))
                          ->addValidator('NotEmpty')
                          ->addFilters(array('StripTags','StringTrim'));
    
    $BeganChantingNA = new  Zend_Form_Element_Select('bgn_chan_from_na');
    $BeganChantingNA->setLabel('Chants Hare Krishna Mahamantra?')
                    ->setName('bgn_chan_from_na')
                    ->SetMultiOptions(array(
                                                array('value'=>'Yes','key'=>'Y'),
                                                array('value'=>'No','key'=>'N'),
                                                ));
    
    $NumberOfRoundsPresentlyChanting = new  Zend_Form_Element_Select('no_rou_pres_chanting');
    $NumberOfRoundsPresentlyChanting->setLabel('Number Of Rounds Presently Chanting')
                                    ->setName('no_rou_pres_chanting')
                                    ->SetMultiOptions(array(
array('value'=>'1','key'=>'1'),array('value'=>'2','key'=>'2'),array('value'=>'3','key'=>'3'),array('value'=>'4','key'=>'4'),
array('value'=>'5','key'=>'5'),array('value'=>'6','key'=>'6'),array('value'=>'7','key'=>'7'),array('value'=>'8','key'=>'8'),
array('value'=>'9','key'=>'9'),array('value'=>'10','key'=>'10'),array('value'=>'11','key'=>'11'),array('value'=>'12','key'=>'12'),
array('value'=>'13','key'=>'13'),array('value'=>'14','key'=>'14'),array('value'=>'15','key'=>'15'),array('value'=>'16','key'=>'16'),
array('value'=>'17','key'=>'17'),array('value'=>'18','key'=>'18'),array('value'=>'19','key'=>'19'),array('value'=>'20','key'=>'20'),
array('value'=>'21','key'=>'21'),array('value'=>'22','key'=>'22'),array('value'=>'23','key'=>'23'),array('value'=>'24','key'=>'24')
                                                           ));
    
    $Chanting16RoundsSinceDay = new  Zend_Form_Element_Select('chan_16_rounds_day');
    $Chanting16RoundsSinceDay ->setLabel('Chanting 16 Rounds(or more) Since')
                              ->setName('chan_16_rounds_day')
                              ->setMultiOptions(Rgm_Basics::getDates());
                              
    $Chanting16RoundsSinceMonth = new Zend_Form_Element_Select('chan_16_rounds_month');
    $Chanting16RoundsSinceMonth ->setName('chan_16_rounds_month')
                                ->setMultiOptions(Rgm_Basics::getMonths());
    
    $Chanting16RoundsSinceYear = new Zend_Form_Element_Select('chan_16_rounds_year');
    $Chanting16RoundsSinceYear ->setName('chan_16_rounds_year')
                               ->setMultiOptions(Rgm_Basics::getYears(1965,2012));
                           
    $IntroBy = new Zend_Form_Element_Text('intro_by');
    $IntroBy->setName('intro_by')
            ->setLabel('Introduced By*')
            ->setRequired(true);
        
    $IntroCenter = new Zend_Form_Element_Select('intro_center');
    $IntroCenter->setName('intro_center')
                ->setLabel('Introduction center*')
                ->setMultiOptions($CenterOptions)
                ->setRequired(true);
                
    $YearOfIntroduction = new Zend_Form_Element_Select('year_introduction');
    $YearOfIntroduction->setName('year_introduction')
                       ->setLabel('Year of Introduction')
                       ->setMultiOptions(Rgm_Basics::getYears(1965,2012))
                       ->setRequired(true);  
    
    $HarinamInitiated = new Zend_Form_Element_Select('harinam_initiatn_na');
    $HarinamInitiated->setLabel('Harinam Initiated?')
                     ->setName('harinam_initiatn_na')
                     ->setMultiOptions(array(
                                             array('value'=>'Yes','key'=>'y'),
                                             array('value'=>'No','key'=>'n')
                                             ));
    
    $DayOfHarinamInitiation= new Zend_Form_Element_Select('harinam_initiatn_day');
    $DayOfHarinamInitiation ->setLabel('Date Of Harinam Initiation')
                            ->setName('harinam_initiatn_day')
                            ->setMultiOptions(Rgm_Basics::getDates());
                               
    $MonthOfHarinamInitiation= new Zend_Form_Element_Select('harinam_initiatn_month');
    $MonthOfHarinamInitiation ->setName('harinam_initiatn_month')
                              ->setMultiOptions(Rgm_Basics::getMonths());
    
    $YearOfHarinamInitiation= new Zend_Form_Element_Select('harinam_initiatn_year');
    $YearOfHarinamInitiation->setName('harinam_initiatn_year')
                            ->setMultiOptions(Rgm_Basics::getYears(1965,2012));
                               
    //$InitiatedName = new Zend_Form_Element_Select('initiated_name_combo');
    //$InitiatedName->setName('initiated_name_combo')
      //            ->setLabel('Initiated Name')
        //          ->setRequired(false);
    
    //$AddNewInitiatedName = new Zend_Form_Element_Button('add_new_initiated_name');
    //$AddNewInitiatedName->setName('add_new_initiated_name')
      //                  ->setValue('New');
                    
    $SpiritualMasterdb =  new Application_Model_DbTable_MstGuru();
    $SpiritualMasterOptions = $SpiritualMasterdb->seekGuru();
    $SpiritualMaster = new Zend_Form_Element_Select('spiritual_master');
    $SpiritualMaster ->setName('spiritual_master')
                     ->setLabel('Spiritual Master')
                     ->setMultiOptions($SpiritualMasterOptions);
                    
    $BrahmanInitiated = new Zend_Form_Element_Select('brahman_initiated_na');
    $BrahmanInitiated->setName('brahman_initiated_na')
                     ->setLabel('Brahman Initiated?')
                     ->setMultiOptions(array(
                                              array('value'=>'Yes','key'=>'Y'),
                                              array('value'=>'No','key'=>'N')
                                            ));
    
    $DayOfBrahmanInitiation = new Zend_Form_Element_Select('date_of_brahman_initiation');
    $DayOfBrahmanInitiation->setName('date_of_brahman_initiation')
                           ->setLabel('Date Of Brahman Initiation')
                           ->setMultiOptions(Rgm_Basics::getDates());
    
    $MonthOfBrahmanInitiation = new Zend_Form_Element_Select('brahman_initiation_month'); 
    $MonthOfBrahmanInitiation->setName('brahman_initiation_month')
                             ->setMultiOptions(Rgm_Basics::getMonths());
    
    $YearOfBrahmanInitiation = new Zend_Form_Element_Select('brahman_initiation_year');
    $YearOfBrahmanInitiation->setName('brahman_initiation_year')
                            ->setMultiOptions(Rgm_Basics::getYears(1965,2012));
                            
    $SanyasInitiated = new Zend_Form_Element_Select('sanyas_initiated_na');
    $SanyasInitiated->setName('sanyas_initiated_na')
                    ->setLabel('Sanyas Initiated?')
                    ->setMultiOptions(array(
                                            array('value'=>'Yes','key'=>'Y'),
                                            array('value'=>'No','key'=>'N')
                                           ));
    
    $DayOfSanyasInitiation = new Zend_Form_Element_Select('sanyas_initiation_day');
    $DayOfSanyasInitiation->setName('sanyas_initiation_day')
                          ->setLabel('Date of Sanyas Initiation')
                          ->setMultiOptions(Rgm_Basics::getDates());
    
    $MonthOfSanyasInitiation = new Zend_Form_Element_Select('sanyas_initiation_month');
    $MonthOfSanyasInitiation->setName('sanyas_initiation_month')
                            ->setMultiOptions(Rgm_Basics::getMonths());
    
    $YearOfSanyasInitiation = new Zend_Form_Element_Select('sanyas_initiation_year');
    $YearOfSanyasInitiation->setName('sanyas_initiation_year')
                           ->setMultiOptions(Rgm_Basics::getYears(1965,2012));
                           
    $SanyasSpiritualMaster = new Zend_Form_Element_Select('sanyas_spiritual_master') ;
    $SanyasSpiritualMaster->setName('sanyas_spiritual_master')
                          ->setLabel('Sanyas Spiritual Master')
                          ->setMultiOptions($SpiritualMasterOptions);
    
    $SpiritualNamedb = new Application_Model_DbTable_MstSpiritualName();
    $SpiritualNameOptions = $SpiritualNamedb->getKeyValues();
    
    $SanyasName = new Zend_Form_Element_Select('sanyas_name');
    $SanyasName->setLabel('Sanyas Name')
               ->setMultiOptions($SpiritualNameOptions);
    
    $SanyasTitle = new Zend_Form_Element_Radio('sanyas_title');
    $SanyasTitle->setName('sanyas_title')
                ->setLabel('Sanyas Title')
                ->setMultiOptions(array(
                                     array('value'=>'Goswami','key'=>'GOSWAMI'),
                                     array('value'=>'Swami','key'=>'SWAMI')
                                  ));
                                                                    
$SubForm_Devotional_Info->addElements(array($BeganChantingFromDay,$BeganChantingFromMonth,$BeganChantingFromYear,
                                            $BeganChantingNA,$NumberOfRoundsPresentlyChanting,$Chanting16RoundsSinceDay,
                                            $Chanting16RoundsSinceMonth,$Chanting16RoundsSinceYear,$IntroBy,$IntroCenter,
                                            $YearOfIntroduction,$HarinamInitiated,$DayOfHarinamInitiation,$MonthOfHarinamInitiation,
                                            $YearOfHarinamInitiation,//$InitiatedName,$AddNewInitiatedName,
                                            $SpiritualMasterdb,$SpiritualMaster,$BrahmanInitiated,$DayOfBrahmanInitiation,
                                            $MonthOfBrahmanInitiation,$YearOfBrahmanInitiation,$DayOfSanyasInitiation,
                                            $MonthOfSanyasInitiation,$YearOfSanyasInitiation,$SanyasSpiritualMaster,
                                            $SpiritualNamedb,$SanyasName,$SanyasTitle));
                                      
$SubForm_ServicesRendered_Info =new Zend_Form_SubForm();

//    $ServicesRenderedDB = new Application_Model_DbTable_MstServices();
//    $ServicesRenderedOptions= $ServicesRenderedDB->getKeyValues();
//    
//    $ServicesRendered = new Zend_Form_Element_Multiselect('services_rendered');
//    $ServicesRendered->setName('services_rendered')
//                     ->setLabel('Services Rendered')
//                     ->setMultiOptions($ServicesRenderedOptions);
//                     
//    $ServicesInterestedToRender = new Zend_Form_Element_Multiselect('interest_render_services'); 
//    $ServicesInterestedToRender->setName('interest_render_services')
//                               ->setLabel('Interested in Rendering Services')
//                               ->setMultiOptions($ServicesRenderedOptions);
//                               
    $Remarks = new Zend_Form_Element_Textarea('remarks');
    $Remarks->setName('remarks')
            ->setLabel('Remarks On the Devotee')
            ->setAttrib('rows', '4')
            ->setAttrib('cols', '100');
                           
$SubForm_ServicesRendered_Info->addElements(array(//$ServicesRendered,
                                                  //$ServicesInterestedToRender,
                                                  $Remarks
                                                  ));

//Adding SUBFORMS
    $this->addSubForms(array(
                             'basic_info'      => $SubForm_BasicInfo,
                             'personal_info'   => $SubForm_Personal_Info,
                             'address_info'    => $SubForm_Address_Info,
                             'family_info'     => $SubForm_Family_Info,
                             'education_info'  => $SubForm_Education_Info,
                             'office_info'     => $SubForm_Office_Info,
                             'devotional_info' => $SubForm_Devotional_Info,
                             'services_info'   => $SubForm_ServicesRendered_Info
                            ));
    
  }
  
/*
 *
 * Prepare a sub form for display
 *
 * @param  string|Zend_Form_SubForm $spec
 * @return Zend_Form_SubForm
 */
 
    public function prepareSubForm($spec)
    {
        if (is_string($spec)) {
            $subForm = $this->{$spec};
        } 
        
        elseif ($spec instanceof Zend_Form_SubForm) {
            $subForm = $spec;
        } 
        
        else {
            throw new Exception('Invalid argument passed to ' .__FUNCTION__ . '()');
        }
        $this->setSubFormDecorators($subForm)
             ->addSubmitButton($subForm)
             ->addSubFormActions($subForm);
        return $subForm;
    }

/* Add form decorators to an individual sub form
 * @param  Zend_Form_SubForm $subForm
 * @return Application_Form_Devotees_AddNewDevotee
 */
    
    public function setSubFormDecorators(Zend_Form_SubForm $subForm)
    {
        $subForm->setDecorators(array('FormElements',
                                array('HtmlTag', 
                                array('tag' => 'dl',
                                      'class' => 'zend_form')),
                                      'Form',
                 ));
        
        
        //if ($subForm->getName() == 'basic_info') {
//            $subForm->setDecorators(array('PrepareElements',
//                                    array('ViewScript', 
//                                    array('viewScript' => 'devotees/addNewDevotee.phtml')),
//           ));
//        
//        }
//        
//        if ($subForm->getName() == 'personal_info') {
//            $subForm->setDecorators(array('PrepareElements',
//                                    array('ViewScript', 
//                                    array('viewScript' => 'devotees/addNewDevotee.phtml')),
//           ));
//
//        }
//
//        if ($subForm->getName() == 'address_info') {
//            $subForm->setDecorators(array('PrepareElements',
//                                    array('ViewScript', 
//                                    array('viewScript' => 'addNewDevotee.phtml')),
//           ));
//
//        }
//        
//        if ($subForm->getName() == 'family_info') {
//            $subForm->setDecorators(array('PrepareElements',
//                                    array('ViewScript', 
//                                    array('viewScript' => 'addNewDevotee.phtml')),
//           ));
//
//        }
//        
//        if ($subForm->getName() == 'education_info') {
//            $subForm->setDecorators(array('PrepareElements',
//                                    array('ViewScript', 
//                                    array('viewScript' => 'addNewDevotee.phtml')),
//           ));
//
//        }
//        
//        if ($subForm->getName() == 'office_info') {
//            $subForm->setDecorators(array('PrepareElements',
//                                    array('ViewScript', 
//                                    array('viewScript' => 'addNewDevotee.phtml')),
//           ));
//
//        }
//        
//        if ($subForm->getName() == 'devotional_info') {
//            $subForm->setDecorators(array('PrepareElements',
//                                    array('ViewScript', 
//                                    array('viewScript' => 'addNewDevotee.phtml')),
//           ));
//
//        }
//        
//        if ($subForm->getName() == 'services_info') {
//            $subForm->setDecorators(array('PrepareElements',
//                                    array('ViewScript', 
//                                    array('viewScript' => 'addNewDevotee.phtml')),
//           ));
//
//        }

        return $this;
    }
    
/*
 *
 * Add a submit button to an individual sub form
 *
 * @param  Zend_Form_SubForm $subForm
 * @return Application_Form_Devotees_AddNewDevotee
 */
 
    public function addSubmitButton(Zend_Form_SubForm $subForm)
    {
        $subForm->addElement(new Zend_Form_Element_Submit('save',array('label'    => 'Save and continue',
                                                                       'required' => false,
                                                                       'ignore'   => true,
                                                                      )
                                                          ));
        return $this;
    }
    
/*
 *
 * Add action and method to sub form
 *
 * @param  Zend_Form_SubForm $subForm
 * @return Application_Form_Devotees_AddNewDevotee
 */
 
    public function addSubFormActions(Zend_Form_SubForm $subForm)
    {
        $subForm->setAction('/devotees/process')
                ->setMethod('post');
        return $this;
    }
}
