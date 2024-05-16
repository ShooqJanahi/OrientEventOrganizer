<?php
class Users {
    private $userId;
    private $firstName;
    private $lastName;
    private $username;
    private $userType;
    private $password;
    private $email;
    private $phoneNumber;

    public function __construct() {
        $this->userId = null;
        $this->firstName = null;
        $this->lastName = null;
        $this->username = null;
        $this->userType = null;
        $this->password = null;
        $this->email = null;
        $this->phoneNumber = null;
    }

    // Getters and Setters
    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getUserType() {
        return $this->userType;
    }

    public function setUserType($userType) {
        $this->userType = $userType;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPhoneNumber() {
        return $this->phoneNumber;
    }

    public function setPhoneNumber($phoneNumber) {
        $this->phoneNumber = $phoneNumber;
    }

    // Database methods
    public function registerUser() {
        if ($this->isValid()) {
            try {
                $db = Database::getInstance();
                $data = $db->querySql("INSERT INTO dpProj_User (userType, firstName, lastName, username, password, email, phoneNumber) VALUES ('$this->userType', '$this->firstName', '$this->lastName', '$this->username', '$this->password', '$this->email', '$this->phoneNumber')");
                return true;
            } catch (Exception $e) {
                echo 'Exception: ' . $e;
                return false;
            }
        } else {
            return false;
        }
    }

    public function initWithUsername() {
        $db = Database::getInstance();
        $data = $db->singleFetch("SELECT * FROM dpProj_User WHERE username = '$this->username'");
        if ($data) {
            $this->initWith($data->userId, $data->userType, $data->firstName, $data->lastName, $data->username, $data->password, $data->email, $data->phoneNumber);
            return true;
        } else {
            return false;
        }
    }

    private function initWith($userId, $userType, $firstName, $lastName, $username, $password, $email, $phoneNumber) {
        $this->userId = $userId;
        $this->userType = $userType;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
    }

    public function updateDB() {
        if ($this->isValid()) {
            $db = Database::getInstance();
            $data = $db->querySql("UPDATE dpProj_User SET userType='$this->userType', firstName='$this->firstName', lastName='$this->lastName', username='$this->username', password='$this->password', email='$this->email', phoneNumber='$this->phoneNumber' WHERE userId='$this->userId'");
        }
    }

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

    public function getAllUsers() {
        $db = Database::getInstance();
        $data = $db->multiFetch("SELECT * FROM dpProj_User");
        return $data;
    }

    public function isValid() {
        return !empty($this->userType) && !empty($this->firstName) && !empty($this->lastName) && !empty($this->username) && !empty($this->password) && !empty($this->email) && !empty($this->phoneNumber);
    }
}
?>
