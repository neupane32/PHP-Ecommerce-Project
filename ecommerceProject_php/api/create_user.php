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
?>


