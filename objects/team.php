<?php

class Team{

    private $conn;
    private $table_name = "team";

    private $id;            //_[int]
    private $logo;          //_[string]
    private $name;          //_[string]
    private $initial;       //_[string][3]
    private $description;   //_[int]

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function SetID($teamid){
        $this->id = $teamid;
    }

    public function GetTeams(){
        $query = "SELECT * FROM " . $this->table_name;

        $result = $this->conn->query( $query );

        $res = array(
            'teams' => array(),
            'status' => $result->num_rows > 0
        );

        if( $res['status'] ){
            $teams = null;
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $teams[$i]['id'] = $row['team_id'];
                $teams[$i]['logo'] = $row['team_logo'];
                $teams[$i]['name'] = $row['team_name'];
                $teams[$i]['initial'] = $row['team_initial'];
                $teams[$i]['desc'] = $row['team_desc'];
                $i++;
            }

            $res['teams']= $teams;
        }

        return $res;
    }

    public function GetTeamByID(){
        $query = "SELECT * FROM " . $this->table_name ." WHERE team_id={$this->id}";

        $result = $this->conn->query( $query );//var_dump($result > 0 );

        $res = array( 'team' => array(), 'status' => $result->num_rows > 0 );

        if( $res['status'] ){

            $team = null;
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $team['id'] = $row['team_id'];
            $team['logo'] = $row['team_logo'];
            $team['name'] = $row['team_name'];
            $team['initial'] = $row['team_initial'];
            $team['desc'] = $row['team_desc'];

            $res = array(
                'team'      => $team,
                'status'    => true
            );
        }

        return $res;
    }
/*
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
 */
}
?>