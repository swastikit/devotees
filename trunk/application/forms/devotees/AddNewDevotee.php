<?php

class Application_Form_Devotees_AddNewDevotee extends Zend_Form
{
    public function init(){
    //parent::__construct($options);
    
$this->setName('addnewdevotee')
     ->setAction('addnewdevotee')
     ->setAttrib('id','addnewdevotee')
     ->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

    //Preliminary Information aboout Devotee
    
$DevPic = new Zend_Form_Element_File('uplphoto');
$DevPic ->setLabel('Upload Your Photo Here')
        ->setName('uplphoto');
$path     = 'photos/';
$DevPic ->setDestination($path)
        //->setMaxFileSize(2097152)
        ->addValidator('Size',true,array('max'=>'4096000','messages'=>'The maximum permitted image file size is %max% selected image file size is %size%.'))
        ->addValidator('Extension',true,array('jpg,jpeg', 
                                              'messages' => 'photo with only jpg, jpeg or gif format 
                                                             are accepted for uploading profile.'))
        ->setRequired(true)
        ->addValidator('NotEmpty');
        
//->setValidators(array('Size'=>array('min' => 20,'max' => 20000),'Count' =>array('min' => 1,'max' => 3)))
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
        
$centr  =new Application_Model_DbTable_MstCenter();
$CenterOptions =$centr->getKeyValues();
$Center = new Zend_Form_Element_Select('center');
$Center ->setName('center')
        ->setLabel('Center*')
        ->setMultiOptions($CenterOptions)
        ->setRequired(false)
        ->addValidator('NotEmpty')
        ->addFilters(array('StripTags','StringTrim'));


$con=new Application_Model_DbTable_MstCounselor();    
$Counselor = new Zend_Form_Element_Select('counselor');
$CounselorOptions = array(0=>'Select Counselor') + $con->getKeyValues();
$Counselor ->setName('counselor')
           ->setMultiOptions($CounselorOptions)
           ->setLabel('Counselor')
           ->setRequired(false)
           ->addValidator('NotEmpty')
           ->addValidator(new Zend_Validate_Alnum(array('allowWhiteSpace' => true)))
           ->addFilters(array('StripTags','StringTrim', 'StringToLower'));

$ment=new Application_Model_DbTable_MstAstCounselor();
$MentorOptions = array(0=>'Select Mentor') + $ment->getKeyValues();
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
                
$CounseleeStatus = new Application_Model_DbTable_MstCounseleeStatus();
$CounseleeStatusOptions = $CounseleeStatus->getCounseleeStatus();
$CounsellingStatus = new Zend_Form_Element_Select('counselee_status');
$CounsellingStatus ->setName('counselee_status')
                   ->setLabel('Counselling Status*')
                   //->setRequired(true)
                   ->setMultiOptions($CounseleeStatusOptions);

$ActiveStatus = new Zend_Form_Element_Select('active_status');
$ActiveStatus ->setName('active_status')
              ->setLabel('Active Status*')
              ->setMultiOptions(array('A'=>'Active',
                                      'I'=>'Inactive',
                                      'E'=>'Deceased'))
              ->setRequired(true);
                                      //----------------done till here
/*Personal Information */                                           

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
           
$NativePlace = new Zend_Form_Element_Text('native_place');
$NativePlace ->setName('native_place')
             ->setLabel('Native Place* ')
             ->setRequired(true)
             ->addFilters(array('StripTags','StringTrim','StringToLower'));
             
$NativeStatedb = new Application_Model_DbTable_MstState();
$NativeStateOptions = $NativeStatedb-> 

$NativeState = new Zend_Form_Element_Select('native_state');
$NativeState ->setName('native_state')
             ->SetLabel('Native State* ')
             ->setMultiOptions($NativeStateOptions)
             ->setRequired(true)
             ->addFilters(array('StripTags','StringTrim','StringToLower'));

$PresentAddLine1 = new Zend_Form_Element_Text('present_addline1');
$PresentAddLine1 ->setName('addline1')
                 ->setLabel('PlotNo.\Room No.\Wing*')
                 ->setRequired(true)
                 ->addFilters(array('StripTags','StringTrim','StringToLower'));
         
$PresentAddLine2 = new Zend_Form_Element_Text('present_addline2');
$PresentAddLine2 ->setName('addline2')
                 ->setLabel('Bulding\Chawl')
                 ->setRequired(true)
                 ->addFilters(array('StripTags','StringTrim','StringToLower'));
      
$PresentLocality =new Zend_Form_Element_Text('present_locality');
$PresentLocality ->setName('locality')
                 ->setLabel('PlotNo.\Room No.\Wing*')
                 ->setRequired(true)
                 ->addFilters(array('StripTags','StringTrim','StringToLower'));
         
$PresentCity  =new Zend_Form_Element_Text('present_city');
$PresentCity  ->setName('city')
              ->SetLabel('City')
              ->setRequired(true)
              ->addFilters(array('StripTags','StringTrim','StringToLower'));

$PresentState =new Zend_Form_Element_Text('present_state');
$PresentState ->setName('state')
              ->setLabel('State* ')
              ->setRequired(true)
              ->addFilters(array('StripTags','StringTrim','StringToLower'));
             
$PresentCountry =new Zend_Form_Element_Text('present_country');
$PresentCountry ->setName('country')
                ->SetLabel('Country* ')
                ->setRequired(true)
                ->addFilters(array('StripTags','StringTrim','StringToLower'));

$PresentZipCode =new Zend_Form_Element_Text('present_zip_code');
$PresentZipCode ->setName('zip_code')
                ->setLabel('Zip Code* ')
                ->setRequired(true)
                ->addFilters(array('StripTags','StringTrim','StringToLower'));

             
$PermenantAddLine1 = new Zend_Form_Element_Text('permenant_addline1');
$PermenantAddLine1 ->setName('addline1')
                   ->setLabel('PlotNo.\Room No.\Wing*')
                   ->setRequired(true)
                   ->addFilters(array('StripTags','StringTrim','StringToLower'));
         
$PermenantAddLine2 = new Zend_Form_Element_Text('permenant_addline2');
$PermenantAddLine2 ->setName('addline2')
                   ->setLabel('Bulding\Chawl')
                   ->setRequired(true)
                   ->addFilters(array('StripTags','StringTrim','StringToLower'));
      
$PermenantLocality =new Zend_Form_Element_Text('permenant_locality');
$PermenantLocality ->setName('locality')
                   ->setLabel('PlotNo.\Room No.\Wing*')
                   ->setRequired(true)
                   ->addFilters(array('StripTags','StringTrim','StringToLower'));
         
$PermenantCity  =new Zend_Form_Element_Text('permenant_city');
$PermenantCity  ->setName('city')
                ->SetLabel('City')
                ->setRequired(true)
                ->addFilters(array('StripTags','StringTrim','StringToLower'));

$PermenantState =new Zend_Form_Element_Text('permenant_state');
$PermenantState ->setName('state')
                ->setLabel('State* ')
                ->setRequired(true)
                ->addFilters(array('StripTags','StringTrim','StringToLower'));
             
$PermenantCountry =new Zend_Form_Element_Text('permenant_country');
$PermenantCountry ->setName('country')
                  ->SetLabel('Country* ')
                  ->setRequired(true)
                  ->addFilters(array('StripTags','StringTrim','StringToLower'));

$PermenantZipCode =new Zend_Form_Element_Text('permenant_zip_code');
$PermenantZipCode ->setName('zip_code')
                  ->setLabel('Zip Code* ')
                  ->setRequired(true)
                  ->addFilters(array('StripTags','StringTrim','StringToLower'));
             
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
               
$isgurukuli = new Zend_Form_Element_Radio('gurukuli');
$isgurukuli->setName('gurukuli')
           ->setLabel('IS Gurukuli* ')
           ->setMultiOptions(array(
                                    array('value'=>'Yes','key'=>'Y'),
                                    array('value'=>'No','key'=>'N')
                                    ))
           ->setRequired(true)
           ->addFilters(array('StripTags','StringTrim','StringToLower'));
           
$highesteducationdb = new Application_Model_DbTable_MstEducationCategory();
$highesteducationOptions = $highesteducationdb->getEduCategory(); 
$HighEducation = new Zend_Form_Element_Select('highest_education');
$HighEducation->setName('highest_education')
              ->setLabel('Highest Education* ')
              ->setMultiOptions($highesteducationOptions)
              ->setRequired(true)
              ->addFilters(array('StripTags','StringTrim','StringToLower'));
;
$EduDescription = new Zend_Form_Element_Text('education_description');
$EduDescription->setName('education_description')
               ->setLabel('Education Description* ')
               ->addValidator(new Zend_Validate_Alnum(array('allowWhiteSpace' => true)))
               ->setRequired(true)
               ->addFilters(array('StripTags','StringTrim','StringToLower'));
               
$Occupationdb = new Application_Model_DbTable_MstOccupation();
$OccupationOptions = $Occupationdb->listOccupation();                
$Occupation = new Zend_Form_Element_Select('occupation');
$Occupation->setName('education_description')
           ->setLabel('Education Description* ')
           ->setMultiOptions($OccupationOptions)
           ->setRequired(true)
           ->addFilters(array('StripTags','StringTrim','StringToLower'));
           
$PresDesignation = new Zend_Form_Element_Text('present_designation');
$PresDesignation->setName('present_designation')
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
         
$Organization = new Zend_Form_Element_Text('organization');
$Organization->setName('organization')
             ->setLabel('Organization ')
             ->setRequired(true)
             ->addFilters(array('StripTags','StringTrim','StringToLower'));
         
$OffAddress = new Zend_Form_Element_Text('office_address');
$OffAddress->setName('office_address')
           ->setLabel('Address')
           ->setRequired(true)
           ->addFilters(array('StripTags','StringTrim','StringToLower'));
         
$OffLocality = new Zend_Form_Element_Text('office_locality');
$OffLocality->setName('office_locality')
            ->setLabel('Locality ')
            ->setRequired(true)
            ->addFilters(array('StripTags','StringTrim','StringToLower'));
         
$OffCity = new Zend_Form_Element_Text('office_city');
$OffCity->setName('office_city')
        ->setLabel('City')
        ->setRequired(true)
        ->addFilters(array('StripTags','StringTrim','StringToLower'));
         
$OffState = new Zend_Form_Element_Text('office_state');
$OffState->setName('office_state')
         ->setLabel('State')
         ->setRequired(true)
         ->addFilters(array('StripTags','StringTrim','StringToLower'));
         
$OffCountry = new Zend_Form_Element_Text('office_country');
$OffCountry->setName('office_country')
           ->setLabel('Country')
           ->setRequired(true)
           ->addFilters(array('StripTags','StringTrim','StringToLower'));
         
$OffZip = new Zend_Form_Element_Text('Office_zip_code');
$OffZip->setName('Office_zip_code')
       ->setLabel('Zip-Code')
       ->setRequired(true)
       ->addFilters(array('StripTags','StringTrim','StringToLower'));
    
$BeganChantingFromDay = new  Zend_Form_Element_Select('bgn_chan_from_day');
$BeganChantingFromDay->setLabel('Started Chanting From')
                     ->setName('bgn_chan_from_day')
                     ->setMultiOptions(Rgm_Basics::getDates())
                     ->addValidator('NotEmpty')
                     ->addFilters(array('StripTags','StringTrim'));

$BeganChantingFromMonth = new  Zend_Form_Element_Select('bgn_chan_from_month');
$BeganChantingFromMonth->setLabel('Started Chanting From')
                       ->setName('bgn_chan_from_month')
                       ->setMultiOptions(Rgm_Basics::getMonths())
                       ->addValidator('NotEmpty')
                       ->addFilters(array('StripTags','StringTrim'));
                  
$BeganChantingFromYear = new  Zend_Form_Element_Select('bgn_chan_from_year');
$BeganChantingFromYear->setLabel('Started Chanting From')
                      ->setName('bgn_chan_from_year')
                      ->setMultiOptions(Rgm_Basics::getYears(1965,2012))
                      ->addValidator('NotEmpty')
                      ->addFilters(array('StripTags','StringTrim'));

$BeganChantingNA = new  Zend_Form_Element_Select('bgn_chan_from_na');
$BeganChantingNA->setLabel('N/A')
                ->setName('bgn_chan_from_na')
                ->SetMultiOptions(array(
                                            array('value'=>'Yes','key'=>'Y'),
                                            array('value'=>'No','key'=>'N'),
                                            ));

$NumberOfRoundsPresentlyChanting = new  Zend_Form_Element_Select('no_rou_pre_chanting');
$NumberOfRoundsPresentlyChanting->setLabel('Number Of Rounds Presently Chanting')
                                ->setName('no_rou_pre_chanting')
                                ->SetMultiOptions(array(
array('value'=>'1','key'=>'1'),array('value'=>'2','key'=>'2'),array('value'=>'3','key'=>'3'),array('value'=>'4','key'=>'4'),
array('value'=>'5','key'=>'5'),array('value'=>'6','key'=>'6'),array('value'=>'7','key'=>'7'),array('value'=>'8','key'=>'8'),
array('value'=>'9','key'=>'9'),array('value'=>'10','key'=>'10'),array('value'=>'11','key'=>'11'),array('value'=>'12','key'=>'12'),
array('value'=>'13','key'=>'13'),array('value'=>'14','key'=>'14'),array('value'=>'15','key'=>'15'),array('value'=>'16','key'=>'16'),
                                                       ));

$Chanting16RoundsSince = new  Zend_Form_Element_Select('chan_16_rounds_since');
$Chanting16RoundsSince ->setLabel('Chanting 16 Rounds(or more) Since')
                       ->setName('chan_16_rounds_since');
                       
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
                   ->setMultiOptions(Rgm_Basics::getYears(1965,2012))
                   ->setRequired(true);  

$HarinamInitiated = new Zend_Form_Element_Select('harinam_initiatn_na');
$HarinamInitiated->setLabel(Rgm_Basics::encodeDiacritics('Harinam Initiated'))
                 ->setName('harinam_initiatn_date_na')
                 ->setMultiOptions(array(
                                         array('value'=>'Yes','key'=>'Y'),
                                         array('value'=>'No','key'=>'N')
                                         ));

$DayOfHarinamInitiation= new Zend_Form_Element_Select('harinam_initiatn_day');
$DayOfHarinamInitiation->setLabel(Rgm_Basics::encodeDiacritics('Date Of Harinam Initiation'))
                       ->setName('harinam_initiation_day')
                       ->setMultiOptions(Rgm_Basics::getDates());
                           
$MonthOfHarinamInitiation= new Zend_Form_Element_Select('harinam_initiatn_month');
$MonthOfHarinamInitiation->setName('harinam_initiation_month')
                         ->setMultiOptions(Rgm_Basics::getMonths());

$YearOfHarinamInitiation= new Zend_Form_Element_Select('harinam_initiatn_year');
$YearOfHarinamInitiation->setName('harinam_initiation_year')
                        ->setMultiOptions(Rgm_Basics::getYears(1965,2012));
                           
$InitiatedName = new Zend_Form_Element_Select('initiated_name_combo');
$InitiatedName->setName('initiated_name_combo')
              ->setLabel('Initiated Name')
              ->setRequired(false);

$AddNewInitiatedName = new Zend_Form_Element_Button('add_new_initiated_name');
$AddNewInitiatedName->setName('add_new_initiated_name')
                    ->setValue('New');
                
$SpiritualMasterdb =  new Application_Model_DbTable_MstGuru();
$SpiritualMasterOptions = $SpiritualMasterdb->seekGuru();
$SpiritualMaster = new Zend_Form_Element_Select('spiritual_master');
$SpiritualMaster->setName('spiritual_master')
                ->setLabel('Spiritual Master')
                ->setMultiOptions($SpiritualMasterOptions);
                
$BrahmanInitiated = new Zend_Form_Element_Select('harinam_initiated');
$BrahmanInitiated->setName('harinam_initiated')
                 ->setLabel('Brahman Initiated')
                 ->setMultiOptions(array(
                                          array('value'=>'Yes','key'=>'Y'),
                                          array('value'=>'No','key'=>'N')
                                        ));

$DayOfBrahmanInitiation = new Zend_Form_Element_Select('date_of_brahman_initiation');
$DayOfBrahmanInitiation->setName('date_of_brahman_initiation')
                       ->setLabel(Rgm_Basics::encodeDiacritics('Date Of Brahman Initiation'))
                       ->setMultiOptions(Rgm_Basics::getDates());

$MonthOfBrahmanInitiation = new Zend_Form_Element_Select('brahman_initiation_month'); 
$MonthOfBrahmanInitiation->setName('brahman_initiation_month')
                         ->setMultiOptions(Rgm_Basics::getMonths());

$YearOfBrahmanInitiation = new Zend_Form_Element_Select('brahman_initiation_year');
$YearOfBrahmanInitiation->setName('brahman_initiation_year')
                        ->setMultiOptions(Rgm_Basics::getYears(1965,2012));

$DayOfSanyasInitiation = new Zend_Form_Element_Select('sanyas_initiation_day');
$DayOfSanyasInitiation->setName('sanyas_initiation_day')
                      ->setLabel('Date of Sanyas Initiation')
                      ->setMultiOptions(Rgm_Basics::getDates());

$MonthOfSanyasInitiation = new Zend_Form_Element_Select('sanyas_initiation_month');
$MonthOfSanyasInitiation->setName('sanyas_initiation_month')
                        ->setMultiOptions(Rgm_Basics::getMonths());

$YearOfSanyasInitiation = new Zend_Form_Element_Select('sanyas_initiation_year');
$YearOfSanyasInitiation->setName('sanyas_initiation_day')
                       ->setMultiOptions(Rgm_Basics::getYears(1965,2012));
                       
$SanyasSpiritualMaster = new Zend_Form_Element_Select('sanyas_spiritual_master') ;
$SanyasSpiritualMaster->setName('sanyas_spiritual_master')
                      ->setLabel(Rgm_Basics::encodeDiacritics('Sanyas Spiritual Master'))
                      ->setMultiOptions($SpiritualMasterOptions);

$SpiritualNamedb = new Application_Model_DbTable_MstSpiritualName();
$SpiritualNameOptions = $SpiritualNamedb->getKeyValues();

$SanyasName = new Zend_Form_Element_Select('sanyas_name');
$SanyasName->setLabel(Rgm_Basics::encodeDiacritics('Sanyas Name'))
           ->setMultiOptions($SpiritualNameOptions);

$SanyasTitle = new Zend_Form_Element_Radio('sanyas_title');
$SanyasTitle->setName('sanyas_title')
            ->setLabel(Rgm_Basics::encodeDiacritics('Sanyas Title'))
            ->setOptions(array(
                                 array('value'=>Rgm_Basics::encodeDiacritics('Goswami'),'key'=>'GOSWAMI'),
                                 array('value'=>Rgm_Basics::encodeDiacritics('Swami'),'key'=>'SWAMI')
                              ));
                                      
//$ServicesRendered = new Zend_Form_Element_

$Submit = new Zend_Form_Element_Submit('submit');
$Submit ->setLabel('Submit')
        ->setName('submit');
        
$this->addElements(array($DevPic,$Fname,$Mname,$Lname,$Day,$Month,$Year,$Gender,$CountryCode,$Mobile,$PhoneNumber,$Email,$Center,$Counselor,
                         $Mentor,$CounsellingStatus,$ActiveStatus,$MotherTongue,$BldGrp,$PrevReligion,$LanguagesKnown,$NativePlace,$NativeState,$MaritalStatus,$Submit));
        }
}