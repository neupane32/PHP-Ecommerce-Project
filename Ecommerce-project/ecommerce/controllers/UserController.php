<?php
require_once '../models/User.php';

class UserController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new User($db);
    }

    public function createUser($username, $password, $email) {
        $this->userModel->username = $username;
        $this->userModel->password = $password;
        $this->userModel->email = $email;

        if ($this->userModel->createUser()) {
            return "User created successfully";
        } else {
            return "Failed to create user";
        }
    }

    public function authenticate($username, $password) {
        $this->userModel->username = $username;
        $this->userModel->password = $password;

        if ($this->userModel->checkCredentials()) {
            $token = $this->userModel->generateToken();
            if ($token) {
                return ["message" => "Correct password", "token" => $token];
            }
        }
        return ["message" => "Incorrect password"];
    }

    public function getUserByToken($token) {
        $this->userModel->token = $token;
        return $this->userModel->getUserByToken();
    }
}
