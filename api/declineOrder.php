<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"));
$order_id = $data->order_id;

$update = $conn->prepare("UPDATE orders SET status='Declined' WHERE order_id=?");
$update->bind_param("i", $order_id);

if ($update->execute()) {
  echo json_encode(['success' => true, 'message' => 'Order declined.']);
} else {
  echo json_encode(['success' => false, 'message' => 'Failed to decline order.']);
}
?>
