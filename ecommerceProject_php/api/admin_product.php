<?php
require_once '../config/database.php';
require_once '../controllers/AdminController.php';

header("Content-Type: application/json");

$database = new Database();
$dbConnection = $database->getConnection();
$adminController = new AdminController($dbConnection);

$requestMethod = $_SERVER["REQUEST_METHOD"];
$headers = apache_request_headers(); // Get request headers
$adminToken = isset($headers['Authorization']) ? $headers['Authorization'] : '';
$data = json_decode(file_get_contents("php://input"));

if ($requestMethod === 'GET') {
    $productId = isset($data->product_id) ? intval($data->product_id) : null;

    // Use the AdminController to get products with token validation
    $response = $adminController->getProducts($adminToken, $productId);
    echo json_encode($response);
} else {
    echo json_encode([
        'message' => 'Invalid request method'
    ]);
}
?>

