<?php
require_once '../config/database.php';
require_once '../controllers/ProductController.php';
require_once '../controllers/UserController.php';

header("Content-Type: application/json; charset=UTF-8");

$database = new Database();
$db = $database->getConnection();

$productController = new ProductController($db);
$userController = new UserController($db);

$request_method = $_SERVER["REQUEST_METHOD"];
$headers = apache_request_headers();
$token = isset($headers['Authorization']) ? $headers['Authorization'] : '';

$user = $userController->getUserByToken($token);

if ($user) {
    switch ($request_method) {
        case 'GET':
            if (!empty($_GET['product_id'])) {
                $product_id = intval($_GET['product_id']);
                $product = $productController->getProductById($product_id, $user['user_id']);
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
            if (!empty($data['product_name']) && !empty($data['description']) && isset($data['price'])) {
                $response = $productController->createProduct($data['product_name'], $data['description'], $data['price'], $user['user_id']);
                echo json_encode(["message" => $response]);
            } else {
                echo json_encode(["message" => "Incomplete data"]);
            }
            break;
        case 'PUT':
            parse_str(file_get_contents("php://input"), $put_vars);
            if (!empty($put_vars['product_id']) && !empty($put_vars['product_name']) && !empty($put_vars['description']) && isset($put_vars['price'])) {
                $response = $productController->updateProduct(
                    intval($put_vars['product_id']),
                    $put_vars['product_name'],
                    $put_vars['description'],
                    floatval($put_vars['price']),
                    $user['user_id']
                );
                echo json_encode(["message" => $response ? "Product updated successfully" : "Failed to update product"]);
            } else {
                echo json_encode(["message" => "Incomplete data"]);
            }
            break;
        case 'DELETE':
            parse_str(file_get_contents("php://input"), $delete_vars);
            if (!empty($delete_vars['product_id'])) {
                $response = $productController->deleteProduct(intval($delete_vars['product_id']), $user['user_id']);
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
