<?php
require_once '../config/database.php';
require_once '../controllers/AdminController.php';

header("Content-Type: application/json");

$database = new Database();
$dbConnection = $database->getConnection();
$adminController = new AdminController($dbConnection);
$requestMethod = $_SERVER["REQUEST_METHOD"];

$data = json_decode(file_get_contents("php://input"));
$headers = apache_request_headers(); // Get request headers
$adminToken = isset($headers['Authorization']) ? $headers['Authorization'] : '';

switch ($requestMethod) {
    case 'POST':
        if (isset($data->username) && isset($data->password) && $adminToken) {
            $response = $adminController->createUser($data->username, $data->password, $adminToken);
            echo json_encode($response);
        } else {
            echo json_encode([
                'message' => 'Username, password, and admin token required'
            ]);
        }
        break;

    case 'DELETE':
        if (isset($data->user_id) && $adminToken) {
            $response = $adminController->deleteUser(intval($data->user_id), $adminToken);
            echo json_encode([
                "message" => $response ? "User deleted successfully" : "Failed to delete user"
            ]);
        } else {
            echo json_encode([
                'message' => 'User ID and admin token required'
            ]);
        }
        break;

    default:
        echo json_encode([
            'message' => 'Invalid request method'
        ]);
        break;
}

/*
Make sure:
-To keep authorization along with value as admin token. To verify ur admin

Admin can create and delete the user
*create the user
POST
URL:http://localhost/project_php/api/create_user.php

Body:
{
    "username":"prinsajoshi4",
    "password":"prinsajoshi4"    
}

Response:
{
    "message": "User created successfully"
}

*delete the user
DELETE
URL: http://localhost/project_php/api/create_user.php

Body:
{
    "user_id":5
}

Response:
{
    "message": "User deleted successfully"
}

*/

?>


