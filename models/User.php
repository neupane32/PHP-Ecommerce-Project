<?php
class User {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($username, $password, $role = 'user') {
        $query = "INSERT INTO " . $this->table_name . " (username, password, role) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $encodedPassword = base64_encode($password);
        $stmt->bind_param("sss", $username, $encodedPassword, $role);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function authenticate($username, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = ? AND password = ?";
        $stmt = $this->conn->prepare($query);
        $encodedPassword = base64_encode($password);
        $stmt->bind_param("ss", $username, $encodedPassword);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function getUserRole($userId) {
        $query = "SELECT role FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['role'];
        } else {
            return null;
        }
    }
}
