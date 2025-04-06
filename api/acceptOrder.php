<?php
include 'db.php'; // db connection

$data = json_decode(file_get_contents("php://input"));

$order_id = $data->order_id;

// Step 1: Update order status
$update = $conn->prepare("UPDATE orders SET status='Accepted' WHERE order_id=?");
$update->bind_param("i", $order_id);
$update->execute();

// Step 2: Fetch order details
$query = $conn->prepare("SELECT dropoff_location, shipping_datetime FROM orders WHERE order_id=?");
$query->bind_param("i", $order_id);
$query->execute();
$result = $query->get_result();
$order = $result->fetch_assoc();

if ($order) {
    $shipment_date = $order['shipping_datetime'];
    $destination = $order['dropoff_location'];
    $tracking_number = 'TRK-' . strtoupper(bin2hex(random_bytes(5)));

    // Step 3: Insert into shipping table
    $insert = $conn->prepare("INSERT INTO shipping (order_id, shipment_date, destination, tracking_number) VALUES (?, ?, ?, ?)");
    $insert->bind_param("isss", $order_id, $shipment_date, $destination, $tracking_number);
    $insert->execute();

    // Step 4: Remove the order from the orders table
    $delete = $conn->prepare("DELETE FROM orders WHERE order_id=?");
    $delete->bind_param("i", $order_id);
    $delete->execute();

    echo json_encode(['success' => true, 'message' => 'Order accepted, shipment created, and order removed from the orders table.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Order not found.']);
}
?>
