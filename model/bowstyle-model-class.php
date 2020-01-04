<?php
namespace scoreboard\model;

class Bowstyle_Model_Class extends Model_Class {
    protected $connection;

    protected $table_name;

    protected $id_col;

    public function __construct($db) {
        $this->connection = $db;
        $this->table_name = 'bowstyles';
        $this->id_col = 'bowstyle_id';
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
        ];

        return array_merge($default_data, $new_data);
    }

    /**
     * Create Default Bowstyle
     *
     * @param array $default_data Bowstyle Default Data
     * @return boolean
     */
    public function create_default($default_data) {
        $data = $this->merge_data($default_data);

        $sql =
            "INSERT INTO {$this->table_name} (bowstyle_id,bowstyle_name)
        VALUES (" . $data['id'] . ",'" . $data['name'] . "')";

        return ($this->connection->query($sql) === TRUE);
    }

    /**
     * Bowstyle List
     *
     * @return array empty | array[ bowstyles[] ]
     */
    public function list() {
        $res = array();

        $query = "SELECT bowstyle_id, bowstyle_name FROM {$this->table_name}";

        if ($result = $this->connection->query($query)) {
            if ($result->num_rows > 0) {
                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $res['bowstyles'][$i]['id'] = $row['bowstyle_id'];
                    $res['bowstyles'][$i]['name'] = $row['bowstyle_name'];

                    $i++;
                }
            }
        }

        return $res;
    }

    /**
     * Option Data
     *
     * @param integer $selected_id Selected ID
     * @return array
     */
    public function option_data($selected_id = 0) {
        $res = array();
        $res[0]['id'] = 0;
        $res[0]['name'] = 'Choose';
        $res[0]['selected'] = '';

        $query = "SELECT bowstyle_id, bowstyle_name FROM {$this->table_name}";

        if ($result = $this->connection->query($query)) {
            if ($result->num_rows > 0) {
                $i = 1;
                while ($row = $result->fetch_assoc()) {
                    $res[$i]['id'] = $row['bowstyle_id'];
                    $res[$i]['name'] = $row['bowstyle_name'];
                    if($row['bowstyle_id'] == $selected_id){
                        $res[$i]['selected'] = 'selected="selected"';
                    }else{
                        $res[$i]['selected'] = '';
                    }

                    $i++;
                }
            }
        }

        return $res;
    }
}
?>