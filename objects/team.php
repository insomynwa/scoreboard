<?php

class Team{

    private $conn;
    private $table_name = "team";

    private $id;            //_[int]
    private $logo;          //_[string]
    private $name;          //_[string]
    private $initial;       //_[string][3]
    private $description;   //_[int]
    private $arr_players = array();
    private $arr_gamedraws = array();

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function SetID($teamid){
        $this->id = $teamid;
    }

    public function GetGameDraws(){
        $gamedraws = new GameDraw($this->conn);
        $gamedraws->SetContestantID($this->id);
        $res = $gamedraws->GetGameDrawsByTeamID();
        if( $res['status'] ){
            $this->arr_gamedraws = $res['gamedraws'];
        }
        return $this->arr_gamedraws;
    }

    public function GetPlayers(){
        $players = new Player($this->conn);
        $players->SetTeamId($this->id);
        $res = $players->GetPlayersByTeamID();
        if( $res['status'] ){
            $this->arr_players = $res['players'];
        }
        return $this->arr_players;
    }

    public function GetTeam(){
        $res = array( 'status' => false );
        $query = "SELECT * FROM " . $this->table_name;

        if( $result = $this->conn->query( $query ) ){
            $teams = array();
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $teams[$i]['id'] = $row['team_id'];
                $teams[$i]['logo'] = $row['team_logo'];
                $teams[$i]['name'] = $row['team_name'];
                $teams[$i]['initial'] = $row['team_initial'];
                $teams[$i]['desc'] = $row['team_desc'];
                $i++;
            }

            $res = array(
                'teams'      => $teams,
                'status'    => true
            );
        }

        return $res;
    }

    public function GetTeamByID(){
        $res = array( 'status' => false );
        $query = "SELECT * FROM {$this->table_name} WHERE team_id={$this->id} LIMIT 1";

        if( $result = $this->conn->query( $query ) ){

            $team = array();
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

    public function DeleteTeam(){
        $sql = "DELETE FROM {$this->table_name} WHERE team_id={$this->id}";

        $res = array( 'status' => false );//var_dump($this->timer, $this->point, $this->desc, $this->id);
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }
}
?>