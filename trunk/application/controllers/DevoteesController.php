<?php

class DevoteesController extends Zend_Controller_Action
{
      
    public function init(){
    /* Initialize action controller here */
    }

    public function indexAction(){
        
    }
    
    public function listdevoteesAction(){
    $a=new Rgm_UserServices();
    // action body
    $page = $this->_getParam('page', 1);
    $PAGE_SIZE = 10;
    $offset = ($page - 1) * $PAGE_SIZE;
    //FOR PAGINATION--------------------------------------------
    $devotees = new Application_Model_DbTable_Devotee();
    $listOfDevotees = $devotees->getDevotees($PAGE_SIZE, $offset);
    $this->view->listdevotees = $listOfDevotees;
    $dids = $devotees->getAllDids();
    $paginator = Zend_Paginator::factory($dids);
    $paginator->setItemCountPerPage($PAGE_SIZE);
    // Number of items in a page. I have kept 10 so you can easily see the
    //pagination when there is more than 10 items inserted to devotee table.
    $paginator->setCurrentPageNumber($page);
    $this->view->paginator = $paginator;
    //FOR NUMBER RECORDS FOUND----------------------------------
    $num_rec_found = $devotees->getNumberOfRecords();
    $this->view->number_of_records_found = $num_rec_found;
    //FOR LIST OF COUNSELORS------------------------------------
    $counselors = new Application_Model_DbTable_MstCounselor();
    $counselor = $counselors->getCounselor();
    $this->view->counselor = $counselor;
    //FOR LIST OF ISKCON CENTERS--------------------------------
    $centers = new Application_Model_DbTable_MstCenter();
    $center = $centers->getKeyValues();
    $this->view->center = $center;
    //FOR LIST OF DIKSA GURU------------------------------------
    $gurus = new Application_Model_DbTable_MstGuru();
    $guru = $gurus->seekGuru();
    $this->view->guru = $guru;
    //FOR LIST OF MENTORS ---------------------------------------
    $mentors = new Application_Model_DbTable_MstAstCounselor();
    $mentor = $mentors->getMentor();
    $this->view->mentor = $mentor;
    $this->view->searchOptions=Rgm_Basics::getDevoteeSearchOptions();
    $this->view->selSearchBy='display_name';
    /*-------------------Counselor List--------------------------*/
    $con=new Application_Model_DbTable_MstCounselor();
    $rs = new stdClass;
    $rs->result = $con->listIdNames('');
    $this->view->jsonCounselor=Zend_Json::encode($rs);
    /*-------------------Asram List------------------------------*/
    $asram = new Application_Model_DbTable_MstAsram();
    $this->view->asram = $asram->listPairs();
    $this->view->selAsramIds = $asram->listIds();
    /*-------------------Blood Group List------------------------*/
    $this->view->bloodGroup = Rgm_Basics::getBloodGroupsAsoArr();
    $this->view->selBloodGroup = Rgm_Basics::getBloodGroupsIds();
    /*-------------------Initiation Status List------------------*/
    $this->view->initStatus = array(1=>'Not initiated',
                                    2=>'Harinaam Initiated',
                                    3=>'Brahman Initiated',
                                    4=>'Sanyas Initiated');
    $this->view->selInitStatusIds = array(1,2,3,4);
    /*-------------------Status List-----------------------------*/
    $this->view->status = array('A'=>'Active','I'=>'Inactive','E'=>'Deceased');
    $this->view->selStatusIds = array('A','I','E');
}

    /* For Ajax Use Called by Counselor Autocomplete Combobox */
    public function listKeyValueCounselorAction(){
        $search=$this->getRequest()->getParam('s');
        $con=new Application_Model_DbTable_MstCounselor();
        $rs = new stdClass;
        $rs->result = $con->listIdNames($search);
        $this->_helper->json($rs);
    }

//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------

    protected $_form;
    protected $_namespace = 'DevoteesController';
    protected $_session;
 
    public function getForm()
    {
        if (null === $this->_form) {
            $this->_form = new Application_Form_Devotees_AddNewDevotee('', array('disableLoadDefaultDecorators' => false));
        }
        return $this->_form;
    }
    
 
/*
 *
 * Get the session namespace we're using
 *
 * @return Zend_Session_Namespace
 */
    public function getSessionNamespace(){
        if (null === $this->_session) {
            $this->_session = new Zend_Session_Namespace($this->_namespace);
        }
        return $this->_session;
    }
    
/*
 *
 * Get a list of forms already stored in the session
 *
 * @return array
 */
    public function getStoredForms(){
        $stored = array();
        foreach ($this->getSessionNamespace() as $key => $value) {
            $stored[] = $key;
        }
        return $stored;
    }
   
/*
 *
 * Get list of all subforms available
 *
 * @return array
 */
    public function getPotentialForms(){
        return array_keys($this->getForm()->getSubForms());
    }
    
/*
 *
 * What sub form was submitted?
 *
 * @return false|Zend_Form_SubForm
 */
    public function getCurrentSubForm(){
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return false;
        }
 
        foreach ($this->getPotentialForms() as $name) {
            if ($data = $request->getPost($name, false)) {
                if (is_array($data)) {
                    return $this->getForm()->getSubForm($name);
                    break;
                }
            }
        }
        return false;
    }
    
/*
 *
 * Get the next sub form to display
 *
 * @return Zend_Form_SubForm|false
 */
    public function getNextSubForm(){
        $storedForms    = $this->getStoredForms();
        $potentialForms = $this->getPotentialForms();
 
        foreach ($potentialForms as $name) {
            if (!in_array($name, $storedForms)) {
                return $this->getForm()->getSubForm($name);
            }
                  
        }
        return false;
    }
    
/* 
 * Is the sub form valid?
 *
 * @param  Zend_Form_SubForm $subForm
 * @param  array $data
 * @return bool
 */
    public function subFormIsValid(Zend_Form_SubForm $subForm, array $data){
        $name = $subForm->getName();
        if ($subForm->isValid($data)) {
            $this->getSessionNamespace()->$name = $subForm->getValues();
            return true;
        }
        return false;
    }
    
    /*
     *
     * Is the full form valid?
     *
     * @return bool
     */
    public function formIsValid(){
        $data = array();
        foreach ($this->getSessionNamespace() as $key => $info) {
            Zend_Debug::dump();
                 $data[$key] = $info[$key]; 
        }
        return $this->getForm()->isValid($data);
    }
    
    
    public function addnewdevoteeAction(){
        // Either re-display the current page, or grab the "next"
        // (first) sub form
            if (!$form = $this->getCurrentSubForm()) {
                $form = $this->getNextSubForm();
            }
            
            if($form){            
               $form = $this->getForm()->prepareSubForm($form);
               $this->view->form = $form;
            }
             
    }
    
    public function basicinfoAction(){
        //display basic info of devotee
                                
    }
    
    public function personalinfoAction(){
        //display basic info of devotee
    }
    
    public function addressinfoAction(){
        //display basic info of devotee
    }
    
    public function familyinfoAction(){
        //display basic info of devotee
    }
    
    public function officeinfoAction(){
        //display basic info of devotee
    }
    
    public function devotionalinfoAction(){
        //display basic info of devotee
    }
    
    public function servicesinfoAction(){
        //display basic info of devotee
    }
    
    public function verificationAction(){
        // it was created to just display the final data once the addnewdevotee form is successfully validated.
    }
    
    public function processAction(){
        if (!$form = $this->getCurrentSubForm()){
            return $this->_forward('addnewdevotee');
        }
        
        if (!$this->subFormIsValid($form,$this->getRequest()->getPost())) {
            $this->view->form = $this->getForm()->prepareSubForm($form);
            return $this->render('addnewdevotee');
        }
        
        $form = $this->getNextSubForm();
        
        if($form) {
           $this->view->form = $this->getForm()->prepareSubForm($form);
           return $this->render('addnewdevotee');
        }
        Zend_Debug::dump($this->getSessionNamespace());
        $this->view->info = $this->getSessionNamespace();
        
          $form = new Application_Form_Devotees_AddNewDevotee();
          $form->setName('addnewdevotee');
          $d = new Application_Model_DbTable_Devotee();
          $auth = Zend_Auth::getInstance();
          $authArray=$auth->getIdentity();
          $userid = $authArray['user_id'];
          $u = new Application_Model_DbTable_Mstuser();
          $user=$u->getBasicInfo($userid);
          
/*
This section takes data from the session in
which data is submitted through subforms of 
AddNewDevotee form
*/

$this->info = $this->getSessionNamespace();

$dev_data = array();
foreach($this->info as $info):
foreach($info as $form=> $data):
foreach($data as $key => $value):
        $dev_data[$key] = $value; // this line gives us devotee data to be inserted in the table.
        endforeach;
endforeach;
endforeach;

/*
below mentioned is a code
for renaming the photo
*/

$fullFilePath = $form->uplphoto->getFileName();
$fullfilename = pathinfo($fullFilePath);
$dev_photo_name = $this->getlastdid().'_'.rand(0,100).'.'.$fullfilename['extension'];  

/*
Below is the data to
be inserted in devotee table
*/
//-------------------Date of Birth--------------------------------------
//if(array_key_exists('birth_day',$dev_data)&&
//   array_key_exists('birth_month',$dev_data)&&
//   array_key_exists('birth_year',$dev_data)){
    
$birthdatearray = array('year'  =>$dev_data['birth_year'], 
                        'month' =>$dev_data['birth_month'], 
                        'day'   =>$dev_data['birth_day']
                  );    
//}

$birthdate = new Zend_Date($birthdatearray);

//-------------------Date of Begining of chanting------------------------
//if(array_key_exists('bgn_chan_from_day',$dev_data)&&
//   array_key_exists('bgn_chan_from_month',$dev_data)&&
//   array_key_exists('bgn_chan_from_year',$dev_data)){
    
$bgn_chan_from_datearray = array('year'  =>$dev_data['bgn_chan_from_year'], 
                                 'month' =>$dev_data['bgn_chan_from_month'], 
                                 'day'   =>$dev_data['bgn_chan_from_year']
                  );    
//}
$bgn_chan_from_date = new Zend_Date($bgn_chan_from_datearray);

//-------------------Date of Begining of 16 rounds chanting---------------
//if(array_key_exists('chan_16_rounds_year',$dev_data)&&
//   array_key_exists('chan_16_rounds_month',$dev_data)&&
//   array_key_exists('chan_16_rounds_year',$dev_data)){
    
$chan_16_rounds_datearray = array('year'  =>$dev_data['chan_16_rounds_year'], 
                                  'month' =>$dev_data['chan_16_rounds_month'], 
                                  'day'   =>$dev_data['chan_16_rounds_day']
                  );    
//}
$chan_16_rounds_date = new Zend_Date($chan_16_rounds_datearray);

//-------------------Date of harinam initiation---------------------------
//if(array_key_exists('harinam_initiatn_day',$dev_data)&&
//   array_key_exists('harinam_initiatn_month',$dev_data)&&
//   array_key_exists('harinam_initiatn_year',$dev_data)){
    
$harinam_initiatn_datearray = array('year'  =>$dev_data['harinam_initiatn_year'], 
                                    'month' =>$dev_data['harinam_initiatn_month'], 
                                    'day'   =>$dev_data['harinam_initiatn_day']
                  );    
//}

$harinam_initiatn_date = new Zend_Date($harinam_initiatn_datearray);

//-------------------Date of brahman initiation------------------------
//if(array_key_exists('date_of_brahman_initiation',$dev_data)&&
//   array_key_exists('brahman_initiation_month',$dev_data)&&
//   array_key_exists('brahman_initiation_year',$dev_data)){
    
$brahman_initiation_datearray = array('year'  =>$dev_data['brahman_initiation_year'], 
                                      'month' =>$dev_data['brahman_initiation_month'], 
                                      'day'   =>$dev_data['date_of_brahman_initiation']
                  );                      
//}
$brahman_initiation_date = new Zend_Date($brahman_initiation_datearray);

//-------------------Date of sanyas_initiation------------------------
//if(array_key_exists('sanyas_initiation_day',$dev_data)&&
//   array_key_exists('sanyas_initiation_month',$dev_data)&&
//   array_key_exists('sanyas_initiation_year',$dev_data)){

$sanyas_initiation_datearray = array('year'  =>$dev_data['sanyas_initiation_year'], 
                                     'month' =>$dev_data['sanyas_initiation_month'], 
                                     'day'   =>$dev_data['sanyas_initiation_day']
                  );    
//}
$sanyas_initiation_date = new Zend_Date($sanyas_initiation_datearray);
    
$devotee_data = array( 'pics'            =>$dev_photo_name, //this will give you the file name now you have to rename it then it will be inserted into the database
                       'first_name'      =>$dev_data['first_name'],
                       'middle_name'     =>$dev_data['middle_name'],
                       'last_name'       =>$dev_data['last_name'],
                       'do_birth'        =>$birthdate->toString('yyyyMMddHHmmss'),
                       'gender'          =>$dev_data['gender'],
                       'country_id'      =>$dev_data['cc'],
                       'center_id'       =>$dev_data['center'],
                       'counselor_id'    =>$dev_data['counselor'],
                       'mobile'          =>$dev_data['mobile'],
                       'email'           =>$dev_data['email'],
                       'pres_phone'      =>$dev_data['phone_number'],
                       'devotee_status'  =>$dev_data['active_status'],
                       'asram_status_id' =>$dev_data['marital_status'],
                       'mother_tongue_id'=>$dev_data['mother_tongue'],
                       'counselee_status'=>$dev_data['counselee_status'],
                       'blood_group'     =>$dev_data['bld_grp'],
                       'religion_id'     =>$dev_data['previous_religion'],
                       'native_place'    =>$dev_data['native_place'],
                       'native_state_id' =>$dev_data['native_state'],
                       'pres_add1'       =>$dev_data['present_addline1'],
                       'pres_add2'       =>$dev_data['present_addline2'],
                       'pres_locality_id'=>$dev_data['present_locality'],
                       'pres_pin'        =>$dev_data['present_zip_code'],
                       'pres_city_id'    =>$dev_data['present_city'],
                       'pres_state_id'   =>$dev_data['present_state'],
                       'pres_country_id' =>$dev_data['present_country'],
                       //'pres_perm'       =>$this->getRequest()->getPost(''),
                       'perm_add1'       =>$dev_data['permenant_addline1'],
                       'perm_add2'       =>$dev_data['permenant_addline2'],
                       'perm_locality_id'=>$dev_data['permenant_locality'],
                       'perm_pin'        =>$dev_data['permenant_zip_code'],
                       'perm_city_id'    =>$dev_data['permenant_city'],
                       'perm_state_id'   =>$dev_data['permenant_state'],
                       'perm_country_id' =>$dev_data['permenant_country'],
                       'perm_phone'      =>$dev_data['phone_number'],
                       'father_name'     =>$dev_data['father_name'],
                       //'father_did'    =>$this->getRequest()->getPost(''),
                       'mother_name'     =>$dev_data['mother_name'],
                       //'mother_did'    =>$this->getRequest()->getPost(''),
                       //'spouse_name'   =>$this->getRequest()->getPost(''),
                       //'spouse_did'    =>$this->getRequest()->getPost(''),
                       //'do_marriage'   =>$dev_data[$key].'-'.$dev_data[$key].'-'.$dev_data[$key];
                       'isgurukuli'      =>$dev_data['gurukuli'],
                       'edu_cat_id'      =>$dev_data['highest_education'],
             'education_qualification'   =>$dev_data['education_description'],
                       'occupation_id'   =>$dev_data['occupation'],
                       'designation'     =>$dev_data['designation'],
                       'merits'          =>$dev_data['merits_awards'],
                       'skill_set'       =>$dev_data['skill_sets'],
                       'off_name'        =>$dev_data['office_name'],
                       'off_add1'        =>$dev_data['office_address_line1'],
                       'off_add2'        =>$dev_data['office_address_line2'],
                       'off_locality_id' =>$dev_data['office_locality'],
                       'off_city_id'     =>$dev_data['office_city'],
                       'off_state_id'    =>$dev_data['office_state'],
                       'off_country_id'  =>$dev_data['office_country'],
                       'off_pin'         =>$dev_data['office_zip_code'],
                       'off_phone'       =>$dev_data['office_phone'],
                       'chanting_started'=>$bgn_chan_from_date->toString('yyyyMMddHHmmss'),
                        'chk_chant_start'=>$dev_data['bgn_chan_from_na'],
                           'no_of_rounds'=>$dev_data['no_rou_pres_chanting'],
                    'chanting_16_started'=>$chan_16_rounds_date->toString('yyyyMMddHHmmss'),
                         //'chk_chant_16'=>$dev_data[],
                       'intro_by'        =>$dev_data['intro_by'],
                       'intro_year'      =>$dev_data['year_introduction'],
                       'intro_center'    =>$dev_data['intro_center'],
                       'chk_date_harinam'=>$dev_data['harinam_initiatn_na'],
                       'do_harinaminit'  =>$harinam_initiatn_date->toString('yyyyMMddHHmmss'),
                       'chk_date_brahmin'=>$dev_data['brahman_initiated_na'],
                       'do_brahmininit'  =>$brahman_initiation_date->toString('yyyyMMddHHmmss'),
                       'ini_guru_id'     =>$dev_data['sanyas_spiritual_master'],
                       'chk_date_sanyas' =>$dev_data['sanyas_initiation_day'],
                       'do_sanyasinit'   =>$sanyas_initiation_date->toString('yyyyMMddHHmmss'),
                       'sanyas_name'     =>$dev_data['sanyas_name'],
                       'sanyas_title'    =>$dev_data['sanyas_title'],
                       'sanyas_guru_id'  =>$dev_data['sanyas_spiritual_master'],
                'spiritualname_sanyas_id'=>$dev_data['sanyas_name'],
                       'remarks'         =>$dev_data['remarks'],
                       //'do_deceased'   =>$dev_data[$key].'-'.$dev_data[$key].'-'.$dev_data[$key],
                       'user_id'         =>$user['id'],
                     //'iys'             =>$this->getRequest()->getPost(''),
                     //'cong'            =>$this->getRequest()->getPost(''),
                     //'spiritualname_id'=>$this->getRequest()->getPost(''),
                     
                     //'gurukulname'     =>$this->getRequest()->getPost(''),
                     //'isspousespdisp'  =>$this->getRequest()->getPost(''),
                       'isactive'        =>$user['is_active'],
                    //'local_version_status' =>$this->getRequest()->getPost(''),
                    //'local_version_do_modify'   =>$this->getRequest()->getPost(''),
                    //'modified'        =>$this->getRequest()->getPost(''),
                    //'pan'             =>$this->getRequest()->getPost(''),
                    //'receipt_name'    =>$this->getRequest()->getPost(''),
                      'entered_date'    =>Zend_Date::now()->toString('yyyyMMddHHmmss'),
                   'dolm'               =>$user['dolm'],
                    'modibyuid'         =>$user['modi_by_uid'],
                      'entered_by_uid'  =>$user['entered_by_uid'],
                  //'verified'          =>$this->getRequest()->getPost(''),
                      'do_verify'       =>Zend_Date::now()->toString('yyyyMMddHHmmss'),
                  //'verified_by_uid'   =>$this->getRequest()->getPost('')
);
        $did = $d->insert($devotee_data);
        $this->view->lastrecordinserted = $did; 
                     

        $this->render('verification');
        //Clear the session data
        Zend_Session::namespaceUnset($this->_namespace);
        
    }
            
        public function getlastdid(){
            $bootstrap = $this->getInvokeArg('bootstrap');
            $resource = $bootstrap->getPluginResource('multidb');
            $db1 = $resource->getDb('db1');
            $db3 = $resource->getDb('db3');
            $select = $db3->select()
                          ->from(array('t' => 'TABLES'), array('t.AUTO_INCREMENT'))
                          ->where('TABLE_NAME = ?','devotee')
                          ->where('TABLE_SCHEMA = ?', 'devotees_3_0');
            $stmt = $db3->query($select);
            $result = $stmt->fetchAll();
            
            foreach($result as $key => $value){
                    foreach($value as $data1 => $data2){
                    }            
            }
            $this->view->lastdid = $data2;    
        }    
        
            
    
/*
public function addnewdevoteeAction()
{  
*/            
        

    /*Form Validation*/
   // if($this->getRequest()->isPost()){
     //   $formData = $this->getRequest()->getPost();
   // if($form->isValidPartial($formData)){
     //  $uploadedData = $form->getValues();
     //  $fullFilePath = $form->uplphoto->getFileName();
      // Zend_Debug::dump($uploadedData, '$uploadedData');
      // Zend_Debug::dump($fullFilePath, '$fullFilePath');
       
     //  $d->insert($devotee_data);
     //  echo "Photo Uploaded";
     //  $form->populate($formData);
     //  $this->_redirect('devotees/listdevotees');                
    //    exit;
    //  } else{
    //           $form->getErrors();
    //           $form->isValid($formData);
     //}
   // }
   //   $this->view->form = $form; 
 //}
}        