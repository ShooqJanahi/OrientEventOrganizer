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
            $_SESSION['username'] = $this->getUsername();
            setcookie('userId', $_SESSION['userId'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
            setcookie('username', $_SESSION['username'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
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
                $_SESSION['username'] = $this->getUsername();
                setcookie('userId', $_SESSION['userId'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
                setcookie('username', $_SESSION['username'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
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
        $_SESSION['username'] = '';
        setcookie('userId', '', time() - 3600, '/', $this->domain);
        setcookie('username', '', time() - 3600, '/', $this->domain);
        session_destroy();
    }
}
