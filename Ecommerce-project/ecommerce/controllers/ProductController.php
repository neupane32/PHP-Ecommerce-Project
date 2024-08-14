<?php
require_once '../models/Product.php';

class ProductController {
    private $productModel;

    public function __construct($db) {
        $this->productModel = new Product($db);
    }

    public function createProduct($product_name, $description, $price, $user_id) {
        $this->productModel->product_name = $product_name;
        $this->productModel->description = $description;
        $this->productModel->price = $price;
        $this->productModel->user_id = $user_id;

        if ($this->productModel->createProduct()) {
            return "Product created successfully";
        } else {
            return "Failed to create product";
        }
    }

    public function getAllProducts($user_id) {
        return $this->productModel->getAllProducts($user_id);
    }

    public function getProductById($product_id, $user_id) {
        return $this->productModel->getProductById($product_id, $user_id);
    }

    public function updateProduct($product_id, $product_name, $description, $price, $user_id) {
        return $this->productModel->updateProduct($product_id, $product_name, $description, $price, $user_id);
    }

    public function deleteProduct($product_id, $user_id) {
        return $this->productModel->deleteProduct($product_id, $user_id);
    }
}
