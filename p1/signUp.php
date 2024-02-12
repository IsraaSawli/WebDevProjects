<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

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

// Select the database
if (!mysqli_select_db($conn, $dbname)) {
    die("Database selection failed: " . mysqli_error($conn));
}

$emailError = "";
$passwordError = "";
$correctInsert = "";

if(isset($_POST["submit"])) {
    $email = isset($_POST["email"]) ? $_POST["email"] : null;
    $pass = isset($_POST["password"]) ? $_POST["password"] : null;

    // Check if both email and password are not empty before proceeding
    if ($email && $pass) {
        // Check if the email already exists in the database
        $checkQuery = "SELECT * FROM students WHERE email = ?";
        $stmtCheck = $conn->prepare($checkQuery);
        $stmtCheck->bind_param("s", $email);
        $stmtCheck->execute();
        $result = $stmtCheck->get_result();
        
        if ($result->num_rows > 0) {
            $emailError = "Error: This email is already registered.";
        } else {
            // Password strength regex pattern
            $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";

            // Check if the password matches the pattern
            if (preg_match($passwordPattern, $pass)) {
                // Password is strong, proceed with insertion
                $insertQuery = "INSERT INTO students (email, passwordd, date_entered) VALUES (?, ?, NOW())";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param("ss", $email, $pass);

                // Execute the query
                if ($stmt->execute()) {
                    // Fetch the auto-incremented ID of the inserted user
                    $userId = $stmt->insert_id;
                    $correctInsert = "Your email and password are saved. Your ID is $userId.";
                    // Redirect to login page with message
                    header("location: login.php?message=" . urlencode($correctInsert));
                    exit(); // Ensure script execution stops after redirect
                } else {
                    $str = '<p style="color: red;">Could not insert data because:<br />';
                    $str .= $conn->error;
                    $str .= '.</p><p>The query being run was:' . $insertQuery . '</p>';
                    print $str;
                }

                // Close prepared statement
                $stmt->close();
            } else {
                $passwordError = "Error: Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
            }
        }

        // Close prepared statement
        $stmtCheck->close();
    } else {
        $emailError = "Error: Email is required.";
        $passwordError = "Error: Password is required.";
    }
}
?>

<head>
<title>Sign Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
    max-width: 700px;
    margin: 50px auto;
    padding: 60px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: rgba(249, 249, 249, 0.5); /* Adjust the opacity by changing the last value */
}

        .container h2 {
            padding-top:0px;
            margin-top:0px;
            text-align: center;
            font-size:2em;
            color:#007bff;
            font-family: system-ui;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .error {
            color: red;
            font-size: 1.2em;
            margin-top: 5px;
        }
        .correct {
            color: green;
            font-weight: bold;
        }
        .btn-submit {
            width: 100%;
            padding: 15px;
            margin-top: 15px;
            font-size: 16px;
            border-radius: 5px;
            border: none;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }
        .btn-submit:hover {
            background-color: #0056b3;
        }
        body {
    font-family: Arial, sans-serif;
    background-position-y: -125px;
    background-image: url('lock.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: scroll; /* Scrollable background */
    display: flex;
    justify-content: center; /* Center the container horizontally */
    align-items: center; /* Center the container vertically */
    height: 100vh; /* Ensure the body takes up the full height of the viewport */
}
    </style>
</head>
<body>
   
    <div class="container">
        <h2>Sign Up</h2>
        <form action="signUp.php" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <?php if(isset($emailError)) echo "<p class='error'>$emailError</p>"; ?>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <?php if(isset($passwordError)) echo "<p class='error'>$passwordError</p>"; ?>
            </div>
            <button type="submit" class="btn-submit" name="submit">Sign Up</button>
            <?php if(isset($correctInsert)) echo "<p class='correct'>$correctInsert</p>";?>
        </form>
    </div>
</body>
</html>
