<?php
class User {
    private $conn;
    private $table_name = "users";

    public $user_id;
    public $username;
    public $password;
    public $email;
    public $token;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createUser() {
        // Encode the password using base64
        $this->password = base64_encode($this->password);

        $query = "INSERT INTO " . $this->table_name . " SET username=?, password=?, email=?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("sss", $this->username, $this->password, $this->email);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function checkCredentials() {
        // Convert the password to base64
        $encoded_password = base64_encode($this->password);

        $query = "SELECT * FROM " . $this->table_name . " WHERE username = ? AND password = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ss", $this->username, $encoded_password);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $this->user_id = $user['user_id'];
            return true;
        }

        return false;
    }

    public function generateToken() {
        // Generate a random token
        $this->token = bin2hex(random_bytes(16));
        $query = "UPDATE " . $this->table_name . " SET token=? WHERE user_id=?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("si", $this->token, $this->user_id);

        if ($stmt->execute()) {
            return $this->token;
        }

        return false;
    }

    public function getUserByToken() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE token = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("s", $this->token);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return false;
    }
}
