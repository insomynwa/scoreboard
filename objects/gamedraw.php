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
        $query = "SELECT * FROM {$this->table_name} ORDER BY gamedraw_num ASC";

        $result = $this->conn->query( $query );

        $res = array( 'gamedraws' => array(), 'status' => $result->num_rows > 0 );

        if( $res['status'] ){
            $i = 0;
            $gamedraws = null;
            while($row = $result->fetch_assoc()) {
                $gamedraws[$i]['id'] = $row['gamedraw_id'];
                $gamedraws[$i]['num'] = $row['gamedraw_num'];

                $gamemode = new GameMode($this->conn);
                $gamemode->SetID( $row['gamemode_id'] );
                $tempRes = $gamemode->GetGameModeByID();
                if( $tempRes['status'] ){
                    $gamedraws[$i]['gamemode'] = $tempRes['gamemode'];
                }else{
                    $gamedraws[$i]['gamemode'] = array();
                }

                /*
                * TO-DO: Harus Dinamis
                */
                /* $contestant = new Contestant($this->conn);
                $contestant->SetID( $row['contestant_id'] );
                $contestant->SetGameMode( $row['gamemode_id'] );
                $tempRes = $contestant->GetContestantByID();
                if( $tempRes['status'] ){
                    $gamedraws[$i]['contestant'] = $tempRes['contestant'];
                } */

                $gamestatus = new GameStatus($this->conn);
                $gamestatus->SetID( $row['gamestatus_id'] );
                $tempRes = $gamestatus->GetGameStatusByID();
                if( $tempRes['status'] ){
                    $gamedraws[$i]['gamestatus'] = $tempRes['gamestatus'];
                }else{
                    $gamedraws[$i]['gamestatus'] = array();
                }

                /*
                * TO-DO: Harus Dinamis
                */
                if( $row['gamemode_id'] == 1 ){ // Beregu

                    $team = new Team($this->conn);
                    $team->SetID( $row['contestant_a_id'] );
                    $tempRes = $team->GetTeamByID();
                    if( $tempRes['status'] ){
                        $gamedraws[$i]['contestant_a'] = $tempRes['team'];
                    }else{
                        $gamedraws[$i]['contestant_a'] = array();
                    }

                    $team->SetID( $row['contestant_b_id'] );
                    $tempRes = $team->GetTeamByID();
                    if( $tempRes['status'] ){
                        $gamedraws[$i]['contestant_b'] = $tempRes['team'];
                    }else{
                        $gamedraws[$i]['contestant_b'] = array();
                    }
                }else if( $row['gamemode_id'] == 2 ){ // Individu

                    $player = new Player($this->conn);
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
                    }
                }

                $i++;
            }

            $res = array(
                'gamedraws'      => $gamedraws,
                'status'    => true
            );
        }

        return $res;
    }

    public function GetGameDrawByID(){
        $query = "SELECT * FROM {$this->table_name} WHERE gamedraw_id={$this->id}";

        $result = $this->conn->query( $query );//var_dump($result > 0 );

        $res = array( 'gamedraw' => array(), 'status' => $result->num_rows > 0 );

        if( $res['status'] ){

            $gamedraw = null;
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $gamedraw['id'] = $row['gamedraw_id'];
            $gamedraw['num'] = $row['gamedraw_num'];

            $gamemode = new GameMode($this->conn);
            $gamemode->SetID( $row['gamemode_id'] );
            $tempRes = $gamemode->GetGameModeByID();
            if( $tempRes['status'] ){
                $gamedraw['gamemode'] = $tempRes['gamemode'];
            }else{
                $gamedraw['gamemode'] = array();
            }

            $gamestatus = new GameStatus($this->conn);
            $gamestatus->SetID( $row['gamestatus_id'] );
            $tempRes = $gamestatus->GetGameStatusByID();
            if( $tempRes['status'] ){
                $gamedraw['gamestatus'] = $tempRes['gamestatus'];
            }else{
                $gamedraw['gamestatus'] = array();
            }

            /*
            * TO-DO: Harus Dinamis
            */
            if( $row['gamemode_id'] == 1 ){ // Beregu

                $team = new Team($this->conn);
                $team->SetID( $row['contestant_a_id'] );
                $tempRes = $team->GetTeamByID();
                if( $tempRes['status'] ){
                    $gamedraw['contestant_a'] = $tempRes['team'];
                }else{
                    $gamedraw['contestant_a'] = array();
                }

                $team->SetID( $row['contestant_b_id'] );
                $tempRes = $team->GetTeamByID();
                if( $tempRes['status'] ){
                    $gamedraw['contestant_b'] = $tempRes['team'];
                }else{
                    $gamedraw['contestant_b'] = array();
                }
            }else if( $row['gamemode_id'] == 2 ){ // Individu

                $player = new Player($this->conn);
                $player->SetID( $row['contestant_a_id'] );
                $tempRes = $player->GetPlayerByID();
                if( $tempRes['status'] ){
                    $gamedraw['contestant_a'] = $tempRes['player'];
                }else{
                    $gamedraw['contestant_a'] = array();
                }

                $player->SetID( $row['contestant_b_id'] );
                $tempRes = $player->GetPlayerByID();
                if( $tempRes['status'] ){
                    $gamedraw['contestant_b'] = $tempRes['player'];
                }else{
                    $gamedraw['contestant_b'] = array();
                }
            }

            $res = array(
                'gamedraw'  => $gamedraw,
                'status'    => true
            );
        }

        return $res;
    }

    public function UpdateGameDraw(){
        $sql = "UPDATE {$this->table_name} SET gamedraw_num={$this->num} WHERE gamedraw_id={$this->id}";

        $res = array( 'status' => false );//var_dump($this->timer, $this->point, $this->desc, $this->id);
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

    public function DeleteGameDrawsByPlayer(){
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
    }

    private function countGameDrawByPlayer(){
        $sql = "SELECT COUNT(*) as nGameDraw FROM {$this->table_name} WHERE gamemode_id={$this->gamemode_id} AND ( contestant_a_id={$this->contestant_id} OR contestant_b_id={$this->contestant_id} )";

        $result = $this->conn->query( $sql );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        return $row['nGameDraw'];
    }

    public function GetGameDrawsByPlayerID(){
        /*
        * TO-DO: Harus Dinamis
        */
        $query = "SELECT * FROM {$this->table_name} WHERE gamemode_id=2 AND ( contestant_a_id={$this->contestant_id} OR contestant_b_id={$this->contestant_id} )";

        $result = $this->conn->query( $query );

        $res = array( 'gamedraws' => array(), 'status' => $result->num_rows > 0 );

        if( $res['status'] ){
            $i = 0;
            $gamedraws = null;
            while($row = $result->fetch_assoc()) {
                $gamedraws[$i]['id'] = $row['gamedraw_id'];
                $gamedraws[$i]['num'] = $row['gamedraw_num'];

                $gamemode = new GameMode($this->conn);
                $gamemode->SetID( $row['gamemode_id'] );
                $tempRes = $gamemode->GetGameModeByID();
                if( $tempRes['status'] ){
                    $gamedraws[$i]['gamemode'] = $tempRes['gamemode'];
                }else{
                    $gamedraws[$i]['gamemode'] = array();
                }

                $gamestatus = new GameStatus($this->conn);
                $gamestatus->SetID( $row['gamestatus_id'] );
                $tempRes = $gamestatus->GetGameStatusByID();
                if( $tempRes['status'] ){
                    $gamedraws[$i]['gamestatus'] = $tempRes['gamestatus'];
                }else{
                    $gamedraws[$i]['gamestatus'] = array();
                }

                $player = new Player($this->conn);
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
                }

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
        /*
        * TO-DO: Harus Dinamis
        */
        $query = "SELECT * FROM {$this->table_name} WHERE gamemode_id=1 AND ( contestant_a_id={$this->contestant_id} OR contestant_b_id={$this->contestant_id} )";

        $result = $this->conn->query( $query );

        $res = array( 'gamedraws' => array(), 'status' => $result->num_rows > 0 );

        if( $res['status'] ){
            $i = 0;
            $gamedraws = null;
            while($row = $result->fetch_assoc()) {
                $gamedraws[$i]['id'] = $row['gamedraw_id'];
                $gamedraws[$i]['num'] = $row['gamedraw_num'];

                $gamemode = new GameMode($this->conn);
                $gamemode->SetID( $row['gamemode_id'] );
                $tempRes = $gamemode->GetGameModeByID();
                if( $tempRes['status'] ){
                    $gamedraws[$i]['gamemode'] = $tempRes['gamemode'];
                }else{
                    $gamedraws[$i]['gamemode'] = array();
                }

                $gamestatus = new GameStatus($this->conn);
                $gamestatus->SetID( $row['gamestatus_id'] );
                $tempRes = $gamestatus->GetGameStatusByID();
                if( $tempRes['status'] ){
                    $gamedraws[$i]['gamestatus'] = $tempRes['gamestatus'];
                }else{
                    $gamedraws[$i]['gamestatus'] = array();
                }

                $team = new Team($this->conn);
                $team->SetID( $row['contestant_a_id'] );
                $tempRes = $team->GetTeamByID();
                if( $tempRes['status'] ){
                    $gamedraws[$i]['contestant_a'] = $tempRes['team'];
                }else{
                    $gamedraws[$i]['contestant_a'] = array();
                }

                $team->SetID( $row['contestant_b_id'] );
                $tempRes = $team->GetTeamByID();
                if( $tempRes['status'] ){
                    $gamedraws[$i]['contestant_b'] = $tempRes['team'];
                }else{
                    $gamedraws[$i]['contestant_b'] = array();
                }

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