<?php

require_once '../config/database.php'; // Database connection    
require_once '../models/OrderModel.php'; // get Order model functions  

// if customer creates a new order
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $total = $_POST['total'];

    // نداء للموديل
    $new_id = create_order($conn, $user_id, $name, $email, $phone, $address, $total);

    if ($new_id) {
        header("Location: index.php?msg=success");
    } else {
        echo "Error creating order!";
    }
}

// if the admin updates order status
if (isset($_POST['action']) && $_POST['action'] == 'update_status') {
    $id = $_POST['order_id'];
    $status = $_POST['status'];

    update_order_status($conn, $id, $status);
    header("Location: index.php");
}

// if the admin deletes an order
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    delete_order($conn, $id);
    header("Location: index.php");
}
?>