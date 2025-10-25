<?php
require_once 'config/database.php';

class ProductModel {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Get All Products
    public function getAllProducts() {
        $query = "SELECT * FROM Products";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get Product By Id
    public function getProductById($id) {
        $query = "SELECT * FROM Products WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get Products By Category
    public function getProductsByCategory($categoryId) {
        $query = "SELECT * FROM Products WHERE category_id = :categoryId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
