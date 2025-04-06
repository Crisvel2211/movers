<?php
include 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Get all orders
        if (!isset($_GET['id'])) {
            $result = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
            $orders = [];
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }
            echo json_encode($orders);
        } 
        // Get one order by ID
        elseif (isset($_GET['id'])) {
            $orderId = $_GET['id'];
            $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
            $stmt->bind_param("i", $orderId);
            $stmt->execute();
            $result = $stmt->get_result();
            $order = $result->fetch_assoc();
            echo json_encode($order);
        }
        break;

    case 'POST':
        // Create new order
        $data = json_decode(file_get_contents("php://input"), true);
        $orderType = $data['order_type'];
        $pickupLocation = $data['pickup_location'];
        $dropoffLocation = $data['dropoff_location'];
        $shippingDateTime = $data['shipping_date_time'];

        $stmt = $conn->prepare("INSERT INTO orders (order_type, pickup_location, dropoff_location, shipping_date_time) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $orderType, $pickupLocation, $dropoffLocation, $shippingDateTime);
        $stmt->execute();

        echo json_encode(["success" => true, "message" => "Order created successfully"]);
        break;

    case 'PUT':
        // Update order
        $data = json_decode(file_get_contents("php://input"), true);
        $orderId = $data['order_id'];
        $orderType = $data['order_type'];
        $pickupLocation = $data['pickup_location'];
        $dropoffLocation = $data['dropoff_location'];
        $shippingDateTime = $data['shipping_date_time'];

        $stmt = $conn->prepare("UPDATE orders SET order_type = ?, pickup_location = ?, dropoff_location = ?, shipping_date_time = ? WHERE order_id = ?");
        $stmt->bind_param("ssssi", $orderType, $pickupLocation, $dropoffLocation, $shippingDateTime, $orderId);
        $stmt->execute();

        echo json_encode(["success" => true, "message" => "Order updated successfully"]);
        break;

    case 'DELETE':
        // Delete order
        $data = json_decode(file_get_contents("php://input"), true);
        $orderId = $data['order_id'];

        $stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();

        echo json_encode(["success" => true, "message" => "Order deleted successfully"]);
        break;

    default:
        echo json_encode(["error" => "Method not allowed"]);
        break;
}
?>
