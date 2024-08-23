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
$data = json_decode(file_get_contents("php://input"));


if ($user) {
    switch ($requestMethod) {
        case 'GET':
            if (isset($data->product_id)) {
                // Convert the value received from GET request parameter named into an integer
                $productId = intval($data->product_id);
                $product = $productController->getProductById($productId, $user['user_id']);
                if ($product) {
                    echo json_encode($product);
                } else {
                    echo json_encode(["message" => "Product not found"]);
                }
            } else {
                $products = $productController->getAllProducts($user['user_id']);
                echo json_encode($products);
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            if (!empty($data['product_name']) && !empty($data['description']) && !empty($data['category']) && isset($data['price'])) {
                $response = $productController->createProduct(
                    $data['product_name'],
                    $data['description'],
                    $data['category'],
                    $data['price'],
                    $user['user_id']
                );
                echo json_encode(["message" => $response]);
            } else {
                echo json_encode(["message" => "Incomplete data"]);
            }
            break;

        case 'PUT':
            $data = json_decode(file_get_contents("php://input"), true);
            if (!empty($data['product_id']) && !empty($data['product_name']) && !empty($data['description']) && !empty($data['category']) && isset($data['price'])) {
                $response = $productController->updateProduct(
                    intval($data['product_id']),
                    $data['product_name'],
                    $data['description'],
                    $data['category'],
                    floatval($data['price']),
                    $user['user_id']
                );
                echo json_encode(["message" => $response ? "Product updated successfully" : "Failed to update product"]);
            } else {
                echo json_encode(["message" => "Incomplete data"]);
            }
            break;

        case 'DELETE':
            $data = json_decode(file_get_contents("php://input"), true);
            if (!empty($data['product_id'])) {
                $response = $productController->deleteProduct(intval($data['product_id']), $user['user_id']);
                echo json_encode(["message" => $response ? "Product deleted successfully" : "Failed to delete product"]);
            } else {
                echo json_encode(["message" => "Product ID required"]);
            }
            break;

        default:
            echo json_encode(["message" => "Invalid request method"]);
            break;
    }
} else {
    echo json_encode(["message" => "Invalid or missing token"]);
}
?>
