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

// Perform search if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchId = $_POST['searchId'];

    // Fetch user data based on ID
    $sql = "SELECT * FROM students WHERE S_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $searchId);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Fetch all users if no search is performed
    $sql = "SELECT * FROM students";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <form method="post">
        <label for="searchId">Search by ID:</label>
        <input type="text" id="searchId" name="searchId">
        <button type="submit">Search</button>
    </form>
    <table>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Password</th>
            <th>Date Joined</th>
            <th>Is Admin</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['S_id']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['passwordd']; ?></td>
                <td><?php echo $row['date_entered']; ?></td>
                <td><?php echo $row['is_admin'] ? 'Yes' : 'No'; ?></td>
                <td><a href="delete_user.php?id=<?php echo $row['S_id']; ?>">Delete</a></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
