<?php
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/UserController.php';

class AdminController {
    private $admin;
    private $userController;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->admin = new Admin($db);
        $this->userController = new UserController($db); // Pass db to UserController
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
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['admin_id'] = $admin['id'];
                echo json_encode(["message" => "Login successful", "admin" => $admin]);
            } else {
                header('HTTP/1.0 401 Unauthorized');
                echo json_encode(["message" => "Login failed"]);
            }
        }
    }

    public function createUser() {
        $this->authorizeAdmin();

        // Get POST data
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['username']) && isset($data['password']) && isset($data['role'])) {
            $username = $data['username'];
            $password = $data['password'];
            $role = $data['role'];

            $result = $this->userController->createUser($username, $password, $role);

            if ($result) {
                echo json_encode(["message" => "User created successfully"]);
            } else {
                echo json_encode(["message" => "User creation failed"]);
            }
        } else {
            header('HTTP/1.0 400 Bad Request');
            echo json_encode(["message" => "Invalid input"]);
        }
    }

    private function authorizeAdmin() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['admin_id'])) {
            header('HTTP/1.0 403 Forbidden');
            echo json_encode(["message" => "You do not have permission to perform this action"]);
            exit;
        }
    }
}
