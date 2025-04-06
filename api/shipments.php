<?php
include 'db.php'; // Include database connection

$method = $_SERVER['REQUEST_METHOD']; // Get the request method

switch ($method) {
    case 'GET':
        // Get all shipments
        if (!isset($_GET['id'])) {
            $result = $conn->query("SELECT * FROM shipping ORDER BY shipment_date DESC");
            $shipments = [];
            while ($row = $result->fetch_assoc()) {
                $shipments[] = $row;
            }
            echo json_encode($shipments);
        } 
        // Get one shipment by ID
        elseif (isset($_GET['id'])) {
            $shipmentId = $_GET['id'];
            $stmt = $conn->prepare("SELECT * FROM shipping WHERE shipment_id = ?");
            $stmt->bind_param("i", $shipmentId);
            $stmt->execute();
            $result = $stmt->get_result();
            $shipment = $result->fetch_assoc();
            echo json_encode($shipment);
        }
        break;

    case 'POST':
        // Create new shipment
        $data = json_decode(file_get_contents("php://input"), true);
        $shipmentDate = $data['shipment_date'];
        $shipmentStatus = $data['shipment_status'];
        $destination = $data['destination'];
        $trackingNumber = $data['tracking_number'];

        $stmt = $conn->prepare("INSERT INTO shipping (shipment_date, shipment_status, destination, tracking_number) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $shipmentDate, $shipmentStatus, $destination, $trackingNumber);
        $stmt->execute();

        echo json_encode(["success" => true, "message" => "Shipment created successfully"]);
        break;

    case 'PUT':
        // Update shipment, shipment_id passed via URL
        if (isset($_GET['id'])) {
            $shipmentId = $_GET['id']; // Retrieve shipment_id from URL

            // Get data from the request body
            $data = json_decode(file_get_contents("php://input"), true); 

            // Validate required fields (shipment_date, shipment_status, destination, and tracking_number)
            if (isset($data['shipment_date'], $data['shipment_status'], $data['destination'], $data['tracking_number'])) {
                $shipmentDate = $data['shipment_date'];
                $shipmentStatus = $data['shipment_status'];
                $destination = $data['destination'];
                $trackingNumber = $data['tracking_number'];

                // Prepare and execute the update query (updating shipment_date, shipment_status, destination, and tracking_number)
                $stmt = $conn->prepare("UPDATE shipping SET shipment_date = ?, shipment_status = ?, destination = ?, tracking_number = ? WHERE shipment_id = ?");
                $stmt->bind_param("ssssi", $shipmentDate, $shipmentStatus, $destination, $trackingNumber, $shipmentId);
                $stmt->execute();

                echo json_encode(["success" => true, "message" => "Shipment updated successfully"]);
            } else {
                echo json_encode(["success" => false, "message" => "Missing required data"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Shipment ID is missing"]);
        }
        break;

    case 'DELETE':
        // Delete shipment, shipment_id passed via URL
        if (isset($_GET['id'])) {
            $shipmentId = $_GET['id']; // Retrieve shipment_id from URL

            // Prepare and execute the delete query
            $stmt = $conn->prepare("DELETE FROM shipping WHERE shipment_id = ?");
            $stmt->bind_param("i", $shipmentId);
            $stmt->execute();

            echo json_encode(["success" => true, "message" => "Shipment deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Shipment ID is missing"]);
        }
        break;

    default:
        echo json_encode(["error" => "Method not allowed"]);
        break;
}
?>
