<?php
namespace scoreboard\model;

// use scoreboard\model\Model_Class;

class Scoreboard_Style_Model_Class extends Model_Class {

    protected $connection;
    protected $table_name;
    protected $id_col;

    public function __construct($db) {
        $this->connection = $db;
        $this->table_name = 'scoreboard_style';
        $this->id_col = 'id';
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
            'bowstyle_id' => 0,
            'name' => '',
            'config' => '',
        ];

        return array_merge($default_data, $new_data);
    }

    /**
     * Create Default Bowstyle
     *
     * @param array $default_data
     * @return boolean
     */
    public function create_default($default_data) {
        $data = $this->merge_data($default_data);

        $sql =
            "INSERT INTO {$this->table_name} (id, bowstyle_id, style_name, style_config)
        VALUES (" . $data['id'] . "," . $data['bowstyle_id'] . ", '" . $data['name'] . "', '" . $data['config'] . "' )";

        if ($this->connection->query($sql) === TRUE) {

            return true;
        }

        return false;
    }

    /**
     * Active Style Info
     *
     * @param integer $style_id
     * @return array [ 'bowstyle_name', 'style_name' ]
     */
    public function active_style_info( $style_id = 0) {
        $res = array();
        $sql =
        "SELECT bs.bowstyle_name, ss.style_name
        FROM {$this->table_name} ss
        LEFT JOIN bowstyles bs ON bs.bowstyle_id = ss.bowstyle_id
        WHERE ss.id = {$style_id}";

        if ($result = $this->connection->query( $sql )) {
            if($result->num_rows == 1){
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $res['bowstyle_name'] = $row['bowstyle_name'];
                $res['style_name'] = $row['style_name'];
            }
        }
        return $res;
    }

    /**
     * Style Config by ID
     *
     *
     * @param int $style_id Style ID
     * @return array [style_config]
     */
    public function style_config($style_id=0) {
        $res = array();

        $query = "SELECT style_config FROM {$this->table_name} WHERE id={$style_id}";

        if ($result = $this->connection->query($query)) {
            if ( $result->num_rows == 1 ) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                return $row['style_config'];
            }
        }

        return $res;
    }

    /**
     * Create New
     *
     * @param array $data
     * @param boolean $return_id
     * @return array [status,*latest_id]
     */
    public function insert($data=null, $return_id=false){
        $res = array('status' => false);
        if( is_null($data) ) return $res;

        $bowstyle_id = $data['bowstyle_id'];
        $style_name = $data['style_name'];
        $style_config = $data['style_config'];
        $sql = "INSERT INTO {$this->table_name} (bowstyle_id, style_name, style_config) VALUES ({$bowstyle_id}, '{$style_name}', '{$style_config}')";

        $res['status'] = $this->connection->query($sql);

        if ($return_id) {
            $res['latest_id'] = $this->connection->insert_id;
        }
        return $res;
    }

    /**
     * Update Data
     *
     * @param integer $style_id
     * @param array $data
     * @return boolean
     */
    public function update_data($style_id=0,$data=null){
        if( is_null($data) || $style_id == 0 ) return false;

        $style_name = $data['style_name'];
        $style_config = $data['style_config'];

        $sql = "UPDATE {$this->table_name} SET
        style_config='{$style_config}',
        style_name='{$style_name}'
        WHERE id={$style_id}";

        return $this->connection->query($sql);
    }

    /**
     * Delete Style Config
     *
     * @return boolean
     */
    public function delete_data($style_id=0) {
        if( $style_id == 0 ) return false;

        $sql = "DELETE FROM {$this->table_name} WHERE id={$style_id}";

        return $this->connection->query($sql);
    }

    /**
     * Bowstyle ID
     *
     * @param integer $style_id Style ID
     * @return integer Bowstyle ID
     */
    public function bowstyle_id($style_id=0){
        $query =
        "SELECT bowstyle_id FROM {$this->table_name}
        WHERE id = {$style_id}";

        if ($result = $this->connection->query($query)) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            return $row['bowstyle_id'];
        }

        return 0;
    }

    /**
     * Style Option Data
     *
     * @param integer $bowstyle_id Bowstyle ID
     * @param integer $style_id Bowstyle ID
     * @return array [ 'id', 'name', 'selected' ]
     */
    public function style_option_data($bowstyle_id = 0, $style_id=0){
        $res = array();
        $res[0]['id'] = 0;
        $res[0]['name'] = 'Choose';
        $res[0]['selected'] = '';

        $query = "SELECT id, style_name FROM {$this->table_name} WHERE bowstyle_id={$bowstyle_id}";

        if ($result = $this->connection->query($query)) {
            if ($result->num_rows > 0) {
                $i = 1;
                while ($row = $result->fetch_assoc()) {
                    $res[$i]['id'] = $row['id'];
                    $res[$i]['name'] = $row['style_name'];
                    if($row['id'] == $style_id){
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