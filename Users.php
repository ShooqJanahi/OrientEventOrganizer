<?php


  class Users {
    private $userId;
    private $username;
    private $userType;
    private $firstName;
    private $lastName;
    private $password;
    private $email;
    private $phoneNumber;
    
    public function getUserId() {
        return $this->userId;
    }
    
    public function getUsername() {
        return $this->username;
    }       

    public function getUserType() {
        return $this->userType;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhoneNumber() {
        return $this->phoneNumber;
    }

    public function setUserId($userId): void {
        $this->userId = $userId;
    }
    
    public function setUserName($username): void {
        $this->username = $username;
    }

    public function setUserType($userType): void {
        $this->userType = $userType;
    }

    public function setFirstName($firstName): void {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName): void {
        $this->lastName = $lastName;
    }

    public function setPassword($password): void {
        $this->password = $password;
    }

    public function setEmail($email): void {
        $this->email = $email;
    }

    public function setPhoneNumber($phoneNumber): void {
        $this->phoneNumber = $phoneNumber;
    }

        
    public function __construct() {
        $this->userId = null;
        $this->username = null;
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
        $data = $db->singleFetch("SELECT * FROM dbProj_User WHERE userId='$userId'");
        $this->initWith($data->userId, $data->username, $data->userType, $data->firstName, $data->lastName, $data->password, $data->email, $data->phoneNumber);
    }

    public function checkUser($username, $password) {
        $db = Database::getInstance();
        $data = $db->singleFetch("SELECT * FROM dpProj_User WHERE username = '$username' AND password = '$password'");
        $this->initWith($data->userId, $data->username, $data->userType, $data->firstName, $data->lastName, $data->password, $data->email, $data->phoneNumber);
    }

    function initWithUsername() {

        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM dpProj_User WHERE username = \'' . $this->username . '\'');
        if ($data != null) {
            return false;
        }
        return true;
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

    private function initWith($userId, $username, $userType, $firstName, $lastName, $password, $email, $phoneNumber) {
        $this->userId = $userId;
        $this->username = $data->username;
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
            $data = $db->querySql("UPDATE dpProj_User SET username='$this->username', firstName='$this->firstName', lastName='$this->lastName', password='$this->password', email='$this->email', phoneNumber='$this->phoneNumber' WHERE userId='$this->userId'");
        }
    }

    public function getAllUsers() {
        $db = Database::getInstance();
        $data = $db->multiFetch("SELECT * FROM dpProj_User");
        return $data;
    }

    public function isValid() {
        return !empty($this->username) && !empty($this->userType) && !empty($this->firstName) && !empty($this->lastName) && !empty($this->password) && !empty($this->email) && !empty($this->phoneNumber);
    }
}



