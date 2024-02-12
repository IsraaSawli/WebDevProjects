<?php
session_start();
include 'selectdb.php';

// Check if the form is submitted and the "submit" button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm'])) {
    $dateOfBirth = $_SESSION["date"];
    $level =  $_SESSION["level"];
    $selectedInstruments = isset($_SESSION["Iname"]) ? $_SESSION["Iname"] : [];

    // Store student name in session
    $_SESSION["sname"] = "israa"; 
    $sname = $_SESSION["sname"];

    // Check if the number of registered students exceeds the maximum allowed for any instrument
    $invalidInstruments = [];

    foreach ($selectedInstruments as $instrumentName) {
        $selectMaxNumQuery = "SELECT maxNum FROM Instruments WHERE Iname = '$instrumentName'";
        $resultMaxNum = mysqli_query($dbc, $selectMaxNumQuery);

        if ($resultMaxNum && $rowMaxNum = mysqli_fetch_assoc($resultMaxNum)) {
            $maxNum = $rowMaxNum["maxNum"];

            $selectNumRegisteredQuery = "SELECT COUNT(*) as numRegistered FROM registedStudents
                                         JOIN Instruments ON registedStudents.Iid = Instruments.Iid
                                         WHERE Instruments.Iname = '$instrumentName'";
            $resultNumRegistered = mysqli_query($dbc, $selectNumRegisteredQuery);

            if ($resultNumRegistered && $rowNumRegistered = mysqli_fetch_assoc($resultNumRegistered)) {
                $numRegistered = $rowNumRegistered["numRegistered"];

                if ($numRegistered >= $maxNum) {
                    $invalidInstruments[] = $instrumentName;
                }
            }
        }
    }

    if (!empty($invalidInstruments)) {
        echo '<p class="error-message"><span>&#9888;</span>
        Sorry, the maximum number of students reached for the following instrument(s): ' . implode(", ", $invalidInstruments) . '. Please choose another instrument.</p>';
    } else {
        // Insert data into Students table
        $insertStudentQuery = "INSERT INTO Students (Sname, DOB, level) VALUES ('$sname', '$dateOfBirth', '$level')";
        $resultInsertStudent = mysqli_query($dbc, $insertStudentQuery);

        // Check if data insertion was successful
        if ($resultInsertStudent) {
            $studentId = mysqli_insert_id($dbc); // retrieve the newly inserted student's ID

            // Insert selected instruments into registedStudents table
            foreach ($selectedInstruments as $instrumentName) {
                $selectInstrumentIdQuery = "SELECT Iid FROM Instruments WHERE Iname = '$instrumentName'";
                $resultInstrumentId = mysqli_query($dbc, $selectInstrumentIdQuery);

                if ($resultInstrumentId && $row = mysqli_fetch_assoc($resultInstrumentId)) {
                    $instrumentId = $row["Iid"];
                    $insertRegistedStudentsQuery = "INSERT INTO registedStudents (Iid, Stid) VALUES ($instrumentId, $studentId)";
                    mysqli_query($dbc, $insertRegistedStudentsQuery);
                }
            }

            $_SESSION["studentId"] = $studentId; // Store the new student ID in session
            echo "Data inserted successfully!";
            header('Location: welcome.php');
            exit();
        } else {
            echo "Error inserting data into Students table: " . mysqli_error($dbc);
        }
    }
}

?>

<head>
    <title>Registration System</title>
    <style>
       span {
        font-size:2em; font-weight: bold; margin-left:10px;
}

        body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 100vh;
            background-color: #eaf6f6;
            margin: 0;
            font-family: system-ui;
        }

        #formContainer,
        #tableContainer {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
        }

        #myForm {
            width: 500px;
            background: #93e4c1;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 60px;
            text-align: center;
            margin-bottom: 20px;
            color: #118a7e;
        }

        label {
            margin-left: 10px;
            font-size: 1.5em;
            font-weight: bold;
            color: #118a7e;
        }

        input[type="text"],
        input[type="date"],
        select,
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 1px solid #1f6f78;
            border-radius: 4px;
            background-color: #118a7e;
            color: white;
            font-size: 1.2em;
            font-weight: bold;
        }

        input[type="submit"] {
            background-color: #118a7e;
            color: white;
            font-size: 1.2em;
            font-weight: bold;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #6db193;
        }

        input[type="checkbox"] {
            margin-right: 5px;
            transform: scale(1.3);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 80px;
        }

        table, th, td {
            border: 10px solid #93e4c1;
            padding: 10px;
            text-align: left;
            background: #118a7e;
            color: white;
            font-weight: bold;
            font-size: 1em;
        }

        .instrument-name {
            font-size: 25px;
            color: #118a7e;
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
        }

       
        .error-message {
            color: white; 
            font-size: 1.3em;
            margin-top: 10px;
            background-color:red;
            padding-bottom:10px;
        }
    </style>
</head>
<body>
    <div id="formContainer">
        <form method="post" action="main.php" id="myForm">
            <h1>Regist</h1>
            <label for="date">Date of Birth:</label>
            <input type="date" name="date" id="date"><br><br>
            <label for="level">Level:</label>
            <select name="level" id="level">
                <option value="beginner">Beginner</option>
                <option value="intermediate">Intermediate</option>
                <option value="advanced">Advanced</option>
            </select><br><br>
            <label>Instruments:</label> <br><br>
            <?php
            $selectByNameQuery = "SELECT Iname FROM Instruments";
            $resultByName = mysqli_query($dbc, $selectByNameQuery);

            if ($resultByName) {
                while ($row = mysqli_fetch_assoc($resultByName)) {
                    echo '<label><input type="checkbox" name="Iname[]" value="' . $row['Iname'] . '"><span class="instrument-name">' . $row['Iname'] . '</span></label><br>';
                }
            }
            ?>

            <input type="submit" name="show" value="show">
            <?php if (isset($_POST['show'])) echo '<input type="submit" name="confirm" value="Confirm">'; ?>
        </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['show'])) {
        $_SESSION["date"] = $_POST["date"];
        $_SESSION["level"] = $_POST["level"];
        $_SESSION["Iname"] = isset($_POST["Iname"]) ? $_POST["Iname"] : [];
    
        $instrumentsChosen = isset($_POST['Iname']) ? $_POST['Iname'] : [];
    
        // Display selected instruments in a table
        if (!empty($instrumentsChosen)) {
            echo '<table class= "table-container" border="1">';
            echo '<tr><th>Instrument</th><th>Price</th><th>Start Date</th><th>End Date</th></tr>';
    
            foreach ($instrumentsChosen as $i) {
                $sql = "SELECT Iname, price, Sdate, Edate FROM Instruments WHERE Iname='$i'";
                $result = mysqli_query($dbc, $sql);
    
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>' . $row['Iname'] . '</td>';
                        echo '<td>' . $row['price'] . '</td>';
                        echo '<td>' . $row['Sdate'] . '</td>';
                        echo '<td>' . $row['Edate'] . '</td>';
                        echo '</tr>';
                    }
                }
            }
    
            echo '</table>';
        }
    }
    ?>

</body>
</html>