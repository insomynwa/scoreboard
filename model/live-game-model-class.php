<?php
namespace scoreboard\model;

class Live_Game_Model_Class extends Model_Class {
    protected $connection;

    protected $table_name;

    protected $id_col;
    private $live_game_id;

    public function __construct($db) {
        $this->connection = $db;
        $this->table_name = 'livegame';
        $this->id_col = 'livegame_id';
        $this->live_game_id = 1;
    }

    /**
     * Gameset ID.
     * Can be use to check if there is a live game
     *
     * @param boolean $live_check For live check
     * @return integer|boolean If $live_check is TRUE, return boolean
     */
    public function gameset_id($live_check = false) {
        $query = "SELECT gameset_id FROM {$this->table_name} WHERE livegame_id={$this->live_game_id}";

        if ($result = $this->connection->query($query)) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $gameset_id = $row['gameset_id'];
            if ($live_check) {
                return $gameset_id == 0 ? false : true;
            }
            return $gameset_id;
        }
        return 0;
    }

    /**
     * Merge Default Data and New Data
     *
     * @param array $new_data
     * @return array
     */
    protected function merge_data($new_data) {
        $default_data = [
            'id' => 0,
            'gameset_id' => '',
            'style_id' => '',
        ];

        return array_merge($default_data, $new_data);
    }

    /**
     * Create Default Config
     *
     * @param array $default_data
     * @return boolean
     */
    public function create_default($default_data) {
        $data = $this->merge_data($default_data);

        $sql =
            "INSERT INTO {$this->table_name} (livegame_id,gameset_id,scoreboard_style_id)
        VALUES (" . $data['id'] . ",'" . $data['gameset_id'] . "', '" . $data['style_id'] . "')";

        if ($this->connection->query($sql) === true) {

            return true;
        }

        return false;
    }

    /**
     * Set Live Game
     *
     * @param integer Game Set ID
     * @return boolean
     */
    public function set_live_game($gameset_id = 0) {
        $query = "UPDATE {$this->table_name} SET gameset_id={$gameset_id} WHERE livegame_id=1";

        return $this->connection->query($query);
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
    public function set_style($scoreboard_style_id = 0) {
        $query = "UPDATE {$this->table_name} SET scoreboard_style_id={$scoreboard_style_id} WHERE livegame_id=1";

        return $this->connection->query($query);
    }

    /**
     * Get Scoreboard Style ID
     *
     * @return integer
     */
    public function get_style_id() {
        $query = "SELECT scoreboard_style_id FROM {$this->table_name} WHERE livegame_id=1";

        if ($result = $this->connection->query($query)) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            return $row['scoreboard_style_id'];
        }

        return 0;
    }

    /**
     * Is score ready to launch
     *
     * @return boolean
     */
    public function is_ready() {
        $res = false;
        $query = "SELECT gameset_id, scoreboard_style_id FROM {$this->table_name} WHERE livegame_id=1";

        if ($result = $this->connection->query($query)) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $game_is_ready = $row['gameset_id'] > 0;
            $style_is_ready = $row['scoreboard_style_id'] > 0;
            $res = ($game_is_ready && $style_is_ready);
        }

        return $res;
    }

    /**
     * Clean Scoreboard Style
     *
     * @return boolean
     */
    public function clean_style() {
        $sql = "UPDATE {$this->table_name} SET scoreboard_style_id = 0 WHERE livegame_id=1";

        return $this->connection->query($sql);
    }

    /**
     * Get Game Bow Style ID
     *
     * @return integer
     */
    public function game_bowstyle_id() {
        $query =
            "SELECT gd.bowstyle_id
        FROM {$this->table_name} l
        LEFT JOIN gameset gs
        ON l.gameset_id = gs.gameset_id
        LEFT JOIN gamedraw gd
        ON gs.gamedraw_id = gd.gamedraw_id";

        if ($result = $this->connection->query($query)) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($row['bowstyle_id'] != NULL) {
                return $row['bowstyle_id'];
            }
        }
        return 0;
    }

    /**
     * Game Data BM (Bowstyle ID & )
     *
     * @return array [ 'bowstyle_id', 'gamemode_id' ]
     */
    public function game_data_bm_id() {
        $res = array();
        $query =
        "SELECT gd.bowstyle_id, gd.gamemode_id
        FROM {$this->table_name} l
        LEFT JOIN gameset gs
        ON l.gameset_id = gs.gameset_id
        LEFT JOIN gamedraw gd
        ON gs.gamedraw_id = gd.gamedraw_id";

        if ($result = $this->connection->query($query)) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($row['bowstyle_id'] != NULL && $row['gamemode_id'] != NULL) {
                $res['bowstyle_id'] = $row['bowstyle_id'];
                $res['gamemode_id'] = $row['gamemode_id'];
            }
        }
        return $res;
    }

    /**
     * Get Style Bowstyle ID
     *
     *
     * @return integer Style Bowstyle ID
     */
    public function get_style_bowstyle_id() {
        $query = "SELECT ss.bowstyle_id
        FROM {$this->table_name} l
        LEFT JOIN scoreboard_style ss ON ss.id = l.scoreboard_style_id";

        if ($result = $this->connection->query($query)) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($row['bowstyle_id'] != NULL) {
                return $row['bowstyle_id'];
            }
        }
        return 0;

    }
}
?>