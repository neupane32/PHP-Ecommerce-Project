<?php
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/UserController.php'; // Include the UserController

class AdminController {
    private $admin;
    private $userController;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->admin = new Admin($db);
        $this->userController = new UserController(); // Initialize UserController
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

            $encodedPassword = base64_encode($password);

            $admin = $this->admin->authenticate($username, $encodedPassword);

            if ($admin) {
                // Start the session and store admin information
                session_start();
                $_SESSION['admin_id'] = $admin['id'];
                echo json_encode(["message" => "Login successful", "admin" => $admin]);

                // After login success, proceed to create a user
                $this->createUser(); // Call createUser immediately for testing
            } else {
                header('HTTP/1.0 401 Unauthorized');
                echo json_encode(["message" => "Login failed"]);
            }
        }
    }

    public function createUser() {
        $this->authorizeAdmin(); // Ensure the current user is an admin

        // Example data; in a real application, you would get this from a request
        $username = 'newuser'; // Replace with dynamic data in production
        $password = 'password123';
        $role = 'user'; // or 'admin' if creating another admin

        $result = $this->userController->createUser($username, $password, $role);

        if ($result) {
            echo json_encode(["message" => "User created successfully"]);
        } else {
            echo json_encode(["message" => "User creation failed"]);
        }
    }

    private function authorizeAdmin() {
        session_start();
        if (!isset($_SESSION['admin_id'])) {
            header('HTTP/1.0 403 Forbidden');
            echo json_encode(["message" => "You do not have permission to perform this action"]);
            exit;
        }
    }
}
