<?php

require_once './models/Admin.php';  // Add this line to include the Admin model
require_once './controllers/Controller.php'; // If 'Controller.php' is a base class, include it

class AdminController extends Controller {
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
            // Fetch username and password from the request
            $username = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];

            // Base64 encode the username and password
            $encodedUsername = base64_encode($username);
            $encodedPassword = base64_encode($password);

            // Authenticate the user using the encoded values
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
