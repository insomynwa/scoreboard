<?php

class Live_Game{

    private $conn;
    private $table_name = "livegame";

    private $id;            //_[int]
    private $gameset_id;          //_[string]
    private $scoreboard_style_id;
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

    /**
     * Set Scoreboard Style
     *
     * return true/false
     *
     * @param integer $scoreboard_style_id
     *
     * @return boolean
     */
    public function set_style( $scoreboard_style_id = 0 ) {
        $query = "UPDATE {$this->table_name} SET scoreboard_style_id={$scoreboard_style_id} WHERE livegame_id=1";

        if ($this->conn->query( $query )){
            return true;
        }

        return false;
    }

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
     * Get Data
     *
     * return [status, gameset_id, scoreboard_style_id, game_bowstyle_id, style_bowstyle_id ]
     * @return array
     */
    public function get_data() {
        $res = array( 'status' => false );
        // $query = "SELECT gameset_id, scoreboard_style_id FROM {$this->table_name}";
        $query =
        "SELECT l.gameset_id, l.scoreboard_style_id, ss.style, gd.bowstyle_id as game_bowstyle_id, ss.bowstyle_id as style_bowstyle_id, b.bowstyle_name as style_bowstyle_name
        FROM livegame l
        LEFT JOIN gameset gs
        ON l.gameset_id = gs.gameset_id
        LEFT JOIN gamedraw gd
        ON gs.gamedraw_id = gd.gamedraw_id
        LEFT JOIN scoreboard_style ss on ss.id = l.scoreboard_style_id
        LEFT JOIN bowstyles b ON b.bowstyle_id = ss.bowstyle_id
        ";

        if( $result = $this->conn->query( $query ) ){
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            // if($row['gameset_id'] != 0){
                $res['status'] = true;
                $res['gameset_id'] = $row['gameset_id'];
                $res['scoreboard_style_id'] = $row['scoreboard_style_id'];
                $res['style'] = $row['style'];
                $res['game_bowstyle_id'] = $row['game_bowstyle_id'];
                $res['style_bowstyle_id'] = $row['style_bowstyle_id'];
                $res['style_bowstyle_name'] = $row['style_bowstyle_name'];
            // }
        }

        return $res;
    }

    /**
     * Get Live Game Set
     *
     * return Game Set ID (number)
     * @return integer
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
     * Get Scoreboard Style ID
     *
     * @return number
     */
    public function get_style_id(){
        $style_id = 0;
        $query = "SELECT scoreboard_style_id FROM {$this->table_name} WHERE livegame_id=1";

        if( $result = $this->conn->query( $query ) ){
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $style_id = $row['scoreboard_style_id'];
        }

        return $style_id;
    }

    /**
     * Get Style Bowstyle ID
     *
     * return [status, style_id, bowstyle_id ]
     * @return array
     */
    public function get_style_bowstyle_id(){
        $res = array( 'status' => false );

        $query = "SELECT l.scoreboard_style_id, ss.bowstyle_id
        FROM {$this->table_name} l
        LEFT JOIN scoreboard_style ss ON ss.id = l.scoreboard_style_id";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = $result->num_rows == 1;
            if($res['status']){
                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                $res['style_id'] = $row['scoreboard_style_id'];
                $res['bowstyle_id'] = $row['bowstyle_id'];
            }
        }

        return $res;

    }

    /**
     * Clean Scoreboard Style
     *
     * @return boolean
     */
    public function clean_style(){
        $sql = "UPDATE {$this->table_name} SET scoreboard_style_id = 0 WHERE livegame_id=1";

        return $this->conn->query($sql);
    }

    /**
     * Get Live Game Bow Style
     *
     * return ['status'=>false] / [ 'status', 'bowstyle_id' ]
     *
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
     * Get Bow Style ID
     *
     * @return integer
     */
    public function get_bowstyle_id() {
        $query =
        "SELECT gd.bowstyle_id
        FROM {$this->table_name} l
        LEFT JOIN gameset gs
        ON l.gameset_id = gs.gameset_id
        LEFT JOIN gamedraw gd
        ON gs.gamedraw_id = gd.gamedraw_id";

        if( $result = $this->conn->query( $query ) ){
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            if($row['bowstyle_id'] != NULL){
                return $row['bowstyle_id'];
            }
        }
        return 0;
    }

    /**
     * Get Live Game Scoreboard
     *
     * return ['status'] / ['status','style_config']
     *
     * @return array
     */
    /* public function get_livegame_scoreboard() {
        $res = array( 'status' => false );

        $query =
        "SELECT s.style_config
        FROM livegame l
        LEFT JOIN scoreboard_style s ON s.id = l.scoreboard_style_id";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = $result->num_rows == 1;
            if($res['status']){
                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                $res['style_config'] = $row['style_config'];
            }
        }

        return $res;
    } */

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

    /**
     * Check has live game
     *
     * return true/false
     *
     * @return boolean
     */
    /* public function is_has_livegame() {
        return ( $this->get_live_gameset_id() >= 0 );
    } */

    /* public function UpdateLiveGame(){
        $query = "UPDATE {$this->table_name} SET gameset_id={$this->gameset_id} WHERE livegame_id=1";

        $result = $this->conn->query( $query );

        $res = array( 'status' => false );

        if ($result){
            $res['status'] = true;
        }
        return $res;
    } */

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
        $sql = "INSERT INTO {$this->table_name} (livegame_id,gameset_id,scoreboard_style_id) VALUES (1,0,0)";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    /**
     * Create Default Live Game
     *
     * @return boolean
     */
    public function create_default_game() {
        $sql = "INSERT INTO {$this->table_name} (livegame_id,gameset_id,scoreboard_style_id) VALUES (1,0,0)";

        if($this->conn->query($sql) === TRUE) {

            return true;
        }

        return false;
    }

    public function CountLiveGame(){
        $sql = "SELECT COUNT(livegame_id) as nLiveGame FROM {$this->table_name}";

        $result = $this->conn->query( $sql );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        return $row['nLiveGame'];
    }

    /**
     * Check if Live Game is defined
     * > default Live Game
     *
     * @return boolean
     */
    public function is_created() {
        $sql = "SELECT COUNT(livegame_id) as nGame FROM {$this->table_name}";

        $result = $this->conn->query( $sql );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        if( $row['nGame'] == 0 ) {
            return false;
        }
        return true;
    }
}
?>