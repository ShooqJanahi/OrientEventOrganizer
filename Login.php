<?php
//hello world
//Testing - Hawraa
class Login extends Users {
    public $ok;
    public $salt;
    public $domain;

    function __construct() {
        parent::__construct();
        $this->ok = false;
        $this->salt = 'ENCRYPT';
        $this->domain = '';

        if (!$this->checkSession())
            $this->checkCookie();
    }

    function checkSession() {
        if (!empty($_SESSION['userId'])) {
            $this->ok = true;
            return true;
        } else {
            return false;
        }
    }

    function checkCookie() {
        if (!empty($_COOKIE['userId'])) {
            $this->ok = true;
            return $this->check($_COOKIE['userId']);
        } else {
            return false;
        }
    }

    function check($userId) {
        $this->initWithUid($userId);
        if ($this->getUserId() != null && $this->getUserId() == $userId) {
            $this->ok = true;
            $_SESSION['userId'] = $this->getUserId();
            $_SESSION['userType'] = $this->getUserType();
            $_SESSION['firstName'] = $this->getFirstName();
            $_SESSION['lastName'] = $this->getLastName();
            setcookie('userId', $_SESSION['userId'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
            setcookie('userType', $_SESSION['userType'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
            setcookie('firstName', $_SESSION['firstName'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
            setcookie('lastName', $_SESSION['lastName'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
            return true;
        } else {
            $error[] = 'Wrong Username';
            return false;
        }
    }

    function login($username, $password) {
        try {
            $this->checkUser($username, $password);
            if ($this->getUserId() != null) {
                $this->ok = true;
                $_SESSION['userId'] = $this->getUserId();
                $_SESSION['userType'] = $this->getUserType();
                $_SESSION['firstName'] = $this->getFirstName();
                $_SESSION['lastName'] = $this->getLastName();
                setcookie('userId', $_SESSION['userId'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
                setcookie('userType', $_SESSION['userType'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
                setcookie('firstName', $_SESSION['firstName'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
                setcookie('lastName', $_SESSION['lastName'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
                return true;
            } else {
                $error[] = 'Wrong Username OR password';
                return false;
            }
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            return false;
        }
    }

    function logout() {
        $this->ok = false;
        $_SESSION['userId'] = '';
        $_SESSION['userType'] = '';
        $_SESSION['firstName'] = '';
        $_SESSION['lastName'] = '';
        setcookie('userId', '', time() - 3600, '/', $this->domain);
        setcookie('userType', '', time() - 3600, '/', $this->domain);
        setcookie('firstName', '', time() - 3600, '/', $this->domain);
        setcookie('lastName', '', time() - 3600, '/', $this->domain);
        session_destroy();
    }
}
