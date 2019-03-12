<?php
class Database{

    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "scoreboard_panahan";
    private $username = "root";
    private $password = "";
    public $conn;

    // get the database connection
    public function getConnection(){

        $this->conn = new mysqli( $this->host, $this->username, $this->password, $this->db_name);

        if ($this->conn->connect_error) {
            die("Connection error: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}
?>