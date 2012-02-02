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
    $num_rec_found = $devotees->getNumberofRecords();
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
    //$did = '';
    /* 
    $fname = $this->getRequest()->getPost('first_name');
    $mname = $this->getRequest()->getPost('middle_name');
    $lname = $this->getRequest()->getPost('last_name');
    $d_o_b = array($this->getRequest()->getPost('day'),$this->getRequest()->getPost('month'),$this->getRequest()->getPost('year'));
    $gender = $this->getRequest()->getPost('gender');
    $countryCode = $this->getRequest()->getPost('cc');
    $CenterId = $this->getRequest()->getPost('center');
    $Counselor_Id = $this->getRequest()->getPost('counselor');
    $mobile_number = $this->getRequest()->getPost('mobile');
    $email_id = $this->getRequest()->getPost('email');
    $present_phone = $this->getRequest()->getPost('phone_number');
    $counselee_Status = $this->getRequest()->getPost('counselee_status');
    $Devotee_Status = $this->getRequest()->getPost('active_status');
    $Asram_Status = $this->getRequest()->getPost('marital_status');
    $Mother_Tongue = $this->getRequest()->getPost('mother_tongue');
    $Blood_
    */
    $devotee_data = array(
              //'did'             =>$did, 
              'first_name'      =>$this->getRequest()->getPost('first_name') ,
              'middle_name'     =>$this->getRequest()->getPost('middle_name'),
              'last_name'       =>$this->getRequest()->getPost('middle_name'),
              'do_birth'        =>array($this->getRequest()->getPost('day'),$this->getRequest()->getPost('month'),$this->getRequest()->getPost('year')),
              'gender'          =>$this->getRequest()->getPost('middle_name'),
              'country_id'      =>$this->getRequest()->getPost('middle_name'),
              'center_id'       =>$this->getRequest()->getPost('middle_name'),
              'counselor_id'    =>$this->getRequest()->getPost('middle_name'),
              'mobile'          =>$this->getRequest()->getPost('middle_name'),
              'email'           =>$this->getRequest()->getPost('middle_name'),
              'pres_phone'      =>$this->getRequest()->getPost('middle_name'),
              'devotee_status'  =>$this->getRequest()->getPost('middle_name'),
              'asram_status_id' =>$this->getRequest()->getPost('middle_name'),
              'mother_tongue_id'=>$this->getRequest()->getPost('middle_name'),
              'counselee_status'=>$this->getRequest()->getPost('middle_name'),
              'blood_group'     =>$this->getRequest()->getPost('middle_name'),
              'religion_id'     =>'',
              'native_place'    =>'',
              'native_state_id' =>''
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