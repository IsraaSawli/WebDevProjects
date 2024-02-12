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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchType = $_POST['searchType'];
    $searchValue = $_POST['searchValue'];

    // Perform search based on the selected search type
    if ($searchType === 'id') {
        // Search by student ID
        $sql = "SELECT * FROM marks WHERE student_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $searchValue);
    } elseif ($searchType === 'subject') {
        // Search by subject
        $sql = "SELECT * FROM marks WHERE subject = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $searchValue);
    }

    $stmt->execute();
    $result = $stmt->get_result();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Marks</title>
    <style>
      body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f2f2f2;
}

h1 {
    text-align: center;
    margin-top: 20px;
}

form {
    text-align: center;
    margin-top: 20px;
}

label, select, input[type="text"], button {
    margin: 5px;
}
body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
           
        }

table {
    width: 80%;
    margin: 20px auto;
    border-collapse: collapse;
    background-color: #fff;
    border: 1px solid #ddd;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

th {
    background-color: #f2f2f2;
}

td a {
    text-decoration: none;
    color: blue;
}

td a:hover {
    text-decoration: underline;
}


    </style>
</head>
<body>
<div class="background-container"></div>
    <h1>Search Marks</h1>
    <form method="post">
        <label for="searchType">Search By:</label>
        <select name="searchType" id="searchType">
            <option value="id">Student ID</option>
            <option value="subject">Subject</option>
        </select>
        <input type="text" name="searchValue" placeholder="Enter search value">
        <button type="submit">Search</button>
    </form>
    
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") { ?>
        
        <table>
            <tr>
                <th>Student ID</th>
                <th>Subject</th>
                <th>Marks</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['student_id']; ?></td>
                    <td><?php echo $row['subject']; ?></td>
                    <td><?php echo $row['marks']; ?></td>
                    <td><a href="edit.php?id=<?php echo $row['student_id']; ?>&subject=<?php echo $row['subject']; ?>">Edit</a></td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>
</body>
</html>

<?php
// Close statement and database connection


?>
