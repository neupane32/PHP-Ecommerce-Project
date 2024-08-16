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


/*

Make sure:
-In headers keep key as authorization and value as admin's token

*Get by id
GET(get product as per mentioned product id by admin)
URL: http://localhost/project_php/api/admin_product.php

Body:
{
    "product_id": 4
}

Response:
[
    {
        "product_id": 4,
        "product_name": "Gaming mouse",
        "description": "Mechanical gaming keyboard with customizable RGB backlighting mouse.",
        "category": "Furniture",
        "price": "59.99",
        "user_id": 7
    }
]


*Get all
GET(get all proucts by admin)

URL: http://localhost/project_php/api/admin_product.php

Body: 
[
    {
        "product_id": 4,
        "product_name": "Gaming mouse",
        "description": "Mechanical gaming keyboard with customizable RGB backlighting mouse.",
        "category": "Furniture",
        "price": "59.99",
        "user_id": 7
    },
    {
        "product_id": 5,
        "product_name": "Gaming mouse skkjsjkdskj",
        "description": "Mechanical gaming keyboard with customizable RGB backlighting mouse.",
        "category": "Furniture",
        "price": "59.99",
        "user_id": 7
    },
    {
        "product_id": 6,
        "product_name": "Gaming mouse1",
        "description": "Mechanical gaming keyboard with customizable RGB backlighting mouse.",
        "category": "Electronics",
        "price": "59.99",
        "user_id": 7
    },
    {
        "product_id": 7,
        "product_name": "Gaming mouse2",
        "description": "Mechanical gaming keyboard with customizable RGB backlighting mouse.",
        "category": "Electronics",
        "price": "59.99",
        "user_id": 7
    },
    {
        "product_id": 8,
        "product_name": "Gaming mouse3",
        "description": "Mechanical gaming keyboard with customizable RGB backlighting mouse.",
        "category": "Electronics",
        "price": "59.99",
        "user_id": 7
    },
    {
        "product_id": 9,
        "product_name": "Gaming mouse4",
        "description": "Mechanical gaming keyboard with customizable RGB backlighting mouse.",
        "category": "Electronics",
        "price": "59.99",
        "user_id": 7
    },
    {
        "product_id": 10,
        "product_name": "Desk Lamp",
        "description": "Adjustable desk lamp with LED lighting and touch control.",
        "category": "Furniture",
        "price": "39.99",
        "user_id": 8
    },
    {
        "product_id": 11,
        "product_name": "Gaming mouse6",
        "description": "Mechanical gaming keyboard with customizable RGB backlighting mouse.",
        "category": "Electronics",
        "price": "59.99",
        "user_id": 8
    },
    {
        "product_id": 12,
        "product_name": "Gaming mouse7",
        "description": "Mechanical gaming keyboard with customizable RGB backlighting mouse.",
        "category": "Electronics",
        "price": "59.99",
        "user_id": 8
    }
]

*/
?>

