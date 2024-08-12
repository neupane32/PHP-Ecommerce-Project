<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/Database.php';

class UserController {
    private $user;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->user = new User($db);
    }

    public function createUser($username, $password, $role = 'user') {
        if ($this->user->create($username, $password, $role)) {
            echo json_encode(["message" => "User created successfully"]);
        } else {
            echo json_encode(["message" => "User creation failed"]);
        }
    }

    public function loginUser() {
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="User Area"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Unauthorized';
            exit;
        } else {
            $username = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];

            $user = $this->user->authenticate($username, $password);

            if ($user) {
                echo json_encode(["message" => "Login successful", "user" => $user]);
            } else {
                header('HTTP/1.0 401 Unauthorized');
                echo json_encode(["message" => "Login failed"]);
            }
        }
    }

    public function authorize($userId, $requiredRole) {
        $userRole = $this->user->getUserRole($userId);

        if ($userRole === $requiredRole) {
            return true;
        } else {
            header('HTTP/1.0 403 Forbidden');
            echo json_encode(["message" => "You do not have permission to perform this action"]);
            return false;
        }
    }
}
