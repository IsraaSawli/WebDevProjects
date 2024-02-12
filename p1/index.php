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

// Fetch marks data for the logged-in user
$userId = $_SESSION['id'];
$sql = "SELECT * FROM marks WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Close statement and database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Marks</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8); /* Transparent white background */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        body {
            font-family: Arial, sans-serif;
            background-position-y: -80px;
            background-image: url('3949116.jpg');
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, User ID: <?php echo $userId; ?></h1>
        <h2>Your Marks</h2>
        <table>
            <tr>
                <th>Subject</th>
                <th>Marks</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['subject']; ?></td>
                    <td><?php echo $row['marks']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <a href="download_grades.php">download grades</a>
</body>
</html>
