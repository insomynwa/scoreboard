<?php

class GameDraw{

    private $conn;
    private $table_name = "gamedraw";

    private $id;            //_[int]
    private $num;           //_[int]
    private $bowstyle_id;   //_[int]
    private $gamemode_id;      //_id_[int]
    private $contestant_id;   //_id_[int]
    private $gamestatus_id;    //_id_[int]
    private $contestant_a_id;   //_id_[int]
    private $contestant_b_id;   //_id_[int]
    private $arr_gamesets = array();
    private $arr_bowstyle = array();
    private $arr_gamemode = array();
    private $arr_contestant_a = array();
    private $arr_contestant_b = array();
    private $arr_gamestatus = array();
    private $game_total_points;
    private $game_points;
    private $action;

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function SetID( $id ){
        $this->id = $id;
    }

    public function id($id){
        $this->id = $id;
        return $this;
    }

    public function action( $action){
        $this->action = $action;
        return $this;
    }

    public function SetNum( $num ){
        $this->num = $num;
    }

    public function SetBowstyleID( $bowstyle_id ){
        $this->bowstyle_id = $bowstyle_id;
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

    public function GetBowstyle(){
        $bowstyle = new Bowstyle($this->conn);
        $bowstyle->SetID( $this->bowstyle_id );
        $tempRes = $bowstyle->GetBowstyleByID();
        if( $tempRes['status'] ){
            $this->arr_bowstyle = $tempRes['bowstyle'];
        }
        return $this->arr_bowstyle;
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

    public function GetTotalSetPoints($contestant_id){
        $score = new Score($this->conn);
        $res = $score->GetTotalSetPointsByGameDraw($this->id, $contestant_id);
        if( $res['status']){
            $this->game_total_points = $res['point']['game_total_points'];
            $this->game_points = $res['point']['game_points'];
        }
        return array('game_total_points'=>$this->game_total_points, 'game_points' => $this->game_points);
    }

    public function CreateGameDraw(){
        $sql = "INSERT INTO " . $this->table_name . " ( bowstyle_id, gamedraw_num, gamemode_id, contestant_a_id, contestant_b_id) VALUES ( '{$this->bowstyle_id}', '{$this->num}', '{$this->gamemode_id}', '{$this->contestant_a_id}', '{$this->contestant_b_id}')";

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
                    $gamedraws[$i]['bowstyle_id'] = $row['bowstyle_id'];
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

    public function get_gamedraw_list(){
        $res = array( 'status' => false );
        $query =
        "SELECT gd.gamedraw_id, gd.gamedraw_num, bs.bowstyle_name, gm.gamemode_name, ta.team_name as contestant_a_name, tb.team_name as contestant_b_name
        FROM {$this->table_name} gd
        INNER JOIN bowstyles bs ON bs.bowstyle_id = gd.bowstyle_id
        INNER JOIN gamemode gm ON gm.gamemode_id = gd.gamemode_id
        INNER JOIN team ta ON ta.team_id = gd.contestant_a_id
        INNER JOIN team tb ON tb.team_id = gd.contestant_b_id
        WHERE gd.gamemode_id = 1
        UNION
        SELECT gd.gamedraw_id, gd.gamedraw_num, bs.bowstyle_name, gm.gamemode_name, pa.player_name as contestant_a_name, pb.player_name as contestant_b_name
        FROM {$this->table_name} gd
        INNER JOIN bowstyles bs ON bs.bowstyle_id = gd.bowstyle_id
        INNER JOIN gamemode gm ON gm.gamemode_id = gd.gamemode_id
        INNER JOIN player pa ON pa.player_id = gd.contestant_a_id
        INNER JOIN player pb ON pb.player_id = gd.contestant_b_id
        WHERE gd.gamemode_id = 2
        ORDER BY gamedraw_num DESC";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $res['has_value'] = false;
            if($result->num_rows>0){
                $res['has_value'] = true;
                $i = 0;
                $gamedraws = array();
                $gamedraw_option = array();
                while($row = $result->fetch_assoc()) {
                    $gamedraws[$i]['id'] = $row['gamedraw_id'];
                    $gamedraws[$i]['num'] = $row['gamedraw_num'];
                    $gamedraws[$i]['bowstyle_name'] = $row['bowstyle_name'];
                    $gamedraws[$i]['gamemode_name'] = $row['gamemode_name'];
                    $gamedraws[$i]['contestant_a_name'] = $row['contestant_a_name'];
                    $gamedraws[$i]['contestant_b_name'] = $row['contestant_b_name'];

                    $gamedraw_option[$i]['id'] = $row['gamedraw_id'];
                    $gamedraw_option[$i]['label'] = $row['gamedraw_num'] . '. ' . $row['contestant_a_name'] . ' vs ' . $row['contestant_b_name'];

                    $i++;
                }
                $res['gamedraws'] = $gamedraws;
                $res['gamedraw_option'] = $gamedraw_option;
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
                $gamedraw['bowstyle_id'] = $row['bowstyle_id'];
                $gamedraw['gamemode_id'] = $row['gamemode_id'];
                $gamedraw['gamestatus_id'] = $row['gamestatus_id'];
                $gamedraw['contestant_a_id'] = $row['contestant_a_id'];
                $gamedraw['contestant_b_id'] = $row['contestant_b_id'];

                $res['gamedraw'] = $gamedraw;
            }
        }

        return $res;
    }

    public function get_this_gamedraw(){
        $res = array( 'status' => false );

        $selected_column = '*';
        if($this->action=='update'){
            $selected_column = 'gamedraw_id, gamedraw_num, bowstyle_id, gamemode_id, contestant_a_id, contestant_b_id';
        }
        $query =
        "SELECT {$selected_column}
        FROM {$this->table_name}
        WHERE gamedraw_id={$this->id}";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $res['has_value'] = $result->num_rows > 0;
            if($res['has_value']){
                $gamedraw = array();
                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

                if($this->action=='update'){
                    $gamedraw['id'] = $row['gamedraw_id'];
                    $gamedraw['num'] = $row['gamedraw_num'];
                    $gamedraw['bowstyle_id'] = $row['bowstyle_id'];
                    $gamedraw['gamemode_id'] = $row['gamemode_id'];
                    $gamedraw['contestant_a_id'] = $row['contestant_a_id'];
                    $gamedraw['contestant_b_id'] = $row['contestant_b_id'];
                }

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

    public function count_gamedraw(){
        $sql = "SELECT COUNT(*) as nGameDraw FROM {$this->table_name}";

        $res = array( 'status' => false );
        if($result = $this->conn->query( $sql )){
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $res['count'] = $row['nGameDraw'];
            $res['status'] = true;
        }

        return $res;
    }

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
                $gamedraws[$i]['bowstyle_id'] = $row['bowstyle_id'];
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
                $gamedraws[$i]['bowstyle_id'] = $row['bowstyle_id'];
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