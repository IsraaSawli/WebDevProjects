<?php
session_start();

// Assuming your database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Grading_System";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch student's marks
$studentId = $_SESSION['id'];
$sql = "SELECT subject, marks FROM marks WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentId);
$stmt->execute();
$result = $stmt->get_result();

// Generate CSV data
$csvData = "Subject,Marks\n";
while ($row = $result->fetch_assoc()) {
    $csvData .= $row['subject'] . ',' . $row['marks'] . "\n";
}

// Set headers for file download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="student_grades.csv"');

// Output CSV data
echo $csvData;

// Close statement and database connection
$stmt->close();
$conn->close();
?>
