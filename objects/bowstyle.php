<?php

class Bowstyle{

    private $conn;
    private $table_name = "bowstyles";

    private $id;     //_[int]
    private $name;   //_[string]

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function SetID( $id ){
        $this->id = $id;
    }

    public function SetName( $name ){
        $this->name = $name;
    }

    /* public function GetBowstyles(){
        $query = "SELECT bowstyle_id, bowstyle_name FROM " . $this->table_name;

        $result = $this->conn->query( $query );

        $res = array( 'bowstyles' => array(), 'status' => $result->num_rows > 0 );

        if( $res['status'] ){
            $i = 0;
            $bowstyles = null;
            while($row = $result->fetch_assoc()) {
                $bowstyles[$i]['id'] = $row['bowstyle_id'];
                $bowstyles[$i]['name'] = $row['bowstyle_name'];

                $i++;
            }

            $res = array(
                'bowstyles'      => $bowstyles,
                'status'    => true
            );
        }

        return $res;
    } */

    /**
     * Get Bow Style List
     *
     * return [ bowstyles, status ]
     * @return array
     */
    public function get_bowstyle_list(){
        $query = "SELECT bowstyle_id, bowstyle_name FROM {$this->table_name}";

        $result = $this->conn->query( $query );

        $res = array( 'bowstyles' => array(), 'status' => $result->num_rows > 0 );

        if( $res['status'] ){
            $i = 0;
            $bowstyles = null;
            while($row = $result->fetch_assoc()) {
                $bowstyles[$i]['id'] = $row['bowstyle_id'];
                $bowstyles[$i]['name'] = $row['bowstyle_name'];

                $i++;
            }

            $res['bowstyles'] = $bowstyles;
        }

        return $res;
    }

    /**
     * Get Bow Styles List
     *
     * return [status,bowstyles]
     * @return array
     */
    public function get_list(){
        $res = array( 'status' => false );

        $query = "SELECT bowstyle_id, bowstyle_name FROM {$this->table_name}";

        if( $result = $this->conn->query( $query ) ){
            if( $result->num_rows > 0 ) {
                $res['status'] = true;

                $i = 0;
                while($row = $result->fetch_assoc()) {
                    $res['bowstyles'][$i]['id'] = $row['bowstyle_id'];
                    $res['bowstyles'][$i]['name'] = $row['bowstyle_name'];

                    $i++;
                }
            }
        }

        return $res;
    }

    /* public function GetBowstyleByID(){
        $res = array ( 'status' => false );
        $query = "SELECT * FROM {$this->table_name} WHERE bowstyle_id={$this->id}";

        if( $result = $this->conn->query( $query ) ){

            $bowstyle = array();
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $bowstyle['id'] = $row['bowstyle_id'];
            $bowstyle['name'] = $row['bowstyle_name'];

            $res['status'] = true;
            $res['bowstyle'] = $bowstyle;
        }

        return $res;
    } */

    /**
     * Set Bow Style Data
     * param [ 'id', 'name' ]
     * @param array $bowstyle_data
     * @return instance
     */
    public function set_data($bowstyle_data){
        $data = array(
            'id'            => $bowstyle_data['id'] == 0 ? 0 : $bowstyle_data['id'],
            'name'          => $bowstyle_data['name'] == '' ? 'style': $bowstyle_data['name']
        );

        $this->id = $data['id'];
        $this->name = $data['name'];

        return $this;
    }

    /**
     * Create Bow Style
     *
     * @return void
     */
    public function create(){
        $sql = "INSERT INTO {$this->table_name} (bowstyle_id,bowstyle_name) VALUES ({$this->id}, '{$this->name}')";

        return $this->conn->query($sql);
    }

    /**
     * Count Bow Style
     *
     * @return number
     */
    public function count(){
        $sql = "SELECT COUNT(bowstyle_id) as nBowstyle FROM {$this->table_name}";

        $result = $this->conn->query( $sql );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        return $row['nBowstyle'];
    }

    /**
     * Create Default Bow Style
     *
     * @param int $style_id
     * @param string $style_name
     * @return boolean
     */
    public function create_default_style( $style_id, $style_name ) {
        $sql = "INSERT INTO {$this->table_name} (bowstyle_id,bowstyle_name) VALUES ({$style_id}, '{$style_name}')";

        if($this->conn->query($sql) === TRUE) {

            return true;
        }

        return false;
    }

    /**
     * Check if Bow Style is defined
     * > default Bow Style
     *
     * @return boolean
     */
    public function is_created() {
        $sql = "SELECT COUNT(*) as nStyle FROM {$this->table_name}";

        $result = $this->conn->query( $sql );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        if( $row['nStyle'] == 0 ) {
            return false;
        }
        return true;
    }
}
?>