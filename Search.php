<?php
require_once 'Database.php';

class Search {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function searchHallsByAvailability($date, $duration, $audience, $time, $search) {
        $queryParams = [];
        $sql = "SELECT h.*, t.timingSlotStart, t.timingSlotEnd FROM dbProj_Hall h
                LEFT JOIN dpProj_HallsTimingSlots t ON h.hallId = t.hallId";
        $whereClauses = ["1=1"];

        // Check for audience capacity
        if (!empty($audience)) {
            $whereClauses[] = "h.capacity >= ?";
            $queryParams[] = $audience;
        }

        // Check for search term in hall name or description
        if (!empty($search)) {
            $whereClauses[] = "(h.hallName LIKE ? OR h.description LIKE ?)";
            $searchTerm = '%' . $search . '%';
            $queryParams[] = $searchTerm;
            $queryParams[] = $searchTerm;
        }

        // Check for date and duration
        if (!empty($date) && !empty($duration)) {
            $endDate = date('Y-m-d', strtotime($date . ' + ' . $duration . ' days'));
            $whereClauses[] = "h.hallId NOT IN (
                SELECT r.hallId FROM dbProj_Reservation r
                WHERE r.startDate <= ? AND r.endDate >= ?
            )";
            $queryParams[] = $endDate;
            $queryParams[] = $date;
        }

        // Check for time slot availability
        if (!empty($time)) {
            $whereClauses[] = "t.timingSlotStart <= ? AND t.timingSlotEnd >= ?";
            $queryParams[] = $time;
            $queryParams[] = $time;
        }

        // Finalize the query with WHERE clauses
        $sql .= " WHERE " . join(" AND ", $whereClauses);

        // Prepare and execute the SQL statement
        $stmt = $this->db->dblink->prepare($sql);
        if ($stmt) {
            $types = str_repeat('s', count($queryParams));
            $stmt->bind_param($types, ...$queryParams);
            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch results
            $halls = [];
            while ($row = $result->fetch_assoc()) {
                $halls[] = $row;
            }
            $stmt->close();

            return $halls;
        } else {
            throw new Exception("Error preparing statement: " . $this->db->dblink->error);
        }
    }

    public function searchHallsByText($search) {
        $queryParams = [];
        $sql = "SELECT h.*, t.timingSlotStart, t.timingSlotEnd FROM dbProj_Hall h
                LEFT JOIN dpProj_HallsTimingSlots t ON h.hallId = t.hallId
                WHERE MATCH(h.hallName, h.description) AGAINST (?)";
        $queryParams[] = $search;

        // Prepare and execute the SQL statement
        $stmt = $this->db->dblink->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('s', ...$queryParams);
            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch results
            $halls = [];
            while ($row = $result->fetch_assoc()) {
                $halls[] = $row;
            }
            $stmt->close();

            return $halls;
        } else {
            throw new Exception("Error preparing statement: " . $this->db->dblink->error);
        }
    }
}
?>
