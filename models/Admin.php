<?php
class Admin {
    private $conn;
    private $table_name = "admins";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function authenticate($username, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = ? AND password = ?";
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            die("Prepare failed: (" . $this->conn->errno . ") " . $this->conn->error);
        }

        $stmt->bind_param("ss", $username, $password);

        if (!$stmt->execute()) {
            die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }

        $result = $stmt->get_result();

        if (!$result) {
            die("Get result failed: (" . $stmt->errno . ") " . $stmt->error);
        }

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }
}
