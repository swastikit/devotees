<?php
class Application_Model_DbTable_Mstuser extends Zend_Db_Table_Abstract
{
    protected $_name = 'mst_user';
    public function findCredentials($username, $pwd)
    {
        $select = $this->_db->select();
        $select->from(array('u' => 'mst_user'), array('u.id', 'u.login','u.role_id'))
                ->joinLeft(array('d'=>'devotee'), 'u.did=d.did', array('d.first_name','d.middle_name','d.last_name','d.search_name'))
                ->joinLeft(array('r' => 'mst_user_role'), 'u.role_id = r.id', array('role_name' => 'r.name'))
                ->where('u.is_active = ?','Y')
                ->where('u.is_blocked = ?','N')
                ->where('u.login = ?', $username)
                ->where('u.pwd = ?', $this->hashPassword($pwd));
        $stmt = $select->query();
        $row = $stmt->fetchObject();
        if ($row) {
            return $row;
        }
        return false;
    }
    /*
    Returns true    = if the user has temp pwd
            false   = if the user has not temp pwd
            -1      = if the user not found
    */
    public function isTemporaryPwd($userId)
    {
        $row=$this->find($userId);
        if ($row) {
            $r=current($row);
            if($r[0]['is_temporary_pwd']=='Y'){
                return true;
            }else if($r[0]['is_temporary_pwd']=='N'){
                return false;
            }
        }else{
            return -1;    
        }
    }
    
    /*
    Returns 1 = if blocked
            0 = if not blocked
            -1= if not found
    */
    public function isBlocked($login){
        $select = $this->_db->select($login);
        $select->from(array('u' => 'mst_user'), array('u.id', 'u.is_blocked'))
                        ->where('u.is_active = ?','Y')
                        ->where('u.login = ?', $login);
        $stmt = $select->query();
        $row = $stmt->fetchObject();
        if ($row) {
            if($row->is_blocked=='Y'){
                return 1;
            }else{
                return 0;
            }
        }
        return -1;
    }
    
    public function findByLogin($login){
        $select = $this->_db->select();
        $select->from(array('u' => 'mst_user'), array('u.id','u.login','u.security_answer01','u.security_answer02','u.security_answer03'))
                        ->joinLeft(array('d'=>'devotee'), 'u.did=d.did', array('d.first_name','d.middle_name','d.last_name','d.search_name','d.email','d.mobile','d.country_id'))
                        ->joinLeft(array('q1' => 'mst_user_security_questions'), 'u.security_question_id01 = q1.id', array('security_question01' => 'q1.question'))
                        ->joinLeft(array('q2' => 'mst_user_security_questions'), 'u.security_question_id02 = q2.id', array('security_question02' => 'q2.question'))
                        ->joinLeft(array('q3' => 'mst_user_security_questions'), 'u.security_question_id03 = q3.id', array('security_question03' => 'q3.question'))
                        ->where('u.is_active = ?','Y')
                        ->where('u.is_blocked = ?','N')
                        ->where('u.login = ?', $login);
        $stmt = $select->query();
        $row = $stmt->fetchObject();
        if ($row) {
            return $row;
        }
        return false;
    }
    public function findById($id){
        $select = $this->_db->select();
        $select->from(array('u' => 'mst_user'), array('u.id','u.login','u.security_answer01','u.security_answer02','u.security_answer03','u.security_question_id01','u.security_question_id02','u.security_question_id03'))
                        ->joinLeft(array('d'=>'devotee'), 'u.did=d.did', array('d.first_name','d.middle_name','d.last_name' ,'d.search_name','d.email','d.mobile','d.country_id'))
                        ->joinLeft(array('q1' => 'mst_user_security_questions'), 'u.security_question_id01 = q1.id', array('security_question01' => 'q1.question'))
                        ->joinLeft(array('q2' => 'mst_user_security_questions'), 'u.security_question_id02 = q2.id', array('security_question02' => 'q2.question'))
                        ->joinLeft(array('q3' => 'mst_user_security_questions'), 'u.security_question_id03 = q3.id', array('security_question03' => 'q3.question'))
                        ->where('u.is_active = ?','Y')
                        ->where('u.is_blocked = ?','N')
                        ->where('u.id = ?', $id);
        $stmt = $select->query();
        $row = $stmt->fetchObject();
        if ($row) {
            return $row;
        }
        return false;
    }
    public function findByIdArray($id){
        $row = $this->fetch($this->select()->where('id = ?', $id));
        if ($row) {
            return $row;
        }
        return false;
    }
    public function verifyPrimaryInfo($login,$email){
        $user=$this->findByLogin($login);
        if($user){
            if($user->email==$email){
                return $user;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    public function verifySecurityAnswers($login, array $answers = array()){
        $user=$this->findByLogin($login);
        if($user){
            if($user->security_answer01==$answers->ans01 
            && $user->security_answer02==$answers->ans02
            && $user->security_answer03==$answers->ans03)
            {
                return $user;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    public function resetPassword($login, $password){
        $this->_db->beginTransaction();
        try {
            $row = $this->fetchRow($this->select( )->where('login = ?', $login));
            $row->pwd = $this->hashPassword($password);
            $row->save();
            $this->_db->commit();
            return array('user'=>$row,'result'=>'ok');
        
        } catch (Exception $e) {
            $this->_db->rollBack();
            return array('user'=>null,'result'=>$e->getMessage());
        }
    }
    public function block($login, $remarks){
        $this->_db->beginTransaction();
        try {
            $row = $this->fetchRow($this->select()->where('login = ?', $login));
            $row->is_blocked = 'Y';
            $row->blocked_date = date('Y-m-d H:i:s');
            $row->blocked_reason=$remarks;
            $row->save();
            $this->_db->commit();
            return array('user'=>$row,'result'=>'ok');
        
        } catch (Exception $e) {
            $this->_db->rollBack();
            return array('user'=>null,'result'=>$e->getMessage());
        }
    }
    protected function hashPassword($pwd)
    {
        return md5($pwd);
    }
    public function updatePreliminaryInfo($id,$data){
        $row = $this->fetchRow($this->select()->where('id = ?', $id));
        if($row){
            $this->_db->beginTransaction();    
            try {
                //-------Check for duplicate login--------------
                $select = $this->_db->select()->from('mst_user', array('count' => new Zend_Db_Expr('COUNT(id)')))
                ->where('login = ?', $data['login'])
                ->where('id <> ?', $id);
                if($this->_db->fetchOne($select)>0){
                    $this->_db->rollBack();
                    return array('user'=>null,'result'=>'DUPLICATE_LOGIN');
                }
                $row->login = $data['login'];
                $row->security_question_id01 = $data['security_question_id01'];
                $row->security_answer01 = $data['security_answer01'];
                $row->security_question_id02 = $data['security_question_id02'];
                $row->security_answer02 = $data['security_answer02'];
                $row->is_temporary_pwd = 'N';
                $row->dolm = date('Y-m-d H:i:s');
                $row->last_pwd_changed_date = date('Y-m-d H:i:s');
                $auth = Zend_Auth::getInstance();
                if($auth->hasIdentity()){
                    $authArray=$auth->getIdentity();
                    $userid = $authArray['user_id'];
                    $row->modi_by_uid = $userid;
                }
                $row->save();

                //--------------Update The Devotee-----------------
                $d=new Application_Model_DbTable_Devotee();
                $dRowSet= $d->find($row->did);
                if($dRowSet){
                    $dRow=$dRowSet->getRow(0);
                    $dRow->first_name=$data['fName'];
                    $dRow->middle_name=$data['mName'];
                    $dRow->last_name=$data['lName'];
                    $dRow->country_id=$data['country_id'];
                    
                    $dRow->email=$data['email'];
                    $dRow->mobile=$data['mobile'];
                    $dRow->save();
                }
                $this->_db->commit();
                return array('user'=>$row,'result'=>'ok');
            } catch (Exception $e) {
                $this->_db->rollBack();
                return array('user'=>null,'result'=>$e->getMessage());
            }
        }
    }
    
    /*
    Returns the list of mstuser for the purpose of list in tabler format.
    used by admin/user/list user
    */
    public function listUsers($limit,$offset,$options = Array()){
        $defaults = Array('searchname'=>'', 'searchlogin'=>'', 'sortfield' => 'd.encoded_search_name','sortorder' => 'ASC');
        $options = array_merge($defaults, $options);
        $select = $this->_db->select();
   		$select->from(array('u'=>'mst_user'),array('u.id', 'u.login', 'u.pwd', 'u.is_temporary_pwd', 'u.role_id', 'u.did','u.remarks','u.is_active','u.is_blocked','u.blocked_reason','u.blocked_date','u.owner_uid','u.entered_by_uid','u.entered_date','u.modi_by_uid','u.dolm','u.last_login'))	
                ->joinLeft(array('r'=>'mst_user_role') , 'u.role_id = r.id', array('role'=>'r.name'))
                ->joinLeft(array('d'=>'devotee'), 'u.did=d.did', array('d.did','d.first_name','d.middle_name','d.last_name','d.initiated_name', 'encoded_name'=>'d.encoded_search_name', 'd.search_name', 'd.mobile', 'd.email', 'd.country_id', 'd.do_birth' , 'd.gender', 'd.center_id', 'd.counselor_id', 'd.blood_group', 'd.pics', 'd.devotee_status'))
                ->joinLeft(array('cn'=>'mst_country'), 'd.country_id = cn.id', array('country'=>'cn.name','cn.tel_code'))
                ->joinLeft(array('a'=>'mst_asram'), 'd.asram_status_id = a.id', array('ashram'=>'a.name'))
                ->joinLeft(array('c'=>'mst_center'), 'd.center_id = c.id', array('centername'=>'c.name'))
                ->joinLeft(array('con'=>'mst_counselor') , 'd.counselor_id = con.id' , array())
                ->joinLeft(array('con_dev'=>'devotee'), 'con.did=con_dev.did', array('counselorname' => 'con_dev.search_name'))
                ->joinLeft(array('astcon'=>'mst_astcounselor') , 'd.ast_counselor_id = astcon.id' ,array())
                ->joinLeft(array('astcon_dev'=>'devotee'), 'astcon.did=astcon_dev.did', array('astcounselorname' => 'astcon_dev.search_name'))
                ->joinLeft(array('hg'=>'mst_guru'), 'd.ini_guru_id = hg.id', array('iniguruname'=>'hg.name'))
                ->joinLeft(array('sg'=>'mst_guru'), 'd.sanyas_guru_id = sg.id', array('sanguruname'=>'sg.name'))

                ->joinLeft(array('o'=>'mst_user'),'u.owner_uid=o.id', array('ownerlogin'=>'o.login'))
                ->joinLeft(array('o_dev'=>'devotee'), 'o.did=o_dev.did', array('ownername' => 'o_dev.search_name'))
                ->joinLeft(array('e'=>'mst_user'),'u.entered_by_uid=e.id', array('enteredbylogin'=>'e.login'))
                ->joinLeft(array('e_dev'=>'devotee'), 'e.did=e_dev.did', array('enteredbyname' => 'e_dev.search_name'))
                ->joinLeft(array('m'=>'mst_user'),'u.modi_by_uid=m.id', array('modifiedbylogin'=>'m.login'))
                ->joinLeft(array('m_dev'=>'devotee'), 'm.did=m_dev.did', array('modifiedbyname' => 'm_dev.search_name'))
                
                ->where($this->_db->quoteInto('d.encoded_search_name LIKE ?','%' . Rgm_Basics::encodeDiacritics($options['searchname']) . '%'))
                ->orWhere($this->_db->quoteInto('u.login LIKE ?','%' . $options['searchlogin'] . '%'))
                ->order($options['sortfield'] . ' ' . $options['sortorder'])
                ->limit($limit,$offset);
        $results = $this->getAdapter()->fetchAll($select);
        return $results;
    }
    /*
    Returns only id fields to determine Pageing
    */
    public function listUsersIds($options = Array()){
        $defaults = Array('searchname'=>'', 'searchlogin'=>'', 'sortfield' => 'd.encoded_search_name','sortorder' => 'ASC');
        $options = array_merge($defaults, $options);
        $select = $this->_db->select();
   		$select->from(array('u'=>'mst_user'),array('u.id'))	
                ->joinLeft(array('r'=>'mst_user_role') , 'u.role_id = r.id', array())
                ->joinLeft(array('d'=>'devotee'), 'u.did=d.did', array())
                ->joinLeft(array('cn'=>'mst_country'), 'd.country_id = cn.id', array())
                ->joinLeft(array('a'=>'mst_asram'), 'd.asram_status_id = a.id', array())
                ->joinLeft(array('c'=>'mst_center'), 'd.center_id = c.id', array())
                ->joinLeft(array('con'=>'mst_counselor') , 'd.counselor_id = con.id' , array())
                ->joinLeft(array('con_dev'=>'devotee'), 'con.did=con_dev.did', array())
                ->joinLeft(array('astcon'=>'mst_astcounselor') , 'd.ast_counselor_id = astcon.id' ,array())
                ->joinLeft(array('astcon_dev'=>'devotee'), 'astcon.did=astcon_dev.did', array())
                ->joinLeft(array('hg'=>'mst_guru'), 'd.ini_guru_id = hg.id', array())
                ->joinLeft(array('sg'=>'mst_guru'), 'd.sanyas_guru_id = sg.id',  array())

                ->joinLeft(array('o'=>'mst_user'),'u.owner_uid=o.id',  array())
                ->joinLeft(array('o_dev'=>'devotee'), 'o.did=o_dev.did',  array())
                ->joinLeft(array('e'=>'mst_user'),'u.entered_by_uid=e.id',  array())
                ->joinLeft(array('e_dev'=>'devotee'), 'e.did=e_dev.did',  array())
                ->joinLeft(array('m'=>'mst_user'),'u.modi_by_uid=m.id',  array())
                ->joinLeft(array('m_dev'=>'devotee'), 'm.did=m_dev.did',  array())
                
                ->where($this->_db->quoteInto('d.encoded_search_name LIKE ?','%' . Rgm_Basics::encodeDiacritics($options['searchname']) . '%'))
                ->orWhere($this->_db->quoteInto('u.login LIKE ?','%' . $options['searchlogin'] . '%'));

        $results = $this->getAdapter()->fetchAll($select);
        return $results;
    }
    //Returns the basic information of the user
    public function getBasicInfo($id){
        $select = $this->_db->select();
   		$select->from(array('u'=>'mst_user'),array('u.id', 'u.login', 'u.pwd', 'u.is_temporary_pwd', 'u.role_id', 'u.did','u.remarks','u.is_active','u.is_blocked','u.blocked_reason','u.blocked_date','u.owner_uid','u.entered_by_uid','u.entered_date','u.modi_by_uid','u.dolm','u.last_login'))	
                ->joinLeft(array('r'=>'mst_user_role') , 'u.role_id = r.id', array('role'=>'r.name'))
                ->joinLeft(array('d'=>'devotee'), 'u.did=d.did', array('d.did','d.first_name','d.middle_name','d.last_name','d.initiated_name', 'encoded_name'=>'d.encoded_search_name', 'd.search_name', 'd.mobile', 'd.email', 'd.country_id', 'd.do_birth' , 'd.gender', 'd.center_id', 'd.counselor_id', 'd.mobile', 'd.email', 'd.blood_group', 'd.pics', 'd.devotee_status'))
                ->joinLeft(array('cn'=>'mst_country'), 'd.country_id = cn.id', array('country'=>'cn.name','cn.tel_code'))                
                ->joinLeft(array('a'=>'mst_asram'), 'd.asram_status_id = a.id', array('ashram'=>'a.name'))
                ->joinLeft(array('c'=>'mst_center'), 'd.center_id = c.id', array('centername'=>'c.name'))
                ->joinLeft(array('con'=>'mst_counselor') , 'd.counselor_id = con.id' , array())
                ->joinLeft(array('con_dev'=>'devotee'), 'con.did=con_dev.did', array('counselorname' => 'con_dev.search_name'))
                ->joinLeft(array('astcon'=>'mst_astcounselor') , 'd.ast_counselor_id = astcon.id' ,array())
                ->joinLeft(array('astcon_dev'=>'devotee'), 'astcon.did=astcon_dev.did', array('astcounselorname' => 'astcon_dev.search_name'))
                ->joinLeft(array('hg'=>'mst_guru'), 'd.ini_guru_id = hg.id', array('iniguruname'=>'hg.name'))
                ->joinLeft(array('sg'=>'mst_guru'), 'd.sanyas_guru_id = sg.id', array('sanguruname'=>'sg.name'))

                ->joinLeft(array('o'=>'mst_user'),'u.owner_uid=o.id', array('ownerlogin'=>'o.login'))
                ->joinLeft(array('o_dev'=>'devotee'), 'o.did=o_dev.did', array('ownername' => 'o_dev.search_name'))
                ->joinLeft(array('e'=>'mst_user'),'u.entered_by_uid=e.id', array('enteredbylogin'=>'e.login'))
                ->joinLeft(array('e_dev'=>'devotee'), 'e.did=e_dev.did', array('enteredbyname' => 'e_dev.search_name'))
                ->joinLeft(array('m'=>'mst_user'),'u.modi_by_uid=m.id', array('modifiedbylogin'=>'m.login'))
                ->joinLeft(array('m_dev'=>'devotee'), 'm.did=m_dev.did', array('modifiedbyname' => 'm_dev.search_name'))
                ->Where('u.id=?',$id);
        $results = $this->getAdapter()->fetchRow($select);
        return $results;
    }
    //Returns the list of db 
    //$option == ALL=all; checked=only assigned to this user; unchecked=those which are not assigned to this user.
    public function getDbList($id,$option){
        $sqlChecked="SELECT db.id, db.name, db.description, db.is_active, 'checked' checked FROM mst_database db where db.id in (select u_d.database_id from mst_user_vs_database u_d where u_d.user_id=$id)";
        $sqlUnChecked="SELECT db.id, db.name, db.description, db.is_active, '' checked FROM mst_database db where db.id not in (select u_d.database_id from mst_user_vs_database u_d where u_d.user_id=$id)";
        if($option=='ALL'){
            $results = $this->getAdapter()->fetchAll($sqlChecked . " UNION ALL " . $sqlUnChecked);
        }else if($option=='CHECKED'){
            $results = $this->getAdapter()->fetchAll($sqlChecked);
        }else if($option=='UNCHECKED'){
            $results = $this->getAdapter()->fetchAll($sqlUnChecked);
        }
        return $results;
    }
    //Returns the list of centers assigned to this user 
    //$option == ALL=all; checked=only assigned to this user; unchecked=those which are not assigned to this user.
    public function getCenterList($id,$option){
        $sqlChecked="SELECT c.id, c.name, c.country_id, cnt.name AS country, 'checked' checked, c.isactive FROM mst_center AS c Left Join mst_country AS cnt ON c.country_id = cnt.id WHERE c.id IN (SELECT u_c.center_id FROM  mst_user_vs_center u_c WHERE u_c.user_id=$id)";
        $sqlUnChecked="SELECT c.id, c.name, c.country_id, cnt.name AS country, '' checked, c.isactive FROM mst_center AS c Left Join mst_country AS cnt ON c.country_id = cnt.id WHERE c.id NOT IN (SELECT u_c.center_id FROM  mst_user_vs_center u_c WHERE u_c.user_id=$id)";
        $sqlAll = $sqlChecked . " UNION ALL " . $sqlUnChecked;
        $sqlOrderBy = " ORDER BY country ASC, name ASC";
        if($option=='ALL'){
            $results = $this->getAdapter()->fetchAll($sqlAll . $sqlOrderBy);
        }else if($option=='CHECKED'){
            $results = $this->getAdapter()->fetchAll($sqlChecked . $sqlOrderBy);
        }else if($option=='UNCHECKED'){
            $results = $this->getAdapter()->fetchAll($sqlUnChecked . $sqlOrderBy);
        }
        return $results;
    }
    //Returns the list of Counselor assigned to this user 
    //$option == ALL=all; checked=only assigned to this user; unchecked=those which are not assigned to this user.
    public function getCounselorList($id,$option){
        $sqlChecked="SELECT a.id, a.did, d.search_name AS name, d.encoded_search_name, c.name AS center, c.id AS center_id, 'checked' checked, a.isactive FROM mst_counselor AS a LEFT JOIN devotee AS d ON a.did = d.did LEFT JOIN mst_center AS c ON d.center_id = c.id WHERE a.id IN (SELECT u.counselor_id FROM  mst_user_vs_counselor u WHERE u.user_id=$id)";
        $sqlUnChecked="SELECT a.id, a.did, d.search_name AS name, d.encoded_search_name, c.name AS center, c.id AS center_id, '' checked, a.isactive FROM mst_counselor AS a LEFT JOIN devotee AS d ON a.did = d.did LEFT JOIN mst_center AS c ON d.center_id = c.id WHERE a.id NOT IN (SELECT u.counselor_id FROM  mst_user_vs_counselor u WHERE u.user_id=$id)";
        $sqlAll = $sqlChecked . " UNION ALL " . $sqlUnChecked;
        $sqlOrderBy = " ORDER BY encoded_search_name ASC";
        if($option=='ALL'){
            $results = $this->getAdapter()->fetchAll($sqlAll . $sqlOrderBy);
        }else if($option=='CHECKED'){
            $results = $this->getAdapter()->fetchAll($sqlChecked . $sqlOrderBy);
        }else if($option=='UNCHECKED'){
            $results = $this->getAdapter()->fetchAll($sqlUnChecked . $sqlOrderBy);
        }
        return $results;
    }
    //Returns the list of mentor assigned to this user 
    //$option == ALL=all; checked=only assigned to this user; unchecked=those which are not assigned to this user.
    public function getMentorList($id,$option){
        $sqlChecked="SELECT a.id, a.did, d.search_name AS name, d.encoded_search_name, c.name AS center, c.id AS center_id, 'checked' checked, a.isactive FROM mst_astcounselor AS a LEFT JOIN devotee AS d ON a.did = d.did LEFT JOIN mst_center AS c ON d.center_id = c.id WHERE a.id IN (SELECT u.astcounselor_id FROM  mst_user_vs_mentor u WHERE u.user_id=$id)";
        $sqlUnChecked="SELECT a.id, a.did, d.search_name AS name, d.encoded_search_name, c.name AS center, c.id AS center_id, '' checked, a.isactive FROM mst_astcounselor AS a LEFT JOIN devotee AS d ON a.did = d.did LEFT JOIN mst_center AS c ON d.center_id = c.id WHERE a.id NOT IN (SELECT u.astcounselor_id FROM  mst_user_vs_mentor u WHERE u.user_id=$id)";
        $sqlAll = $sqlChecked . " UNION ALL " . $sqlUnChecked;
        $sqlOrderBy = " ORDER BY encoded_search_name ASC";
        if($option=='ALL'){
            $results = $this->getAdapter()->fetchAll($sqlAll . $sqlOrderBy);
        }else if($option=='CHECKED'){
            $results = $this->getAdapter()->fetchAll($sqlChecked . $sqlOrderBy);
        }else if($option=='UNCHECKED'){
            $results = $this->getAdapter()->fetchAll($sqlUnChecked . $sqlOrderBy);
        }
        return $results;
    }
    //Returns the list of mentor assigned to this user 
    //$option == ALL=all; checked=only assigned to this user; unchecked=those which are not assigned to this user.
    public function getGuruList($id,$option){
        $sqlChecked="SELECT c.id, c.name, 'checked' checked, c.isactive FROM mst_guru AS c WHERE c.id IN (SELECT u.guru_id FROM  mst_user_vs_guru u WHERE u.user_id=$id)";
        $sqlUnChecked="SELECT c.id, c.name, '' checked, c.isactive FROM mst_guru AS c WHERE c.id NOT IN (SELECT u.guru_id FROM  mst_user_vs_guru u WHERE u.user_id=$id)";
        $sqlAll = $sqlChecked . " UNION ALL " . $sqlUnChecked;
        $sqlOrderBy = " ORDER BY name ASC";
        if($option=='ALL'){
            $results = $this->getAdapter()->fetchAll($sqlAll . $sqlOrderBy);
        }else if($option=='CHECKED'){
            $results = $this->getAdapter()->fetchAll($sqlChecked . $sqlOrderBy);
        }else if($option=='UNCHECKED'){
            $results = $this->getAdapter()->fetchAll($sqlUnChecked . $sqlOrderBy);
        }
        return $results;
    }
    //Returns the list of action assigned to this user 
    //$option == ALL=all; checked=only assigned to this user; unchecked=those which are not assigned to this user.
    public function getActionList($id,$option){
        //SELECT m.id, m.name, m.description, m.is_active, mo.name AS module FROM mst_user_method AS m LEFT JOIN mst_user_menu_main AS mo ON m.menu_id = mo.id WHERE m.is_public='N' AND m.id IN (SELECT u.method_id FROM  mst_user_method_vs_user u WHERE u.user_id=2) ORDER BY module,name
        $sqlChecked="SELECT m.id, m.name, m.description, m.is_active, mo.id AS moduleid, mo.name AS module, 'checked' checked FROM mst_user_method AS m LEFT JOIN mst_user_menu_main AS mo ON m.menu_id = mo.id WHERE m.is_public='N' AND m.id IN (SELECT u.method_id FROM  mst_user_method_vs_user u WHERE u.user_id=$id)";
        $sqlUnChecked="SELECT m.id, m.name, m.description, m.is_active, mo.id AS moduleid, mo.name AS module, '' checked FROM mst_user_method AS m LEFT JOIN mst_user_menu_main AS mo ON m.menu_id = mo.id WHERE m.is_public='N' AND m.id NOT IN (SELECT u.method_id FROM  mst_user_method_vs_user u WHERE u.user_id=$id)";
        $sqlAll = $sqlChecked . " UNION ALL " . $sqlUnChecked;
        $sqlOrderBy = " ORDER BY module ASC, name ASC";
        if($option=='ALL'){
            $results = $this->getAdapter()->fetchAll($sqlAll . $sqlOrderBy);
        }else if($option=='CHECKED'){
            $results = $this->getAdapter()->fetchAll($sqlChecked . $sqlOrderBy);
        }else if($option=='UNCHECKED'){
            $results = $this->getAdapter()->fetchAll($sqlUnChecked . $sqlOrderBy);
        }
        return $results;
    }
    //Returns the list of Devotees assigned to this user
    //$option is irrlevent 
    public function getDevoteeList($id,$option,$page=null){
        $sqlWhere="";
        $sql = "SELECT d.did,d.first_name,d.middle_name,d.last_name,d.organization_name,d.search_name,c.name centername, d.pics"
                . " FROM devotee AS d"
                . " Left Join mst_center AS c ON d.center_id = c.id";
        $sqlWhere=" where d.contact_type='I' and d.isactive='Y' and d.did<>1 and d.did in (select did from mst_user_vs_devotee where user_id=$id)";
        $sql = $sql . $sqlWhere . " order by d.encoded_search_name";// LIMIT 20";
        $results = $this->getAdapter()->fetchAll($sql);
        return $results;
    }
    
    /*
    Returns ture/false
    used by UserServices.php
    */
    public function hasAccessToMethod($method,$uid){
        $sql = $this->_db->quoteInto("SELECT a.id FROM mst_user_method_vs_user AS a where a.user_id = ?",$uid) . $this->_db->quoteInto(" and a.method_id in (select b.id from mst_user_method AS b where b.method_name= ? and b.is_active='Y')",$method );
        $result=$this->_db->query($sql)->rowCount();
        return $result;
    }
    /*
    ------------Special Methods in mst_user_method--------------------
    These are used by UserServices.php, Mstuser.php etc.
    */
    const METHOD_CAN_VIEW_LIST_OF_DEVOTEES_ALL='CAN_VIEW_LIST_OF_DEVOTEES_ALL';
    const METHOD_CAN_VIEW_DEVOTEE_PROFILE_ALL='CAN_VIEW_DEVOTEE_PROFILE_ALL';
    const METHOD_CAN_EDIT_DEVOTEE_PROFILE_ALL='CAN_EDIT_DEVOTEE_PROFILE_ALL';
}