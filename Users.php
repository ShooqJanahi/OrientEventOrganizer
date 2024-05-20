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
                $sql = "INSERT INTO dbProj_User (userType, firstName, lastName, username, password, email, phoneNumber) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $params = [
                    $this->userType,
                    $this->firstName,
                    $this->lastName,
                    $this->username,
                    $this->password,
                    $this->email,
                    $this->phoneNumber
                ];
                $stmt = $db->querySQL($sql, $params);
                if ($stmt) {
                    $this->userId = $db->getLastInsertId();
                    error_log("User registered successfully. UserID: " . $this->userId);
                    return true;
                } else {
                    error_log("Error registering user: " . $db->error);
                    return false;
                }
            } catch (Exception $e) {
                error_log("Exception registering user: " . $e->getMessage());
                return false;
            }
        } else {
            error_log("Invalid data for registering user");
            return false;
        }
    }

    public function initWithUserId($userId) {
        $db = Database::getInstance();
        $sql = "SELECT * FROM dbProj_User WHERE userId = ?";
        $params = [$userId];
        $userData = $db->singleFetch($sql, $params);
        if ($userData) {
            $this->userId = $userData->userId;
            $this->firstName = $userData->firstName;
            $this->lastName = $userData->lastName;
            $this->username = $userData->username;
            $this->userType = $userData->userType;
            $this->password = $userData->password;
            $this->email = $userData->email;
            $this->phoneNumber = $userData->phoneNumber;
            return true;
        } else {
            error_log("User data not found for userId: " . $userId);
        }
        return false;
    }

    public function updateUserDB() {
        if ($this->isValid()) {
            $db = Database::getInstance();
            $sql = "UPDATE dbProj_User SET firstName = ?, lastName = ?, username = ?, email = ?, phoneNumber = ? WHERE userId = ?";
            $params = [
                $this->firstName,
                $this->lastName,
                $this->username,
                $this->email,
                $this->phoneNumber,
                $this->userId
            ];
            return $db->querySQL($sql, $params);
        }
        return false;
    }

    public function isValid() {
        return !empty($this->firstName) && !empty($this->lastName) && !empty($this->username) && !empty($this->email) && !empty($this->phoneNumber);
    }
}
?>
