<?php
require_once '../config/database.php';
require_once '../controllers/AdminController.php';

header("Content-Type: application/json");

$database = new Database();
$dbConnection = $database->getConnection();
$controller = new AdminController($dbConnection);

$data = json_decode(file_get_contents("php://input"));

if (isset($data->username) && isset($data->password)) {
    $response = $controller->authenticate($data->username, $data->password);
    echo json_encode($response);
} else {
    echo json_encode([
        'message' => 'Username and password required'
    ]);
}
?>
