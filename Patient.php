<?php
require_once 'PatientRecord.php';
require_once 'Insurance.php';
class Patient implements PatientRecord{

    private $_id;
    private $pn;
    private $first;
    private $last;
    private $dob;
    private $insurances = [];

    public function __construct($pn) {
        
        // Query for fetching from the database
        $query = "SELECT * FROM patient WHERE pn = '$pn'";
        // Again the same issue because of Mac
        //  $mysqli = new mysqli('localhost', 'root', 'root', 'test_db')
        $mysqli = new mysqli('localhost', 'root', 'root', 'test_db', 3306, '/Applications/MAMP/tmp/mysql/mysql.sock');

        $result = $mysqli->query($query);

        if ($row = $result->fetch_assoc()) {
            $this->_id = $row['_id'];
            $this->pn = $row['pn'];
            $this->first = $row['first'];
            $this->last = $row['last'];
            $this->dob = $row['dob'];

            // Query for looking for insurances 
            $insuranceQuery = "SELECT _id FROM insurance WHERE patient_id = " . $this->_id;
            $insuranceResult = $mysqli->query($insuranceQuery);
            // For each insurance make a new Insurance 
            while ($insuranceRow = $insuranceResult->fetch_assoc()) {
                $this->insurances[] = new Insurance($insuranceRow['_id']);
            }
        }
        $mysqli->close();
    }


    public function getId() {
        return $this->_id;
    }

    public function getPatientNumber() {
        return $this->pn;
    }

    public function getName() {
        return $this->first . ' ' . $this->last;
    }

    public function getInsurances() {
        return $this->insurances;
    }

    // Prints the insurance table in required format
    public function printInsuranceTable($date) {
        foreach ($this->insurances as $insurance) {
            echo $this->pn . ", " . $this->getName() . ", " . $insurance->getInsuranceName() . ", " . ($insurance->isValid($date) ? "Yes" : "No") . "\n";
        }
    }
}

?>