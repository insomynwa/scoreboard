<?php
namespace scoreboard\model;

class Gamedraw_Model_Class{

    private $connection;
    private $table_name;
    private $id_col;

    public function __construct( $connection ){
        $this->connection = $connection;
        $this->table_name = 'gamedraw';
        $this->id_col = 'gamedraw_id';
    }

    /**
     * Team Gamedraws ID
     *
     * @param integer Team ID
     * @return array [gamedraw_id1, gamedraw_id2, gamedraw_id..n]
     */
    public function team_gamedraws_id($team_id = 0) {
        $gamedraws_id = array();
        if ($team_id > 0) {

            $query =
            "SELECT gamedraw_id FROM {$this->table_name}
            WHERE gamemode_id = 1 AND (contestant_a_id = {$team_id} OR contestant_b_id = {$team_id})
            UNION
            SELECT gamedraw_id FROM {$this->table_name}
            WHERE gamemode_id = 2 AND ( contestant_a_id IN
            ( SELECT player_id FROM player WHERE team_id = {$team_id} )
            OR contestant_b_id IN
            ( SELECT player_id FROM player WHERE team_id = {$team_id} ) )";

            if ($result = $this->connection->query($query)) {
                if ($result->num_rows > 0) {
                    $i = 0;
                    while ($row = $result->fetch_assoc()) {
                        $gamedraws_id[$i] = $row['gamedraw_id'];
                        $i++;
                    }
                }
            }
        }

        return $gamedraws_id;
    }

    /**
     * Create Game Draw
     *
     * @param array $name Game Draw Data
     * @return boolean true | false
     */
    public function create_game_draw( $data=null){

        // $game_draw_data = array(
        //     'id'                => $gamedraw_id,
        //     'num'               => 1,
        //     'bowstyle_id'       => 0,
        //     'gamemode_id'       => 0,
        //     'contestant_a_id'   => 0,
        //     'contestant_b_id'   => 0
        // );
        $num = $data['num'];
        $bowstyle_id = $data['bowstyle_id'];
        $gamemode_id = $data['gamemode_id'];
        $contestant_a_id = $data['contestant_a_id'];
        $contestant_b_id = $data['contestant_b_id'];
        $sql = "INSERT INTO {$this->table_name} ( bowstyle_id, gamedraw_num, gamemode_id, contestant_a_id, contestant_b_id) VALUES ( {$bowstyle_id}, {$num}, {$gamemode_id}, {$contestant_a_id}, {$contestant_b_id})";

        return ($this->connection->query($sql) === TRUE);
    }

    /**
     * Update Game Draw
     *
     * @param array $data Game Draw Data
     * @return boolean true | false
     */
    public function update_game_draw($data=null){
        $id = $data['id'];
        $num = $data['num'];
        $sql = "UPDATE {$this->table_name} SET gamedraw_num={$num} WHERE gamedraw_id={$id}";

        return ($this->connection->query($sql) === TRUE);
    }

    /**
     * Delete Gamedraw
     *
     * @param integer Gamedraw ID
     * @return boolean
     */
    public function delete_gamedraw($gamedraw_id=0) {
        $sql = "DELETE FROM {$this->table_name} WHERE gamedraw_id = {$gamedraw_id}";
        return $this->connection->query($sql);
    }

    /**
     * Delete Gamedraws
     *
     * @param array Gamedraws ID
     * @return boolean
     */
    public function delete_gamedraws($gamedraws_id=null) {
        $imp_gamedraws_id = implode(',', $gamedraws_id);
        $sql = "DELETE FROM {$this->table_name} WHERE gamedraw_id IN ({$imp_gamedraws_id})";
        return $this->connection->query($sql);
    }

    /**
     * Player Gamedraws ID
     *
     * @param array|integer Player ID
     * @return array [gamedraw_id1, gamedraw_id2, gamedraw_id..n]
     */
    public function player_gamedraws_id($player_id=0) {
        $gamedraws_id = array();

        $query =
        "SELECT gamedraw_id FROM {$this->table_name}
        WHERE gamemode_id = 2 AND (contestant_a_id={$player_id} OR contestant_b_id={$player_id})";

        if ($result = $this->connection->query($query)) {
            if ($result->num_rows > 0) {
                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $gamedraws_id[$i] = $row['gamedraw_id'];
                    $i++;
                }
            }
        }

        return $gamedraws_id;
    }

    /**
     * Game Draw List
     *
     * return empty
     * @return array
     */
    public function list(){
        $res = array();

        $query = "SELECT gd.gamedraw_id, gd.gamedraw_num, bs.bowstyle_name, gm.gamemode_name, ta.team_name as contestant_a_name, tb.team_name as contestant_b_name
        FROM {$this->table_name} gd
        INNER JOIN bowstyles bs ON bs.bowstyle_id = gd.bowstyle_id
        INNER JOIN gamemode gm ON gm.gamemode_id = gd.gamemode_id
        INNER JOIN team ta ON ta.team_id = gd.contestant_a_id
        INNER JOIN team tb ON tb.team_id = gd.contestant_b_id
        WHERE gd.gamemode_id = 1
        UNION
        SELECT gd.gamedraw_id, gd.gamedraw_num, bs.bowstyle_name, gm.gamemode_name, pa.player_name as contestant_a_name, pb.player_name as contestant_b_name
        FROM {$this->table_name} gd
        INNER JOIN bowstyles bs ON bs.bowstyle_id = gd.bowstyle_id
        INNER JOIN gamemode gm ON gm.gamemode_id = gd.gamemode_id
        INNER JOIN player pa ON pa.player_id = gd.contestant_a_id
        INNER JOIN player pb ON pb.player_id = gd.contestant_b_id
        WHERE gd.gamemode_id = 2
        ORDER BY gamedraw_num DESC";

        if( $result = $this->connection->query( $query ) ){
            if( $result->num_rows > 0 ) {

                $i = 0;
                while($row = $result->fetch_assoc()) {
                    $res['gamedraws'][$i]['id'] = $row['gamedraw_id'];
                    $res['gamedraws'][$i]['num'] = $row['gamedraw_num'];
                    $res['gamedraws'][$i]['bowstyle_name'] = $row['bowstyle_name'];
                    $res['gamedraws'][$i]['gamemode_name'] = $row['gamemode_name'];
                    $res['gamedraws'][$i]['contestant_a_name'] = $row['contestant_a_name'];
                    $res['gamedraws'][$i]['contestant_b_name'] = $row['contestant_b_name'];
                    $res['gamedraws'][$i]['label'] = $row['gamedraw_num'] . '. ' . $row['contestant_a_name'] . ' vs ' . $row['contestant_b_name'];

                    $i++;
                }
            }
        }

        return $res;
    }

    /**
     * Bowstyle ID & Gamemode ID
     *
     * @param integer $gamedraw_id Gamedraw ID
     * @return array empty | [ bowstyle_id, gamemode_id ]
     */
    private function bowstyle_gamemode_id($gamedraw_id=0){
        $res = array();
        $query = "SELECT bowstyle_id, gamemode_id FROM {$this->table_name} WHERE gamedraw_id={$gamedraw_id}";
        if( $result = $this->connection->query( $query ) ){
            if($result->num_rows > 0){
                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                $res['bowstyle_id'] = $row['bowstyle_id'];
                $res['gamemode_id'] = $row['gamemode_id'];
            }
        }
        return $res;
    }

    /**
     * Summary
     *
     * @param integer $gamedraw_id Gamedraw ID
     * @return array empty | [ summaries ]
     */
    public function summary($gamedraw_id=0){
        $res = array();
        $bg_id = $this->bowstyle_gamemode_id($gamedraw_id);
        if( ! empty($bg_id)){
            $bowstyle_id = $bg_id['bowstyle_id'];
            $gamemode_id = $bg_id['gamemode_id'];
            $query = 'SELECT ';
            $selected_column = 'gd.gamedraw_num as draw, bs.bowstyle_name as style, gm.gamemode_name as gamemode, gs.gameset_num as sets';
            $gamemode_query = '';
            $score_query = '';

            if($bowstyle_id == 1 ){ // Recurve Column
                $selected_column .= ', sa.set_points as score_a, sb.set_points as score_b';
            }else { // Compound Column
                $selected_column .= ', (sa.score_1 + sa.score_2 + sa.score_3 + sa.score_4 + sa.score_5 + sa.score_6) as score_a, (sb.score_1 + sb.score_2 + sb.score_3 + sb.score_4 + sb.score_5 + sb.score_6) as score_b';
            }

            if($gamemode_id == 1){ // beregu
                $selected_column .= ', ta.team_name as player_a, tb.team_name as player_b ';
                $gamemode_query .=
                "RIGHT JOIN team ta ON gd.contestant_a_id=ta.team_id 
                RIGHT JOIN team tb ON gd.contestant_b_id=tb.team_id ";
                $score_query .=
                "RIGHT JOIN score sa ON gs.gameset_id=sa.gameset_id AND ta.team_id=sa.contestant_id 
                RIGHT JOIN score sb ON gs.gameset_id=sb.gameset_id AND tb.team_id=sb.contestant_id ";
            }else { // individu
                $selected_column .= ', pa.player_name as player_a, pb.player_name as player_b ';
                $gamemode_query .=
                "RIGHT JOIN player pa ON gd.contestant_a_id=pa.player_id 
                RIGHT JOIN player pb ON gd.contestant_b_id=pb.player_id ";
                $score_query .=
                "RIGHT JOIN score sa ON gs.gameset_id=sa.gameset_id AND pa.player_id=sa.contestant_id 
                RIGHT JOIN score sb ON gs.gameset_id=sb.gameset_id AND pb.player_id=sb.contestant_id ";
            }
            $query .= $selected_column . "FROM {$this->table_name} gd ";
            $query .=
            "LEFT JOIN bowstyles bs ON gd.bowstyle_id=bs.bowstyle_id
            LEFT JOIN gamemode gm ON gd.gamemode_id=gm.gamemode_id ";
            $query .= $gamemode_query . "RIGHT JOIN gameset gs ON gd.gamedraw_id=gs.gamedraw_id ";
            $query .= $score_query . "WHERE gd.gamedraw_id={$gamedraw_id} AND gd.gamemode_id={$gamemode_id} AND gd.bowstyle_id={$bowstyle_id}";

            if( $result = $this->connection->query( $query ) ){
                if($result->num_rows>0){
                    $i=0;
                    while($row = $result->fetch_assoc()) {
                        $res['summaries'][$i]['draw'] = $row['draw'];
                        $res['summaries'][$i]['style'] = $row['style'];
                        $res['summaries'][$i]['gamemode'] = $row['gamemode'];
                        $res['summaries'][$i]['sets'] = $row['sets'];
                        $res['summaries'][$i]['player_a'] = $row['player_a'];
                        $res['summaries'][$i]['player_b'] = $row['player_b'];
                        $res['summaries'][$i]['score_a'] = $row['score_a'];
                        $res['summaries'][$i]['score_b'] = $row['score_b'];

                        $i++;
                    }
                }
            }
        }
        return $res;
    }

    /**
     * Modal Form Data
     *
     * @param integer $gamedraw_id Gamedraw ID
     * @return array empty | [ id, num, bowstyle_id, gamemode_id, contestant_a_id, contestant_b_id ]
     */
    public function modal_form_data($gamedraw_id=0){
        $res = array();

        if( $gamedraw_id == 0) return $res;

        $query =
        "SELECT gamedraw_id, gamedraw_num, bowstyle_id, gamemode_id, contestant_a_id, contestant_b_id
        FROM {$this->table_name}
        WHERE gamedraw_id={$gamedraw_id}";

        if( $result = $this->connection->query( $query ) ){
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $res['id'] = $row['gamedraw_id'];
            $res['num'] = $row['gamedraw_num'];
            $res['bowstyle_id'] = $row['bowstyle_id'];
            $res['gamemode_id'] = $row['gamemode_id'];
            $res['contestant_a_id'] = $row['contestant_a_id'];
            $res['contestant_b_id'] = $row['contestant_b_id'];
        }

        return $res;
    }

    /**
     * Contestants ID
     *
     * @param integer $gamedraw_id
     * @return array () | [contestant_a_id,contestant_b_id]
     */
    public function contestants_id($gamedraw_id=0){
    $sql = "SELECT contestant_a_id, contestant_b_id FROM {$this->table_name} WHERE gamedraw_id={$gamedraw_id}";

        $res = array();
        if($result = $this->connection->query( $sql )){
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $res['contestant_a_id'] = $row['contestant_a_id'];
            $res['contestant_b_id'] = $row['contestant_b_id'];
        }

        return $res;
    }

    /**
     * New Num Gamedraw
     *
     * @return integer new number of gamedraw
     */
    public function new_number(){
        $sql = "SELECT COUNT(gamedraw_id) as nGameDraw FROM {$this->table_name}";

        if($result = $this->connection->query( $sql )){
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            return $row['nGameDraw'] + 1;
        }

        return 1;
    }
}
?>