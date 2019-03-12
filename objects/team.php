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

    public function getATeam( $id ){
        $query = "SELECT * FROM " . $this->table_name . " WHERE tim_id = {$id}";

        $stmt = $this->conn->query( $query );

        return $stmt->fetch_assoc();
    }

    public function getTeams(){
        $query = "SELECT * FROM " . $this->table_name;

        $result = $this->conn->query( $query );

        $teams = null;
        $res = null;

        if( $result->num_rows > 0){
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
        $res = array( 'teams' => array(), 'status' => false );

        return $res;
    }

    public function getTeamById($teamid){
        $query = "SELECT * FROM " . $this->table_name ." WHERE tim_id={$teamid}";

        $result = $this->conn->query( $query );

        $team = null;
        $res = null;

        // var_dump($result);
        if( $result->num_rows > 0){
            // $i = 0;
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            // var_dump($row);die;
            // while($row = $result->fetch_assoc()) {
                $team['id'] = $row['tim_id'];
                $team['name'] = $row['tim_name'];
                $team['logo'] = $row['tim_logo'];
                // $i++;
            // }

            $res = array(
                'team'      => $team,
                'status'    => 'true'
            );

            return $res;
        }
        $res = array( 'team' => '', 'status' => false );

        return $res;
    }
}
?>