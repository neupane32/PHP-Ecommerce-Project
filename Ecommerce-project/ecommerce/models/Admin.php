<?php
class Admin {
    private $conn;
    private $table_name = "admins_table";

    public $username;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function checkCredentials() {
        // Convert the password to base64
        $encoded_password = base64_encode($this->password);

        $query = "SELECT * FROM " . $this->table_name . " WHERE username = ? AND password = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ss", $this->username, $encoded_password);

        $stmt->execute();
        $result = $stmt->get_result();

        // If credentials are correct, fetch the user data
        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); // Return user data as an associative array
        }

        return false;
    }
}
