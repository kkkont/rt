<?php
require_once 'PatientRecord.php';

class Insurance implements PatientRecord {
    private $_id;
    private $patient_id;
    private $iname;
    private $from_date;
    private $to_date;

    public function __construct($id) {

        // Again the same issue because of Mac
        // $mysqli = new mysqli('localhost', 'root', 'root', 'test_db')
        $mysqli = new mysqli('localhost', 'root', 'root', 'test_db', 3306, '/Applications/MAMP/tmp/mysql/mysql.sock');


        // Fetch the insurance record based on the id
        $query = "SELECT * FROM insurance WHERE _id = $id";
        $result = $mysqli->query($query);

        if ($row = $result->fetch_assoc()) {
            $this->_id = $row['_id'];
            $this->patient_id = $row['patient_id'];
            $this->iname = $row['iname'];
            $this->from_date = $row['from_date'];
            $this->to_date = $row['to_date'];
        }

        $mysqli->close();
    }

    public function getId() {
        return $this->_id;
    }

    public function getPatientNumber() {
        return $this->patient_id;
    }

    public function getInsuranceName() {
        return $this->iname;
    }

    // This method checks if the insurance is valid or not
    public function isValid($date) {
        // First we convert provided date to correct format
        $date = DateTime::createFromFormat('m-d-y', $date)->format('Y-m-d');

        // If the to_date is null
        if (!$this->to_date) {
            // Check if the from_date is before the provided date
            return $date >= $this->from_date;
        } else {
            // Check if the provided date falls within the range
            return $date >= $this->from_date && $date <= $this->to_date;
        }
    }
}
?>
