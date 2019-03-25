<?php

class GameDraw{

    private $conn;
    private $table_name = "gamedraw";

    private $id;            //_[int]
    private $num;           //_[int]
    private $gamemode_id;      //_id_[int]
    private $contestant_id;   //_id_[int]
    private $gamestatus_id;    //_id_[int]
    private $contestant_a_id;   //_id_[int]
    private $contestant_b_id;   //_id_[int]
    private $arr_gamesets = array();
    private $arr_gamemode = array();
    private $arr_contestant_a = array();
    private $arr_contestant_b = array();
    private $arr_gamestatus = array();

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function SetID( $id ){
        $this->id = $id;
    }

    public function SetNum( $num ){
        $this->num = $num;
    }

    public function SetGameModeID( $gamemode_id ){
        $this->gamemode_id = $gamemode_id;
    }

    public function SetContestantID( $contestant_id ){
        $this->contestant_id = $contestant_id;
    }

    public function SetContestantAID( $contestant_a_id ){
        $this->contestant_a_id = $contestant_a_id;
    }

    public function SetContestantBID( $contestant_b_id ){
        $this->contestant_b_id = $contestant_b_id;
    }

    public function SetGameStatusID( $gamestatus_id ){
        $this->gamestatus_id = $gamestatus_id;
    }

    public function GetGameMode(){
        $gamemode = new GameMode($this->conn);
        $gamemode->SetID( $this->gamemode_id );
        $tempRes = $gamemode->GetGameModeByID();
        if( $tempRes['status'] ){
            $this->arr_gamemode = $tempRes['gamemode'];
        }
        return $this->arr_gamemode;
    }

    public function GetPlayerContestantA(){
        $player = new Player($this->conn);
        $player->SetID( $this->contestant_a_id );
        $tempRes = $player->GetPlayerByID();
        if( $tempRes['status'] ){
            $this->arr_contestant_a = $tempRes['player'];
        }
        return $this->arr_contestant_a;
    }

    public function GetPlayerContestantB(){
        $player = new Player($this->conn);
        $player->SetID( $this->contestant_b_id );
        $tempRes = $player->GetPlayerByID();
        if( $tempRes['status'] ){
            $this->arr_contestant_b = $tempRes['player'];
        }
        return $this->arr_contestant_b;
    }

    public function GetTeamContestantA(){
        $team = new Team($this->conn);
        $team->SetID( $this->contestant_a_id );
        $tempRes = $team->GetTeamByID();
        if( $tempRes['status'] ){
            $this->arr_contestant_a = $tempRes['team'];
        }
        return $this->arr_contestant_a;
    }

    public function GetTeamContestantB(){
        $team = new Team($this->conn);
        $team->SetID( $this->contestant_b_id );
        $tempRes = $team->GetTeamByID();
        if( $tempRes['status'] ){
            $this->arr_contestant_b = $tempRes['team'];
        }
        return $this->arr_contestant_b;
    }

    public function GetGameStatus(){
        $gamestatus = new GameStatus($this->conn);
        $gamestatus->SetID( $this->gamestatus_id );
        $tempRes = $gamestatus->GetGameStatusByID();
        if( $tempRes['status'] ){
            $this->arr_gamestatus = $tempRes['gamestatus'];
        }
        return $this->arr_gamestatus;
    }

    public function GetGameSets(){
        $gameset = new GameSet($this->conn);
        $gameset->SetGameDrawID($this->id);
        $res = $gameset->GetGameSetsByGameDraw();
        if( $res['status'] ){
            $this->arr_gamesets = $res['gamesets'];
        }
        return $this->arr_gamesets;
    }

    public function CreateGameDraw(){
        $sql = "INSERT INTO " . $this->table_name . " (gamedraw_num, gamemode_id, contestant_a_id, contestant_b_id) VALUES ('{$this->num}', '{$this->gamemode_id}', '{$this->contestant_a_id}', '{$this->contestant_b_id}')";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    public function GetGameDraws(){
        $res = array( 'status' => false );
        $query = "SELECT * FROM {$this->table_name} ORDER BY gamedraw_num ASC";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $res['has_value'] = false;
            if($result->num_rows>0){
                $res['has_value'] = true;
                $i = 0;
                $gamedraws = array();
                while($row = $result->fetch_assoc()) {
                    $gamedraws[$i]['id'] = $row['gamedraw_id'];
                    $gamedraws[$i]['num'] = $row['gamedraw_num'];
                    $gamedraws[$i]['gamemode_id'] = $row['gamemode_id'];
                    $gamedraws[$i]['gamestatus_id'] = $row['gamestatus_id'];
                    $gamedraws[$i]['contestant_a_id'] = $row['contestant_a_id'];
                    $gamedraws[$i]['contestant_b_id'] = $row['contestant_b_id'];

                    $i++;
                }
                $res['gamedraws'] = $gamedraws;
            }
        }

        return $res;
    }

    public function GetGameDrawByID(){
        $res = array( 'status' => false );
        $query = "SELECT * FROM {$this->table_name} WHERE gamedraw_id={$this->id}";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $res['has_value'] = $result->num_rows > 0;
            if($res['has_value']){
                $gamedraw = array();
                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                $gamedraw['id'] = $row['gamedraw_id'];
                $gamedraw['num'] = $row['gamedraw_num'];
                $gamedraw['gamemode_id'] = $row['gamemode_id'];
                $gamedraw['gamestatus_id'] = $row['gamestatus_id'];
                $gamedraw['contestant_a_id'] = $row['contestant_a_id'];
                $gamedraw['contestant_b_id'] = $row['contestant_b_id'];
                $res['gamedraw'] = $gamedraw;
            }
        }

        return $res;
    }

    public function UpdateGameDraw(){
        $sql = "UPDATE {$this->table_name} SET gamedraw_num={$this->num} WHERE gamedraw_id={$this->id}";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    public function DeleteGameDraw(){
        $sql = "DELETE FROM {$this->table_name} WHERE gamedraw_id={$this->id}";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    /* public function DeleteGameDrawsByPlayer(){
        $res = array( 'status' => false );
        if( $this->countGameDrawByPlayer() > 0){
            $sql = "DELETE FROM {$this->table_name} WHERE gamemode_id=2 AND ( contestant_a_id={$this->contestant_id} OR contestant_b_id={$this->contestant_id} )";

            if($this->conn->query($sql) === TRUE) {

                $res = array(
                    'status'    => true
                );
            }
        }else{
        }

        return $res;
    } */

    /* private function countGameDrawByPlayer(){
        $sql = "SELECT COUNT(*) as nGameDraw FROM {$this->table_name} WHERE gamemode_id={$this->gamemode_id} AND ( contestant_a_id={$this->contestant_id} OR contestant_b_id={$this->contestant_id} )";

        $result = $this->conn->query( $sql );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        return $row['nGameDraw'];
    } */

    public function GetGameDrawsByPlayerID(){

        $res = array( 'status' => false );
        /*
        * TO-DO: Harus Dinamis
        */
        $query = "SELECT * FROM {$this->table_name} WHERE gamemode_id=2 AND ( contestant_a_id={$this->contestant_id} OR contestant_b_id={$this->contestant_id} )";

        if( $result = $this->conn->query( $query ) ){
            $i = 0;
            $gamedraws = array();
            while($row = $result->fetch_assoc()) {
                $gamedraws[$i]['id'] = $row['gamedraw_id'];
                $gamedraws[$i]['num'] = $row['gamedraw_num'];
                $gamedraws[$i]['gamemode_id'] = $row['gamemode_id'];
                $gamedraws[$i]['gamestatus_id'] = $row['gamestatus_id'];
                $gamedraws[$i]['contestant_a_id'] = $row['contestant_a_id'];
                $gamedraws[$i]['contestant_b_id'] = $row['contestant_b_id'];

                /* $gamemode = new GameMode($this->conn);
                $gamemode->SetID( $row['gamemode_id'] );
                $tempRes = $gamemode->GetGameModeByID();
                if( $tempRes['status'] ){
                    $gamedraws[$i]['gamemode'] = $tempRes['gamemode'];
                }else{
                    $gamedraws[$i]['gamemode'] = array();
                } */

                /* $gamestatus = new GameStatus($this->conn);
                $gamestatus->SetID( $row['gamestatus_id'] );
                $tempRes = $gamestatus->GetGameStatusByID();
                if( $tempRes['status'] ){
                    $gamedraws[$i]['gamestatus'] = $tempRes['gamestatus'];
                }else{
                    $gamedraws[$i]['gamestatus'] = array();
                } */

                /* $player = new Player($this->conn);
                $player->SetID( $row['contestant_a_id'] );
                $tempRes = $player->GetPlayerByID();
                if( $tempRes['status'] ){
                    $gamedraws[$i]['contestant_a'] = $tempRes['player'];
                }else{
                    $gamedraws[$i]['contestant_a'] = array();
                }

                $player->SetID( $row['contestant_b_id'] );
                $tempRes = $player->GetPlayerByID();
                if( $tempRes['status'] ){
                    $gamedraws[$i]['contestant_b'] = $tempRes['player'];
                }else{
                    $gamedraws[$i]['contestant_b'] = array();
                } */

                $i++;
            }

            $res = array(
                'gamedraws'      => $gamedraws,
                'status'    => true
            );
        }

        return $res;
    }

    public function GetGameDrawsByTeamID(){

        $res = array( 'status' => false );
        /*
        * TO-DO: Harus Dinamis
        */
        $query = "SELECT * FROM {$this->table_name} WHERE gamemode_id=1 AND ( contestant_a_id={$this->contestant_id} OR contestant_b_id={$this->contestant_id} )";

        if( $result = $this->conn->query( $query ) ){
            $i = 0;
            $gamedraws = array();
            while($row = $result->fetch_assoc()) {
                $gamedraws[$i]['id'] = $row['gamedraw_id'];
                $gamedraws[$i]['num'] = $row['gamedraw_num'];
                $gamedraws[$i]['gamemode_id'] = $row['gamemode_id'];
                $gamedraws[$i]['gamestatus_id'] = $row['gamestatus_id'];
                $gamedraws[$i]['contestant_a_id'] = $row['contestant_a_id'];
                $gamedraws[$i]['contestant_b_id'] = $row['contestant_b_id'];

                $i++;
            }

            $res = array(
                'gamedraws'      => $gamedraws,
                'status'    => true
            );
        }

        return $res;
    }
}
?>