<?php
require_once '../config/database.php';
require_once '../controllers/UserController.php';

/*
Specifies the content type and character encoding as UTF-8
*/
header("Content-Type: application/json; charset=UTF-8");

// Create a database object and establish a connection
$database = new Database();
$db = $database->getConnection();

// Create a UserController object
$userController = new UserController($db);

$requestMethod = $_SERVER["REQUEST_METHOD"];

// Decode the JSON input data
$data = json_decode(file_get_contents("php://input"));

// Handle POST requests for authentication
if ($requestMethod === 'POST') {        
    if (!empty($data->username) && !empty($data->password)) {
        // Authenticate the user
        $response = $userController->authenticate($data->username, $data->password);
        echo json_encode(["message" => $response]);
    } else {
        echo json_encode(["message" => "Incomplete data"]);
    }
} else {
    echo json_encode(["message" => "Invalid request method"]);
}
/*
User can login
POST
URL:http://localhost/project_php/api/users.php

Body: 
{
    "username":"prinsajoshi4",
    "password":"prinsajoshi4"
}

Response:
{
    "message": {
        "message": "Correct password",
        "token": "8ef88b54156fb5742b8148a45881719b"
    }
}

*/

?>
