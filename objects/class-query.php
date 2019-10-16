<?php

class Class_Query{

    /* private $conn;
    private $gamedraw_table = 'gamedraw';
    private $gameset_table = 'gameset';
    private $gamemode_table = 'gamemode';
    private $gamestatus_table = "gamestatus";
    private $bowstyles_table = "bowstyles";
    private $player_table = "player";
    private $team_table = "team";

    public function __construct( $db ){
        $this->conn = $db;
    }

    public function get_game_table(){
        $res = array( 'status' => false );
        $query =
        "SELECT gd.gamedraw_id, gd.gamedraw_num, bs.bowstyle_name, gm.gamemode_name, ta.team_name as contestant_a_name, tb.team_name as contestant_b_name, gs.gameset_id, gs.gameset_num as gameset_num, s.gamestatus_id, s.gamestatus_name
        FROM {$this->gamedraw_table} gd
        INNER JOIN {$this->bowstyles_table} bs ON bs.bowstyle_id = gd.bowstyle_id
        INNER JOIN {$this->gamemode_table} gm ON gm.gamemode_id = gd.gamemode_id
        INNER JOIN {$this->team_table} ta ON ta.team_id = gd.contestant_a_id
        INNER JOIN {$this->team_table} tb ON tb.team_id = gd.contestant_b_id
        LEFT JOIN {$this->gameset_table} gs ON gs.gamedraw_id = gd.gamedraw_id
        LEFT JOIN {$this->gamestatus_table} s ON s.gamestatus_id = gs.gameset_status
        WHERE gd.gamemode_id = 1
        UNION
        SELECT gd.gamedraw_id, gd.gamedraw_num, bs.bowstyle_name, gm.gamemode_name, pa.player_name as contestant_a_name, pb.player_name as contestant_b_name, gs.gameset_id, gs.gameset_num as gameset_num, s.gamestatus_id, s.gamestatus_name
        FROM {$this->gamedraw_table} gd
        INNER JOIN {$this->bowstyles_table} bs ON bs.bowstyle_id = gd.bowstyle_id
        INNER JOIN {$this->gamemode_table} gm ON gm.gamemode_id = gd.gamemode_id
        INNER JOIN {$this->player_table} pa ON pa.player_id = gd.contestant_a_id
        INNER JOIN {$this->player_table} pb ON pb.player_id = gd.contestant_b_id
        LEFT JOIN {$this->gameset_table} gs ON gs.gamedraw_id = gd.gamedraw_id
        LEFT JOIN {$this->gamestatus_table} s ON s.gamestatus_id = gs.gameset_status
        WHERE gd.gamemode_id = 2
        ORDER BY gamedraw_num DESC, gameset_num DESC";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $res['has_value'] = false;
            if($result->num_rows>0){
                $res['has_value'] = true;
                $i = 0;
                $id_gamedraw = 0;
                $gamedraws = array();
                $gamesets = array();
                $gamedraw_option = array();
                while($row = $result->fetch_assoc()) {
                    if($row['gamedraw_id'] != $id_gamedraw){
                        $gamedraws[$i]['id'] = $row['gamedraw_id'];
                        $gamedraws[$i]['num'] = $row['gamedraw_num'];
                        $gamedraws[$i]['bowstyle_name'] = $row['bowstyle_name'];
                        $gamedraws[$i]['gamemode_name'] = $row['gamemode_name'];
                        $gamedraws[$i]['contestant_a_name'] = $row['contestant_a_name'];
                        $gamedraws[$i]['contestant_b_name'] = $row['contestant_b_name'];

                        $gamedraw_option[$i]['id'] = $row['gamedraw_id'];
                        $gamedraw_option[$i]['label'] = $row['gamedraw_num'] . '. ' . $row['contestant_a_name'] . ' vs ' . $row['contestant_b_name'];

                        $id_gamedraw = $row['gamedraw_id'];
                    }

                    if($row['gameset_id'] !== NULL){
                        $gamesets[$i]['id'] = $row['gameset_id'];
                        $gamesets[$i]['game_num'] = $row['gamedraw_num'];
                        $gamesets[$i]['set_num'] = $row['gameset_num'];
                        $gamesets[$i]['bowstyle_name'] = $row['bowstyle_name'];
                        $gamesets[$i]['gamemode_name'] = $row['gamemode_name'];
                        $gamesets[$i]['contestant_a_name'] = $row['contestant_a_name'];
                        $gamesets[$i]['contestant_b_name'] = $row['contestant_b_name'];
                        $gamesets[$i]['gamestatus_id'] = $row['gamestatus_id'];
                        $gamesets[$i]['gamestatus_name'] = $row['gamestatus_name'];
                    }

                    $i++;
                }
                $res['gamedraws'] = $gamedraws;
                $res['gamedraw_option'] = $gamedraw_option;
                $res['gamesets'] = $gamesets;
            }
        }

        return $res;

    } */

}
?>