<?php
session_start();

$emailError = "";
$passwordError = "";
$loginError = "";
$loginSuccess = false; // Variable to indicate successful login

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

if(isset($_POST["submit"])) {
    $email = isset($_POST["email"]) ? $_POST["email"] : null;
    $pass = isset($_POST["password"]) ? $_POST["password"] : null;

    // Check if both email and password are not empty before proceeding
    if ($email && $pass) {
        // Query the database to find a record that matches the entered email
        $query = "SELECT * FROM students WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a matching record is found
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Compare the password entered by the user with the password stored in the database
            if ($pass === $row['passwordd']) {
                // Passwords match, user is logged in
                $loginSuccess = true;
                $_SESSION['id']=$row['S_id'];
                if ($row['email'] === 'admin@example.com') {
                    // Redirect admin to private.php
                    header("location: adminHome.php");
                    exit();
                } else {
                    // Redirect regular user to index.php
                    header("location: index.php");
                    exit();
                }
            } else {
                // Passwords do not match, display error message
                $loginError = "Error: Incorrect email or password.";
            }
        } else {
            // No matching record found for the entered email
            $loginError = "Error: Email not found.";
        }

        // Close prepared statement
        $stmt->close();
    } else {
        // Both email and password are required fields
        $emailError = "Error: Email is required.";
        $passwordError = "Error: Password is required.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
       
       .container {
    max-width: 400px;
    margin: 50px auto;
    padding: 40px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add box shadow for depth */
}

.container h2 {
    text-align: center;
    margin-bottom: 20px; /* Add margin below heading */
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold; /* Make labels bold */
}

.form-group input {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border-radius: 5px;
    border: 1px solid #ccc;
    transition: border-color 0.3s; /* Add transition effect for smoother hover */
}

/* Add hover effect for input fields */
.form-group input:hover {
    border-color: #007bff; /* Change border color on hover */
}

.error {
    color: red;
    font-size: 1.2em;
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
    transition: background-color 0.3s; /* Add transition effect for smoother hover */
}

/* Add hover effect for submit button */
.btn-submit:hover {
    background-color: #0056b3; /* Change background color on hover */
}
      
body {
    font-family: Arial, sans-serif;
    background-position-y: -80px;
    background-image: url('7559125.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: scroll; /* Scrollable background */
    display: flex;
    justify-content: center; /* Center the container horizontally */
    align-items: center; /* Center the container vertically */
    height: 100vh; /* Ensure the body takes up the full height of the viewport */
}
.back-home {
    color: #007bff;
    text-decoration: none;
    font-size: 15px;
    padding-left:15px;
    
}

.back-home:hover {
    
    color: purple;
}


    </style>
</head>
<body>
    
    <div class="container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <?php if(isset($emailError)) echo "<p class='error'>$emailError</p>"; ?>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <?php if(isset($passwordError)) echo "<p class='error'>$passwordError</p>"; ?>
            </div>
            <button type="submit" class="btn-submit" name="submit">Login</button>
            <?php if($loginSuccess) echo "<p class='success'>Login successful!</p>"; ?>
            <?php if(isset($loginError) && !$loginSuccess) echo "<p class='error'>$loginError</p>"; ?>
        </form>
        <a href="home.php" class="back-home">Back Home</a>

    </div>
</body>
</html>
