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
     * Get Game Status List
     *
     * return [ status, has_value, gamestatuses ]
     * @return array
     */
    public function get_gamestatus_list(){
        $res = array( 'status' => false );

        $query =
        "SELECT gamestatus_id, gamestatus_name
        FROM {$this->table_name}";

        if( $result = $this->connection->query( $query ) ){
            $res['status'] = true;
            $res['has_value'] = $result->num_rows>0;
            $res['gamestatuses'] = array();
            if($res['has_value']){
                $i = 0;
                $gamestatuses = array();
                while($row = $result->fetch_assoc()) {
                    $gamestatuses[$i]['id'] = $row['gamestatus_id'];
                    $gamestatuses[$i]['name'] = $row['gamestatus_name'];

                    $i++;
                }

                $res['gamestatuses'] = $gamestatuses;
            }
        }

        return $res;
    }

    /**
     * Get Game Status List
     *
     * return [status,gamestatuses]
     * @return array
     */
    public function get_list(){
        $res = array();

        $query = "SELECT gamestatus_id, gamestatus_name FROM {$this->table_name}";

        if( $result = $this->connection->query( $query ) ){
            if( $result->num_rows > 0 ) {
                $res['status'] = true;

                $i = 0;
                while($row = $result->fetch_assoc()) {
                    $res['gamestatuses'][$i]['id'] = $row['gamestatus_id'];
                    $res['gamestatuses'][$i]['name'] = $row['gamestatus_name'];

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
    public function create_default( $default_data ) {
        $data = $this->merge_data( $default_data );

        $sql =
        "INSERT INTO {$this->table_name} (gamestatus_id,gamestatus_name)
        VALUES (". $data['id'] . ",'" . $data['name'] . "')";

        if($this->connection->query($sql) === TRUE) {

            return true;
        }

        return false;
    }
}
?>