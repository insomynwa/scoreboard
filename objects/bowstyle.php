<?php

class Bowstyle{

    private $conn;
    private $table_name = "bowstyles";

    private $id;     //_[int]
    private $name;   //_[string]

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function SetID( $id ){
        $this->id = $id;
    }

    public function SetName( $name ){
        $this->name = $name;
    }

    public function GetBowstyles(){
        $query = "SELECT * FROM " . $this->table_name;

        $result = $this->conn->query( $query );

        $res = array( 'bowstyles' => array(), 'status' => $result->num_rows > 0 );

        if( $res['status'] ){
            $i = 0;
            $bowstyles = null;
            while($row = $result->fetch_assoc()) {
                $bowstyles[$i]['id'] = $row['bowstyle_id'];
                $bowstyles[$i]['name'] = $row['bowstyle_name'];

                $i++;
            }

            $res = array(
                'bowstyles'      => $bowstyles,
                'status'    => true
            );
        }

        return $res;
    }

    public function GetBowstyleByID(){
        $res = array ( 'status' => false );
        $query = "SELECT * FROM {$this->table_name} WHERE bowstyle_id={$this->id}";

        if( $result = $this->conn->query( $query ) ){

            $bowstyle = array();
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $bowstyle['id'] = $row['bowstyle_id'];
            $bowstyle['name'] = $row['bowstyle_name'];

            $res['status'] = true;
            $res['bowstyle'] = $bowstyle;
        }

        return $res;
    }

    public function CreateDefaultBowstyle(){
        $sql = "INSERT INTO {$this->table_name} (bowstyle_id,bowstyle_name) VALUES ({$this->id}, '{$this->name}')";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    public function CountBowstyle(){
        $sql = "SELECT COUNT(*) as nBowstyle FROM {$this->table_name}";

        $result = $this->conn->query( $sql );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        return $row['nBowstyle'];
    }
}
?>