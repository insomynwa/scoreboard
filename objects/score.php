<?php

class Score{

    private $conn;
    private $table_name = "score";

    public $id;
    public $name;
    public $logo;

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function getScore( $gamesetid, $teamid ){
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

        /* if($this->conn->query($sql) === TRUE) {
            $res = array(
                'status'    => true
            );

            return $res;
        } */

        return $res;
    }
}

?>