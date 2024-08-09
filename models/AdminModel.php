<?php

class AdminModel {
    private $db_name;

    public function __construct($db_name)
    {
        $this->db_name = $db_name;
    }

    public function getAdminDetails($username){
        $query = "SELECT username, password FROM admins WHERE username = ?";
        $stmt = $this->db_name->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();

    }

}