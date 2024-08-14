<?php
require_once '../models/Admin.php';

class AdminController {
    private $adminModel;

    public function __construct($db) {
        $this->adminModel = new Admin($db);
    }

    public function authenticate($username, $password) {
        $this->adminModel->username = $username;
        $this->adminModel->password = $password;

        if ($this->adminModel->checkCredentials()) {
            return "Correct details: Now you can create user!!";
        } else {
            return "Incorrect password";
        }
    }
}
