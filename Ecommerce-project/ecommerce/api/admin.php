<?php
require_once '../config/database.php';
require_once '../controllers/AdminController.php';

header("Content-Type: application/json; charset=UTF-8");

$database = new Database();
$db = $database->getConnection();

$adminController = new AdminController($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->username) && !empty($data->password)) {
    $response = $adminController->authenticate($data->username, $data->password);
    echo json_encode(["message" => $response]);
} else {
    echo json_encode(["message" => "Incomplete data"]);
}
