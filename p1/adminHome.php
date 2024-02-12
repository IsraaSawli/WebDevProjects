<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home Page</title>
    <style>
        /* Apply CSS styles to buttons */
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            border: 2px solid #007bff;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .button:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        /* Apply CSS styles to (admin) text */
        .admin-text {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Welcome Admin <span class="admin-text">(admin)</span></h1>
    <!-- Buttons for searching and adding grades -->
    <a href="search.php" class="button">Search</a>
    <a href="private.php" class="button">Add Grades</a>
    <a href="user.php" class="button">Manage Users</a>
</body>
</html>
