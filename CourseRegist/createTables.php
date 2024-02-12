<?php 
include "selectdb.php";

$queryCreateTable1 = 'CREATE TABLE IF NOT EXISTS Students (
    Stid INT AUTO_INCREMENT PRIMARY KEY,
    Sname VARCHAR(255) NOT NULL,
    DOB date NOT NULL,
    level VARCHAR(50) NOT NULL
);';

$queryCreateTable2 = 'CREATE TABLE IF NOT EXISTS Instruments (
    Iid INT AUTO_INCREMENT PRIMARY KEY,
    Iname VARCHAR(255) NOT NULL,
    Price INT NOT NULL,
    Sdate date NOT NULL,
    Edate date NOT NULL,
    maxNum int NOT NULL
);';

$queryCreateTable3 = 'CREATE TABLE IF NOT EXISTS registedStudents (
    Iid INT,
    Stid INT,
    FOREIGN KEY(Iid) REFERENCES Instruments(Iid),
    FOREIGN KEY(Stid) REFERENCES Students(Stid),
    PRIMARY KEY(Iid, Stid)
);';

if (!mysqli_query($dbc, $queryCreateTable1) || 
    !mysqli_query($dbc, $queryCreateTable2) ||
    !mysqli_query($dbc, $queryCreateTable3)) {
    $str = '<p style="color: red;">Could not create the table because:<br />';
    $str .= mysqli_error($dbc);
    $str .= '.</p>';
    echo $str;
    exit; 
} else {
    echo '<p style="color: green;">Successfully created the tables.</p>';
}

// Array of instruments to be inserted
$instrumentsData = [
    ["oud", 250, "2024-02-01", "2024-03-01", 30],
    ["trumpet", 200, "2024-03-10", "2024-04-10", 30],
    ["zither", 800, "2024-02-01", "2024-03-07", 30],
    ["flute", 700, "2024-02-01", "2024-03-09", 30],
    ["violin", 250, "2024-02-01", "2024-03-11", 30],
    ["tambourine", 200, "2024-02-01", "2024-03-01", 30],
    ["lute", 250, "2024-04-01", "2024-05-01", 30],
    ["dulcimer", 200, "2024-05-01", "2024-06-01", 30],
    ["piano", 800, "2024-06-01", "2024-06-30", 30]
];

foreach ($instrumentsData as $instrument) {
    $instrumentName = $instrument[0];

    // Check if the instrument already exists
    $checkInstrumentQuery = "SELECT Iid FROM Instruments WHERE Iname = '$instrumentName'";
    $resultCheckInstrument = mysqli_query($dbc, $checkInstrumentQuery);

    if (!$resultCheckInstrument || mysqli_num_rows($resultCheckInstrument) == 0) {
        // Instrument doesn't exist, insert it
        $insert = "INSERT INTO Instruments(Iname, Price, Sdate, Edate, maxNum) VALUES ('{$instrument[0]}', {$instrument[1]}, '{$instrument[2]}', '{$instrument[3]}', {$instrument[4]})";

        if (!mysqli_query($dbc, $insert)) {
            $str = '<p style="color: red;">Could not insert data into the table because:<br />';
            $str .= mysqli_error($dbc);
            $str .= '.</p>';
            echo $str;
            exit;
        } else {
            echo '<p style="color: green;">Successfully inserted data into the table.</p>';
        }
    } else {
        echo "<p style='color: orange;'>Instrument '$instrumentName' already exists(will not be reinserted).</p>";
    }
}

?>
