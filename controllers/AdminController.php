<?php


require_once './models/AdminModel.php';

class AdminController {
    private $adminModel;

    public function __construct($db_name) {
        $this->adminModel = new AdminModel($db_name);
    }

    public function authenticate() {
        $headers = apache_request_headers();
        $authorizationHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';

        if ($authorizationHeader) {
            list($type, $credentials) = explode(' ', $authorizationHeader, 2);

            if ($type === 'Basic') {
                $decodedCredentials = base64_decode($credentials);
                list($username, $password) = explode(':', $decodedCredentials, 2);

                $adminData = $this->adminModel->getAdminDetails($username);

                if ($adminData && password_verify($password, $adminData['password'])) {
                    return [
                        'success' => true,
                        'message' => 'Admin login successful.',
                        'token' => $this->generateToken($username)
                    ];
                } else {
                    return ['success' => false, 'message' => 'Invalid credentials.'];
                }
            } else {
                return ['success' => false, 'message' => 'Invalid authorization type.'];
            }
        } else {
            return ['success' => false, 'message' => 'Authorization header missing.'];
        }
    }

    private function generateToken($username) {
        return base64_encode($username . ':' . uniqid() . ':' . time());
    }
}
