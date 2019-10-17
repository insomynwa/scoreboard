<?php

class Team{

    private $conn;
    private $table_name = "team";

    private $id;            //_[int]
    private $logo;          //_[string]
    private $name;          //_[string]
    private $initial;       //_[string][3]
    private $description;   //_[int]
    private $arr_players = array();
    private $arr_gamedraws = array();

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function SetID($teamid){
        $this->id = $teamid;
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

    public function delete(){
        $sql = "DELETE FROM {$this->table_name} WHERE team_id={$this->id}";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $res = array(
                'status'    => true
            );
        }

        return $res;
    }

    /**
     * Set Team Data
     *
     * @param array $team_data
     * @return instance
     */
    public function set_data($team_data){
        $data = array(
            'id'            => $team_data['id'] == 0 ? 0 : $team_data['id'],
            'name'          => $team_data['name'] == '' ? 'team name': $team_data['name'],
            'logo'          => $team_data['logo'] == '' ? 'no-image.png': $team_data['logo'],
            'initial'       => $team_data['initial'] == '' ? 'initial name' : $team_data['initial'],
            'description'   => $team_data['description'] == '' ? 'description' : $team_data['description']
        );

        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->logo = $data['logo'];
        $this->initial = $data['initial'];
        $this->description = $data['description'];

        return $this;
    }

    /**
     * Create Team
     *
     * @return query_result
     */
    public function create(){
        $sql = "INSERT INTO {$this->table_name} (team_name,team_logo,team_initial,team_desc) VALUES ('{$this->name}','{$this->logo}', '{$this->initial}', '{$this->description}' )";

        return $this->conn->query($sql);
    }

    /**
     * Update Team
     *
     * @return query_result
     */
    public function update(){
        $query = "UPDATE {$this->table_name} SET team_logo='{$this->logo}', team_name='{$this->name}', team_initial='{$this->initial}', team_desc='{$this->description}' WHERE team_id={$this->id}";

        return $this->conn->query($query);
    }

    public function get_team_list(){
        $res = array( 'status' => false );
        $query =
        "SELECT team_id, team_logo, team_name
        FROM {$this->table_name}
        ORDER BY team_name ASC";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $res['has_value'] = false;
            if($result->num_rows>0){
                $res['has_value'] = true;
                $i = 0;
                $teams = array();
                while($row = $result->fetch_assoc()) {
                    $teams[$i]['id'] = $row['team_id'];
                    $teams[$i]['logo'] = $row['team_logo'];
                    $teams[$i]['name'] = $row['team_name'];

                    $i++;
                }
                $res['teams'] = $teams;
            }
        }

        return $res;

    }

    /* public function get_team_option(){
        $res = array( 'status' => false );
        $query =
        "SELECT team_id, team_name
        FROM {$this->table_name}
        ORDER BY team_id DESC";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $res['has_value'] = false;
            if($result->num_rows>0){
                $res['has_value'] = true;
                $i = 0;
                $teams = array();
                while($row = $result->fetch_assoc()) {
                    $teams[$i]['id'] = $row['team_id'];
                    $teams[$i]['name'] = $row['team_name'];

                    $i++;
                }
                $res['teams'] = $teams;
            }
        }

        return $res;

    } */

    /* public function GetLogo(){
        $res = array( 'status' => false );
        $query = "SELECT team_logo FROM {$this->table_name} WHERE team_id={$this->id} LIMIT 1";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $res['has_value'] = $result->num_rows > 0;
            if($res['has_value']){
                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

                $res['logo'] = $row['team_logo'];
            }
        }

        return $res;
    } */

    /**
     * Get Team Logo
     *
     * return ['logo','status']
     * @return array (logo,status)
     */
    public function get_logo(){
        $res = array( 'status' => false );
        $query = "SELECT team_logo FROM {$this->table_name} WHERE team_id={$this->id} LIMIT 1";

        if( $result = $this->conn->query( $query ) ){
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $res['logo'] = $row['team_logo'];
            $res['status'] = ($result->num_rows > 0) && ($res['logo'] != "");
        }

        return $res;
    }

    /* public function GetName(){
        $res = array( 'status' => false );
        $query = "SELECT team_name FROM {$this->table_name} WHERE team_id={$this->id} LIMIT 1";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $res['has_value'] = $result->num_rows > 0;
            if($res['has_value']){
                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

                $res['name'] = $row['team_name'];
            }
        }

        return $res;
    } */

    /**
     * Get Team Name
     *
     * return [ 'name', 'status' ]
     * @return array
     */
    public function get_name(){
        $res = array( 'status' => false );
        $query = "SELECT team_name FROM {$this->table_name} WHERE team_id={$this->id} LIMIT 1";

        if( $result = $this->conn->query( $query ) ){
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $res['name'] = $row['team_name'];
            $res['status'] = ($result->num_rows > 0) && ($res['name'] != "");
        }

        return $res;
    }

    /* public function GetGameDraws(){
        $gamedraws = new GameDraw($this->conn);
        $gamedraws->SetContestantID($this->id);
        $res = $gamedraws->GetGameDrawsByTeamID();
        if( $res['status'] ){
            $this->arr_gamedraws = $res['gamedraws'];
        }
        return $this->arr_gamedraws;
    } */

    public function GetPlayers(){
        $players = new Player($this->conn);
        $players->SetTeamId($this->id);
        $res = $players->GetPlayersByTeamID();
        if( $res['status'] ){
            $this->arr_players = $res['players'];
        }
        return $this->arr_players;
    }

    public function GetTeam(){
        $res = array( 'status' => false );
        $query = "SELECT * FROM " . $this->table_name;

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $res['has_value'] = false;
            // var_dump($result->num_rows);
            if($result->num_rows > 0){
                $teams = array();
                $i = 0;
                while($row = $result->fetch_assoc()) {
                    $teams[$i]['id'] = $row['team_id'];
                    $teams[$i]['logo'] = $row['team_logo'];
                    $teams[$i]['name'] = $row['team_name'];
                    $teams[$i]['initial'] = $row['team_initial'];
                    $teams[$i]['desc'] = $row['team_desc'];
                    $i++;
                }

                $res['teams'] = $teams;
                $res['has_value'] = true;
            }
        }

        return $res;
    }

    public function GetTeamByID(){
        $res = array( 'status' => false );
        $query = "SELECT * FROM {$this->table_name} WHERE team_id={$this->id} LIMIT 1";

        if( $result = $this->conn->query( $query ) ){

            $team = array();
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $team['id'] = $row['team_id'];
            $team['logo'] = $row['team_logo'];
            $team['name'] = $row['team_name'];
            $team['initial'] = $row['team_initial'];
            $team['desc'] = $row['team_desc'];

            $res = array(
                'team'      => $team,
                'status'    => true
            );
        }

        return $res;
    }

    public function DeleteTeam(){
        $sql = "DELETE FROM {$this->table_name} WHERE team_id={$this->id}";

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