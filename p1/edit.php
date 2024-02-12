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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $newMarks = $_POST['newMarks'];
    $studentId = $_POST['studentId'];
    $subject = $_POST['subject']; // Add this line to get the subject information

    // Prepare and execute the SQL update statement with both student ID and subject
    $sql = "UPDATE marks SET marks = ? WHERE student_id = ? AND subject = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dis", $newMarks, $studentId, $subject); // Update the bind_param to include the subject

    if ($stmt->execute()) {
        $message = "Marks updated successfully.";
    } else {
        $error = "Error updating marks: " . $conn->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Fetch student's marks
$studentId = $_GET['id'];
$sql = "SELECT * FROM marks WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Close statement and database connection
$stmt->close();
$conn->close();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Marks</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
        }
        .message.error {
            color: red;
        }
        .message.success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Marks</h1>
        <?php if (!empty($error)) { ?>
            <p class="message error"><?php echo $error; ?></p>
        <?php } ?>
        <?php if (!empty($message)) { ?>
            <p class="message success"><?php echo $message; ?></p>
        <?php } ?>
        <form method="post">
            <label for="newMarks">New Marks:</label>
            <input type="number" name="newMarks" value="<?php echo $row['marks']; ?>" required>
            <input type="hidden" name="studentId" value="<?php echo $studentId; ?>">
            <input type="hidden" name="subject" value="<?php echo $row['subject']; ?>">
            <button type="submit">Update Marks</button>
        </form>
    </div>
</body>
