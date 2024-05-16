
<?php
require_once 'User.php';

class Employee extends Users {
    private $employeeId;
    private $department;
    private $position;
    private $joinDate;

    
    public function __construct() {
        parent::__construct();
        $this->employeeId = null;
        $this->department = null;
        $this->position = null;
        $this->joinDate = null;
    }

    // Getters and Setters
    public function getEmployeeId() {
        return $this->employeeId;
    }

    public function setEmployeeId($employeeId) {
        $this->employeeId = $employeeId;
    }

    public function getDepartment() {
        return $this->department;
    }

    public function setDepartment($department) {
        $this->department = $department;
    }

    public function getPosition() {
        return $this->position;
    }

    public function setPosition($position) {
        $this->position = $position;
    }

    public function getJoinDate() {
        return $this->joinDate;
    }

    public function setJoinDate($joinDate) {
        $this->joinDate = $joinDate;
    }
    
    
    
    public function deleteEmployee() {
        try {
            $db = Database::getInstance();
            $data = $db->querySql("DELETE FROM dbProj_Employee WHERE employeeId='$this->employeeId'");
            return true;
        } catch (Exception $e) {
            echo 'Exception: ' . $e;
            return false;
        }
    }

    public function initWithEmployeeId($employeeId) {
        $db = Database::getInstance();
        $data = $db->singleFetch("SELECT * FROM dbProj_Employee WHERE employeeId='$employeeId'");
        $this->initWith($data->userId, $data->department, $data->position, $data->joinDate);
    }

    public function registerEmployee() {
        if ($this->isValid()) {
            try {
                $db = Database::getInstance();
                $data = $db->querySql("INSERT INTO dbProj_Employee (userId, department, position, joinDate) VALUES ('$this->userId', '$this->department', '$this->position', '$this->joinDate')");
                return true;
            } catch (Exception $e) {
                echo 'Exception: ' . $e;
                return false;
            }
        } else {
            return false;
        }
    }

    private function initWith($userId, $department, $position, $joinDate) {
        $this->userId = $userId;
        $this->department = $department;
        $this->position = $position;
        $this->joinDate = $joinDate;
    }

    public function updateDB() {
        if ($this->isValid()) {
            $db = Database::getInstance();
            $data = $db->querySql("UPDATE dbProj_Employee SET department='$this->department', position='$this->position', joinDate='$this->joinDate' WHERE employeeId='$this->employeeId'");
        }
    }

    public function getAllEmployees() {
        $db = Database::getInstance();
        $data = $db->multiFetch("SELECT * FROM dbProj_Employee");
        return $data;
    }

    public function isValid() {
        return !empty($this->userId) && !empty($this->department) && !empty($this->position) && !empty($this->joinDate);
    }
}
?>
