<?php
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../config/Database.php';

class AdminController {
    private $admin;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->admin = new Admin($db);
    }

    public function login() {
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="Admin Area"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Unauthorized';
            exit;
        } else {
            $username = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];

            // Debugging: Print the encoded values
            $encodedUsername = base64_encode($username);
            $encodedPassword = base64_encode($password);

            // For debugging purposes
            echo "Encoded Username: $encodedUsername<br>";
            echo "Encoded Password: $encodedPassword<br>";

            $admin = $this->admin->authenticate($encodedUsername, $encodedPassword);

            if ($admin) {
                echo json_encode(["message" => "Login successful", "admin" => $admin]);
            } else {
                header('HTTP/1.0 401 Unauthorized');
                echo json_encode(["message" => "Login failed"]);
            }
        }
    }
}
