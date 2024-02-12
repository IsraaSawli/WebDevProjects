<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grading Management System</title>
    <style>
body {
    background-image: url('3966070.jpg');
    background-size: cover;
    background-repeat: no-repeat;
}



/* Button styles */
.btn-container {
    text-align: center;
    margin-top: 50px;
}

.btn-container button {
    padding: 15px 30px; /* Increased padding for the buttons */
    margin: 0 10px;
    font-size: 20px; /* Increased font size for the buttons */
    border: none;
    border-radius: 5px;
    cursor: pointer;
    background-color: #007bff;
    color: white;
}

.btn-container button:hover {
    background-color: #0056b3;
}

/* Content container styles */
.content {
    max-width: 800px;
    margin: 50px auto;
    padding: 30px; /* Increased padding for the content */
    background-color: rgba(255, 255, 255, 0.5);
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
}

.content h2 {
    text-align: center;
    font-size: 30px; /* Increased font size for the headings */
}

.content p {
    text-align: justify;
    font-size: 18px; /* Increased font size for the paragraphs */
}


    </style>
</head>
<body>

    
    <div class="content">
        <h2>Welcome to the Grading Management System</h2>
        <p>
            The Grading Management System is a web-based platform designed to simplify the process of grading and managing student data.
            Whether you're a teacher, administrator, or student, our system provides a user-friendly interface for entering grades, generating reports, and tracking student progress.
        </p>
        <p>
            To get started, please login or sign up for an account using the buttons below. If you're new to the system, signing up is quick and easy!
        </p>
        <div class="btn-container">
            <a href="login.php"><button>Login</button></a>
            <a href="signUp.php"><button>Sign Up</button></a>
        </div>
    </div>
</body>
</html>
