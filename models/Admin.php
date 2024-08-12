<?php
class Admin {
    private $conn;
    private $table_name = "admins";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function insertAdmin($username, $password) {
        $query = "INSERT INTO " . $this->table_name . " (username, password) VALUES (:username, :password)";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

