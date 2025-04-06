<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once 'db.php'; // Include the database connection

// Get the HTTP method of the request
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        // Create a new transport asset
        createTransportAsset($conn);
        break;
    case 'PUT':
        // Update an existing transport asset
        updateTransportAsset($conn);
        break;
    case 'DELETE':
        // Delete a transport asset
        deleteTransportAsset($conn);
        break;
    case 'GET':
        if (isset($_GET['id'])) {
            // Get a specific transport asset by ID
            getTransportAsset($conn, $_GET['id']);
        } else {
            // Get all transport assets
            getAllTransportAssets($conn);
        }
        break;
    default:
        // Invalid request method
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

function createTransportAsset($conn) {
    $data = json_decode(file_get_contents("php://input"));
    
    $vehicle_id = mysqli_real_escape_string($conn, $data->vehicle_id);
    $plate_number = mysqli_real_escape_string($conn, $data->plate_number);
    $brand = mysqli_real_escape_string($conn, $data->brand);
    $model = mysqli_real_escape_string($conn, $data->model);
    $year = mysqli_real_escape_string($conn, $data->year);
    $depreciation_value = mysqli_real_escape_string($conn, $data->depreciation_value);
    $vin = mysqli_real_escape_string($conn, $data->vin);
    $condition = mysqli_real_escape_string($conn, $data->condition);
    $status = isset($data->status) ? mysqli_real_escape_string($conn, $data->status) : 'Pending';

    $sql = "INSERT INTO transport_assets (vehicle_id, plate_number, brand, model, year, depreciation_value, vin, `condition`, status)
            VALUES ('$vehicle_id', '$plate_number', '$brand', '$model', $year, $depreciation_value, '$vin', '$condition', '$status')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => 'Transport Asset created successfully']);
    } else {
        echo json_encode(['error' => 'Error: ' . $conn->error]);
    }
}


function updateTransportAsset($conn) {
    $id = isset($_GET['id']) ? $_GET['id'] : null;  // Get ID from URL
    $data = json_decode(file_get_contents("php://input"));
    
    if ($id && $data) {
        $vehicle_id = mysqli_real_escape_string($conn, $data->vehicle_id);
        $plate_number = mysqli_real_escape_string($conn, $data->plate_number);
        $brand = mysqli_real_escape_string($conn, $data->brand);
        $model = mysqli_real_escape_string($conn, $data->model);
        $year = mysqli_real_escape_string($conn, $data->year);
        $depreciation_value = mysqli_real_escape_string($conn, $data->depreciation_value);
        $vin = mysqli_real_escape_string($conn, $data->vin);
        $condition = mysqli_real_escape_string($conn, $data->condition);
        $status = mysqli_real_escape_string($conn, $data->status);

        $sql = "UPDATE transport_assets 
                SET vehicle_id='$vehicle_id', plate_number='$plate_number', brand='$brand', model='$model', 
                    year=$year, depreciation_value=$depreciation_value, vin='$vin', condition='$condition', status='$status' 
                WHERE id=$id";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => 'Transport Asset updated successfully']);
        } else {
            echo json_encode(['error' => 'Error: ' . $conn->error]);
        }
    } else {
        echo json_encode(['error' => 'Invalid ID or data']);
    }
}


function deleteTransportAsset($conn) {
    $id = isset($_GET['id']) ? $_GET['id'] : null;  // Get ID from URL

    if ($id) {
        $sql = "DELETE FROM transport_assets WHERE id=$id";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => 'Transport Asset deleted successfully']);
        } else {
            echo json_encode(['error' => 'Error: ' . $conn->error]);
        }
    } else {
        echo json_encode(['error' => 'Invalid ID']);
    }
}


function getAllTransportAssets($conn) {
    $sql = "SELECT * FROM transport_assets";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $assets = [];
        while ($row = $result->fetch_assoc()) {
            $assets[] = $row;
        }
        echo json_encode($assets);
    } else {
        echo json_encode(['message' => 'No transport assets found']);
    }
}

function getTransportAsset($conn, $id) {
    $sql = "SELECT * FROM transport_assets WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $asset = $result->fetch_assoc();
        echo json_encode($asset);
    } else {
        echo json_encode(['message' => 'Transport Asset not found']);
    }
}


$conn->close();
?>
