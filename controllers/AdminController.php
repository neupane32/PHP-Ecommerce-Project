<?php

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
            $username = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];
            $admin = $this->admin->authenticate($username, $password);

            if ($admin) {
                echo json_encode(["message" => "Login successful", "admin" => $admin]);
            } else {
                header('HTTP/1.0 401 Unauthorized');
                echo json_encode(["message" => "Login failed"]);
            }
        }
    }
}
