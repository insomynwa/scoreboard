<?php
namespace scoreboard\model;

class Config_Model_Class extends Model_Class {
    protected $connection;

    protected $table_name;

    protected $id_col;

    public function __construct($db) {
        $this->connection = $db;
        $this->table_name = 'config';
        $this->id_col = 'config_id';
    }

    /**
     * Scoreboard Form Style Config
     *
     * @return boolean|string false | String: decode it!
     */
    public function scoreboard_form_config(){
        $query = "SELECT config_value FROM {$this->table_name} WHERE config_name = 'form_scoreboard'";

        if ($result = $this->connection->query($query)) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($row['config_value'] != null && $row['config_value'] != '') {
                return $row['config_value'];
            }
        }
        return false;
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
            'value' => '',
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
            "INSERT INTO {$this->table_name} (config_id,config_name,config_value)
        VALUES (" . $data['id'] . ",'" . $data['name'] . "', '" . $data['value'] . "')";

        if ($this->connection->query($sql) === true) {

            return true;
        }

        return false;
    }
}
