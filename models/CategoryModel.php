
<?php
// models/CategoryModel.php

require_once __DIR__ . '/../config/database.php';

class CategoryModel
{
    private $db;

    public function __construct()
    {
        global $conn;
        $this->db = $conn;
    }

    public function createCategory(string $name, ?string $description): bool
    {
        $stmt = $this->db->prepare("INSERT INTO Categories (name, description) VALUES (?, ?)");
        $stmt->bind_param('ss', $name, $description);
        
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }

    public function getAllCategories(): array
    {
        $sql = "SELECT id, name, description FROM Categories ORDER BY name ASC";
        $res = $this->db->query($sql);
        $rows = [];
        if ($res) {
            while ($r = $res->fetch_assoc()) {
                $rows[] = $r;
            }
            $res->free();
        }
        return $rows;
    }

    public function getCategoryById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT id, name, description FROM Categories WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $category = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        return $category;
    }

    public function updateCategory(int $id, string $name, ?string $description): bool
    {
        $stmt = $this->db->prepare("UPDATE Categories SET name = ?, description = ? WHERE id = ?");
        $stmt->bind_param('ssi', $name, $description, $id);
        
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }

    public function deleteCategory(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM Categories WHERE id = ?");
        $stmt->bind_param('i', $id);
        
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
}
?>


















