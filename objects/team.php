<?php

class Team{

    private $conn;
    private $table_name = "tim";

    public $id;
    public $name;
    public $logo;

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function countTeams(){
        $sql = "SELECT COUNT(*) as nTeams FROM " . $this->table_name;

        $result = $this->conn->query( $sql );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        return $row['nTeams'];
    }

    public function getTeams(){
        $query = "SELECT * FROM " . $this->table_name;

        $result = $this->conn->query( $query );

        $res = array( 'teams' => array(), 'status' => false );

        if( $result->num_rows > 0){
            $teams = null;
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $teams[$i]['id'] = $row['tim_id'];
                $teams[$i]['logo'] = $row['tim_logo'];
                $teams[$i]['name'] = $row['tim_name'];
                $i++;
            }

            $res = array(
                'teams'      => $teams,
                'status'    => 'true'
            );

            return $res;
        }

        return $res;
    }

    public function getTeamById($teamid){
        $query = "SELECT * FROM " . $this->table_name ." WHERE tim_id={$teamid}";

        $result = $this->conn->query( $query );

        $res = array( 'team' => array(), 'status' => false );

        if( $result->num_rows > 0){

            $team = null;
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $team['id'] = $row['tim_id'];
            $team['name'] = $row['tim_name'];
            $team['logo'] = $row['tim_logo'];

            $res = array(
                'team'      => $team,
                'status'    => true
            );
        }

        return $res;
    }
}
?>