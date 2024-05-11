<?php


  class Users {
    private $userId;
    private $userType;
    private $firstName;
    private $lastName;
    private $password;
    private $email;
    private $phoneNumber;
    
    public function __construct() {
        $this->userId = null;
        $this->userType = null;
        $this->firstName = null;
        $this->lastName = null;
        $this->password = null;
        $this->email = null;
        $this->phoneNumber = null;
    }

    // Getters and setters...

    public function deleteUser() {
        try {
            $db = Database::getInstance();
            $data = $db->querySql("DELETE FROM dpProj_User WHERE userId='$this->userId'");
            return true;
        } catch (Exception $e) {
            echo 'Exception: ' . $e;
            return false;
        }
    }

    public function initWithUid($userId) {
        $db = Database::getInstance();
        $data = $db->singleFetch("SELECT * FROM dpProj_User WHERE userId='$userId'");
        $this->initWith($data->userId, $data->userType, $data->firstName, $data->lastName, $data->password, $data->email, $data->phoneNumber);
    }

    public function checkUser($username, $password) {
        $db = Database::getInstance();
        $data = $db->singleFetch("SELECT * FROM dpProj_User WHERE username = '$username' AND password = '$password'");
        $this->initWith($data->userId, $data->userType, $data->firstName, $data->lastName, $data->password, $data->email, $data->phoneNumber);
    }

    public function registerUser() {
        if ($this->isValid()) {
            try {
                $db = Database::getInstance();
                $data = $db->querySql("INSERT INTO dpProj_User (userType, firstName, lastName, password, email, phoneNumber) VALUES ('$this->userType', '$this->firstName', '$this->lastName', '$this->password', '$this->email', '$this->phoneNumber')");
                return true;
            } catch (Exception $e) {
                echo 'Exception: ' . $e;
                return false;
            }
        } else {
            return false;
        }
    }

    private function initWith($userId, $userType, $firstName, $lastName, $password, $email, $phoneNumber) {
        $this->userId = $userId;
        $this->userType = $userType;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->password = $password;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
    }

    public function updateDB() {
        if ($this->isValid()) {
            $db = Database::getInstance();
            $data = $db->querySql("UPDATE dpProj_User SET userType='$this->userType', firstName='$this->firstName', lastName='$this->lastName', password='$this->password', email='$this->email', phoneNumber='$this->phoneNumber' WHERE userId='$this->userId'");
        }
    }

    public function getAllUsers() {
        $db = Database::getInstance();
        $data = $db->multiFetch("SELECT * FROM dpProj_User");
        return $data;
    }

    public function isValid() {
        return !empty($this->userType) && !empty($this->firstName) && !empty($this->lastName) && !empty($this->password) && !empty($this->email) && !empty($this->phoneNumber);
    }
}



