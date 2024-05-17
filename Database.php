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
        $this->dblink = mysqli_connect('localhost', 'u202101977', 'u202101977', 'db202101977');
        if (!$this->dblink) {
            die('CAN NOT CONNECT: ' . mysqli_connect_error());
        } else {
            echo 'Connected successfully to the database.<br>'; // Debugging line
        }
    }

    function __destruct() {
        if (!is_null($this->dblink)) {
            $this->close();
        }
    }

    function close() {
        mysqli_close($this->dblink);
    }

    function querySQL($sql) {
        if ($sql != null && $sql != '') {
            $result = mysqli_query($this->dblink, $sql);
            if (!$result) {
                die('Query error: ' . mysqli_error($this->dblink)); // Debugging line
            }
            return $result;
        }
        return null;
    }

    function singleFetch($sql) {
        $fet = null;
        if ($sql != null && $sql != '') {
            $res = mysqli_query($this->dblink, $sql);
            if (!$res) {
                die('Error in query: ' . mysqli_error($this->dblink)); // Debugging line
            }
            $fet = mysqli_fetch_object($res);
        }
        return $fet;
    }

    function multiFetch($sql) {
        $result = [];
        if ($sql != null && $sql != '') {
            $res = mysqli_query($this->dblink, $sql);
            if (!$res) {
                die('Error in query: ' . mysqli_error($this->dblink)); // Debugging line
            }
            while ($fet = mysqli_fetch_object($res)) {
                $result[] = $fet;
            }
            echo 'Fetched ' . count($result) . ' rows.<br>'; // Debugging line
        }
        return $result;
    }

    function mkSafe($string) {
        return mysqli_real_escape_string($this->dblink, $string);
    }

    function getRows($sql) {
        $rows = 0;
        if ($sql != null && $sql != '') {
            $result = mysqli_query($this->dblink, $sql);
            $rows = mysqli_num_rows($result);
        }
        return $rows;
    }

    function prepare($query) {
        return mysqli_prepare($this->dblink, $query);
    }
}
?>
