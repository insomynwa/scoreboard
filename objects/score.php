<?php

class Score{

    private $conn;
    private $table_name = "score";

    private $id;
    private $gameset_id;
    private $contestant_id;
    private $timer;
    private $score_1;
    private $score_2;
    private $score_3;
    private $score_4;
    private $score_5;
    private $score_6;
    private $point;
    private $desc;


    public function __construct( $db ){
        $this->conn = $db;
    }

    public function SetID( $id ){
        $this->id = $id;
    }

    public function SetGameSetID( $gameset_id ){
        $this->gameset_id = $gameset_id;
    }

    public function SetContestantID( $contestant_id ){
        $this->contestant_id = $contestant_id;
    }

    public function SetTimer($timer){
        $this->timer = $timer;
    }

    public function SetScore1( $score_1 ){
        $this->score_1 = $score_1;
    }

    public function SetScore2( $score_2 ){
        $this->score_2 = $score_2;
    }

    public function SetScore3( $score_3 ){
        $this->score_3 = $score_3;
    }

    public function SetScore4( $score_4 ){
        $this->score_4 = $score_4;
    }

    public function SetScore5( $score_5 ){
        $this->score_5 = $score_5;
    }

    public function SetScore6( $score_6 ){
        $this->score_6 = $score_6;
    }

    public function SetPoint($point){
        $this->point = $point;
    }

    public function SetDesc( $desc ){
        $this->desc = $desc;
    }

    public function GetTotalSetPointsByGameDraw($gamedraw_id, $contestant_id){
        $res = array( 'status' => false );
        $query = "SELECT SUM(score.score_1 + score.score_2 + score.score_3 + score.score_4 + score.score_5 + score.score_6) as game_total_points, SUM(score.set_points) as game_points ".
        "FROM gamedraw ".
        "LEFT JOIN gameset ".
        "ON gamedraw.gamedraw_id = gameset.gamedraw_id
        LEFT JOIN score ".
        "ON gameset.gameset_id = score.gameset_id ".
        "WHERE gamedraw.gamedraw_id =".$gamedraw_id." AND score.contestant_id=".$contestant_id;

        if( $result = $this->conn->query( $query ) ){

            $point = array();
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $point['game_total_points'] = $row['game_total_points'];
            $point['game_points'] = $row['game_points'];

            $res = array(
                'point'      => $point,
                'status'    => true
            );
        }

        return $res;
    }

    /**
     * Set Score Data
     *
     * param [ gameset_id, contestant_id ]
     * @param array $score_data
     * @return instance
     */
    public function set_data($score_data){
        $data = array(
            'gameset_id'                => $score_data['gameset_id'] == 0 ? 0 : $score_data['gameset_id'],
            'contestant_id'               => $score_data['contestant_id'] == 0 ? 1: $score_data['contestant_id']
        );

        $this->gameset_id = $data['gameset_id'];
        $this->contestant_id = $data['contestant_id'];

        return $this;
    }

    /**
     * Get A Contestant Live Score
     *
     * return:
     * [status,score] => 'true', [timer,score_1,score_2,score..6, set_scores, set_points]
     * [status] => 'false'
     *
     * @param int $gamemode_id
     * @param int $contestant_id
     * @return array
     */
    public function get_contestant_live ( $gamemode_id, $contestant_id ){
        $res = array( 'status' => false );

        $query_select = "s.score_timer, s.score_1, s.score_2, s.score_3, s.score_4, s.score_5, s.score_6, ( s.score_1 + s.score_2 + s.score_3 + s.score_4 + s.score_5 + s.score_6 ) as set_scores, s.set_points, s.score_desc";
        $addon_query = "";
        if( $gamemode_id == 1 ) {
            $query_select .= ", p.player_name as name";
            $addon_query .= "LEFT JOIN player p ON s.contestant_id = p.player_id";
        }else if( $gamemode_id == 2 ) {
            $query_select .= ", t.team_name as name";
            $addon_query .= "LEFT JOIN team t ON s.contestant_id = t.team_id";
        }
        $query =
        "SELECT {$query_select}
        FROM livegame l
        LEFT JOIN {$this->table_name} s ON s.gameset_id = l.gameset_id AND s.contestant_id = {$contestant_id}
        LEFT JOIN gameset gs ON gs.gameset_id = s.gameset_id
        $addon_query
        ";

        if( $result = $this->conn->query( $query ) ){
            if($result->num_rows > 0){
                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                $res = [
                    'status'    => true,
                    'score'     => [
                        'name'          => $row[ 'name' ],
                        'timer'         => $row[ 'score_timer'],
                        'score_1'       => $row[ 'score_1' ],
                        'score_2'       => $row[ 'score_2' ],
                        'score_3'       => $row[ 'score_3' ],
                        'score_4'       => $row[ 'score_4' ],
                        'score_5'       => $row[ 'score_5' ],
                        'score_6'       => $row[ 'score_6' ],
                        'set_scores'    => $row[ 'set_scores'],
                        'set_points'    => $row[ 'set_points'],
                        'desc'          => $row[ 'score_desc']
                    ]
                ];
            }
        }

        return $res;
    }

    /**
     * Get Live Score
     * return:
     * true     > 'status'      : true
     *          > 'scores'      : []
     *          > 'contestants'  : []
     *
     * false    > 'status'      : false
     *
     * @return array
     */
    public function get_live() {
        $res = array( 'status' => false );

        $query =
        "SELECT gd.gamemode_id, gd.bowstyle_id, gs.gameset_num, s.contestant_id, s.score_timer, s.score_1, s.score_2, s.score_3, s.score_4, s.score_5, s.score_6, s.score_desc,
        ( s.score_1 + s.score_2 + s.score_3 + s.score_4 + s.score_5 + s.score_6) as set_scores, s.set_points, ts.game_points, ts.game_scores, ts.n_sets, ss.style_config
        FROM livegame l
        LEFT JOIN gameset gs ON gs.gameset_id = l.gameset_id
        LEFT JOIN gamedraw gd ON gd.gamedraw_id = gs.gamedraw_id
        LEFT JOIN score s ON s.gameset_id = gs.gameset_id
        LEFT JOIN scoreboard_style ss ON ss.id = l.scoreboard_style_id
        LEFT JOIN
        (
        SELECT s2.contestant_id, SUM(s2.set_points) as game_points,
        SUM( s2.score_1 + s2.score_2 + s2.score_3 + s2.score_4 + s2.score_5 + s2.score_6) as game_scores, COUNT(s2.contestant_id) as n_sets
        FROM gameset gs2
        LEFT JOIN score s2 ON s2.gameset_id = gs2.gameset_id
        LEFT JOIN gamedraw gd2 ON gd2.gamedraw_id = gs2.gamedraw_id
        WHERE gs2.gamedraw_id IN
        (
        SELECT gs3.gamedraw_id
        FROM gameset gs3
        RIGHT JOIN livegame l3 ON l3.gameset_id = gs3.gameset_id
        )
        GROUP BY s2.contestant_id
        ) as ts
        ON ts.contestant_id = s.contestant_id
        ";

        if( $result = $this->conn->query( $query ) ){
            if($result->num_rows > 1){
                $i=0;
                $res[ 'status' ] = true;
                while($row = $result->fetch_assoc()) {
                    $res[ 'scores' ][ 'gamemode_id' ]                       = $row[ 'gamemode_id' ];
                    $res[ 'scores' ][ 'bowstyle_id' ]                       = $row[ 'bowstyle_id' ];
                    $res[ 'scores' ][ 'sets' ][ 'curr_set' ]                = $row[ 'gameset_num' ];
                    $res[ 'scores' ][ 'sets' ][ 'end_set' ]                 = $row[ 'n_sets' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'id' ]           = $row[ 'contestant_id' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'score_timer' ]  = $row[ 'score_timer' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'score_1' ]      = $row[ 'score_1' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'score_2' ]      = $row[ 'score_2' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'score_3' ]      = $row[ 'score_3' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'score_4' ]      = $row[ 'score_4' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'score_5' ]      = $row[ 'score_5' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'score_6' ]      = $row[ 'score_6' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'set_scores' ]   = $row[ 'set_scores' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'game_scores' ]  = $row[ 'game_scores' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'set_points' ]   = $row[ 'set_points' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'game_points' ]  = $row[ 'game_points' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'desc' ]         = $row[ 'score_desc' ];
                    $res[ 'scores' ][ 'style_config' ]                      = $row[ 'style_config' ];
                    $i++;
                }
            }
        }

        return $res;
    }

    /**
     * Get Live Score for Scoreboard Form
     *
     * @return array
     */
    public function get_form_live() {
        $res = array( 'status' => false );

        $query =
        "SELECT gd.gamedraw_id, gd.gamemode_id, gs.gameset_id, gs.gameset_num, s.score_id, s.contestant_id, s.score_timer, s.score_1, s.score_2, s.score_3, s.score_4, s.score_5, s.score_6, s.score_desc,
        ( s.score_1 + s.score_2 + s.score_3 + s.score_4 + s.score_5 + s.score_6) as set_scores, s.set_points, ts.game_points, ts.game_scores, ts.n_sets
        FROM livegame l
        LEFT JOIN gameset gs ON gs.gameset_id = l.gameset_id
        LEFT JOIN gamedraw gd ON gd.gamedraw_id = gs.gamedraw_id
        LEFT JOIN score s ON s.gameset_id = gs.gameset_id
        LEFT JOIN
        (
        SELECT s2.contestant_id, SUM(s2.set_points) as game_points,
        SUM( s2.score_1 + s2.score_2 + s2.score_3 + s2.score_4 + s2.score_5 + s2.score_6) as game_scores, COUNT(s2.contestant_id) as n_sets
        FROM gameset gs2
        LEFT JOIN score s2 ON s2.gameset_id = gs2.gameset_id
        LEFT JOIN gamedraw gd2 ON gd2.gamedraw_id = gs2.gamedraw_id
        WHERE gs2.gamedraw_id IN
        (
        SELECT gs3.gamedraw_id
        FROM gameset gs3
        RIGHT JOIN livegame l3 ON l3.gameset_id = gs3.gameset_id
        )
        GROUP BY s2.contestant_id
        ) as ts
        ON ts.contestant_id = s.contestant_id
        ";

        if( $result = $this->conn->query( $query ) ){
            if($result->num_rows > 1){
                $i=0;
                $res[ 'status' ] = true;
                while($row = $result->fetch_assoc()) {
                    $res[ 'scores' ][ 'gamedraw_id' ]                       = $row[ 'gamedraw_id' ];
                    $res[ 'scores' ][ 'gameset_id' ]                        = $row[ 'gameset_id' ];
                    $res[ 'scores' ][ 'gamemode_id' ]                       = $row[ 'gamemode_id' ];
                    $res[ 'scores' ][ 'sets' ][ 'curr_set' ]                = $row[ 'gameset_num' ];
                    $res[ 'scores' ][ 'sets' ][ 'end_set' ]                 = $row[ 'n_sets' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'score_id' ]     = $row[ 'score_id' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'id' ]           = $row[ 'contestant_id' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'score_timer' ]  = $row[ 'score_timer' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'score_1' ]      = $row[ 'score_1' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'score_2' ]      = $row[ 'score_2' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'score_3' ]      = $row[ 'score_3' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'score_4' ]      = $row[ 'score_4' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'score_5' ]      = $row[ 'score_5' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'score_6' ]      = $row[ 'score_6' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'set_scores' ]   = $row[ 'set_scores' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'game_scores' ]  = $row[ 'game_scores' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'set_points' ]   = $row[ 'set_points' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'game_points' ]  = $row[ 'game_points' ];
                    $res[ 'scores' ][ 'contestants' ][$i][ 'desc' ]         = $row[ 'score_desc' ];
                    $i++;
                }
            }
        }

        return $res;
    }

    /**
     * Create A Contestant Score
     *
     * called after set data >> set_data()
     * @return boolean
     */
    public function create(){
        $sql = "INSERT INTO {$this->table_name} (gameset_id, contestant_id) VALUES ('{$this->gameset_id}', '{$this->contestant_id}')";

        return $this->conn->query($sql);
    }

    public function CreateScore(){
        $sql = "INSERT INTO " . $this->table_name . " (gameset_id, contestant_id) VALUES ('{$this->gameset_id}', '{$this->contestant_id}')";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    /* public function UpdateScore(){
        $sql = "UPDATE " . $this->table_name . " SET score_timer={$this->timer}, score_1={$this->score_1}, score_2={$this->score_2}, score_3={$this->score_3}, score_4={$this->score_4}, score_5={$this->score_5}, score_6={$this->score_6}, score_desc='{$this->desc}', set_points='{$this->point}' WHERE score_id={$this->id}";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    } */

    public function UpdateScore(){
        $sql = "UPDATE " . $this->table_name . " SET score_1={$this->score_1}, score_2={$this->score_2}, score_3={$this->score_3}, score_4={$this->score_4}, score_5={$this->score_5}, score_6={$this->score_6}, score_desc='{$this->desc}', set_points='{$this->point}' WHERE score_id={$this->id}";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    public function UpdateScoreTimer(){
        $sql = "UPDATE " . $this->table_name . " SET score_timer={$this->timer} WHERE score_id={$this->id}";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    public function DeleteScore(){
        $sql = "DELETE FROM {$this->table_name} WHERE score_id={$this->id}";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    /**
     * Delete Team Score
     *
     * @param number $team_id
     * return ['status']
     * @return array
     */
    public function delete_team_related_score($team_id){
        $sql =
        "DELETE FROM {$this->table_name}
        WHERE gameset_id IN
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
                        ( SELECT p.player_id FROM player p WHERE team_id={$team_id} )
                        OR
                        gd.contestant_b_id IN
                        ( SELECT p.player_id FROM player p WHERE team_id={$team_id} )
                    )
                )
                OR
                (
                    gd.gamemode_id=1
                    AND
                    (
                        gd.contestant_a_id = {$team_id}
                        OR
                        gd.contestant_b_id = {$team_id}
                    )
                )
            )
        )";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    /**
     * Delete Player Score
     *
     * @param number $player_id
     *
     * @return boolean
     */
    public function delete_player_related_score($player_id){
        $sql =
        "DELETE FROM {$this->table_name}
        WHERE gameset_id IN
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

        return $this->conn->query($sql);
    }

    /**
     * Delete Game Draw Score
     *
     * @param number $gamedraw_id
     * @return boolean
     */
    public function delete_gamedraw_related_score($gamedraw_id){
        $sql =
        "DELETE FROM {$this->table_name}
        WHERE gameset_id IN
        (
            SELECT gameset_id
            FROM gameset
            WHERE gamedraw_id={$gamedraw_id}
        )";

        return $this->conn->query($sql);
    }

    /**
     * Delete Game Set Score
     *
     * @param number $gameset_id
     * @return boolean
     */
    public function delete_gameset_related_score($gameset_id){
        $sql =
        "DELETE FROM {$this->table_name}
        WHERE gameset_id={$gameset_id}";

        return $this->conn->query($sql);
    }

    /* public function DeleteScoreByGameSetID(){
        $sql = "DELETE FROM {$this->table_name} WHERE gameset_id={$this->gameset_id}";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    } */

    public function GetScoreByGameSetAndContestant(){
        $res = array( 'status' => false );
        $query = "SELECT * FROM {$this->table_name} WHERE gameset_id={$this->gameset_id} AND contestant_id={$this->contestant_id}";

        if( $result = $this->conn->query( $query ) ){

            $score = array();
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $score['id'] = $row['score_id'];
            $score['gameset_id'] = $this->gameset_id;
            $score['contestant_id'] = $this->contestant_id;
            $score['timer'] = $row['score_timer'];
            $score['score_1'] = $row['score_1'];
            $score['score_2'] = $row['score_2'];
            $score['score_3'] = $row['score_3'];
            $score['score_4'] = $row['score_4'];
            $score['score_5'] = $row['score_5'];
            $score['score_6'] = $row['score_6'];
            $score['point'] = $row['set_points'];
            $score['desc'] = $row['score_desc'];

            $res = array(
                'score'      => $score,
                'status'    => true
            );
        }

        return $res;
    }

    public function GetScoresByGameSet(){
        $res = array( 'status' => false );
        $query = "SELECT *, (score_1 + score_2 + score_3 + score_4 + score_5 + score_6) as game_total_points FROM {$this->table_name} WHERE gameset_id={$this->gameset_id}";

        if( $result = $this->conn->query( $query ) ){
            $i = 0;
            $scores = array();
            while($row = $result->fetch_assoc()) {
                $scores[$i]['id'] = $row['score_id'];
                $scores[$i]['gameset_id'] = $this->gameset_id;
                $scores[$i]['contestant_id'] = $row['contestant_id'];
                $scores[$i]['timer'] = $row['score_timer'];
                $scores[$i]['score_1'] = $row['score_1'];
                $scores[$i]['score_2'] = $row['score_2'];
                $scores[$i]['score_3'] = $row['score_3'];
                $scores[$i]['score_4'] = $row['score_4'];
                $scores[$i]['score_5'] = $row['score_5'];
                $scores[$i]['score_6'] = $row['score_6'];
                $scores[$i]['point'] = $row['set_points'];
                $scores[$i]['game_total_points'] = $row['game_total_points'];
                $scores[$i]['desc'] = $row['score_desc'];

                $i++;
            }

            $res = array(
                'scores'      => $scores,
                'status'    => true
            );
        }

        return $res;
    }
}

?>