<?php

require_once 'application/models/Base.php';

class Application_Model_Users extends Application_Model_Base
{

    public function create($data)
    {

        $data = array(
            'name' => $data['name'],
            'forename' => $data['forename'],
            'username' => $data['username'],
            'email' => $data['email'],
            'tel' => $data['tel'],
            'password' => $data['password'],
            'created' => date("Y-m-d"),
            'ip' => $_SERVER['REMOTE_ADDR'],
        );

        $this->addData('users', $data);
        
        return true;
    }



    public function createUsername($name,$forename)
    {
        $name = preg_replace("/[^a-zA-Z0-9]+/", "", $name);
        $forename = preg_replace("/[^a-zA-Z0-9]+/", "",$forename);
        $userName = $name . substr($forename, 0, 1);
        $userName = strtoupper($userName);

        $exists = $this->db->get_var("SELECT COUNT(*) FROM users
                    WHERE username like  '{$userName}%'");

        if ($exists) {
            $userName = $userName . $exists;
            return $userName;
        }
        return $userName;
    }

    public function createRandomPassword($numberOfChars = 8) {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass="";
        for ($i = 0; $i < $numberOfChars; $i++) {
            $n = rand(0, strlen($alphabet)-1);
            $pass .= $alphabet[$n];
        }
        return $pass;
    }

    public function getUser($username) {
        $sql = "SELECT * FROM users WHERE username = '{$username}'";
        $row = $this->db->get_row($sql);
        return $row;
    }


}

?>
