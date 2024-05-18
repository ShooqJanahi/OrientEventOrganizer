<?php

class Database {
    public static $instance = null;
    public $dblink = null;

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    function __construct() {
        if (is_null($this->dblink)) {
            $this->connect();
        }
    }

    function connect() {
        $this->dblink = mysqli_connect('localhost', 'u202203500', 'u202203500', 'db202203500') or die('CAN NOT CONNECT');
    }

    function __destruct() {
        if (!is_null($this->dblink)) {
            $this->close($this->dblink);
        }
    }

    function close() {
        mysqli_close($this->dblink);
    }

    function querySQL($sql) {
        if ($sql != null || $sql != '') {
            $sql = $this->mkSafe($sql);
            mysqli_query($this->dblink, $sql);
        }
    }

    function singleFetch($sql, $params = []) {
        $fet = null;
        if ($stmt = mysqli_prepare($this->dblink, $sql)) {
            if (!empty($params)) {
                $types = str_repeat('s', count($params)); // assuming all parameters are strings
                mysqli_stmt_bind_param($stmt, $types, ...$params);
            }
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $fet = mysqli_fetch_object($result);
            mysqli_stmt_close($stmt);
        }
        return $fet;
    }

    function multiFetch($sql, $params = []) {
        $result = [];
        $counter = 0;
        if ($stmt = mysqli_prepare($this->dblink, $sql)) {
            if (!empty($params)) {
                $types = str_repeat('s', count($params)); // assuming all parameters are strings
                mysqli_stmt_bind_param($stmt, $types, ...$params);
            }
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            while ($fet = mysqli_fetch_object($res)) {
                $result[$counter] = $fet;
                $counter++;
            }
            mysqli_stmt_close($stmt);
        }
        return $result;
    }

    function mkSafe($string) {
        return $string;
    }

    function getRows($sql, $params = []) {
        $rows = 0;
        if ($stmt = mysqli_prepare($this->dblink, $sql)) {
            if (!empty($params)) {
                $types = str_repeat('s', count($params)); // assuming all parameters are strings
                mysqli_stmt_bind_param($stmt, $types, ...$params);
            }
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $rows = mysqli_num_rows($result);
            mysqli_stmt_close($stmt);
        }
        return $rows;
    }
}
