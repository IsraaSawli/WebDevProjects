<?php
session_start();
include 'selectdb.php';

$studentId = isset($_SESSION["studentId"]) ? $_SESSION["studentId"] : null;

if (!$studentId) {
    echo "Error: Student ID not found in session.";
    header('Location: main.php');
    exit();
}


$selectStudentQuery = "SELECT * FROM Students WHERE Stid = $studentId";
$resultStudent = mysqli_query($dbc, $selectStudentQuery);

if ($resultStudent && $rowStudent = mysqli_fetch_assoc($resultStudent)) {
    $sname = $rowStudent["Sname"];
    $dateOfBirth = $rowStudent["DOB"];
    $level = $rowStudent["level"];

    // Fetch instruments chosen by the student
    $selectInstrumentsQuery = "SELECT Instruments.Iname, Instruments.price, Instruments.Sdate, Instruments.Edate
                               FROM Instruments
                               JOIN registedStudents ON Instruments.Iid = registedStudents.Iid
                               WHERE registedStudents.Stid = $studentId";
    $resultInstruments = mysqli_query($dbc, $selectInstrumentsQuery);

    if (!$resultInstruments) {
        echo "Error fetching instruments: " . mysqli_error($dbc);
        // Redirect to main.php or another appropriate page
        header('Location: main.php');
        exit();
    }
} else {
    echo "Error fetching student details: " . mysqli_error($dbc);
    
    // Redirect to main.php or another appropriate page
    header('Location: main.php');
    exit();
}

// Calculate total price
$totalPrice = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <title>Welcome</title>
    <style>
        body {
            font-family: system-ui;
            background-color: #eaf6f6;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        h1, h2 {
            margin: 10px 0;
            font-size: 2.3em;
            color:#118a7e ;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 2px solid #118a7e;
            padding: 10px;
            text-align: left;
           
        }

        th, td {
            background: #93e4c1;
            color: #118a7e;
            font-size:1.4em;
           
        }

        .total-price {
            font-weight: bold;
            font-size: 1.8em;
            color:#118a7e;
        }

        .confirmation {
            background: #93e4c1;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
            font-size:1.4em;
            font-weight:bold;
            color:#01352c;
        }

        a {
            color: #118a7e;
            text-decoration: none;
            border: 2px solid #118a7e;
            padding: 10px 20px;
            border-radius: 5px;
            background: #93e4c1;
            margin-top: 20px;
            margin-bottom: 20px;
            display: inline-block;
            font-size: 1.2em;
        }

        a:hover {
            background: #118a7e;
            color: #93e4c1;
            
        }

        .label {
            color:#118a7e;
            font-size:2em;
            text-decoration: underline;
        }

        .data-submitted {
            color: #01352c; 
            font-size:1.8em;
            font-weight:bold;
        }
    </style>
</head>
<body>
    <h1>Welcome <span class="student-name data-submitted"><?php echo $sname; ?></span>!</h1>
    <h2>Your Details:</h2>
    <p><span class="label">Date of Birth:</span> <span class="data-submitted"><?php echo $dateOfBirth; ?></span></p>
    <p><span class="label">Level:</span> <span class="data-submitted"><?php echo $level; ?></span></p>

    <?php if (isset($resultInstruments) && mysqli_num_rows($resultInstruments) > 0) { ?>
        <h2>Instruments Chosen:</h2>
        <table border="1">
            <tr>
                <th>Instrument</th>
                <th>Price</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
            <?php while ($rowInstrument = mysqli_fetch_assoc($resultInstruments)) {
                $totalPrice += $rowInstrument['price']; ?>
                <tr>
                    <td><?php echo $rowInstrument['Iname']; ?></td>
                    <td><?php echo $rowInstrument['price']; ?></td>
                    <td><?php echo $rowInstrument['Sdate']; ?></td>
                    <td><?php echo $rowInstrument['Edate']; ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p class="no-instruments data-submitted">No instruments chosen.</p>
    <?php } ?>

    <p class="total-price">Total Price: <?php echo $totalPrice; ?>$</p>

    
    <div class="confirmation">&check; Your data has been added successfully.</div>

    <a href="main.php">Back</a>

</body>
</html>
