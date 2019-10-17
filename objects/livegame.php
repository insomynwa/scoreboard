<?php

class Live_Game{

    private $conn;
    private $table_name = "livegame";

    private $id;            //_[int]
    private $gameset_id;          //_[string]
    private $arr_gameset = array();

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function SetID($id){
        $this->id = $id;
    }

    public function SetGameSetID($gameset_id){
        $this->gameset_id = $gameset_id;
    }

    /* public function GetGameSet(){
        $gameset = new GameSet($this->conn);
        $gameset->SetID( $this->gameset_id);
        $res = $gameset->GetGameSetByID();
        if( $res['status'] ){
            $this->arr_gameset = $res['gameset'];
        }
        return $this->arr_gameset;
    } */

    public function GetLiveGameID(){
        $res = array( 'status' => false );
        $query = "SELECT livegame_id, gameset_id FROM {$this->table_name} WHERE livegame_id=1 LIMIT 1";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            if(is_numeric($row['livegame_id']) && $row['livegame_id'] > 0){

                $res['live_game'] = $row['gameset_id'];
            }
        }

        return $res;
    }

    /**
     * Get Live Game Set
     *
     * return Game Set ID
     * @return num
     */
    public function get_live_gameset_id(){
        $current_id = 0;
        $query = "SELECT gameset_id FROM {$this->table_name} WHERE livegame_id=1";

        if( $result = $this->conn->query( $query ) ){
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $current_id = $row['gameset_id'];
        }

        return $current_id;
    }

    /**
     * Get Live Game Bow Style
     *
     * return ['status'=>false] / [ 'status', 'bowstyle_id' ]
     * @return array
     */
    public function get_live_bowstyle_id(){
        $res = array( 'status' => false );
        $query =
        "SELECT gd.bowstyle_id
        FROM {$this->table_name} l
        LEFT JOIN gameset gs
        ON l.gameset_id = gs.gameset_id
        LEFT JOIN gamedraw gd
        ON gs.gamedraw_id = gd.gamedraw_id
        LEFT JOIN bowstyles bs
        ON gd.bowstyle_id = bs.bowstyle_id";

        if( $result = $this->conn->query( $query ) ){
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            if($row['bowstyle_id'] != NULL){
                $res['status'] = true;
                $res['bowstyle_id'] = $row['bowstyle_id'];
            }
        }

        return $res;
    }

    /**
     * Check Team Live Game
     *
     * @param number $teamid
     * @return boolean
     */
    public function is_team_playing($teamid){
        $query =
        "SELECT COUNT(l.gameset_id) as live
        FROM {$this->table_name} l
        WHERE l.gameset_id IN
        (
            SELECT gs.gameset_id
            FROM gameset gs
            WHERE gs.gamedraw_id IN
            (
                SELECT gd.gamedraw_id
                FROM gamedraw gd
                WHERE
                (
                    gd.gamemode_id=2
                    AND
                    (
                        gd.contestant_a_id IN
                        ( SELECT p.player_id FROM player p WHERE team_id={$teamid} )
                        OR
                        gd.contestant_b_id IN
                        ( SELECT p.player_id FROM player p WHERE team_id={$teamid} )
                    )
                )
                OR
                (
                    gd.gamemode_id=1
                    AND
                    (
                        gd.contestant_a_id = {$teamid}
                        OR
                        gd.contestant_b_id = {$teamid}
                    )
                )
            )
        )";

        $result = $this->conn->query( $query );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        if($row['live'] == 1 || $row['live'] == "1"){

            return true;
        }

        return false;
    }

    /**
     * Check Game Draw Live Game
     *
     * @param number $gamedraw_id
     * @return boolean
     */
    public function is_gamedraw_playing($gamedraw_id){
        $query =
        "SELECT COUNT(gameset_id) as live
        FROM livegame
        WHERE gameset_id IN
        (
            SELECT gameset_id
            FROM gameset
            WHERE gamedraw_id={$gamedraw_id}
        )";

        $result = $this->conn->query( $query );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        if($row['live'] == 1 || $row['live'] == "1"){

            return true;
        }

        return false;
    }

    /**
     * Check Game Set Live Game
     *
     * @param number $gameset_id
     * @return boolean
     */
    public function is_gameset_playing($gameset_id){
        $query =
        "SELECT COUNT(gameset_id) as live
        FROM livegame
        WHERE gameset_id={$gameset_id}";

        $result = $this->conn->query( $query );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        if($row['live'] == 1 || $row['live'] == "1"){

            return true;
        }

        return false;
    }

    /**
     * Check Player Live Game
     *
     * @param number $player_id
     * @return boolean
     */
    public function is_player_playing($player_id){
        $query =
        "SELECT COUNT(l.gameset_id) as live
        FROM {$this->table_name} l
        WHERE l.gameset_id IN
        (
            SELECT gameset_id
            FROM gameset
            WHERE gamedraw_id IN
            (
                SELECT gamedraw_id
                FROM gamedraw
                WHERE gamemode_id = 2
                AND
                (
                    contestant_a_id={$player_id}
                    OR
                    contestant_b_id={$player_id}
                )
            )
        )";

        $result = $this->conn->query( $query );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        if($row['live'] == 1 || $row['live'] == "1"){

            return true;
        }

        return false;
    }

    /* public function UpdateLiveGame(){
        $query = "UPDATE {$this->table_name} SET gameset_id={$this->gameset_id} WHERE livegame_id=1";

        $result = $this->conn->query( $query );

        $res = array( 'status' => false );

        if ($result){
            $res['status'] = true;
        }
        return $res;
    } */

    /**
     * Set Live Game
     *
     * return [ status]
     * @param number $gameset_id
     * @return array
     */
    public function set_live($gameset_id){
        $query = "UPDATE {$this->table_name} SET gameset_id={$gameset_id} WHERE livegame_id=1";

        $result = $this->conn->query( $query );

        $res = array( 'status' => false );

        if ($result){
            $res['status'] = true;
        }
        return $res;
    }

    /* public function StopLiveGame(){
        $query = "UPDATE {$this->table_name} SET gameset_id=0";

        $result = $this->conn->query( $query );

        $res = array( 'status' => false );

        if ($result){
            $res['status'] = true;
        }
        return $res;
    } */

    public function CreateDefaultLiveGame(){
        $sql = "INSERT INTO {$this->table_name} (livegame_id,gameset_id) VALUES (1,0)";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    public function CountLiveGame(){
        $sql = "SELECT COUNT(livegame_id) as nLiveGame FROM {$this->table_name}";

        $result = $this->conn->query( $sql );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        return $row['nLiveGame'];
    }
}
?>