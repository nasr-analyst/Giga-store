<?php
require_once __DIR__ . '/../config/database.php';

class ProductModel
{
    private $db;

    public function __construct()
    {
        // use the mysqli connection initialized in config/database.php
        global $conn;
        $this->db = $conn;
    }

    public function createProduct(
        string $name,
        string $description,
        float $price,
        ?string $imageUrl,
        int $categoryId
    ): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO Products (name, description, price, image_url, category_id)
             VALUES (?, ?, ?, ?, ?)"
        );
        if (!$stmt) {
            error_log("createProduct prepare error: " . $this->db->error);
            return false;
        }

        $stmt->bind_param('ssdsi', $name, $description, $price, $imageUrl, $categoryId);
        $result = $stmt->execute();
        if (!$result) {
            error_log("createProduct execute error: " . $stmt->error);
        }
        $stmt->close();

        return (bool) $result;
    }


    // Get All Products
    public function getAllProducts()
    {
        $sql = "SELECT * FROM Products";
        $res = $this->db->query($sql);
        $rows = [];
        if ($res) {
            while ($r = $res->fetch_assoc()) {
                $rows[] = $r;
            }
        }
        return $rows;
    }

    // Get Product By Id
    public function getProductById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM Products WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res ? $res->fetch_assoc() : null;
    }

    // Get Products By Category
    public function getProductsByCategory($categoryId)
    {
        $stmt = $this->db->prepare("SELECT * FROM Products WHERE category_id = ?");
        $stmt->bind_param('i', $categoryId);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = [];
        if ($res) {
            while ($r = $res->fetch_assoc()) {
                $rows[] = $r;
            }
        }
        return $rows;
    }

    public function updateProduct(
        int $id,
        string $name,
        string $description,
        float $price,
        ?string $imageUrl,
        int $categoryId
    ): bool {
        $stmt = $this->db->prepare(
            "UPDATE Products
             SET name = ?, description = ?, price = ?, image_url = ?, category_id = ?
             WHERE id = ?"
        );
        if (!$stmt) {
            error_log("updateProduct prepare error: " . $this->db->error);
            return false;
        }

        $stmt->bind_param('ssdsii', $name, $description, $price, $imageUrl, $categoryId, $id);
        $result = $stmt->execute();
        if (!$result) {
            error_log("updateProduct execute error: " . $stmt->error);
        }
        $stmt->close();

        return (bool) $result;
    }

    public function deleteProduct(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM Products WHERE id = ?");
        $stmt->bind_param('i', $id);

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

}
?>