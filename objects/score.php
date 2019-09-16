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