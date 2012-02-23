<?php

class DevoteesController extends Zend_Controller_Action
{
public function init()
{
/* Initialize action controller here */
}
public function indexAction()
{
// action body
}
public function listdevoteesAction()
{
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
    $this->view->selSearchBy='SEARCH_NAME';
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

public function addnewdevoteeAction()
{  
    $form = new Application_Form_Devotees_AddNewDevotee();
    $form->setName('addnewdevotee');
    $d = new Application_Model_DbTable_Devotee();
    
    $devotee_data = array( 
              'first_name'      =>$this->getRequest()->getPost('first_name') ,
              'middle_name'     =>$this->getRequest()->getPost('middle_name'),
              'last_name'       =>$this->getRequest()->getPost('last_name'),
              'do_birth'        =>$this->getRequest()->getPost('day').'-'.$this->getRequest()->getPost('month').'-'.$this->getRequest()->getPost('year'),     //$this->getRequest()->getPost('day').'-'.$this->getRequest()->getPost('month').'-'.$this->getRequest()->getPost('year'),
              'gender'          =>$this->getRequest()->getPost('gender'),
              'country_id'      =>$this->getRequest()->getPost('cc'),
              'center_id'       =>$this->getRequest()->getPost('center'),
              'counselor_id'    =>$this->getRequest()->getPost('counselor'),
              'mobile'          =>$this->getRequest()->getPost('mobile'),
              'email'           =>$this->getRequest()->getPost('email'),
              'pres_phone'      =>$this->getRequest()->getPost('phone_number'),
              'devotee_status'  =>$this->getRequest()->getPost('active_status'),
              'asram_status_id' =>$this->getRequest()->getPost('marital_status'),
              'mother_tongue_id'=>$this->getRequest()->getPost('mother_tongue'),
              'counselee_status'=>$this->getRequest()->getPost('counselee_status'),
              'blood_group'     =>$this->getRequest()->getPost('bld_grp'),
              'religion_id'     =>$this->getRequest()->getPost('previous_religion'),
              'native_place'    =>$this->getRequest()->getPost('native_place'),
              'native_state_id' =>$this->getRequest()->getPost('native_state'),
              'pres_add1'       =>$this->getRequest()->getPost('present_addline1'),
              'pres_add2'       =>$this->getRequest()->getPost('present_addline2'),
              'pres_locality_id'=>$this->getRequest()->getPost('present_locality'),
              'pres_pin'        =>$this->getRequest()->getPost('present_zip_code'),
              'pres_city_id'    =>$this->getRequest()->getPost('present_city'),
              'pres_state_id'   =>$this->getRequest()->getPost('present_state'),
              'pres_country_id' =>$this->getRequest()->getPost('present_country'),
//              'pres_perm'       =>$this->getRequest()->getPost(''),
  //            'perm_add1'       =>$this->getRequest()->getPost('permenant_addline1'),
    //          'perm_add2'       =>$this->getRequest()->getPost('permenant_addline2'),
      //        'perm_locality_id'=>$this->getRequest()->getPost('permenant_locality'),
        //      'perm_pin'        =>$this->getRequest()->getPost('permenant_zip_code'),
          //    'perm_city_id'    =>$this->getRequest()->getPost('permenant_city'),
            //  'perm_state_id'   =>$this->getRequest()->getPost('permenant_state'),
              //'perm_country_id' =>$this->getRequest()->getPost('permenant_country'),
//              'perm_phone'      =>$this->getRequest()->getPost('phone_number'),
              'father_name'     =>$this->getRequest()->getPost('father_name'),
              //'father_did'      =>$this->getRequest()->getPost(''),
              'mother_name'     =>$this->getRequest()->getPost('mother_name'),
              //'mother_did'      =>$this->getRequest()->getPost(''),
              'spouse_name'     =>$this->getRequest()->getPost(''),
              //'spouse_did'      =>$this->getRequest()->getPost(''),
              'do_marriage'     =>$this->getRequest()->getPost(''),
              //'edu_cat_id'      =>$this->getRequest()->getPost(''),
//    'education_qualification'   =>$this->getRequest()->getPost(''),
  //            'occupation_id'   =>$this->getRequest()->getPost(''),
    //          'designation'     =>$this->getRequest()->getPost(''),
      //        'off_name'        =>$this->getRequest()->getPost(''),
        //      'off_add1'        =>$this->getRequest()->getPost(''),
          //    'off_add2'        =>$this->getRequest()->getPost(''),
            //  'off_locality_id' =>$this->getRequest()->getPost(''),
              //'off_pin'         =>$this->getRequest()->getPost(''),
//              'off_city_id'     =>$this->getRequest()->getPost(''),
  //            'off_state_id'    =>$this->getRequest()->getPost(''),
    //          'off_country_id'  =>$this->getRequest()->getPost(''),
      //        'off_phone'       =>$this->getRequest()->getPost(''),
        //      'merits'          =>$this->getRequest()->getPost(''),
          //    'skill_set'       =>$this->getRequest()->getPost(''),
            //  'intro_by'        =>$this->getRequest()->getPost(''),
              //'intro_year'      =>$this->getRequest()->getPost(''),
//              'intro_center'    =>$this->getRequest()->getPost(''),
  //            'remarks'         =>$this->getRequest()->getPost(''),
    //          'do_deceased'     =>$this->getRequest()->getPost(''),
      //        'counselee_status'=>$this->getRequest()->getPost(''),
        //      'pics'            =>$this->getRequest()->getPost(''),
          //    'user_id'         =>$this->getRequest()->getPost(''),
            //  'iys'             =>$this->getRequest()->getPost(''),
              //'cong'            =>$this->getRequest()->getPost(''),
//              'spiritualname_id'=>$this->getRequest()->getPost(''),
  //            'isgurukuli'      =>$this->getRequest()->getPost(''),
    //          'gurukulname'     =>$this->getRequest()->getPost(''),
      //        'isspousespdisp'  =>$this->getRequest()->getPost(''),
        //      'isactive'        =>$this->getRequest()->getPost(''),
        //'local_version_status'  =>$this->getRequest()->getPost(''),
    //'local_version_do_modify'   =>$this->getRequest()->getPost(''),
      //        'modified'        =>$this->getRequest()->getPost(''),
        //      'pan'             =>$this->getRequest()->getPost(''),
          //    'receipt_name'    =>$this->getRequest()->getPost(''),
            //  'entered_date'    =>$this->getRequest()->getPost(''),
              //'entered_by_uid'  =>$this->getRequest()->getPost(''),
             // 'dolm'            =>$this->getRequest()->getPost(''),
             // 'modibyuid'       =>$this->getRequest()->getPost(''),
              //'verified'        =>$this->getRequest()->getPost(''),
              //'do_verify'       =>$this->getRequest()->getPost(''),
              //'verified_by_uid' =>$this->getRequest()->getPost('')
            );        

    /*Form Validation*/
    if($this->getRequest()->isPost()){
        $formData = $this->getRequest()->getPost();
    if($form->isValidPartial($formData)){
       $uploadedData = $form->getValues();
       $fullFilePath = $form->uplphoto->getFileName();
       Zend_Debug::dump($uploadedData, '$uploadedData');
       Zend_Debug::dump($fullFilePath, '$fullFilePath');
       
       $d->insert($devotee_data);
       echo "Photo Uploaded";
       $form->populate($formData);
       //$this->_redirect('devotees/listdevotees');                
        exit;
     } else{
               $form->getErrors();
               $form->isValid($formData);
     }
    }
      $this->view->form = $form; 
 }
}        