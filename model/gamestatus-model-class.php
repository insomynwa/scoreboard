<?php
namespace scoreboard\model;

class Gamestatus_Model_Class extends Model_Class{
    protected $connection;

    protected $table_name;

    protected $id_col;

    public function __construct( $db ){
        $this->connection = $db;
        $this->table_name = 'gamestatus';
        $this->id_col = 'gamestatus_id';
    }

    /**
     * Option Data
     *
     * return [ id, name ]
     * @return array
     */
    public function option_data(){
        $res = array();

        $query = "SELECT gamestatus_id, gamestatus_name FROM {$this->table_name}";

        if( $result = $this->connection->query( $query ) ){
            if( $result->num_rows > 0 ) {
                $i = 0;
                while($row = $result->fetch_assoc()) {
                    $res[$i]['id'] = $row['gamestatus_id'];
                    $res[$i]['name'] = $row['gamestatus_name'];

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
    protected function merge_data( $new_data ){
        $default_data = [
            'id'    => 0,
            'name'  => ''
        ];

        return array_merge( $default_data, $new_data );
    }

    /**
     * Create Default Game Status
     *
     * @param array $default_data
     * @return boolean
     */
    public function create_default() {

        $sql =
        "INSERT INTO {$this->table_name} (gamestatus_id,gamestatus_name)
        VALUES (1,'Stand by'),(2,'Live'),(3,'Finished')";

        return ($this->connection->query($sql) === TRUE);
    }
}
?>