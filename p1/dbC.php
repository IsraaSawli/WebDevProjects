<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Grading_System";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS Grading_System";
if ($conn->query($sql) === FALSE) {
    echo "Error creating database: " . $conn->error;
}

// Select the database
if (!mysqli_select_db($conn, $dbname)) {
    die("Database selection failed: " . mysqli_error($conn));
}

// Create the students table if it doesn't exist
$query = 'CREATE TABLE IF NOT EXISTS students (
    S_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    passwordd TEXT NOT NULL,
    date_entered DATETIME NOT NULL,
    is_admin TINYINT NOT NULL DEFAULT 0
)';
if (!mysqli_query($conn, $query)) {
    $str = '<p style="color: red;">Could not create the table because:<br />';
    $str .= mysqli_error($conn);
    $str .= '.</p><p>The query being run was:' . $query . '</p>';
    print $str;
}

// Insert admin credentials into the table if it's a new database
$result = mysqli_query($conn, "SELECT * FROM students");
if (mysqli_num_rows($result) == 0) {
    $adminEmail = 'admin@example.com'; // Change this to the admin's email
    $adminPassword = password_hash('adminpassword', PASSWORD_DEFAULT); // Change this to the admin's password
    
    $insertAdminQuery = "INSERT INTO students (email, passwordd, date_entered, is_admin) VALUES (?, ?, NOW(), 1)";
    $stmt = $conn->prepare($insertAdminQuery);
    $stmt->bind_param("ss", $adminEmail, $adminPassword);
    if ($stmt->execute()) {
        $correctInsert = "Admin credentials have been saved.";
    } else {
        $str = '<p style="color: red;">Could not insert admin credentials because:<br />';
        $str .= $conn->error;
        $str .= '.</p><p>The query being run was:' . $insertAdminQuery . '</p>';
        print $str;
    }
    $stmt->close();
}

// Create the marks table if it doesn't exist
$query = "CREATE TABLE IF NOT EXISTS marks (
    student_id INT UNSIGNED NOT NULL,
    subject VARCHAR(255) NOT NULL,
    marks FLOAT NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(S_id)
)";

if ($conn->query($query) === FALSE) {
   
    echo "Error creating table: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
