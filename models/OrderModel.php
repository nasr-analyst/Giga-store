<?php

function create_order($conn, $user_id, $name, $email, $phone, $address, $total)
{
    $sql = "INSERT INTO ORDERS 
            (user_id, customer_name, customer_email, customer_phone, shipping_address, total_amount, order_status) 
            VALUES 
            (:uid, :nm, :em, :ph, :addr, :tot, 'pending')";

    $stmt = $conn->prepare($sql);
    $params = [
        ':uid' => $user_id,
        ':nm' => $name,
        ':em' => $email,
        ':ph' => $phone,
        ':addr' => $address,
        ':tot' => $total
    ];

    if ($stmt->execute($params)) {
        return $conn->lastInsertId();
    }
    return false;
}

//  (Read)
function get_all_orders($conn)
{
    $sql = "SELECT * FROM ORDERS ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//  (Update)
function update_order_status($conn, $id, $status)
{
    $sql = "UPDATE ORDERS SET order_status = :st WHERE id = :id";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([':st' => $status, ':id' => $id]);
}

//  (Delete)
function delete_order($conn, $id)
{
    $sql = "DELETE FROM ORDERS WHERE id = :id";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([':id' => $id]);
}
?>