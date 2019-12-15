<?php

class GameMode{

    private $conn;
    private $table_name = "gamemode";

    private $id;     //_[int]
    private $name;   //_[string]
    private $desc;   //_[string]

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function SetID( $id ){
        $this->id = $id;
    }

    public function SetName( $name ){
        $this->name = $name;
    }

    public function SetDesc( $desc ){
        $this->desc = $desc;
    }

    /* public function GetGameModes(){
        $query = "SELECT gamemode_id, gamemode_name, gamemode_desc FROM " . $this->table_name;

        $result = $this->conn->query( $query );

        $res = array( 'gamemodes' => array(), 'status' => $result->num_rows > 0 );

        if( $res['status'] ){
            $i = 0;
            $gamemodes = null;
            while($row = $result->fetch_assoc()) {
                $gamemodes[$i]['id'] = $row['gamemode_id'];
                $gamemodes[$i]['name'] = $row['gamemode_name'];
                $gamemodes[$i]['desc'] = $row['gamemode_desc'];

                $i++;
            }

            $res = array(
                'gamemodes'      => $gamemodes,
                'status'    => true
            );
        }

        return $res;
    } */

    /**
     * Get Game Mode List
     *
     * return [ status, gamemodes ]
     * @return array
     */
    public function get_gamemode_list(){
        $query = "SELECT gamemode_id, gamemode_name, gamemode_desc FROM " . $this->table_name;

        $result = $this->conn->query( $query );

        $res = array( 'gamemodes' => array(), 'status' => $result->num_rows > 0 );

        if( $res['status'] ){
            $i = 0;
            $gamemodes = null;
            while($row = $result->fetch_assoc()) {
                $gamemodes[$i]['id'] = $row['gamemode_id'];
                $gamemodes[$i]['name'] = $row['gamemode_name'];
                $gamemodes[$i]['desc'] = $row['gamemode_desc'];

                $i++;
            }

            $res = array(
                'gamemodes'      => $gamemodes,
                'status'    => true
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
    public function get_list(){
        $res = array( 'status' => false );

        $query = "SELECT gamemode_id, gamemode_name FROM {$this->table_name}";

        if( $result = $this->conn->query( $query ) ){
            if( $result->num_rows > 0 ) {
                $res['status'] = true;

                $i = 0;
                while($row = $result->fetch_assoc()) {
                    $res['gamemodes'][$i]['id'] = $row['gamemode_id'];
                    $res['gamemodes'][$i]['name'] = $row['gamemode_name'];

                    $i++;
                }
            }
        }

        return $res;
    }

    /* public function GetGameModeByID(){
        $res = array ( 'status' => false );
        $query = "SELECT * FROM {$this->table_name} WHERE gamemode_id={$this->id}";

        if( $result = $this->conn->query( $query ) ){

            $gamemode = array();
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $gamemode['id'] = $row['gamemode_id'];
            $gamemode['name'] = $row['gamemode_name'];
            $gamemode['desc'] = $row['gamemode_desc'];

            $res['status'] = true;
            $res['gamemode'] = $gamemode;
        }

        return $res;
    } */

    public function CreateDefaultGameMode(){
        $sql = "INSERT INTO {$this->table_name} (gamemode_id,gamemode_name,gamemode_desc) VALUES ({$this->id}, '{$this->name}', '{$this->desc}')";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    public function CountGameMode(){
        $sql = "SELECT COUNT(gamemode_id) as nGameMode FROM {$this->table_name}";

        $result = $this->conn->query( $sql );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        return $row['nGameMode'];
    }

    /**
     * Create Default Game Mode
     *
     * @param int $mode_id
     * @param string $mode_name
     * @param string $mode_desc
     * @return boolean
     */
    public function create_default_mode( $mode_id, $mode_name, $mode_desc ) {
        $sql = "INSERT INTO {$this->table_name} (gamemode_id,gamemode_name,gamemode_desc) VALUES ({$mode_id}, '{$mode_name}', '{$mode_desc}')";

        if($this->conn->query($sql) === TRUE) {

            return true;
        }

        return false;
    }

    /**
     * Check if Game Mode is defined
     * > default game mode
     *
     * @return boolean
     */
    public function is_created() {
        $sql = "SELECT COUNT(*) as nGameMode FROM {$this->table_name}";

        $result = $this->conn->query( $sql );
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        if( $row['nGameMode'] == 0 ) {
            return false;
        }
        return true;
    }
}
?>