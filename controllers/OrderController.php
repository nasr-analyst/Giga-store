<?php
// Minimal stub for OrderController.php — backend to be implemented by another developer.
// Accepts POST from the frontend and returns a clear JSON "not implemented" response.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Return 501 Not Implemented for POST so frontend and integrator know it's a placeholder
    http_response_code(501);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => false,
        'message' => 'Order endpoint not implemented. Backend developer must implement create order logic.',
        'notes' => 'Expect POST fields: action=create, total_amount, cart_items (JSON), customer_*'
    ]);
    exit;
}

// For GET show a small note for the integrator
http_response_code(200);
header('Content-Type: text/plain; charset=utf-8');
echo "OrderController placeholder. Implement POST handling for order creation.";
exit;
?>