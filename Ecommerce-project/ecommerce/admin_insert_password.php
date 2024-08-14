<?php

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "ecommerce";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if an admin already exists
$query = "SELECT COUNT(*) as total FROM admins_table";
$result = $conn->query($query);
$row = $result->fetch_assoc();

if ($row['total'] > 0) {
    echo json_encode(["message" => "Admin already exists"]);
} else {
    $stmt = $conn->prepare("INSERT INTO admins_table(username, password) VALUES (?, ?)");
    $user = "admin";
    $pass = "adminAashish";

    $hashedPassword = base64_encode($pass);

    $stmt->bind_param("ss", $user, $hashedPassword);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(["message" => "Admin is created successfully!!"]);
    } else {
        echo json_encode(["message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
