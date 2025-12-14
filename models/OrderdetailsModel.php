<?php
require_once __DIR__ . '/../config/database.php';

class OrderDetailsModel
{
    private $db;

    public function __construct()
    {
        global $conn;
        $this->db = $conn;
    }
    
public function createOrderDetail(
        int $orderId,
        int $productId,
        int $quantity,
        float $price
    ): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO Order_Details (order_id, product_id, quantity, price)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param('iiid', $orderId, $productId, $quantity, $price);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    // Get all order details
    public function getAllOrderDetails()
    {
        $sql = "SELECT od.*, p.name AS product_name, p.image_url
                FROM Order_Details od
                JOIN Products p ON od.product_id = p.id";
        $res = $this->db->query($sql);
        $rows = [];
        if ($res) {
            while ($r = $res->fetch_assoc()) {
                $rows[] = $r;
            }
        }
        return $rows;
    }

    // Get order details by order id
    public function getDetailsByOrderId($orderId): array
    {
        $stmt = $this->db->prepare(
            "SELECT od.*, p.name AS product_name, p.image_url
             FROM Order_Details od
             JOIN Products p ON od.product_id = p.id
             WHERE od.order_id = ?"
        );
        if (!$stmt) {
            error_log('OrderDetailsModel prepare failed: ' . $this->db->error);
            return [];
        }
        $stmt->bind_param('i', $orderId);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = [];
        if ($res) {
            while ($r = $res->fetch_assoc()) {
                $rows[] = $r;
            }
        }
        $stmt->close();
        return $rows;
    }

    public function addOrderDetail($orderId, $productId, $quantity, $price)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO Order_Details (order_id, product_id, quantity, price)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param('iiid', $orderId, $productId, $quantity, $price);
        return $stmt->execute();
    }

    public function updateOrderDetail($id, $quantity, $price)
    {
        $stmt = $this->db->prepare(
            "UPDATE Order_Details SET quantity = ?, price = ? WHERE id = ?"
        );
        $stmt->bind_param('idi', $quantity, $price, $id);
        return $stmt->execute();
    }

    public function deleteOrderDetail($id)
    {
        $stmt = $this->db->prepare("DELETE FROM Order_Details WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
?>