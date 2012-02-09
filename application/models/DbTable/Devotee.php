<?php
class Application_Model_DbTable_Devotee extends Zend_Db_Table_Abstract
{
    protected $_name = 'devotee';
    
    public function getDevotees($limit,$offset){
        $select = $this->_db->select();
   		$select->from(array('d'=>'devotee'),array('d.did', 'd.initiated_name','d.first_name','d.middle_name','d.last_name', 'd.search_name', 'd.do_birth' , 'd.gender', 'd.center_id', 'd.counselor_id', 'd.ast_counselor_id', 'd.mobile', 'd.email', 'd.blood_group', 'd.pics', 'd.devotee_status'))	
                ->joinLeft(array('a'=>'mst_asram'), 'd.asram_status_id = a.id', array('ashram'=>'a.name'))
                ->joinLeft(array('c'=>'mst_center'), 'd.center_id = c.id', array('centername'=>'c.name'))
                
                ->joinLeft(array('con'=>'mst_counselor') , 'd.counselor_id = con.id' , array())
                ->joinLeft(array('con_dev'=>'devotee'), 'con.did=con_dev.did', array('counselorname' => 'con_dev.search_name'))

                ->joinLeft(array('astcon'=>'mst_astcounselor') , 'd.ast_counselor_id = astcon.id' ,array())
                ->joinLeft(array('astcon_dev'=>'devotee'), 'astcon.did=astcon_dev.did', array('astcounselorname' => 'astcon_dev.search_name'))
                
                ->joinLeft(array('hg'=>'mst_guru'), 'd.ini_guru_id = hg.id', array('iniguruname'=>'hg.name'))
                ->joinLeft(array('sg'=>'mst_guru'), 'd.sanyas_guru_id = sg.id', array('sanguruname'=>'sg.name'))
                
                
                //->where($this->_db->quoteInto('d.encoded_search_name LIKE ?','%' . Rgm_Basics::encodeDiacritics($like) . '%'))
                ->order('d.search_name ASC')   		       
                ->limit($limit,$offset);
        $results = $this->getAdapter()->fetchAll($select);
        return $results;
    }
    
    
    public function getDevotee( $did ){
        $did = (int)$did;
        $row = $this->fetchRow('did = ' . $did);
        if (!$row) {
            throw new Exception("Count not find row $did");
        }
        return $row->toArray();
    }
    public function getAllDids(){
        $select = $this->_db->select();
        $select->from(array('d' => 'devotee'),array('d.did'));
        $results = $this->getAdapter()->fetchAll($select);
        return $results;
    }
//--------------------------------------------------------------------------------       
    public function getNumberofRecords(){
        $select = $this->_db->select();
        $select->from(array('d'=>'devotee'), array('d.did'));
        $result = $this->getAdapter()->fetchAll($select);
        $num_rows = count($result);
        return $num_rows;
    }
/*    
 Add a new devotee

public function addnewdevotee($devotee_data=array()){
    
        $devotee_data = array(
                              //'did'             =>$did,
                              'first_name'      =>$fname ,
                              'middle_name'     =>$mname ,
                              'last_name'       =>$lname ,
                              'do_birth'        =>$d_o_b ,
                              'gender'          =>$gender ,
                              'country_id'      =>$countryCode,
                              'center_id'       =>$CenterId ,
                              'counselor_id'    =>$Counselor_Id,
                              'mobile'          =>$mobile_number ,
                              'email'           =>$email_id ,
                              'pres_phone'      =>$present_phone ,
                              'counselee_status'=>$counselee_Status ,
                              'devotee_status'  =>$Devotee_Status ,
                              'asram_status_id' =>$Asram_Status,
                              'mother_tongue_id'=>$Mother_Tongue,
                );
                                
    $this->insert($devotee_data);    
    }
    */
}