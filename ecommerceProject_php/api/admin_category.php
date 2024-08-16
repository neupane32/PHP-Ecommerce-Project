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


/*
Make sure:
-In headers keep key as authorization and value as admin's token

GET(get all the list of categories as per mentioned categories by admin)
URL: http://localhost/project_php/api/admin_category.php

Body:
{
    "category": "Furniture"
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
    },
    {
        "product_id": 5,
        "product_name": "Gaming mouse skkjsjkdskj",
        "description": "Mechanical gaming keyboard with customizable RGB backlighting mouse.",
        "category": "Furniture",
        "price": "59.99",
        "user_id": 7
    }
]

DELETE(deletes all the category of the electroncs of admin)
URL: http://localhost/project_php/api/admin_category.php

Body:
{
    "category": "Electronics"
}

*/

?>