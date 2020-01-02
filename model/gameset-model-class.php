<?php
namespace scoreboard\model;

class Gameset_Model_Class {

    private $connection;
    private $table_name;
    private $id_col;

    public function __construct($connection) {
        $this->connection = $connection;
        $this->table_name = 'gameset';
        $this->id_col = 'gameset_id';
    }

    /**
     * Gamedraw Gameset ID
     *
     * @param array|integer Gamedraw ID
     * @return array (gameset_id1, gameset_id2, gameset_id..n)
     */
    public function gamedraw_gamesets_id($gamedraw_id) {
        $gamesets_id = array();

        $query =
        "SELECT gameset_id
        FROM {$this->table_name}
        WHERE gamedraw_id = {$gamedraw_id}";

        if ($result = $this->connection->query($query)) {
            if ($result->num_rows > 0) {
                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $gamesets_id[$i] = $row['gameset_id'];
                    $i++;
                }
            }
        }

        return $gamesets_id;
    }

    /**
     * Create Gameset
     *
     * @param array $name Gameset Data
     * @return integer false (0) | true (latest_id)
     */
    public function create_gameset( $data=null, $get_new_id = false){
        // $gameset_data = array(
        //     'id' => $gameset_id,
        //     'gamedraw_id' => 0,
        //     'num' => 1,
        //     'status_id' => 1,
        // );
        $num = $data['num'];
        $gamedraw_id = $data['gamedraw_id'];
        $sql = "INSERT INTO {$this->table_name} (gamedraw_id, gameset_num) VALUES ({$gamedraw_id}, {$num})";

        $is_success = ($this->connection->query($sql) === TRUE);
        if( $get_new_id ){
            if ($is_success) {
                return $this->connection->insert_id;
            }
            return 0;
        }
        return $is_success;
    }

    /**
     * Update Gameset
     *
     * @param array $data Gameset Data
     * @return boolean true | false
     */
    public function update_gameset($data=null){
        $id = $data['id'];
        $num = $data['num'];
        $status_id = $data['status_id'];

        $sql = "UPDATE {$this->table_name} SET gameset_num={$num}, gameset_status={$status_id} WHERE gameset_id={$id}";

        return ($this->connection->query($sql) === TRUE);
    }

    /**
     * Update Status:
     * Standby: 1,
     * Live: 2,
     * Finish: 3,
     *
     * @param integer $gameset_id Gameset ID
     * @param integer $status Status ID
     * @return boolean
     */
    public function update_status($gameset_id=0, $status=0) {
        $sql = "UPDATE {$this->table_name} SET gameset_status={$status} WHERE gameset_id={$gameset_id}";

        return $this->connection->query($sql);
    }

    /**
     * Delete Gameset
     *
     * @param array|integer Gameset ID
     * @return boolean
     */
    public function delete_gameset($gameset_id) {
        if (is_array($gameset_id)) {
            $imp_gameset_id = implode(',', $gameset_id);
            $sql = "DELETE FROM {$this->table_name} WHERE gameset_id IN ({$imp_gameset_id})";
            return $this->connection->query($sql);
        }else if( is_numeric($gameset_id) || is_int($gameset_id)){
            $sql = "DELETE FROM {$this->table_name} WHERE gameset_id = {$gameset_id}";
            return $this->connection->query($sql);
        }
    }

    /**
     * Delete Gamesets
     *
     * @param array Gamesets ID
     * @return boolean
     */
    public function delete_gamesets($gamesets_id) {
        $imp_gamesets_id = implode(',', $gamesets_id);
        $sql = "DELETE FROM {$this->table_name} WHERE gameset_id IN ({$imp_gamesets_id})";
        return $this->connection->query($sql);
    }

    /**
     * Team Gamesets ID
     *
     * @param integer $team_id Team ID
     * @return array [gameset_id1, gameset_id2, gameset_id..n]
     */
    public function team_gamesets_id($team_id = 0) {
        $gamesets_id = array();
        if ($team_id > 0) {

            $query =
            "SELECT gameset_id FROM {$this->table_name}
            WHERE gamedraw_id IN
            ( SELECT gamedraw_id FROM gamedraw
            WHERE gamemode_id = 1 AND (contestant_a_id = {$team_id} OR contestant_b_id = {$team_id})
            UNION
            SELECT gamedraw_id FROM gamedraw
            WHERE gamemode_id = 2 AND ( contestant_a_id IN
            ( SELECT player_id FROM player WHERE team_id = {$team_id} )
            OR contestant_b_id IN
            ( SELECT player_id FROM player WHERE team_id = {$team_id} ) ) )";

            if ($result = $this->connection->query($query)) {
                if ($result->num_rows > 0) {
                    $i = 0;
                    while ($row = $result->fetch_assoc()) {
                        $gamesets_id[$i] = $row['gameset_id'];
                        $i++;
                    }
                }
            }
        }

        return $gamesets_id;
    }

    /**
     * Player Gamesets ID
     *
     * @param integer $player_id Player ID
     * @return array [gameset_id1, gameset_id2, gameset_id..n]
     */
    public function player_gamesets_id($player_id = 0) {
        $gamesets_id = array();
        if ($player_id > 0) {

            $query =
            "SELECT gameset_id FROM {$this->table_name}
            WHERE gamedraw_id IN
            (SELECT gamedraw_id FROM gamedraw
            WHERE gamemode_id=2 AND (contestant_a_id={$player_id} OR contestant_b_id={$player_id}))";

            if ($result = $this->connection->query($query)) {
                if ($result->num_rows > 0) {
                    $i = 0;
                    while ($row = $result->fetch_assoc()) {
                        $gamesets_id[$i] = $row['gameset_id'];
                        $i++;
                    }
                }
            }
        }

        return $gamesets_id;
    }

    /**
     * Table Data
     *
     * @return array
     */
    public function table_data() {
        $res = array();
        // gamestatus_id, id, game_num, bowstyle_name, contestant_a_name, contestant_b_name, set_num, gamestatus_name
        $query =
        "SELECT gs.gameset_id, gd.gamedraw_num, b.bowstyle_name, gs.gameset_num, s.gamestatus_id, s.gamestatus_name, tA.team_name as contestant_a_name, tB.team_name as contestant_b_name
        FROM {$this->table_name} gs
        LEFT JOIN gamestatus s ON gs.gameset_status = s.gamestatus_id
        LEFT JOIN gamedraw gd ON gs.gamedraw_id = gd.gamedraw_id
        LEFT JOIN bowstyles b ON gd.bowstyle_id = b.bowstyle_id
        LEFT JOIN gamemode gm ON gd.gamemode_id = gm.gamemode_id
        LEFT JOIN team tA ON gd.contestant_a_id = tA.team_id
        LEFT JOIN team tB ON gd.contestant_b_id = tB.team_id
        WHERE gd.gamemode_id = 1
        UNION
        SELECT gs.gameset_id, gd.gamedraw_num, b.bowstyle_name, gs.gameset_num, s.gamestatus_id, s.gamestatus_name, pA.player_name as contestant_a_name, pB.player_name as contestant_b_name
        FROM {$this->table_name} gs
        LEFT JOIN gamestatus s ON gs.gameset_status = s.gamestatus_id
        LEFT JOIN gamedraw gd ON gs.gamedraw_id = gd.gamedraw_id
        LEFT JOIN bowstyles b ON gd.bowstyle_id = b.bowstyle_id
        LEFT JOIN gamemode gm ON gd.gamemode_id = gm.gamemode_id
        LEFT JOIN player pA ON gd.contestant_a_id = pA.player_id
        LEFT JOIN player pB ON gd.contestant_b_id = pB.player_id
        WHERE gd.gamemode_id = 2
        ORDER BY gamedraw_num DESC, gameset_num DESC";

        if ($result = $this->connection->query($query)) {
            if ($result->num_rows > 0) {

                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $res[$i]['id'] = $row['gameset_id'];
                    $res[$i]['game_num'] = $row['gamedraw_num'];
                    $res[$i]['set_num'] = $row['gameset_num'];
                    $res[$i]['bowstyle_name'] = $row['bowstyle_name'];
                    $res[$i]['contestant_a_name'] = $row['contestant_a_name'];
                    $res[$i]['contestant_b_name'] = $row['contestant_b_name'];
                    $res[$i]['gamestatus_id'] = $row['gamestatus_id'];
                    $res[$i]['gamestatus_name'] = $row['gamestatus_name'];

                    $i++;
                }
            }
        }

        return $res;
    }

    /**
     * Get Bowstyle ID
     *
     * @param int $gameset_id Gameset ID
     * @return int Bowstyle ID
     */
    public function bowstyle_id($gameset_id) {

        $query = "SELECT gd.bowstyle_id
        FROM {$this->table_name} gs
        LEFT JOIN gamedraw gd ON gd.gamedraw_id = gs.gamedraw_id
        WHERE gs.gameset_id = {$gameset_id}";

        if ($result = $this->connection->query($query)) {
            if ($result->num_rows == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                return $row['bowstyle_id'];
            }
        }

        return 0;

    }

    /**
     * Summary Data
     *
     * @param integer $gameset_id Gameset ID
     * @return array
     */
    public function summary_data($gameset_id=0){
        $res = array();
        $gamemode_sql =
            "SELECT gd.gamemode_id
            FROM gamedraw gd WHERE gd.gamedraw_id IN (SELECT gs.gamedraw_id FROM {$this->table_name} gs WHERE gs.gameset_id = {$gameset_id})";

        if ($result = $this->connection->query($gamemode_sql)) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $gamemode_id = $row['gamemode_id'];
            $contestant_name = '';
            $join_query = '';
            if($gamemode_id == 1){
                $contestant_name = 't.team_name contestant_name';
                $join_query = 'LEFT JOIN team t ON t.team_id = s.contestant_id';
            }else if( $gamemode_id == 2){
                $contestant_name = 'p.player_name contestant_name';
                $join_query = 'LEFT JOIN player p ON p.player_id = s.contestant_id';
            }

            $summary_sql =
            "SELECT {$contestant_name}, s.score_1, s.score_2, s.score_3, (s.score_1 + s.score_2 + s.score_3) setscores, s.set_points setpoints
            FROM score s {$join_query} WHERE s.gameset_id={$gameset_id}";

            if ($result = $this->connection->query($summary_sql)) {

                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $res[$i]['contestant_name'] = $row['contestant_name'];
                    $res[$i]['score_1'] = $row['score_1'];
                    $res[$i]['score_2'] = $row['score_2'];
                    $res[$i]['score_3'] = $row['score_3'];
                    $res[$i]['setscores'] = $row['setscores'];
                    $res[$i]['setpoints'] = $row['setpoints'];

                    $i++;
                }

            }
        }
        return $res;
    }

    /**
     * Modal Data
     *
     * @param integer $gameset_id Gameset ID
     * @return array empty | [ id, num, gamedraw_id, gameset_status ]
     */
    public function modal_form_data($gameset_id=0){
        $res = array();

        if( $gameset_id == 0) return $res;

        $sql =
        "SELECT gameset_id, gamedraw_id, gameset_num, gameset_status
        FROM {$this->table_name}
        WHERE gameset_id={$gameset_id}";

        if( $result = $this->connection->query( $sql ) ){
            if($result->num_rows > 0){
                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                $res['id'] = $row['gameset_id'];
                $res['num'] = $row['gameset_num'];
                $res['gamedraw_id'] = $row['gamedraw_id'];
                $res['gameset_status'] = $row['gameset_status'];
            }
        }

        return $res;
    }

    /**
     * Last Gamedraw Set Number
     *
     * @param integer $gamedraw_id Gamedraw ID
     * @return integer 0 | last_set
     */
    public function last_set($gamedraw_id=0){
        $query =
            "SELECT MAX(gameset_num) as last_set
        FROM {$this->table_name}
        WHERE gamedraw_id={$gamedraw_id}";

        if ($result = $this->connection->query($query)) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            return $row['last_set'];
        }

        return 0;
    }
}
?>