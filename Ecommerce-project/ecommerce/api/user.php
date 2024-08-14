<?php
require_once '../config/database.php';
require_once '../controllers/UserController.php';
require_once '../controllers/AdminController.php';

header("Content-Type: application/json; charset=UTF-8");

$database = new Database();
$db = $database->getConnection();

$userController = new UserController($db);
$adminController = new AdminController($db);

$request_method = $_SERVER["REQUEST_METHOD"];
$data = json_decode(file_get_contents("php://input"));

if ($request_method === 'POST') {
    if (!empty($data->action)) {
        if ($data->action === 'create') {
            if (!empty($_GET['admin_username']) && !empty($_GET['admin_password'])) {
                $admin_username = $_GET['admin_username'];
                $admin_password = $_GET['admin_password'];

                $adminResponse = $adminController->authenticate($admin_username, $admin_password);

                if ($adminResponse === "Correct password") {
                    if (!empty($data->username) && !empty($data->password) && !empty($data->email)) {
                        $response = $userController->createUser($data->username, $data->password, $data->email);
                        echo json_encode(["message" => $response]);
                    } else {
                        echo json_encode(["message" => "Incomplete data"]);
                    }
                } else {
                    echo json_encode(["message" => "You're not an admin"]);
                }
            } else {
                echo json_encode(["message" => "Admin credentials required"]);
            }
        } elseif ($data->action === 'login') {
            if (!empty($data->username) && !empty($data->password)) {
                $response = $userController->authenticate($data->username, $data->password);
                echo json_encode(["message" => $response]);
            } else {
                echo json_encode(["message" => "Incomplete data"]);
            }
        }
    } else {
        echo json_encode(["message" => "No action specified"]);
    }
} else {
    echo json_encode(["message" => "Invalid request method"]);
}
