<?php

class UserController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createUser($username, $password, $role) {
        try {
            // Check if the user already exists
            $query = "SELECT id FROM users WHERE username = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                return false; // User already exists
            }

            // Proceed to create a new user
            $query = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt->bind_param("sss", $username, $hashedPassword, $role);

            if ($stmt->execute()) {
                return true; // User created successfully
            } else {
                return false; // Failed to create user
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
