<?php
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

if(isset($_POST['submit2'])){
    $id = isset($_POST['sid']) ? $_POST['sid'] : '';
    $mathGrade = isset($_POST['math-grade']) ? $_POST['math-grade'] : '';
    $physicsGrade = isset($_POST['physics-grade']) ? $_POST['physics-grade'] : '';
    $englishGrade = isset($_POST['english-grade']) ? $_POST['english-grade'] : '';
    $biologyGrade = isset($_POST['biology-grade']) ? $_POST['biology-grade'] : '';
    $chemistryGrade = isset($_POST['chemistry-grade']) ? $_POST['chemistry-grade'] : '';

    // Prepare and execute SQL statement to insert grades into the marks table
    $sql = "INSERT INTO marks (student_id, subject, marks) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Bind parameters and execute for each subject
    $stmt->bind_param("iss", $id, $subject, $grade);

    $subject = 'Math';
    $grade = $mathGrade;
    $stmt->execute();

    $subject = 'Physics';
    $grade = $physicsGrade;
    $stmt->execute();

    $subject = 'English';
    $grade = $englishGrade;
    $stmt->execute();

    $subject = 'Biology';
    $grade = $biologyGrade;
    $stmt->execute();

    $subject = 'Chemistry';
    $grade = $chemistryGrade;
    $stmt->execute();

    // Check for errors
    if ($stmt->error) {
        echo "Error inserting grades: " . $stmt->error;
    } 
    

    // Close statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grading Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('i4.jpg');
            background-size: cover;
        }

        

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 40px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input[type="text"] {
            width: calc(100% - 10px);
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-top: 5px;
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            border-radius: 5px;
            border: none;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            margin-top: 20px;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }
        
    </style>
</head>
<body>



<div class="container">
    <h2>Add Grades</h2>
    <form action="private.php" method="post" id="gradeForm">
        <div class="form-group">
            <label for="student">Enter Student ID:</label>
            <input type="text" name="sid" placeholder="Student ID" required>
        </div>
        <div class="form-group">
            <label>Select Subjects:</label>
            <div class="subject-options">
                <label for="math">Math:</label>
                <input type="text" id="math-grade" name="math-grade" placeholder="Enter Math Grade" required>
            </div>
            <div class="subject-options">
                <label for="physics">Physics:</label>
                <input type="text" id="physics-grade" name="physics-grade" placeholder="Enter Physics Grade" required>
            </div>
            <div class="subject-options">
                <label for="english">English:</label>
                <input type="text" id="english-grade" name="english-grade" placeholder="Enter English Grade" required>
            </div>
            <div class="subject-options">
                <label for="biology">Biology:</label>
                <input type="text" id="biology-grade" name="biology-grade" placeholder="Enter Biology Grade" required>
            </div>
            <div class="subject-options">
                <label for="chemistry">Chemistry:</label>
                <input type="text" id="chemistry-grade" name="chemistry-grade" placeholder="Enter Chemistry Grade" required>
            </div>
        </div>
        <input type="submit" class="btn-submit" name="submit2" value="Submit">
    </form>
  
</div>

</body>
</html>
