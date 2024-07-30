<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "how_safe";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from POST request
$barangay = $_POST['barangay'];
$traffic = $_POST['traffic'];
$incidentRate = $_POST['incidentRate'];
$safety = $_POST['safety'];

// Insert data into database
$sql = "INSERT INTO feedback (barangay, traffic, incident_rate, safety) VALUES ('$barangay', '$traffic', '$incidentRate', '$safety')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["message" => "Feedback submitted successfully"]);
} else {
    echo json_encode(["message" => "Error: " . $conn->error]);
}

$conn->close();
?>
