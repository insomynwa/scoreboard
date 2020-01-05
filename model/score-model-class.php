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
    public function create_score( $gameset_id=0, $contestant_id=0){
        $sql = "INSERT INTO {$this->table_name} (gameset_id, contestant_id) VALUES ('{$gameset_id}', '{$contestant_id}')";

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
    public function scoreboard_data() {
        $res = array();

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

        if ($result = $this->connection->query($query)) {
            if ($result->num_rows > 1) {
                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $res['scores']['gamemode_id'] = $row['gamemode_id'];
                    $res['scores']['bowstyle_id'] = $row['bowstyle_id'];
                    $res['scores']['sets']['curr_set'] = $row['gameset_num'];
                    $res['scores']['sets']['end_set'] = $row['n_sets'];
                    $res['scores']['contestants'][$i]['id'] = $row['contestant_id'];
                    $res['scores']['contestants'][$i]['score_timer'] = $row['score_timer'];
                    $res['scores']['contestants'][$i]['score_1'] = $row['score_1'];
                    $res['scores']['contestants'][$i]['score_2'] = $row['score_2'];
                    $res['scores']['contestants'][$i]['score_3'] = $row['score_3'];
                    $res['scores']['contestants'][$i]['score_4'] = $row['score_4'];
                    $res['scores']['contestants'][$i]['score_5'] = $row['score_5'];
                    $res['scores']['contestants'][$i]['score_6'] = $row['score_6'];
                    $res['scores']['contestants'][$i]['set_scores'] = $row['set_scores'];
                    $res['scores']['contestants'][$i]['game_scores'] = $row['game_scores'];
                    $res['scores']['contestants'][$i]['set_points'] = $row['set_points'];
                    $res['scores']['contestants'][$i]['game_points'] = $row['game_points'];
                    $res['scores']['contestants'][$i]['desc'] = $row['score_desc'];
                    $res['scores']['style_config'] = $row['style_config'];
                    $i++;
                }
            }
        }

        return $res;
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
    public function form_data($gamemode_id = 0) {
        $res = array();

        $contestant_col = '';
        $contestant_join = '';
        if( $gamemode_id == 1) { // Team
            $contestant_col = 't.team_logo, t.team_name';
            $contestant_join = 'LEFT JOIN team t ON t.team_id = s.contestant_id';
        }else if( $gamemode_id == 2){ // Individu
            $contestant_col = 't.team_logo, t.team_name, p.player_name';
            $contestant_join =
            "LEFT JOIN player p ON p.player_id = s.contestant_id
            LEFT JOIN team t ON t.team_id = p.team_id";
        }

        $query =
        "SELECT gd.gamedraw_id, gd.gamemode_id, gs.gameset_id, gs.gameset_num, s.score_id, {$contestant_col}, s.score_timer, s.score_1, s.score_2, s.score_3, s.score_4, s.score_5, s.score_6, s.score_desc,
        ( s.score_1 + s.score_2 + s.score_3 + s.score_4 + s.score_5 + s.score_6) as set_scores, s.set_points, ts.game_points, ts.game_scores, ts.n_sets
        FROM livegame l
        LEFT JOIN gameset gs ON gs.gameset_id = l.gameset_id
        LEFT JOIN gamedraw gd ON gd.gamedraw_id = gs.gamedraw_id
        LEFT JOIN score s ON s.gameset_id = gs.gameset_id
        {$contestant_join}
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
        ON ts.contestant_id = s.contestant_id";

        if ($result = $this->connection->query($query)) {
            if ($result->num_rows > 1) {
                $i = 0;
                $res = array();
                while ($row = $result->fetch_assoc()) {
                    $res['gamedraw_id'] = $row['gamedraw_id'];
                    $res['gameset_id'] = $row['gameset_id'];
                    $res['gamemode_id'] = $row['gamemode_id'];
                    $res['sets']['curr_set'] = $row['gameset_num'];
                    $res['sets']['end_set'] = $row['n_sets'];
                    $res['contestants'][$i]['score_id'] = $row['score_id'];
                    // $res['contestants'][$i]['id'] = $row['contestant_id'];
                    $res['contestants'][$i]['logo'] = is_null($row['team_logo']) || $row['team_logo'] == '' ? 'uploads/no-image.png' : 'uploads/'.$row['team_logo'];
                    $res['contestants'][$i]['team'] = $gamemode_id==2 && is_null($row['team_name']) ? 'INDIVIDU' : $row['team_name'];
                    $res['contestants'][$i]['player'] = $gamemode_id!=2 ? '' : $row['player_name'];
                    $res['contestants'][$i]['score_timer'] = $row['score_timer'];
                    $res['contestants'][$i]['score_1'] = $row['score_1'];
                    $res['contestants'][$i]['score_2'] = $row['score_2'];
                    $res['contestants'][$i]['score_3'] = $row['score_3'];
                    $res['contestants'][$i]['score_4'] = $row['score_4'];
                    $res['contestants'][$i]['score_5'] = $row['score_5'];
                    $res['contestants'][$i]['score_6'] = $row['score_6'];
                    $res['contestants'][$i]['set_scores'] = $row['set_scores'];
                    $res['contestants'][$i]['game_scores'] = $row['game_scores'];
                    $res['contestants'][$i]['set_points'] = $row['set_points'];
                    $res['contestants'][$i]['game_points'] = $row['game_points'];
                    $res['contestants'][$i]['desc'] = is_null($row['score_desc']) ? '' : $row['score_desc'];
                    $i++;
                }
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
    public function scoreboard_preview_data($gamemode_id = 0) {
        $res = array();

        $contestant_col = '';
        $contestant_join = '';
        if( $gamemode_id == 1) { // Team
            $contestant_col = 't.team_logo, t.team_name';
            $contestant_join = 'LEFT JOIN team t ON t.team_id = s.contestant_id';
        }else if( $gamemode_id == 2){ // Individu
            $contestant_col = 't.team_logo, t.team_name, p.player_name';
            $contestant_join =
            "LEFT JOIN player p ON p.player_id = s.contestant_id
            LEFT JOIN team t ON t.team_id = p.team_id";
        }

        $query =
        "SELECT gd.gamedraw_id, gd.gamemode_id, gd.bowstyle_id, gs.gameset_id, gs.gameset_num, s.score_id, {$contestant_col}, s.score_timer, s.score_1, s.score_2, s.score_3, s.score_4, s.score_5, s.score_6, s.score_desc,
        ( s.score_1 + s.score_2 + s.score_3 + s.score_4 + s.score_5 + s.score_6) as set_scores, s.set_points, ts.game_points, ts.game_scores, ts.n_sets
        FROM livegame l
        LEFT JOIN gameset gs ON gs.gameset_id = l.gameset_id
        LEFT JOIN gamedraw gd ON gd.gamedraw_id = gs.gamedraw_id
        LEFT JOIN score s ON s.gameset_id = gs.gameset_id
        {$contestant_join}
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
        ON ts.contestant_id = s.contestant_id";

        if ($result = $this->connection->query($query)) {
            if ($result->num_rows > 1) {
                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $res['gamedraw_id'] = $row['gamedraw_id'];
                    $res['gameset_id'] = $row['gameset_id'];
                    $res['gamemode_id'] = $row['gamemode_id'];
                    $res['bowstyle_id'] = $row['bowstyle_id'];
                    $res['sets']['curr_set'] = $row['gameset_num'];
                    $res['sets']['end_set'] = $row['n_sets'];
                    $res['contestants'][$i]['logo'] = is_null($row['team_logo']) || $row['team_logo'] == '' ? 'uploads/no-image.png' : 'uploads/'.$row['team_logo'];
                    $res['contestants'][$i]['team'] = $gamemode_id==2 && is_null($row['team_name']) ? 'INDIVIDU' : $row['team_name'];
                    $res['contestants'][$i]['player'] = $gamemode_id!=2 ? '' : $row['player_name'];
                    // $res['contestants'][$i]['score_id'] = $row['score_id'];
                    // $res['contestants'][$i]['id'] = $row['contestant_id'];
                    $res['contestants'][$i]['score_timer'] = $row['score_timer'];
                    $res['contestants'][$i]['score_1'] = $row['score_1'];
                    $res['contestants'][$i]['score_2'] = $row['score_2'];
                    $res['contestants'][$i]['score_3'] = $row['score_3'];
                    $res['contestants'][$i]['score_4'] = $row['score_4'];
                    $res['contestants'][$i]['score_5'] = $row['score_5'];
                    $res['contestants'][$i]['score_6'] = $row['score_6'];
                    $res['contestants'][$i]['set_scores'] = $row['set_scores'];
                    $res['contestants'][$i]['game_scores'] = $row['game_scores'];
                    $res['contestants'][$i]['set_points'] = $row['set_points'];
                    $res['contestants'][$i]['game_points'] = $row['game_points'];
                    $res['contestants'][$i]['desc'] = is_null($row['score_desc']) ? '' : $row['score_desc'];
                    $i++;
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
        SET score_1={$data['score_1']},
        score_2={$data['score_2']},
        score_3={$data['score_3']},
        score_4={$data['score_4']},
        score_5={$data['score_5']},
        score_6={$data['score_6']},
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