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
     * Radio Data
     *
     * return [ id, name ]
     * @return array
     */
    public function radio_data() {
        $res = array();

        $query = "SELECT gamemode_id, gamemode_name FROM {$this->table_name}";

        if ($result = $this->connection->query($query)) {
            if ($result->num_rows > 0) {

                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $res[$i]['id'] = $row['gamemode_id'];
                    $res[$i]['name'] = $row['gamemode_name'];

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