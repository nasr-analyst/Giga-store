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

    // Get all order details
    public function getAllOrderDetails()
    {
        $sql = "SELECT od.*, p.name AS product_name, p.image_url
                FROM ORDER_DETAILS od
                JOIN PRODUCTS p ON od.product_id = p.id";
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
    public function getDetailsByOrderId($orderId)
    {
        $stmt = $this->db->prepare(
            "SELECT od.*, p.name AS product_name, p.image_url
             FROM ORDER_DETAILS od
             JOIN PRODUCTS p ON od.product_id = p.id
             WHERE od.order_id = ?"
        );
        $stmt->bind_param('i', $orderId);
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

    // Add a new order detail (optional, handled in create_order)
    public function addOrderDetail($orderId, $productId, $quantity, $price)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO ORDER_DETAILS (order_id, product_id, quantity, price)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param('iiid', $orderId, $productId, $quantity, $price);
        return $stmt->execute();
    }

    // Update an order detail (quantity or price)
    public function updateOrderDetail($id, $quantity, $price)
    {
        $stmt = $this->db->prepare(
            "UPDATE ORDER_DETAILS SET quantity = ?, price = ? WHERE id = ?"
        );
        $stmt->bind_param('idi', $quantity, $price, $id);
        return $stmt->execute();
    }

    // Delete an order detail by id
    public function deleteOrderDetail($id)
    {
        $stmt = $this->db->prepare("DELETE FROM ORDER_DETAILS WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
?>
