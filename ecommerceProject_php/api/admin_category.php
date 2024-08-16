<?php
require_once '../config/database.php';
require_once '../controllers/AdminController.php';

header("Content-Type: application/json");

$database = new Database();
$dbConnection = $database->getConnection();
$adminController = new AdminController($dbConnection);

$requestMethod = $_SERVER["REQUEST_METHOD"];
$headers = apache_request_headers();
$adminToken = isset($headers['Authorization']) ? $headers['Authorization'] : '';
$data = json_decode(file_get_contents("php://input"));

switch ($requestMethod) {
    case 'GET':
        $category = $data->category ?? null;
        $response = $adminController->getCategory($adminToken, $category);
        echo json_encode($response);
        break;

    case 'DELETE':
        if (isset($data->category)) {
            $category = $data->category;
            $response = $adminController->deleteCategory($adminToken, $category);
            echo json_encode([
                "message" => $response ? "Category deleted successfully" : "Failed to delete category"
            ]);
        } else {
            echo json_encode([
                'message' => 'Category not found'
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