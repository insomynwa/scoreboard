<?php

class Score{

    private $conn;
    private $table_name = "score";

    private $id;
    private $gameset_id;
    private $contestant_id;
    private $score_1;
    private $score_2;
    private $score_3;
    private $score_4;
    private $score_5;
    private $score_6;
    private $status;


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

    public function SetStatus( $status ){
        $this->status = $status;
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

    public function GetScoreByGameSetAndContestant(){
        $query = "SELECT * FROM " . $this->table_name ." WHERE gameset_id={$this->gameset_id} AND contestant_id={$this->contestant_id}";

        $result = $this->conn->query( $query );//var_dump($result > 0 );

        $res = array( 'score' => array(), 'status' => $result->num_rows > 0 );

        if( $res['status'] ){

            $score = null;
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $score['id'] = $row['score_id'];
            $score['gameset_id'] = $this->gameset_id;
            $score['contestant_id'] = $this->contestant_id;
            $score['score_1'] = $row['score_1'];
            $score['score_2'] = $row['score_2'];
            $score['score_3'] = $row['score_3'];
            $score['score_4'] = $row['score_4'];
            $score['score_5'] = $row['score_5'];
            $score['score_6'] = $row['score_6'];

            $res = array(
                'score'      => $score,
                'status'    => true
            );
        }

        return $res;
    }

    /* public function getScore( $gamesetid, $teamid ){
        $query = "SELECT * FROM " . $this->table_name . " WHERE gameset_id = {$gamesetid} AND tim_id = {$teamid}";

        $stmt = $this->conn->query( $query );

        return $stmt->fetch_assoc();
    }

    public function updateScore( $score_data){
        $sql = "UPDATE " . $this->table_name . " SET timer={$score_data['timer']}, score_1={$score_data['pts1']}, score_2={$score_data['pts2']}, score_3={$score_data['pts3']}, score_4={$score_data['pts4']}, score_5={$score_data['pts5']}, score_6={$score_data['pts6']}, set_points={$score_data['setpts']}, score_status='{$score_data['status']}' WHERE score_id={$score_data['scoreid']}";

        $res = $this->conn->query($sql);
        return $res;
    }

    public function createScore( $score_data){
        $res = null;//var_dump($score_data);
        $res['status'] = false;
        for( $i = 0; $i<sizeof( $score_data['teams']['teams']); $i++ ){
            $team = $score_data['teams']['teams'][$i];

            $sql = "INSERT INTO " . $this->table_name . " (tim_id, gameset_id) VALUES ('{$team}', '{$score_data['gameset_id']}')";

            if($this->conn->query($sql) === TRUE) {
                $res = array(
                    'status'    => true
                );
            }
        }

        return $res;
    } */
}

?>