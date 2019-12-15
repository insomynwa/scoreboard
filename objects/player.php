<?php

class Player{

    private $conn;
    private $table_name = "player";

    private $id;     //_[int]
    private $name;   //_[string]
    private $team_id;   //_id[int]
    private $arr_gamedraws = array();
    private $arr_team = array();

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function SetID($id){
        $this->id = $id;
    }

    /**
     * Set Team ID
     *
     * @param number $id
     * @return instance
     */
    public function id($id){
        $this->id = $id;
        return $this;
    }

    public function SetName( $name ){
        $this->name = $name;
    }

    public function SetTeamId( $team_id ){
        $this->team_id = $team_id;
    }

    public function CreatePlayer(){
        $sql = "INSERT INTO {$this->table_name} (player_name, team_id) VALUES ('{$this->name}', {$this->team_id})";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res['status'] = true;
        }

        return $res;
    }

    /**
     * Set Player Data
     *
     * @param array $player_data
     * @return instance
     */
    public function set_data($player_data){
        $data = array(
            'id'            => $player_data['id'] == 0 ? 0 : $player_data['id'],
            'name'          => $player_data['name'] == '' ? 'player name': $player_data['name'],
            'team_id'          => $player_data['team_id'] == 0 ? 0: $player_data['team_id']
        );

        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->team_id = $data['team_id'];

        return $this;
    }

    /**
     * Create New Player
     *
     * return query result
     * @return boolean
     */
    public function create(){
        $sql = "INSERT INTO {$this->table_name} (player_name, team_id) VALUES ('{$this->name}', {$this->team_id})";

        return $this->conn->query($sql);
    }

    /**
     * Update Player
     *
     * return query result
     * @return boolean
     */
    public function update(){
        $sql = "UPDATE {$this->table_name} SET team_id={$this->team_id}, player_name='{$this->name}' WHERE player_id={$this->id}";

        return $this->conn->query($sql);
    }

    public function get_player_list(){
        $res = array( 'status' => false );
        $query =
        "SELECT p.player_id, p.player_name, t.team_name
        FROM {$this->table_name} p
        LEFT JOIN team t ON p.team_id = t.team_id
        ORDER BY t.team_name ASC";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $res['has_value'] = false;
            if($result->num_rows>0){
                $res['has_value'] = true;
                $i = 0;
                $teams = array();
                while($row = $result->fetch_assoc()) {
                    $teams[$i]['id'] = $row['player_id'];
                    $teams[$i]['name'] = $row['player_name'];
                    if($row['team_name'] == NULL){
                        $teams[$i]['team_name'] = 'INDIVIDU';
                    }else{
                        $teams[$i]['team_name'] = $row['team_name'];
                    }

                    $i++;
                }
                $res['players'] = $teams;
            }
        }

        return $res;

    }

    /**
     * Get Player List
     *
     * return [status,players]
     * @return array
     */
    public function get_list(){
        $res = array( 'status' => false );

        $query = "SELECT p.player_id, p.player_name, t.team_name
        FROM {$this->table_name} p
        LEFT JOIN team t ON p.team_id = t.team_id
        ORDER BY t.team_name ASC";

        if( $result = $this->conn->query( $query ) ){
            if( $result->num_rows > 0 ) {
                $res['status'] = true;

                $i = 0;
                while($row = $result->fetch_assoc()) {
                    $res['players'][$i]['id'] = $row['player_id'];
                    $res['players'][$i]['name'] = $row['player_name'];
                    if($row['team_name'] == NULL){
                        $res['players'][$i]['team_name'] = 'INDIVIDU';
                    }else{
                        $res['players'][$i]['team_name'] = $row['team_name'];
                    }

                    $i++;
                }
            }
        }

        return $res;
    }

    /**
     * Get Live by ID
     *
     * return [logo,team,player]
     *
     * @param int $player_id
     * @return array
     */
    public function get_live( $player_id ) {
        $res = [
            'logo'  => '',
            'team'  => '',
            'player'=> ''
        ];
        $query =
        "SELECT team_logo, team_name, player_name
        FROM {$this->table_name}
        LEFT JOIN team ON team.team_id = player.player_id
        WHERE player_id = {$player_id}";

        if( $result = $this->conn->query( $query ) ){
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $res[ 'logo' ] = is_null($row[ 'team_logo' ]) ? 'no-image.png': $row[ 'team_logo' ];
            $res[ 'team' ] = is_null($row[ 'team_name' ]) ? '' : $row[ 'team_name' ];
            $res[ 'player' ] = $row[ 'player_name' ];
        }
        return $res;
    }

    public function GetPlayersByTeamID(){
        $res = array( 'status' => false );
        $query = "SELECT * FROM {$this->table_name} WHERE team_id={$this->team_id}";

        if( $result = $this->conn->query( $query )){
            $i = 0;
            $players = array();
            while($row = $result->fetch_assoc()) {
                $players[$i]['id'] = $row['player_id'];
                $players[$i]['name'] = $row['player_name'];
                $players[$i]['team_id'] = $row['team_id'];

                /* $team = new Team($this->conn);
                $team->SetID( $this->team_id );
                $tempRes = $team->GetTeamByID();
                if( $tempRes['status'] ){
                    $players[$i]['team'] = $tempRes['team'];
                }else{
                    $players[$i]['team'] = array();
                } */

                $i++;
            }

            $res = array(
                'players'      => $players,
                'status'    => true
            );
        }

        return $res;
    }

    /* public function GetGameDraws(){
        $gamedraws = new GameDraw($this->conn);
        $gamedraws->SetContestantID($this->id);
        $res = $gamedraws->GetGameDrawsByPlayerID();
        if( $res['status'] ){
            $this->arr_gamedraws = $res['gamedraws'];
        }
        return $this->arr_gamedraws;
    } */

    public function GetTeam(){
        $team = new Team($this->conn);
        $team->SetID( $this->team_id);
        $res = $team->GetTeamByID();
        if( $res['status'] ){
            $this->arr_team = $res['team'];
        }
        return $this->arr_team;
    }

    public function GetPlayer(){
        $res = array( 'status' => false );
        $query = "SELECT * FROM {$this->table_name}";

        if($result = $this->conn->query( $query )){
            $res['status'] = true;
            $res['has_value'] = false;
            if($result->num_rows > 0){
                $res['has_value'] = true;
                $players = array();
                $i = 0;
                while($row = $result->fetch_assoc()) {
                    $players[$i]['id'] = $row['player_id'];
                    $players[$i]['name'] = $row['player_name'];
                    $players[$i]['team_id'] = $row['team_id'];

                    $i++;
                }
                $res['players'] = $players;
            }
        }

        return $res;
    }

    public function GetPlayerByID(){
        $res = array( 'status' => false );
        $query = "SELECT * FROM " . $this->table_name ." WHERE player_id={$this->id} LIMIT 1";

        if( $result = $this->conn->query( $query ) ){
            $player = array();
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $player['id'] = $row['player_id'];
            $player['name'] = $row['player_name'];
            $player['team_id'] = $row['team_id'];

            $res['status'] = true;
            $res['player'] = $player;
        }

        return $res;
    }

    /**
     * Get Player By ID
     *
     * return [status,player]
     * @param int $player_id
     * @return array
     */
    public function get_by_id( $player_id ){
        $res = array( 'status' => false );
        $query =
        "SELECT t.team_id, t.team_name, p.player_id, p.player_name
        FROM player p
        LEFT JOIN team t ON t.team_id = p.team_id
        WHERE p.player_id={$player_id}";

        if( $result = $this->conn->query( $query ) ){
            $player = array();
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $res['player']['id'] = $row['player_id'];
            $res['player']['name'] = $row['player_name'];
            $res['player']['team_id'] = $row['team_id'];
            $res['player']['team_name'] = $row['team_name'];

            $res['status'] = true;
        }

        return $res;
    }

    public function UpdatePlayer(){
        $query = "Update " . $this->table_name ." SET team_id={$this->team_id}, player_name='{$this->name}' WHERE player_id={$this->id}";

        $result = $this->conn->query( $query );

        $res = array( 'status' => false );

        if ($result){
            $res['status'] = true;
        }
        return $res;
    }

    public function DeletePlayer(){
        $sql = "DELETE FROM {$this->table_name} WHERE player_id={$this->id}";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    /**
     * Delete Player
     *
     * @return boolean
     */
    public function delete(){
        $sql = "DELETE FROM {$this->table_name} WHERE player_id={$this->id}";

        return $this->conn->query($sql);
    }

    public function delete_team_related_player($teamid){
        $sql =
        "DELETE FROM {$this->table_name}
        WHERE team_id={$teamid}";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }
}
?>