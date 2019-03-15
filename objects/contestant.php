<?php

class Contestant{

    private $conn;
    private $table_name = "contestant";

    private $id;                 //_[int]
    private $contestant_a_id;   //_id[int]
    private $contestant_b_id;   //_id[int]
    private $gamemode_id;

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function SetContestantAID( $contestant_a_id ){
        $this->contestant_a_id = $contestant_a_id;
    }

    public function SetContestantBID( $contestant_b_id ){
        $this->contestant_b_id = $contestant_b_id;
    }

    public function SetID( $id ){
        $this->id = $id;
    }

    public function SetGameMode( $gamemode_id ){
        $this->gamemode_id = $gamemode_id;
    }

    public function CreateContestant(){
        $sql = "INSERT INTO " . $this->table_name . " (contestant_a_id, contestant_b_id) VALUES ('{$this->contestant_a_id}', '{$this->contestant_b_id}')";

        $res = array( 'status' => false );
        if($this->conn->query($sql) === TRUE) {

            $latest_id = $this->conn->insert_id;

            $res = array(
                'status'    => true,
                'latest_id' => $latest_id
            );
        }

        return $res;
    }

    public function GetContestantByID(){
        $query = "SELECT * FROM " . $this->table_name ." WHERE contestant_id={$this->id}";

        $result = $this->conn->query( $query );//var_dump($result > 0 );

        $res = array( 'contestant' => array(), 'status' => $result->num_rows > 0 );

        if( $res['status'] ){

            $contestant = null;
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $contestant['id'] = $row['contestant_id'];
            /*
            * TO-DO: Harus Dinamis
            */
            if( $this->gamemode_id == 1 ){ // Beregu

                $team = new Team($this->conn);
                $team->SetID( $row['contestant_a_id'] );
                $tempRes = $team->GetTeamByID();
                if( $tempRes['status'] ){
                    $contestant['contestant_a'] = $tempRes['team'];
                }

                $team = new Team($this->conn);
                $team->SetID( $row['contestant_b_id'] );
                $tempRes = $team->GetTeamByID();
                if( $tempRes['status'] ){
                    $contestant['contestant_b'] = $tempRes['team'];
                }
            }else if( $this->gamemode_id == 2 ){ // Individu

                $player = new Player($this->conn);
                $player->SetID( $row['contestant_a_id'] );
                $tempRes = $player->GetPlayerByID();
                if( $tempRes['status'] ){
                    $contestant['contestant_a'] = $tempRes['player'];
                }

                $player = new Player($this->conn);
                $player->SetID( $row['contestant_b_id'] );
                $tempRes = $player->GetPlayerByID();
                if( $tempRes['status'] ){
                    $contestant['contestant_b'] = $tempRes['player'];
                }
            }

            $res = array(
                'contestant'      => $contestant,
                'status'    => true
            );
        }

        return $res;
    }

}
?>