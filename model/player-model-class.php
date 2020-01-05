<?php
namespace scoreboard\model;

class Player_Model_Class {

    private $connection;
    private $table_name;
    private $id_col;

    public function __construct($connection) {
        $this->connection = $connection;
        $this->table_name = 'player';
        $this->id_col = 'player_id';
    }

    /**
     * Create Player
     *
     * @param string $name Player Name
     * @param integer $team_id Team ID
     * @return boolean true | false
     */
    public function create_player( $name='player', $team_id=0){
        $sql = "INSERT INTO {$this->table_name} (player_name, team_id) VALUES ('{$name}', {$team_id})";

        return ($this->connection->query($sql) === TRUE);
    }

    /**
     * Update Player
     *
     * @param integer $id Player ID
     * @param string $name Player Name
     * @param integer $team_id Team ID
     * @return boolean true | false
     */
    public function update_player($id=0, $name='player', $team_id=0){
        $sql = "UPDATE {$this->table_name} SET team_id={$team_id}, player_name='{$name}' WHERE player_id={$id}";

        return $this->connection->query($sql) === TRUE;
    }

    /**
     * Delete Player
     *
     * @param array|integer Player ID
     * @return boolean true | false
     */
    public function delete_player($player_id=0) {
        $sql = "DELETE FROM {$this->table_name} WHERE player_id = {$player_id}";
        return $this->connection->query($sql);
    }

    /**
     * Delete Players
     *
     * @param array|integer Players ID
     * @return boolean true | false
     */
    public function delete_players($players_id) {
        $imp_players_id = implode(',', $players_id);
        $sql = "DELETE FROM {$this->table_name} WHERE player_id IN ({$imp_players_id})";
        return $this->connection->query($sql);
    }

    /**
     * Team Players ID
     *
     * @param integer $team_id Team ID
     * @return array (player_id1, player_id2, player_id..n)
     */
    public function team_players_id($team_id = 0) {
        $players_id = array();
        if ($team_id > 0) {

            $query =
            "SELECT player_id
            FROM {$this->table_name}
            WHERE team_id = {$team_id}";

            if ($result = $this->connection->query($query)) {
                if ($result->num_rows > 0) {
                    $i = 0;
                    while ($row = $result->fetch_assoc()) {
                        $players_id[$i] = $row['player_id'];
                        $i++;
                    }
                }
            }
        }

        return $players_id;
    }

    /**
     * Table Data
     *
     *
     * @return array [ id, name, team_name ]
     */
    public function table_data() {
        $res = array();

        $query = "SELECT p.player_id, p.player_name, t.team_name
        FROM {$this->table_name} p
        LEFT JOIN team t ON p.team_id = t.team_id
        ORDER BY t.team_name ASC";

        if ($result = $this->connection->query($query)) {
            if ($result->num_rows > 0) {

                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $res[$i]['id'] = $row['player_id'];
                    $res[$i]['name'] = $row['player_name'];
                    if ($row['team_name'] == NULL) {
                        $res[$i]['team_name'] = 'INDIVIDU';
                    } else {
                        $res[$i]['team_name'] = $row['team_name'];
                    }
                    $i++;
                }
            }
        }

        return $res;
    }

    /**
     * Option Data
     *
     *
     * @return array [ id, name, team_name ]
     */
    public function option_data() {
        $res = array();

        $query = "SELECT p.player_id, p.player_name, t.team_name
        FROM {$this->table_name} p
        LEFT JOIN team t ON p.team_id = t.team_id
        ORDER BY t.team_name ASC";

        if ($result = $this->connection->query($query)) {
            if ($result->num_rows > 0) {

                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $res[$i]['id'] = $row['player_id'];
                    $res[$i]['name'] = $row['player_name'];
                    if ($row['team_name'] == NULL) {
                        $res[$i]['team_name'] = 'INDIVIDU';
                    } else {
                        $res[$i]['team_name'] = $row['team_name'];
                    }
                    $i++;
                }
            }
        }

        return $res;
    }

    /**
     * Data
     *
     *
     * @param integer $player_id Player ID
     * @return mixed false | [player_name, team_name, team_logo]
     */
    public function scoreboard_form_data($player_id=0) {
        $query = "SELECT p.player_name, t.team_name, t.team_logo FROM {$this->table_name} p
        LEFT JOIN team t ON t.team_id = p.team_id
        WHERE p.player_id={$player_id}";

        if ($result = $this->connection->query($query)) {
            $res = array();
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $res['player_name'] = $row['player_name'];
            $res['team_name'] = is_null($row['team_name']) ? '' : $row['team_name'];
            $res['team_logo'] = is_null($row['team_logo']) ? 'no-image.png' : $row['team_logo'];
            return $res;
        }
        return false;
    }

    /**
     * Modal Form Data
     *
     * @param integer $player_id Player ID
     * @return array empty | [ id, name, team_id ]
     */
    public function modal_form_data($player_id=0) {
        $res = array();
        $query =
            "SELECT player_id, player_name, team_id
        FROM {$this->table_name}
        WHERE player_id={$player_id}";

        if ($result = $this->connection->query($query)) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $res['id'] = $row['player_id'];
            $res['name'] = $row['player_name'];
            $res['team_id'] = $row['team_id'];
        }

        return $res;
    }
}
?>