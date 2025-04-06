<?php
session_start();
header("Content-Type: application/json");
require 'db.php'; // Database connection

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email']) || empty(trim($data['email']))) {
    echo json_encode(["error" => "Email is required"]);
    exit();
}

if (!isset($data['password']) || strlen($data['password']) < 6) {
    echo json_encode(["error" => "Password must be at least 6 characters"]);
    exit();
}

$email = trim($data['email']);
$password = trim($data['password']);

// Check user credentials and fetch department & role details
$stmt = $conn->prepare("
    SELECT 
        u.id, u.username, u.email, u.password,  
        u.department_id, d.name AS department_name, 
        u.role_id, r.name AS role_name
    FROM users u
    JOIN departments d ON u.department_id = d.id
    JOIN roles r ON u.role_id = r.id
    WHERE u.email = ?
");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Validate user existence
if (!$user) {
    echo json_encode(["error" => "Invalid email"]);
    exit();
}

// Verify password (Remove password_verify and use direct comparison)
if ($password !== $user['password']) {
    echo json_encode(["error" => "Invalid password"]);
    exit();
}

// Store user data in session
$_SESSION['id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['email'] = $user['email']; // Store email in session
$_SESSION['department_id'] = $user['department_id'];
$_SESSION['department_name'] = $user['department_name'];
$_SESSION['role_id'] = $user['role_id'];
$_SESSION['role_name'] = $user['role_name'];

// Default redirect to 404 page
$redirectUrl = "404Page.php";

// Convert to lowercase for case-insensitive comparison
$department = strtolower(trim($user['department_name']));
$role = strtolower(trim($user['role_name']));

// Check if user is in Civil Registrar department
if ($department === 'logistic2') {
    if ($role === 'admin') {
        $redirectUrl = "pages/admindashboard.php"; // Redirect to Admin folder
    } elseif ($role === 'employee') {
        $redirectUrl = "employee/employee_dashboard.php"; // Redirect to Employee folder
    } 
}

echo json_encode([
    "success" => true,
    "id" => $_SESSION['id'],
    "username" => $_SESSION['username'],
    "email" => $_SESSION['email'], // Include email in response
    "department_id" => $_SESSION['department_id'],
    "department_name" => $_SESSION['department_name'],
    "role_id" => $_SESSION['role_id'],
    "role_name" => $_SESSION['role_name'],
    "redirect" => $redirectUrl
]);
exit();
?>
