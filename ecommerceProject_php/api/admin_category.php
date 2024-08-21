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

function handleGet($adminController, $adminToken, $data) {
    $category = $data->category ?? null;
    $response = $adminController->getCategory($adminToken, $category);
    return json_encode($response);
}

function handleDelete($adminController, $adminToken, $data) {
    if (isset($data->category)) {
        $category = $data->category;
        $response = $adminController->deleteCategory($adminToken, $category);
        return json_encode([
            "message" => $response ? "Category deleted successfully" : "Failed to delete category"
        ]);
    } else {
        return json_encode([
            'message' => 'Category not found'
        ]);
    }
}

switch ($requestMethod) {
    case 'GET':
        echo handleGet($adminController, $adminToken, $data);
        break;

    case 'DELETE':
        echo handleDelete($adminController, $adminToken, $data);
        break;

    default:
        echo json_encode([
            'message' => 'Invalid request method'
        ]);
        break;
}
?>
