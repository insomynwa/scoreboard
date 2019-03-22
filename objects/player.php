<?php

class Player{

    private $conn;
    private $table_name = "player";

    private $id;     //_[int]
    private $name;   //_[string]
    private $team_id;   //_id[int]
    private $arr_gamedraws = array();
    private $arr_team = array();

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function SetID($id){
        $this->id = $id;
    }

    public function SetName( $name ){
        $this->name = $name;
    }

    public function SetTeamId( $team_id ){
        $this->team_id = $team_id;
    }

    public function CreatePlayer(){
        $sql = "INSERT INTO {$this->table_name} (player_name, team_id) VALUES ('{$this->name}', {$this->team_id})";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    public function GetPlayersByTeamID(){
        $res = array( 'status' => false );
        $query = "SELECT * FROM {$this->table_name} WHERE team_id={$this->team_id}";

        if( $result = $this->conn->query( $query )){
            $i = 0;
            $players = array();
            while($row = $result->fetch_assoc()) {
                $players[$i]['id'] = $row['player_id'];
                $players[$i]['name'] = $row['player_name'];
                $players[$i]['team_id'] = $row['team_id'];

                /* $team = new Team($this->conn);
                $team->SetID( $this->team_id );
                $tempRes = $team->GetTeamByID();
                if( $tempRes['status'] ){
                    $players[$i]['team'] = $tempRes['team'];
                }else{
                    $players[$i]['team'] = array();
                } */

                $i++;
            }

            $res = array(
                'players'      => $players,
                'status'    => true
            );
        }

        return $res;
    }

    public function GetGameDraws(){
        $gamedraws = new GameDraw($this->conn);
        $gamedraws->SetContestantID($this->id);
        $res = $gamedraws->GetGameDrawsByPlayerID();
        if( $res['status'] ){
            $this->arr_gamedraws = $res['gamedraws'];
        }
        return $this->arr_gamedraws;
    }

    public function GetTeam(){
        $team = new Team($this->conn);
        $team->SetID( $this->team_id);
        $res = $team->GetTeamByID();
        if( $res['status'] ){
            $this->arr_team = $res['team'];
        }
        return $this->arr_team;
    }

    public function GetPlayer(){
        $res = array( 'status' => false );
        $query = "SELECT * FROM {$this->table_name}";

        if($result = $this->conn->query( $query )){
            $players = array();
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $players[$i]['id'] = $row['player_id'];
                $players[$i]['name'] = $row['player_name'];
                $players[$i]['team_id'] = $row['team_id'];

                /* $team = new Team($this->conn);
                $team->SetID( $row['team_id'] );
                $tempRes = $team->GetTeamByID();
                if( $tempRes['status'] ){
                    $players[$i]['team'] = $tempRes['team'];
                }else{
                    $players[$i]['team'] = array();
                } */

                $i++;
            }
            $res['status'] = true;
            $res['players'] = $players;
        }

        return $res;
    }

    public function GetPlayerByID(){
        $res = array( 'status' => false );
        $query = "SELECT * FROM " . $this->table_name ." WHERE player_id={$this->id} LIMIT 1";

        if( $result = $this->conn->query( $query ) ){
            $player = array();
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $player['id'] = $row['player_id'];
            $player['name'] = $row['player_name'];
            $player['team_id'] = $row['team_id'];

            /* $team = new Team($this->conn);
            $team->SetID( $row['team_id'] );
            $tempRes = $team->GetTeamByID();
            if( $tempRes['status'] ){
                $player['team'] = $tempRes['team'];
            }else{
                $player['team'] = array();
            } */
            $res['status'] = true;
            $res['player'] = $player;
        }

        return $res;
    }

    public function UpdatePlayer(){
        $query = "Update " . $this->table_name ." SET team_id={$this->team_id}, player_name='{$this->name}' WHERE player_id={$this->id}";

        $result = $this->conn->query( $query );//var_dump($result > 0 );

        $res = array( 'status' => false );

        if ($result){
            $res['status'] = true;
        }
        return $res;
    }

    public function DeletePlayer(){
        $sql = "DELETE FROM {$this->table_name} WHERE player_id={$this->id}";

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