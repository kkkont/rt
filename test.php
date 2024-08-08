<?php
require_once 'Patient.php';


$today = date('m-d-y');

// Again the same issue because of Mac        
// $mysqli = new mysqli('localhost', 'root', 'root', 'test_db')
$mysqli = new mysqli('localhost', 'root', 'root', 'test_db', 3306, '/Applications/MAMP/tmp/mysql/mysql.sock');


if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Query for all the patients
$query = "SELECT pn FROM patient ORDER BY pn";
$result = $mysqli->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Create a Patient object for each patient in the database
        $patient = new Patient($row['pn']);
        // Print the insurance table for todays date
        $patient->printInsuranceTable($today);
    }
    $result->free();
} else {
    echo "Error: " . $mysqli->error;
}

$mysqli->close();
?>
