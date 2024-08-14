<?php
class Product {
    private $conn;
    private $table_name = "products";

    public $product_id;
    public $product_name;
    public $description;
    public $price;
    public $user_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createProduct() {
        $query = "INSERT INTO " . $this->table_name . " SET product_name=?, description=?, price=?, user_id=?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ssdi", $this->product_name, $this->description, $this->price, $this->user_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function getAllProducts($user_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getProductById($product_id, $user_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE product_id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ii", $product_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function updateProduct($product_id, $product_name, $description, $price, $user_id) {
        $query = "UPDATE " . $this->table_name . " SET product_name=?, description=?, price=? WHERE product_id=? AND user_id=?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ssdii", $product_name, $description, $price, $product_id, $user_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function deleteProduct($product_id, $user_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE product_id=? AND user_id=?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ii", $product_id, $user_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
