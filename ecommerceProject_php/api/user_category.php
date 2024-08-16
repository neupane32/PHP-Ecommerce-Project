<?php
require_once '../config/database.php';
require_once '../controllers/ProductController.php';
require_once '../controllers/UserController.php';

header("Content-Type: application/json; charset=UTF-8");

// Database object and connection
$database = new Database();
$db = $database->getConnection();

// Product and User objects
$productController = new ProductController($db);
$userController = new UserController($db);

$requestMethod = $_SERVER["REQUEST_METHOD"];
$headers = apache_request_headers();
$token = isset($headers['Authorization']) ? $headers['Authorization'] : ''; // Extract token if present otherwise null

$user = $userController->getUserByToken($token);

if ($user) {
    $data = json_decode(file_get_contents("php://input"), true);
    $category = $data['category'] ?? null; // Use null coalescing operator for simplicity

    if ($category) {
        // Fetch products by category for the user
        $products = $productController->getProductsByCategory($category, $user['user_id']);
        if (!empty($products)) {
            echo json_encode($products);
        } else {
            echo json_encode(["message" => "No products found in this category"]);
        }
    } else {
        // If category is not provided, fetch all products
        $products = $productController->getAllProducts($user['user_id']);
        echo json_encode($products);
    }
} else {
    echo json_encode(["message" => "Invalid or missing token"]);
}

?>
