<?php

// Since im using Mac then I needed to add this very specific path to connect with MySQL server
$conn = new mysqli('localhost', 'root', 'root', 'test_db', 3306, '/Applications/MAMP/tmp/mysql/mysql.sock');

// You can just uncomment this and I figure it will work for u well :) 
// $conn = new mysqli('localhost', 'root', 'root', 'test_db')

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

# Displaying the patient details 
$sql = "SELECT patient.pn, patient.last, patient.first, insurance.iname, 
            DATE_FORMAT(insurance.from_date, '%m-%d-%y') as from_date, 
            DATE_FORMAT(insurance.to_date, '%m-%d-%y') as to_date 
        FROM patient 
        JOIN insurance ON patient._id = insurance.patient_id
        ORDER BY insurance.from_date, patient.last";

$result = $conn->query($sql);

// Display the results from the query
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo $row["pn"]. ", " . $row["last"]. ", " . $row["first"]. ", " . $row["iname"]. ", " . $row["from_date"]. ", " . $row["to_date"]. "\n";
    }
} else {
    echo "No results";
}

# Character statistics
$sql = "SELECT first, last FROM patient"; // Selecting all the first and last names
$result = $conn->query($sql);

$letters = []; // An array to hold all the letters counts

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $fullName = $row["first"] . $row["last"]; // Merge first and last name
        $fullName = preg_replace("/[^A-Za-z]/", "", $fullName); // Removing non alphabetic characters
        $fullName = strtoupper($fullName); // Turning it into uppercase

        // Split the full name into individual characters
        foreach (str_split($fullName) as $char) {
            if (isset($letters[$char])) {
                $letters[$char]++; // Raise the count of existing letter
            } else {
                $letters[$char] = 1; // Set count to 1 for new letter
            }
        }
    } 
}else {
    echo "No results";
}


$totalLetters = array_sum($letters); // Count all the letters in the array

ksort($letters); // Sort the letters alphabetically

foreach ($letters as $char => $count) {
    $percentage = ($count / $totalLetters) * 100; // Calculate the percentage
    echo "$char\t$count\t" . number_format($percentage, 2) . " %\n"; // Display the letter, the count and the percentage
}

$conn->close();
?>