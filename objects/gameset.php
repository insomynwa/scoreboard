<?php

class GameSet{

    private $conn;
    private $table_name = "gameset";

    private $id;
    private $gamedraw_id;
    private $num;
    private $point;
    private $desc;
    private $status;
    private $arr_scores = array();
    private $arr_gamedraw = array();
    private $arr_gameset_status = array();

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function SetID($id){
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

    public function SetGameDrawID($gamedraw_id){
        $this->gamedraw_id = $gamedraw_id;
    }

    public function gamedraw_id($gamedraw_id){
        $this->gamedraw_id = $gamedraw_id;
        return $this;
    }

    public function SetNum($num){
        $this->num = $num;
    }

    public function SetPoint($point){
        $this->point = $point;
    }

    public function SetDesc($desc){
        $this->desc = $desc;
    }

    public function SetStatus($status){
        $this->status = $status;
    }

    public function GetGameStatus(){
        $gamestatus = new GameStatus($this->conn);
        $gamestatus->SetID( $this->status );
        $tempRes = $gamestatus->GetGameStatusByID();
        if( $tempRes['status'] ){
            $this->arr_gameset_status = $tempRes['gamestatus'];
        }
        return $this->arr_gameset_status;
    }

    public function GetGameDraw(){
        $gamedraw = new GameDraw($this->conn);
        $gamedraw->SetID($this->gamedraw_id);
        $res = $gamedraw->GetGameDrawByID();
        if( $res['status'] ){
            $this->arr_gamedraw = $res['gamedraw'];
        }
        return $this->arr_gamedraw;
    }

    public function GetScores(){
        $score = new Score($this->conn);
        $score->SetGameSetID($this->id);
        $res = $score->GetScoresByGameSet();
        if( $res['status'] ){
            $this->arr_scores = $res['scores'];
        }
        return $this->arr_scores;
    }

    public function CreateSet(){
        $res = array( 'status' => false );
        $sql = "INSERT INTO " . $this->table_name . " (gamedraw_id, gameset_num) VALUES ('{$this->gamedraw_id}', '{$this->num}')";

        if($this->conn->query($sql) === TRUE) {

            $latest_id = $this->conn->insert_id;

            $res = array(
                'status'    => true,
                'latest_id' => $latest_id
            );
        }

        return $res;
    }

    public function UpdateGameSet(){
        $sql = "UPDATE " . $this->table_name . " SET gameset_num={$this->num}, gameset_status={$this->status} WHERE gameset_id={$this->id}";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    public function UpdateStatusGameSet(){
        $sql = "UPDATE {$this->table_name} SET gameset_status={$this->status} WHERE gameset_id={$this->id}";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    public function DeleteGameSet(){
        $sql = "DELETE FROM {$this->table_name} WHERE gameset_id={$this->id}";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    public function GetGameSetsByGameDraw(){
        $res = array( 'status' => false );
        $query = "SELECT * FROM {$this->table_name} WHERE gamedraw_id={$this->gamedraw_id}";

        if( $result = $this->conn->query( $query ) ){
            $i = 0;
            $gamesets = array();
            while($row = $result->fetch_assoc()) {
                $gamesets[$i]['id'] = $row['gameset_id'];
                $gamesets[$i]['num'] = $row['gameset_num'];
                $gamesets[$i]['desc'] = $row['gameset_desc'];
                $gamesets[$i]['gameset_status'] = $row['gameset_status'];

                $i++;
            }

            $res = array(
                'gamesets'      => $gamesets,
                'status'    => true
            );
        }

        return $res;
    }

    public function GetGameSetListByGameDrawID($gamedraw_id){
        $res = array( 'status' => false );
        $query =
        "SELECT gs.gameset_id, gs.gameset_num, s.gamestatus_id, s.gamestatus_name
        FROM {$this->table_name} gs
        INNER JOIN gamestatus s ON s.gamestatus_id = gs.gameset_status
        WHERE gs.gamedraw_id = {$gamedraw_id}
        ORDER BY gs.gameset_num ASC";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $res['has_value'] = false;
            if($result->num_rows>0){
                $res['has_value'] = true;
                $i = 0;
                $gamesets = array();
                while($row = $result->fetch_assoc()) {
                    $gamesets[$i]['id'] = $row['gameset_id'];
                    $gamesets[$i]['num'] = $row['gameset_num'];
                    $gamesets[$i]['gamestatus_id'] = $row['gamestatus_id'];
                    $gamesets[$i]['gamestatus'] = $row['gamestatus_name'];

                    $i++;
                }
                $res['gamesets'] = $gamesets;
            }
        }

        return $res;
    }

    /* public function CountGameSetsByGameDraw(){
        $sql = "SELECT COUNT(*) as nGameSet FROM {$this->table_name} WHERE gamedraw_id={$this->gamedraw_id}";

        $result = $this->conn->query( $sql );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        return $row['nGameSet'];
    } */

    public function GetGameSets(){
        $res = array( 'status' => false );

        $query = "SELECT * FROM {$this->table_name}";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $res['has_value'] = $result->num_rows>0;
            $res['gamesets'] = array();
            if($res['has_value']){
                $i = 0;
                $gamesets = array();
                while($row = $result->fetch_assoc()) {
                    $gamesets[$i]['id'] = $row['gameset_id'];
                    $gamesets[$i]['num'] = $row['gameset_num'];
                    $gamesets[$i]['desc'] = $row['gameset_desc'];
                    $gamesets[$i]['gamedraw_id'] = $row['gamedraw_id'];
                    $gamesets[$i]['gameset_status'] = $row['gameset_status'];

                    $i++;
                }

                $res['gamesets'] = $gamesets;
            }
        }

        return $res;
    }

    public function get_gameset_list(){
        $res = array( 'status' => false );

        $query =
        "SELECT gs.gameset_id, gd.gamedraw_num, b.bowstyle_name, gm.gamemode_name, gs.gameset_num, s.gamestatus_id, s.gamestatus_name, tA.team_name as contestant_a_name, tB.team_name as contestant_b_name
        FROM {$this->table_name} gs
        LEFT JOIN gamestatus s ON gs.gameset_status = s.gamestatus_id
        LEFT JOIN gamedraw gd ON gs.gamedraw_id = gd.gamedraw_id
        LEFT JOIN bowstyles b ON gd.bowstyle_id = b.bowstyle_id
        LEFT JOIN gamemode gm ON gd.gamemode_id = gm.gamemode_id
        LEFT JOIN team tA ON gd.contestant_a_id = tA.team_id
        LEFT JOIN team tB ON gd.contestant_b_id = tB.team_id
        WHERE gd.gamemode_id = 1
        UNION
        SELECT gs.gameset_id, gd.gamedraw_num, b.bowstyle_name, gm.gamemode_name, gs.gameset_num, s.gamestatus_id, s.gamestatus_name, pA.player_name as contestant_a_name, pB.player_name as contestant_b_name
        FROM {$this->table_name} gs
        LEFT JOIN gamestatus s ON gs.gameset_status = s.gamestatus_id
        LEFT JOIN gamedraw gd ON gs.gamedraw_id = gd.gamedraw_id
        LEFT JOIN bowstyles b ON gd.bowstyle_id = b.bowstyle_id
        LEFT JOIN gamemode gm ON gd.gamemode_id = gm.gamemode_id
        LEFT JOIN player pA ON gd.contestant_a_id = pA.player_id
        LEFT JOIN player pB ON gd.contestant_b_id = pB.player_id
        WHERE gd.gamemode_id = 2
        ORDER BY gamedraw_num DESC, gameset_num DESC";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $res['has_value'] = $result->num_rows>0;
            $res['gamesets'] = array();
            if($res['has_value']){
                $i = 0;
                $gamesets = array();
                while($row = $result->fetch_assoc()) {
                    $gamesets[$i]['id'] = $row['gameset_id'];
                    $gamesets[$i]['game_num'] = $row['gamedraw_num'];
                    $gamesets[$i]['set_num'] = $row['gameset_num'];
                    $gamesets[$i]['bowstyle_name'] = $row['bowstyle_name'];
                    $gamesets[$i]['gamemode_name'] = $row['gamemode_name'];
                    $gamesets[$i]['contestant_a_name'] = $row['contestant_a_name'];
                    $gamesets[$i]['contestant_b_name'] = $row['contestant_b_name'];
                    $gamesets[$i]['gamestatus_id'] = $row['gamestatus_id'];
                    $gamesets[$i]['gamestatus_name'] = $row['gamestatus_name'];

                    $i++;
                }

                $res['gamesets'] = $gamesets;
            }
        }

        return $res;
    }

    /* public function GetLiveGameSets(){
        $res = array( 'status' => false );
        $query = "SELECT * FROM {$this->table_name} WHERE gameset_status=2";

        if( $result = $this->conn->query( $query ) ){

            $gameset = array();
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            if(is_numeric($row['gameset_id']) && $row['gameset_id'] > 0){
                $gameset['id'] = $row['gameset_id'];
                $gameset['num'] = $row['gameset_num'];
                $gameset['desc'] = $row['gameset_desc'];
                $gameset['gamedraw_id'] = $row['gamedraw_id'];
                $gameset['gameset_status'] = $row['gameset_status'];

                $res = array(
                    'gameset'      => $gameset,
                    'status'    => true
                );
            }else{
                $res['status'] = false;
            }
        }

        return $res;
    } */

    public function GetGameSetByID(){
        $res = array( 'status' => false );
        $query = "SELECT gameset_id,gameset_num,gameset_desc,gamedraw_id,gameset_status FROM {$this->table_name} WHERE gameset_id={$this->id}";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $res['has_value'] = false;
            $gameset = array();
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            if($row['gameset_id'] > 0){
                $res['has_value'] = true;
                $gameset['id'] = $row['gameset_id'];
                $gameset['num'] = $row['gameset_num'];
                $gameset['desc'] = $row['gameset_desc'];
                $gameset['gamedraw_id'] = $row['gamedraw_id'];
                $gameset['gameset_status'] = $row['gameset_status'];

                $res['gameset'] = $gameset;
            }
        }

        return $res;
    }

    public function get_this_gameset(){
        $res = array( 'status' => false );

        $selected_column = '*';
        if($this->action=='update'){
            $selected_column = 'gameset_id, gamedraw_id, gameset_num, gameset_status';
        }
        $query =
        "SELECT {$selected_column}
        FROM {$this->table_name}
        WHERE gameset_id={$this->id}";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $res['has_value'] = $result->num_rows > 0;
            if($res['has_value']){
                $gameset = array();
                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

                if($this->action=='update'){
                    $gameset['id'] = $row['gameset_id'];
                    $gameset['num'] = $row['gameset_num'];
                    $gameset['gamedraw_id'] = $row['gamedraw_id'];
                    $gameset['gameset_status'] = $row['gameset_status'];
                }

                $res['gameset'] = $gameset;
            }
        }

        return $res;
    }

    public function GetLastNum(){
        $res = array( 'status' => false);
        $query = "SELECT gameset_num FROM {$this->table_name} WHERE gamedraw_id={$this->gamedraw_id} ORDER BY gameset_num DESC LIMIT 1";

        $res['last_num'] = 0;
        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $res['has_value'] = $result->num_rows > 0;
            if($res['has_value']){
                $gameset = array();
                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

                $res['last_num'] = $row['gameset_num'];
            }
        }

        return $res;
    }

    public function get_last_set(){
        $res = array( 'status' => false);
        $query =
        "SELECT MAX(gameset_num) last_set
        FROM {$this->table_name}
        WHERE gamedraw_id={$this->gamedraw_id}";

        $res['last_set'] = 0;
        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $res['last_set'] = $row['last_set'];
        }

        return $res;
    }
}
?>