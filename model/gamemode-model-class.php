<?php
namespace scoreboard\model;

class Gamemode_Model_Class extends Model_Class {
    protected $connection;

    protected $table_name;

    protected $id_col;

    public function __construct($db) {
        $this->connection = $db;
        $this->table_name = 'gamemode';
        $this->id_col = 'gamemode_id';
    }

    /**
     * Get Game Mode List
     *
     * return [ status, gamemodes ]
     * @return array
     */
    public function get_gamemode_list() {
        $query = "SELECT gamemode_id, gamemode_name, gamemode_desc FROM " . $this->table_name;

        $result = $this->connection->query($query);

        $res = array('gamemodes' => array(), 'status' => $result->num_rows > 0);

        if ($res['status']) {
            $i = 0;
            $gamemodes = null;
            while ($row = $result->fetch_assoc()) {
                $gamemodes[$i]['id'] = $row['gamemode_id'];
                $gamemodes[$i]['name'] = $row['gamemode_name'];
                $gamemodes[$i]['desc'] = $row['gamemode_desc'];

                $i++;
            }

            $res = array(
                'gamemodes' => $gamemodes,
                'status' => true,
            );
        }

        return $res;
    }

    /**
     * Get Game Mode List
     *
     * return [status,gamemodes]
     * @return array
     */
    public function get_list() {
        $res = array();

        $query = "SELECT gamemode_id, gamemode_name FROM {$this->table_name}";

        if ($result = $this->connection->query($query)) {
            if ($result->num_rows > 0) {
                $res['status'] = true;

                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $res['gamemodes'][$i]['id'] = $row['gamemode_id'];
                    $res['gamemodes'][$i]['name'] = $row['gamemode_name'];

                    $i++;
                }
            }
        }

        return $res;
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
            'name' => '',
            'desc' => '',
        ];

        return array_merge($default_data, $new_data);
    }

    /**
     * Create Default Game Mode
     *
     * @param array $default_data
     * @return boolean
     */
    public function create_default($default_data) {
        $data = $this->merge_data($default_data);

        $sql =
            "INSERT INTO {$this->table_name} (gamemode_id,gamemode_name,gamemode_desc)
        VALUES (" . $data['id'] . ",'" . $data['name'] . "', '" . $data['desc'] . "')";

        if ($this->connection->query($sql) === TRUE) {

            return true;
        }

        return false;
    }
}
?>