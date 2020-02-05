<?php
namespace scoreboard\model;

class Score_Model_Class {

    private $connection;
    private $table_name;
    private $id_col;

    private $id;
    private $timer;

    public function __construct($connection) {
        $this->connection = $connection;
        $this->table_name = 'score';
        $this->id_col = 'score_id';
    }

    /**
     * Gameset Scores ID
     *
     * @param integer Gameset ID
     * @return array empty | [score_id1, score_id2, score_id..n]
     */
    public function gameset_scores_id($gameset_id) {
        $scores_id = array();

        $query =
            "SELECT score_id
        FROM {$this->table_name}
        WHERE gameset_id = {$gameset_id}";

        if ($result = $this->connection->query($query)) {
            if ($result->num_rows > 0) {
                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $scores_id[$i] = $row['score_id'];
                    $i++;
                }
            }
        }

        return $scores_id;
    }

    /**
     * Create Score
     *
     * @param integer $gameset_id Gameset ID
     * @param integer $contestant_id Contestant ID
     * @return boolean true | false
     */
    public function create_score( $gameset_id=0, $contestants=null){
        $contestant_a_id = $contestants['contestant_a_id'];
        $contestant_b_id = $contestants['contestant_b_id'];
        $sql = "INSERT INTO {$this->table_name} (gameset_id, contestant_id) VALUES ('{$gameset_id}', '{$contestant_a_id}')
        ,('{$gameset_id}', '{$contestant_b_id}')";

        return $this->connection->query($sql);
    }

    /**
     * Delete Scores
     *
     * @param array Scores ID
     * @return boolean
     */
    public function delete_scores($scores_id=null) {
        $imp_scores_id = implode(',', $scores_id);
        $sql = "DELETE FROM {$this->table_name} WHERE score_id IN ({$imp_scores_id})";
        return $this->connection->query($sql);
    }

    public function SetID($id) {
        $this->id = $id;
    }

    public function SetTimer($timer) {
        $this->timer = $timer;
    }

    /**
     * Scoreboard_data
     *
     * @return array empty | [ scores ]
     */
    public function scoreboard_data(){
        $res = array();

        $query =
        "SELECT gd.gamedraw_id, gd.gamemode_id, gd.bowstyle_id, gd.contestant_a_id, gd.contestant_b_id, gs.gameset_id, gs.gameset_num, ss.style_config
        FROM livegame l
        LEFT JOIN gameset gs ON l.gameset_id = gs.gameset_id
        LEFT JOIN gamedraw gd ON gs.gamedraw_id = gd.gamedraw_id
        LEFT JOIN scoreboard_style ss ON ss.id = l.scoreboard_style_id";

        if ($result = $this->connection->query($query)) {
            if ($result->num_rows == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $res['scores']['gamemode_id'] = $row['gamemode_id'];
                $res['scores']['bowstyle_id'] = $row['bowstyle_id'];
                $res['scores']['sets']['curr_set'] = $row['gameset_num'];
                $res['scores']['style_config'] = $row['style_config'];

                $contestant_a_id = $row['contestant_a_id'];
                $contestant_b_id = $row['contestant_b_id'];
                $gamedraw_id = $row['gamedraw_id'];
                $gameset_id = $row['gameset_id'];

                $res['scores']['sets']['end_set'] = $this->count_gameset_per_draw($gamedraw_id);

                $scr_a = $this->contestant_score($gameset_id, $contestant_a_id);
                $scr_b = $this->contestant_score($gameset_id, $contestant_b_id);
                if( !empty($scr_a)) {
                    $res['scores']['contestants'][0]['id'] = $scr_a['contestant_id'];
                    $res['scores']['contestants'][0]['score_timer'] = $scr_a['score_timer'];
                    $res['scores']['contestants'][0]['score_1'] = is_null($scr_a['score_1']) ? '' : $scr_a['score_1'];
                    $res['scores']['contestants'][0]['score_2'] = is_null($scr_a['score_2']) ? '' : $scr_a['score_2'];
                    $res['scores']['contestants'][0]['score_3'] = is_null($scr_a['score_3']) ? '' : $scr_a['score_3'];
                    $res['scores']['contestants'][0]['score_4'] = is_null($scr_a['score_4']) ? '' : $scr_a['score_4'];
                    $res['scores']['contestants'][0]['score_5'] = is_null($scr_a['score_5']) ? '' : $scr_a['score_5'];
                    $res['scores']['contestants'][0]['score_6'] = is_null($scr_a['score_6']) ? '' : $scr_a['score_6'];
                    $res['scores']['contestants'][0]['set_scores'] = $this->total_score_per_set( [ $scr_a['score_1'], $scr_a['score_2'], $scr_a['score_3'], $scr_a['score_4'], $scr_a['score_5'], $scr_a['score_6'] ] );
                    $res['scores']['contestants'][0]['set_points'] = $scr_a['set_points'];
                    $res['scores']['contestants'][0]['desc'] = $scr_a['score_desc'];
                }
                if( !empty($scr_b)) {
                    $res['scores']['contestants'][1]['id'] = $scr_b['contestant_id'];
                    $res['scores']['contestants'][1]['score_timer'] = $scr_b['score_timer'];
                    $res['scores']['contestants'][1]['score_1'] = is_null($scr_b['score_1']) ? '' : $scr_b['score_1'];
                    $res['scores']['contestants'][1]['score_2'] = is_null($scr_b['score_2']) ? '' : $scr_b['score_2'];
                    $res['scores']['contestants'][1]['score_3'] = is_null($scr_b['score_3']) ? '' : $scr_b['score_3'];
                    $res['scores']['contestants'][1]['score_4'] = is_null($scr_b['score_4']) ? '' : $scr_b['score_4'];
                    $res['scores']['contestants'][1]['score_5'] = is_null($scr_b['score_5']) ? '' : $scr_b['score_5'];
                    $res['scores']['contestants'][1]['score_6'] = is_null($scr_b['score_6']) ? '' : $scr_b['score_6'];
                    $res['scores']['contestants'][1]['set_scores'] = $this->total_score_per_set( [ $scr_b['score_1'], $scr_b['score_2'], $scr_b['score_3'], $scr_b['score_4'], $scr_b['score_5'], $scr_b['score_6'] ] );
                    $res['scores']['contestants'][1]['set_points'] = $scr_b['set_points'];
                    $res['scores']['contestants'][1]['desc'] = $scr_b['score_desc'];
                }

                $total_val_a = $this->total_score_per_draw($gamedraw_id, $contestant_a_id);
                $res['scores']['contestants'][0]['game_scores'] = $total_val_a['game_scores'];
                $res['scores']['contestants'][0]['game_points'] = $total_val_a['game_points'];
                $total_val_b = $this->total_score_per_draw($gamedraw_id, $contestant_b_id);
                $res['scores']['contestants'][1]['game_scores'] = $total_val_b['game_scores'];
                $res['scores']['contestants'][1]['game_points'] = $total_val_b['game_points'];
            }
        }

        return $res;
    }

    /**
     * Count Game Set per Draw
     *
     * @param integer $gamedraw_id Game Draw ID
     * @return integer
     */
    private function count_gameset_per_draw($gamedraw_id = 0) {

        $query =
        "SELECT COUNT(gs.gameset_id) as total_set
        FROM gameset gs
        WHERE gs.gamedraw_id = {$gamedraw_id}";

        if ($result = $this->connection->query($query)) {
            if ($result->num_rows == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                return $row['total_set'];
            }
        }

        return 0;
    }

    /**
     * Get Contestant Score
     *
     * @param integer $gameset_id Game Set ID
     * @param integer $contestant_id Contestant ID
     * @return array
     */
    private function contestant_score($gameset_id=0,$contestant_id=0) {

        $query =
        "SELECT s.score_id, s.contestant_id, s.score_timer, s.score_1, s.score_2, s.score_3, s.score_4, s.score_5, s.score_6, s.set_points, s.score_desc
        FROM score s
        WHERE s.gameset_id = {$gameset_id} AND s.contestant_id = {$contestant_id}";

        if ($result = $this->connection->query($query)) {
            if ($result->num_rows == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                return $row;
            }
        }

        return [];
    }

    /**
     * Total Score per Draw
     *
     * @param integer $gamedraw_id Game Draw ID
     * @param integer $contestant_id Contestant ID
     * @return array
     */
    private function total_score_per_draw($gamedraw_id=0, $contestant_id =0) {
        $res = array();
        $gamescores = 0;
        $gamepoints = 0;

        $query =
        "SELECT s.score_1, s.score_2, s.score_3, s.score_4, s.score_5, s.score_6, s.set_points
        FROM gameset gs
        RIGHT JOIN score s ON gs.gameset_id = s.gameset_id
        WHERE gs.gamedraw_id = {$gamedraw_id} AND s.contestant_id = {$contestant_id}";

        if ($result = $this->connection->query($query)) {
            if ($result->num_rows > 0) {
                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $gamescores += $this->total_score_per_set( [ $row['score_1'], $row['score_2'], $row['score_3'], $row['score_4'], $row['score_5'], $row['score_6'] ] );
                    $gamepoints += $row['set_points'];
                }
            }
        }
        $res['game_scores'] = $gamescores;
        $res['game_points'] = $gamepoints;

        return $res;
    }

    /**
     * Total Score per Set
     *
     * @param array $scores Score Array
     * @return int
     */
    private function total_score_per_set($scores=array()){
        // $this->total_score_per_set( [ $row['score_1'], $row['score_2'], $row['score_3'], $row['score_4'], $row['score_5'], $row['score_6'] ] );
        $value = 0;
        for($i=0; $i<sizeof($scores); $i++) {
            $score = is_null($scores[$i]) ? 0 : str_replace('*','',$scores[$i]);
            if( !is_numeric($score)) {
                $score = 0;
            }
            $value += $score;
        }
        return $value;
    }

    /**
     * Team Scores ID
     *
     * @param integer $team_id Team ID
     * @return array [score_id1, score_id2, score_id..n]
     */
    public function team_scores_id($team_id = 0) {
        $scores_id = array();
        if ($team_id > 0) {

            $query =
            "SELECT score_id FROM {$this->table_name}
            WHERE gameset_id IN
            ( SELECT gameset_id FROM gameset
            WHERE gamedraw_id IN
            ( SELECT gamedraw_id FROM gamedraw
            WHERE gamemode_id = 1 AND (contestant_a_id = {$team_id} OR contestant_b_id = {$team_id})
            UNION
            SELECT gamedraw_id FROM gamedraw
            WHERE gamemode_id = 2 AND ( contestant_a_id IN
            ( SELECT player_id FROM player WHERE team_id = {$team_id} )
            OR contestant_b_id IN
            ( SELECT player_id FROM player WHERE team_id = {$team_id} ) ) ) )";

            if ($result = $this->connection->query($query)) {
                if ($result->num_rows > 0) {
                    $i = 0;
                    while ($row = $result->fetch_assoc()) {
                        $scores_id[$i] = $row['score_id'];
                        $i++;
                    }
                }
            }
        }

        return $scores_id;
    }

    /**
     * Player Scores ID
     *
     * @param integer $player_id Player ID
     * @return array [score_id1, score_id2, score_id..n]
     */
    public function player_scores_id($player_id = 0) {
        $scores_id = array();
        if ($player_id > 0) {

            $query =
            "SELECT score_id FROM {$this->table_name}
            WHERE gameset_id IN
            (SELECT gameset_id FROM gameset
            WHERE gamedraw_id IN
            (SELECT gamedraw_id FROM gamedraw
            WHERE gamemode_id=2 AND (contestant_a_id={$player_id} OR contestant_b_id={$player_id})))";

            if ($result = $this->connection->query($query)) {
                if ($result->num_rows > 0) {
                    $i = 0;
                    while ($row = $result->fetch_assoc()) {
                        $scores_id[$i] = $row['score_id'];
                        $i++;
                    }
                }
            }
        }

        return $scores_id;
    }

    /**
     * Gamedraw Scores ID
     *
     * @param integer $gamedraw_id Gamedraw ID
     * @return array [score_id1, score_id2, score_id..n]
     */
    public function gamedraw_scores_id($gamedraw_id = 0) {
        $scores_id = array();
        if ($gamedraw_id > 0) {

            $query =
            "SELECT score_id FROM {$this->table_name}
            WHERE gameset_id IN
            ( SELECT gameset_id FROM gameset WHERE gamedraw_id = {$gamedraw_id} )";

            if ($result = $this->connection->query($query)) {
                if ($result->num_rows > 0) {
                    $i = 0;
                    while ($row = $result->fetch_assoc()) {
                        $scores_id[$i] = $row['score_id'];
                        $i++;
                    }
                }
            }
        }

        return $scores_id;
    }

    /**
     * Form Data
     *
     * @param integer $gamemode_id Game Mode ID
     * @return array
     */
    public function form_data( $gamemode_id=0) {
        $res = array();

        $query =
        "SELECT gd.gamedraw_id, gs.gameset_id, gd.gamemode_id, gs.gameset_num, gd.contestant_a_id, gd.contestant_b_id
        FROM livegame l
        LEFT JOIN gameset gs ON l.gameset_id = gs.gameset_id
        LEFT JOIN gamedraw gd ON gs.gamedraw_id = gd.gamedraw_id";

        if ($result = $this->connection->query($query)) {
            if ($result->num_rows == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $res['gamedraw_id'] = $row['gamedraw_id'];
                $res['gameset_id'] = $row['gameset_id'];
                $res['gamemode_id'] = $row['gamemode_id'];
                $res['sets']['curr_set'] = $row['gameset_num'];

                $contestant_a_id = $row['contestant_a_id'];
                $contestant_b_id = $row['contestant_b_id'];

                $res['sets']['end_set'] = $this->count_gameset_per_draw($row['gamedraw_id']);

                $cnt_data_a = $this->contestant_data($gamemode_id, $contestant_a_id);
                if( !empty($cnt_data_a)){
                    $res['contestants'][0]['logo'] = $cnt_data_a['logo'];
                    $res['contestants'][0]['team'] = $cnt_data_a['team'];
                    $res['contestants'][0]['player'] = $cnt_data_a['player'];
                }
                $cnt_data_b = $this->contestant_data($gamemode_id, $contestant_b_id);
                if( !empty($cnt_data_b)) {
                    $res['contestants'][1]['logo'] = $cnt_data_b['logo'];
                    $res['contestants'][1]['team'] = $cnt_data_b['team'];
                    $res['contestants'][1]['player'] = $cnt_data_b['player'];
                }
                $scr_a = $this->contestant_score($row['gameset_id'], $contestant_a_id);
                $scr_b = $this->contestant_score($row['gameset_id'], $contestant_b_id);
                if( !empty($scr_a)) {
                    $res['contestants'][0]['score_id'] = $scr_a['score_id'];
                    $res['contestants'][0]['score_timer'] = $scr_a['score_timer'];
                    $res['contestants'][0]['score_1'] = is_null($scr_a['score_1']) ? '' : $scr_a['score_1'];
                    $res['contestants'][0]['score_2'] = is_null($scr_a['score_2']) ? '' : $scr_a['score_2'];
                    $res['contestants'][0]['score_3'] = is_null($scr_a['score_3']) ? '' : $scr_a['score_3'];
                    $res['contestants'][0]['score_4'] = is_null($scr_a['score_4']) ? '' : $scr_a['score_4'];
                    $res['contestants'][0]['score_5'] = is_null($scr_a['score_5']) ? '' : $scr_a['score_5'];
                    $res['contestants'][0]['score_6'] = is_null($scr_a['score_6']) ? '' : $scr_a['score_6'];
                    $res['contestants'][0]['set_scores'] = $this->total_score_per_set( [ $scr_a['score_1'], $scr_a['score_2'], $scr_a['score_3'], $scr_a['score_4'], $scr_a['score_5'], $scr_a['score_6'] ] );
                    $res['contestants'][0]['set_points'] = $scr_a['set_points'];
                    $res['contestants'][0]['desc'] = is_null($scr_a['score_desc']) ? '' : $scr_a['score_desc'];
                }
                if( !empty($scr_b)) {
                    $res['contestants'][1]['score_id'] = $scr_b['score_id'];
                    $res['contestants'][1]['score_timer'] = $scr_b['score_timer'];
                    $res['contestants'][1]['score_1'] = is_null($scr_b['score_1']) ? '' : $scr_b['score_1'];
                    $res['contestants'][1]['score_2'] = is_null($scr_b['score_2']) ? '' : $scr_b['score_2'];
                    $res['contestants'][1]['score_3'] = is_null($scr_b['score_3']) ? '' : $scr_b['score_3'];
                    $res['contestants'][1]['score_4'] = is_null($scr_b['score_4']) ? '' : $scr_b['score_4'];
                    $res['contestants'][1]['score_5'] = is_null($scr_b['score_5']) ? '' : $scr_b['score_5'];
                    $res['contestants'][1]['score_6'] = is_null($scr_b['score_6']) ? '' : $scr_b['score_6'];
                    $res['contestants'][1]['set_scores'] = $this->total_score_per_set( [ $scr_b['score_1'], $scr_b['score_2'], $scr_b['score_3'], $scr_b['score_4'], $scr_b['score_5'], $scr_b['score_6'] ] );
                    $res['contestants'][1]['set_points'] = $scr_b['set_points'];
                    $res['contestants'][1]['desc'] = is_null($scr_b['score_desc']) ? '' : $scr_b['score_desc'];
                }

                $total_val_a = $this->total_score_per_draw($row['gamedraw_id'], $contestant_a_id);
                $res['contestants'][0]['game_scores'] = $total_val_a['game_scores'];
                $res['contestants'][0]['game_points'] = $total_val_a['game_points'];
                $total_val_b = $this->total_score_per_draw($row['gamedraw_id'], $contestant_b_id);
                $res['contestants'][1]['game_scores'] = $total_val_b['game_scores'];
                $res['contestants'][1]['game_points'] = $total_val_b['game_points'];
            }
        }

        return $res;
    }

    /**
     * Scoreboard Preview Data
     *
     * @param integer $gamemode_id Game Mode ID
     * @return array
     */
    public function scoreboard_preview_data( $gamemode_id = 0) {
        $res = array();

        $query =
        "SELECT gd.gamedraw_id, gs.gameset_id, gd.gamemode_id, gd.bowstyle_id, gs.gameset_num, gd.contestant_a_id, gd.contestant_b_id
        FROM livegame l
        LEFT JOIN gameset gs ON l.gameset_id = gs.gameset_id
        LEFT JOIN gamedraw gd ON gs.gamedraw_id = gd.gamedraw_id";

        if ($result = $this->connection->query($query)) {
            if ($result->num_rows == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $res['gamedraw_id'] = $row['gamedraw_id'];
                $res['gameset_id'] = $row['gameset_id'];
                $res['gamemode_id'] = $row['gamemode_id'];
                $res['bowstyle_id'] = $row['bowstyle_id'];
                $res['sets']['curr_set'] = $row['gameset_num'];

                $contestant_a_id = $row['contestant_a_id'];
                $contestant_b_id = $row['contestant_b_id'];

                $res['sets']['end_set'] = $this->count_gameset_per_draw($row['gamedraw_id']);

                $cnt_data_a = $this->contestant_data($gamemode_id, $contestant_a_id);
                if( !empty($cnt_data_a)){
                    $res['contestants'][0]['logo'] = $cnt_data_a['logo'];
                    $res['contestants'][0]['team'] = $cnt_data_a['team'];
                    $res['contestants'][0]['player'] = $cnt_data_a['player'];
                }
                $cnt_data_b = $this->contestant_data($gamemode_id, $contestant_b_id);
                if( !empty($cnt_data_b)) {
                    $res['contestants'][1]['logo'] = $cnt_data_b['logo'];
                    $res['contestants'][1]['team'] = $cnt_data_b['team'];
                    $res['contestants'][1]['player'] = $cnt_data_b['player'];
                }
                $scr_a = $this->contestant_score($row['gameset_id'], $contestant_a_id);
                $scr_b = $this->contestant_score($row['gameset_id'], $contestant_b_id);
                if( !empty($scr_a)) {
                    $res['contestants'][0]['score_timer'] = $scr_a['score_timer'];
                    $res['contestants'][0]['score_1'] = is_null($scr_a['score_1']) ? '' : $scr_a['score_1'];
                    $res['contestants'][0]['score_2'] = is_null($scr_a['score_2']) ? '' : $scr_a['score_2'];
                    $res['contestants'][0]['score_3'] = is_null($scr_a['score_3']) ? '' : $scr_a['score_3'];
                    $res['contestants'][0]['score_4'] = is_null($scr_a['score_4']) ? '' : $scr_a['score_4'];
                    $res['contestants'][0]['score_5'] = is_null($scr_a['score_5']) ? '' : $scr_a['score_5'];
                    $res['contestants'][0]['score_6'] = is_null($scr_a['score_6']) ? '' : $scr_a['score_6'];
                    $res['contestants'][0]['set_scores'] = $this->total_score_per_set( [ $scr_a['score_1'], $scr_a['score_2'], $scr_a['score_3'], $scr_a['score_4'], $scr_a['score_5'], $scr_a['score_6'] ] );
                    $res['contestants'][0]['set_points'] = $scr_a['set_points'];
                    $res['contestants'][0]['desc'] = is_null($scr_a['score_desc']) ? '' : $scr_a['score_desc'];
                }
                if( !empty($scr_b)) {
                    $res['contestants'][1]['score_timer'] = $scr_b['score_timer'];
                    $res['contestants'][1]['score_1'] = is_null($scr_b['score_1']) ? '' : $scr_b['score_1'];
                    $res['contestants'][1]['score_2'] = is_null($scr_b['score_2']) ? '' : $scr_b['score_2'];
                    $res['contestants'][1]['score_3'] = is_null($scr_b['score_3']) ? '' : $scr_b['score_3'];
                    $res['contestants'][1]['score_4'] = is_null($scr_b['score_4']) ? '' : $scr_b['score_4'];
                    $res['contestants'][1]['score_5'] = is_null($scr_b['score_5']) ? '' : $scr_b['score_5'];
                    $res['contestants'][1]['score_6'] = is_null($scr_b['score_6']) ? '' : $scr_b['score_6'];
                    $res['contestants'][1]['set_scores'] = $this->total_score_per_set( [ $scr_b['score_1'], $scr_b['score_2'], $scr_b['score_3'], $scr_b['score_4'], $scr_b['score_5'], $scr_b['score_6'] ] );
                    $res['contestants'][1]['set_points'] = $scr_b['set_points'];
                    $res['contestants'][1]['desc'] = is_null($scr_b['score_desc']) ? '' : $scr_b['score_desc'];
                }

                $total_val_a = $this->total_score_per_draw($row['gamedraw_id'], $contestant_a_id);
                $res['contestants'][0]['game_scores'] = $total_val_a['game_scores'];
                $res['contestants'][0]['game_points'] = $total_val_a['game_points'];
                $total_val_b = $this->total_score_per_draw($row['gamedraw_id'], $contestant_b_id);
                $res['contestants'][1]['game_scores'] = $total_val_b['game_scores'];
                $res['contestants'][1]['game_points'] = $total_val_b['game_points'];
            }
        }

        return $res;
    }

    /**
     * Contestant Data
     *
     * @param integer $gamemode_id Game Mode ID
     * @param integer $contestant_id Contestant ID
     * @return array
     */
    private function contestant_data($gamemode_id = 0, $contestant_id=0) {
        $res = array();

        if( $gamemode_id == 1) { // team

            $query =
            "SELECT team_logo, team_name
            FROM team
            WHERE team_id = {$contestant_id}";

            if ($result = $this->connection->query($query)) {
                if ($result->num_rows == 1) {
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $res['logo'] = is_null($row['team_logo']) || $row['team_logo'] == '' ? 'uploads/no-image.png' : 'uploads/'.$row['team_logo'];
                    $res['team'] = is_null($row['team_name']) ? '-' : $row['team_name'];
                    $res['player'] = '-';
                }
            }

        }else if( $gamemode_id == 2) { // individu

            $query =
            "SELECT t.team_logo, t.team_name, p.player_name
            FROM player p
            LEFT JOIN team t ON p.team_id = t.team_id
            WHERE p.player_id = {$contestant_id}";

            if ($result = $this->connection->query($query)) {
                if ($result->num_rows == 1) {
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $res['logo'] = is_null($row['team_logo']) || $row['team_logo'] == '' ? 'uploads/no-image.png' : 'uploads/'.$row['team_logo'];
                    $res['team'] = is_null($row['team_name']) ? '-' : $row['team_name'];
                    $res['player'] = $row['player_name'];
                }
            }
        }

        return $res;
    }

    /**
     * Update Score
     *
     * @param array $data Score Data
     * @return boolean true | false
     */
    public function update($data=null){

        // $score_data = [
        //     'gamedraw_id'   => Tools::post_int($_POST[ "{$owner}gamedraw_id" ]),
        //     'gameset_id'    => Tools::post_int($_POST[ "{$owner}gameset_id" ]),
        //     'score_id'      => Tools::post_int($_POST[ "{$owner}id" ]),
        //     'timer'         => isset($_POST[ "{$owner}timer" ]) ? str_replace("s", "", $_POST[ "{$owner}timer" ]) : 0,
        //     'score_1'       => Tools::post_int($_POST[ "{$owner}score1" ]),
        //     'score_2'       => Tools::post_int($_POST[ "{$owner}score2" ]),
        //     'score_3'       => Tools::post_int($_POST[ "{$owner}score3" ]),
        //     'score_4'       => Tools::post_int($_POST[ "{$owner}score4" ]),
        //     'score_5'       => Tools::post_int($_POST[ "{$owner}score5" ]),
        //     'score_6'       => Tools::post_int($_POST[ "{$owner}score6" ]),
        //     'setpoints'     => Tools::post_int($_POST[ "{$owner}setpoints" ]),
        //     'score_desc'    => isset($_POST[ "{$owner}desc" ]) ? $_POST[ "{$owner}desc" ] : ''
        // ];
        $sql =
        "UPDATE {$this->table_name}
        SET score_1='{$data['score_1']}',
        score_2='{$data['score_2']}',
        score_3='{$data['score_3']}',
        score_4='{$data['score_4']}',
        score_5='{$data['score_5']}',
        score_6='{$data['score_6']}',
        score_desc='{$data['score_desc']}',
        set_points='{$data['setpoints']}'
        WHERE score_id={$data['score_id']}";

        return ($this->connection->query($sql) === TRUE);
    }

    public function UpdateScoreTimer() {
        $sql = "UPDATE " . $this->table_name . " SET score_timer={$this->timer} WHERE score_id={$this->id}";

        $res = array('status' => false);
        if ($this->connection->query($sql) === TRUE) {

            $res = array(
                'status' => true,
            );
        }

        return $res;
    }
}

?>