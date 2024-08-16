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

// Fetch all HTTP request headers sent by the client to the server
// Returns an associative array where keys are header names and values are the corresponding header values
$headers = apache_request_headers();
$token = isset($headers['Authorization']) ? $headers['Authorization'] : ''; // Extract token if present otherwise null

$user = $userController->getUserByToken($token);
$data = json_decode(file_get_contents("php://input"));


// Set header
// Key: authorization
// Value: 057f2a000a97fa31f05942ad51a65ea8 (token)

if ($user) {
    switch ($requestMethod) {
        /*
        URL: (get by all)
        http://localhost/zproject1/api/product.php

        Response:
        [{"product_id":2,"product_name":"carroteeeeeee","description":"carrot is vege","price":"199.00","user_id":5},
        {"product_id":4,"product_name":"bananaaa","description":"banana is a fruit","price":"10.00","user_id":5},
        {"product_id":5,"product_name":"carrot","description":"carrot is a vege","price":"10.00","user_id":5},
        {"product_id":6,"product_name":"radish","description":"radish is a vege","price":"10.00","user_id":5}]

        URL: (get by id)
        http://localhost/zproject1/api/product.php?product_id=5

        Response:
        {"product_id":5,"product_name":"carrot","description":"carrot is a vege","price":"10.00","user_id":5}
        */
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

        /*
        URL:
        http://localhost/zproject1/api/product.php

        Body:
        {
            "product_name": "radish",
            "description": "radish is a vege",
            "price": 10,
            "category": "Vegetables"
        }

        Response:
        {"message":"Product created successfully"}
        */
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

        /*
        Update the product
        URL:
        http://localhost/zproject1/api/product.php

        Body:
        {
            "product_id": 6,
            "product_name": "radish",
            "description": "radish is vege",
            "price": 199,
            "category": "Vegetables"
        }

        Response:
        {
            "message": "Product updated successfully"
        }
        */
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

        /*
        URL:
        http://localhost/zproject1/api/product.php

        Body:
        {
            "product_id": 3
        }

        Response:
        {
            "message": "Product deleted successfully"
        }
        */
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
