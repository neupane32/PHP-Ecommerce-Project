
<?Php
require_once './config/database.php';
require_once './controllers/AdminController.php';

$db_name = (new Database())->getConnection();
$adminController = new AdminController($db_name);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = $adminController->authenticate();
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
