<?php

// Updated: throw exceptions on errors so caller can inspect database failures
function create_order($conn, $user_id, $name, $email, $phone, $address, $total, $cart_items)
{
    if (empty($cart_items) || !is_array($cart_items)) {
        throw new InvalidArgumentException('create_order: cart_items is empty or invalid');
    }

    // Aggregate items by product_id to avoid UNIQUE constraint violation
    $aggregated = [];
    foreach ($cart_items as $item) {
        $pid = (int) ($item['id'] ?? 0);
        $qty = max(1, (int) ($item['quantity'] ?? 1));
        $price = (float) ($item['price'] ?? 0.0);

        if ($pid <= 0) {
            throw new RuntimeException("create_order: invalid product id in cart ({$pid})");
        }
        if ($price <= 0) {
            throw new RuntimeException("create_order: invalid price for product {$pid}");
        }

        if (!isset($aggregated[$pid])) {
            $aggregated[$pid] = ['quantity' => 0, 'price' => $price];
        }
        $aggregated[$pid]['quantity'] += $qty;
        // keep the price from the last occurrence (or adjust to business rule)
        $aggregated[$pid]['price'] = $price;
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // ensure products exist (fail fast with clear message)
        $checkStmt = $conn->prepare("SELECT id FROM Products WHERE id = ?");
        if (!$checkStmt) {
            throw new RuntimeException('create_order: prepare Products check failed: ' . $conn->error);
        }
        foreach ($aggregated as $pid => $info) {
            $checkStmt->bind_param('i', $pid);
            $checkStmt->execute();
            $res = $checkStmt->get_result();
            if (!$res || $res->num_rows === 0) {
                $checkStmt->close();
                $conn->rollback();
                throw new RuntimeException("create_order: product id {$pid} does not exist");
            }
        }
        $checkStmt->close();

        // Insert Orders row (handle nullable user_id safely)
        if ($user_id > 0) {
            $sql = "INSERT INTO Orders (user_id, customer_name, customer_email, customer_phone, shipping_address, total_amount, order_status)
                    VALUES (?, ?, ?, ?, ?, ?, 'Pending')";
            $stmt = $conn->prepare($sql);
            if (!$stmt)
                throw new RuntimeException('create_order prepare error: ' . $conn->error);
            $stmt->bind_param('issssd', $user_id, $name, $email, $phone, $address, $total);
        } else {
            $sql = "INSERT INTO Orders (customer_name, customer_email, customer_phone, shipping_address, total_amount, order_status)
                    VALUES (?, ?, ?, ?, ?, 'Pending')";
            $stmt = $conn->prepare($sql);
            if (!$stmt)
                throw new RuntimeException('create_order prepare error: ' . $conn->error);
            $stmt->bind_param('ssssd', $name, $email, $phone, $address, $total);
        }

        if (!$stmt->execute()) {
            $err = 'create_order execute error: ' . $stmt->error;
            $stmt->close();
            $conn->rollback();
            throw new RuntimeException($err);
        }
        $orderId = $conn->insert_id;
        $stmt->close();

        // Insert aggregated order details (one row per product)
        $detailSql = "INSERT INTO Order_Details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $detailStmt = $conn->prepare($detailSql);
        if (!$detailStmt)
            throw new RuntimeException('Order_Details prepare error: ' . $conn->error);

        foreach ($aggregated as $pid => $info) {
            $quantity = (int) $info['quantity'];
            $price = (float) $info['price'];

            if (!$detailStmt->bind_param('iiid', $orderId, $pid, $quantity, $price)) {
                $detailStmt->close();
                $conn->rollback();
                throw new RuntimeException('Order_Details bind_param error: ' . $detailStmt->error);
            }
            if (!$detailStmt->execute()) {
                $detailStmt->close();
                $conn->rollback();
                throw new RuntimeException('Order_Details execute error: ' . $detailStmt->error);
            }
        }
        $detailStmt->close();

        if (!$conn->commit()) {
            $conn->rollback();
            throw new RuntimeException('create_order commit failed: ' . $conn->error);
        }

        return $orderId;
    } catch (Throwable $e) {
        if ($conn->connect_errno === 0 && $conn->errno === 0) {
            // ensure rollback in any case
            $conn->rollback();
        }
        throw $e;
    }
}

// (Read)
function get_all_orders($conn)
{
    $sql = "SELECT * FROM Orders ORDER BY created_at DESC";
    $res = $conn->query($sql);
    $rows = [];
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            $rows[] = $r;
        }
        $res->free();
    }
    return $rows;
}

// (Update)
function update_order_status($conn, $id, $status)
{
    $stmt = $conn->prepare("UPDATE Orders SET order_status = ? WHERE id = ?");
    if (!$stmt) {
        error_log('update_order_status prepare error: ' . $conn->error);
        return false;
    }
    $stmt->bind_param('si', $status, $id);
    $ok = $stmt->execute();
    if (!$ok)
        error_log('update_order_status execute error: ' . $stmt->error);
    $stmt->close();
    return $ok;
}

// (Delete)
function delete_order($conn, $id)
{
    $stmt = $conn->prepare("DELETE FROM Orders WHERE id = ?");
    if (!$stmt) {
        error_log('delete_order prepare error: ' . $conn->error);
        return false;
    }
    $stmt->bind_param('i', $id);
    $ok = $stmt->execute();
    if (!$ok)
        error_log('delete_order execute error: ' . $stmt->error);
    $stmt->close();
    return $ok;
}
?>