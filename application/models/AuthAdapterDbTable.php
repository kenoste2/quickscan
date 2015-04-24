<?php
require_once 'application/models/Base.php';

class Application_Model_AuthAdapterDbTable extends Application_Model_Base {

    protected $username = "";
    protected $password = "";
    protected $valid = false;

    public function setIdentity($userName) {
        $this->username = $userName;
    }

    public function setCredential($password) {
        $this->password = $password;
    }

    public function authenticate() {

        $sql = "select * from users where username = '{$this->username}'";

        $row = $this->db->get_row($sql);
        $userId = $row->id;

        if (!empty($row)) {
            
            if ($row->password == $this->password) {
                $authNamespace = new Zend_Session_Namespace('Zend_Auth');


                $authNamespace->online_user_id = $userId;
                $authNamespace->online_rights = 1;
                $authNamespace->online_user = $this->username;
                $authNamespace->online_name = $row->name ." ". $row->forename;
                $this->valid = true;
            }
            else
                return "no correct user/password";
        }
        else
            return "no correct user/password";
    }

    
    public function isValid() {
        $authNamespace = new Zend_Session_Namespace('Zend_Auth');
        
        if (!empty($authNamespace->online_user)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function hasIdentity() {
        return $this->isValid();
    }
    
    public function clearIdentity() {
        $authNamespace = new Zend_Session_Namespace('Zend_Auth');
        unset($authNamespace->online_user);
    }
    
    public function getIdentityName() {
        $authNamespace = new Zend_Session_Namespace('Zend_Auth');
        return $authNamespace->online_name;
    }
    public function getIdentity() {
        $authNamespace = new Zend_Session_Namespace('Zend_Auth');
        return $authNamespace->online_user;
    }
    
    
}

