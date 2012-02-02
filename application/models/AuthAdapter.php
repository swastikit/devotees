<?php
class Application_Model_AuthAdapter implements Zend_Auth_Adapter_Interface
{
    protected $username;
    protected $password;
    protected $user;
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->user = new Application_Model_DbTable_Mstuser();
    }
    public function authenticate()
    {
        $match = $this->user->findCredentials($this->username, $this->password);
        if (!$match) {
            $result = new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, null);
        } else {
            //This $myuser will be stored in the session
            $myuser = array(
                'role_id' => $match->role_id,
                'role_name' => $match->role_name,
                'user_id' => $match->id,
                'user_name' => $match->name,
                'login' => $match->login);
            $result = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $myuser);
        }
        return $result;
    }
}
