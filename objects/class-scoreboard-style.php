<?php

class Scoreboard_Style{

    private $conn;
    private $table_name = "scoreboard_style";

    private $id;
    private $bowstyle_id;
    private $style;
    private $style_config;

    public function __construct( $db ){
        $this->conn = $db;
    }

    /**
     * Set ID
     *
     * @param num $id
     * @return instance
     */
    public function id( $id ){
        $this->id = $id;

        return $this;
    }

    /**
     * Set Bowstyle ID
     *
     * @param num $bowstyle_id
     * @return instance
     */
    public function bowstyle($bowstyle_id){
        $this->bowstyle_id = $bowstyle_id;

        return $this;
    }

    /**
     * Set Data
     *
     * param [ id, bowstyle_id, style, config ]
     * @param array $new_data
     * @return instance
     */
    public function set_data($new_data){
        $data = array(
            'id'            => $new_data['id'] == 0 ? 0 : $new_data['id'],
            'bowstyle_id'   => $new_data['bowstyle_id'] == 0 ? 1: $new_data['bowstyle_id'],
            'style'         => $new_data['style'] == 0 ? 0 : $new_data['style'],
            'style_config'  => $new_data['style_config'] == '' ? '' : $new_data['style_config']
        );

        $this->id = $data['id'];
        $this->bowstyle_id = $data['bowstyle_id'];
        $this->style = $data['style'];
        $this->style_config = $data['style_config'];

        return $this;
    }

    public function get_list(){
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

    /**
     * Get Style By Bowstyle ID
     *
     * return [ status, styles]
     * @return array
     */
    public function get_styles_by_bowstyle_id(){
        $res = array( 'status' => false );

        $query = "SELECT id, style FROM {$this->table_name} WHERE bowstyle_id={$this->bowstyle_id}";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = $result->num_rows > 0;
            if($res['status']){
                $i=0;
                $styles = array();
                while($row = $result->fetch_assoc()) {
                    $styles[$i]['id'] = $row['id'];
                    $styles[$i]['style'] = $row['style'];
                    $i++;
                }
                $res['styles'] = $styles;
            }
        }

        return $res;
    }

    /**
     * Get Style Config By ID
     *
     * return [ status, style_config ]
     * @return array
     */
    public function get_style_config_by_id(){
        $res = array( 'status' => false );

        $query = "SELECT style_config FROM {$this->table_name} WHERE id={$this->id}";

        if( $result = $this->conn->query( $query ) ){
            $res['status'] = $result->num_rows == 1;
            if($res['status']){
                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                $res['style_config'] = $row['style_config'];
            }
        }

        return $res;
    }

    /**
     * Is table empty?
     *
     * @return boolean
     */
    public function is_table_empty(){
        $sql = "SELECT COUNT(id) as nStyle FROM {$this->table_name}";

        if($result = $this->conn->query( $sql )){
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            if( $row['nStyle'] == 0){
                return true;
            }
        }

        return false;
    }

    /**
     * Create Default Style Configuration
     *
     * return [ status, message]
     * @return array
     */
    public function create_default_style_config(){

        $res['status'] = false;
        $res['message'] = '';

        $data['id'] = 0;
        $data['bowstyle_id'] = 1;
        $data['style'] = 1;
        $data['style_config'] = $this->_render_json_default_style_config($data['bowstyle_id'],$data['style']); // Recurve 1
        $result_query = $this->set_data($data)->create();

        if($result_query['status']){
            $data['style'] = 2;
            $data['style_config'] = $this->_render_json_default_style_config($data['bowstyle_id'],$data['style']); // Recurve 2
            $result_query = $this->set_data($data)->create();

            if($result_query['status']){

                $data['style'] = 3;
                $data['style_config'] = $this->_render_json_default_style_config($data['bowstyle_id'],$data['style']); // Recurve 3
                $result_query = $this->set_data($data)->create();

                if($result_query['status']){

                    $data['bowstyle_id'] = 2;
                    $data['style'] = 1;
                    $data['style_config'] = $this->_render_json_default_style_config($data['bowstyle_id'],$data['style']); // Compound 1
                    $result_query = $this->set_data($data)->create();

                    if($result_query['status']){

                        $data['style'] = 2;
                        $data['style_config'] = $this->_render_json_default_style_config($data['bowstyle_id'],$data['style']); // Compound 2
                        $result_query = $this->set_data($data)->create();

                        if($result_query['status']){

                            $data['style'] = 3;
                            $data['style_config'] = $this->_render_json_default_style_config($data['bowstyle_id'],$data['style']); // Compound 3
                            $result_query = $this->set_data($data)->create();
                            if($result_query['status']){
                                $result['status'] = $result_query['status'];
                                $res['message'] = 'success create default style';
                            }else{
                                $res['message'] = 'ERROR: create Compound 3';
                            }
                        }else{
                            $res['message'] = 'ERROR: create Compound 2';
                        }
                    }else{
                        $res['message'] = 'ERROR: create Compound 1';
                    }
                }else{
                    $res['message'] = 'ERROR: create Recurve 3';
                }
            }else{
                $res['message'] = 'ERROR: create Recurve 2';
            }
        }else{
            $res['message'] = 'ERROR: create Recurve 1';
        }

        return $res;
    }

    /**
     * Create Scoreboard Style
     *
     * return [ status, **latest_id** ]
     * @param boolean $get_new_id
     * @return array
     */
    public function create($get_new_id = false){
        $res = array( 'status' => false );

        $sql = "INSERT INTO {$this->table_name} (bowstyle_id,style,style_config) VALUES ({$this->bowstyle_id}, {$this->style}, '{$this->style_config}')";

        $res['status'] = $this->conn->query($sql);

        if($get_new_id){
            $res['latest_id'] = $this->conn->insert_id;
        }
        return $res;

    }

    /**
     * Update Style Config
     *
     * @return boolean
     */
    public function update(){
        $sql = "UPDATE {$this->table_name} SET style_config='{$this->style_config}'  WHERE id={$this->id}";

        return $this->conn->query($sql);
    }

    /**
     * Render JSON Default Style Config
     *
     * return JSON style config array
     * @param num $bowstyle_id
     * @param num $style
     * @return array
     */
    private function _render_json_default_style_config($bowstyle_id, $style){
        $style_config = array();

        $style_config['logo']['label'] = '';
        $style_config['logo']['visibility'] = false;
        $style_config['logo']['visibility_class'] = 'hide';

        $style_config['team']['label'] = '';
        $style_config['team']['visibility'] = true;
        $style_config['team']['visibility_class'] = '';

        $style_config['player']['label'] = '';
        $style_config['player']['visibility'] = true;
        $style_config['player']['visibility_class'] = '';

        $style_config['timer']['label'] = '';
        $style_config['timer']['visibility'] = false;
        $style_config['timer']['visibility_class'] = 'hide';

        if( $style==2){
            $style_config['score1']['label'] = '';
            $style_config['score1']['visibility'] = true;
            $style_config['score1']['visibility_class'] = '';
            $style_config['score2']['label'] = '';
            $style_config['score2']['visibility'] = true;
            $style_config['score2']['visibility_class'] = '';
            $style_config['score3']['label'] = '';
            $style_config['score3']['visibility'] = true;
            $style_config['score3']['visibility_class'] = '';
        }else{
            $style_config['score1']['label'] = '';
            $style_config['score1']['visibility'] = false;
            $style_config['score1']['visibility_class'] = 'hide';
            $style_config['score2']['label'] = '';
            $style_config['score2']['visibility'] = false;
            $style_config['score2']['visibility_class'] = 'hide';
            $style_config['score3']['label'] = '';
            $style_config['score3']['visibility'] = false;
            $style_config['score3']['visibility_class'] = 'hide';
        }

        $style_config['score4']['label'] = '';
        $style_config['score4']['visibility'] = false;
        $style_config['score4']['visibility_class'] = 'hide';
        $style_config['score5']['label'] = '';
        $style_config['score5']['visibility'] = false;
        $style_config['score5']['visibility_class'] = 'hide';
        $style_config['score6']['label'] = '';
        $style_config['score6']['visibility'] = false;
        $style_config['score6']['visibility_class'] = 'hide';

        $style_config['setpoint']['label'] = '';
        $style_config['setpoint']['visibility'] = false;
        $style_config['setpoint']['visibility_class'] = 'hide';

        if( $style==3){
            $style_config['setscore']['label'] = '';
            $style_config['setscore']['visibility'] = false;
            $style_config['setscore']['visibility_class'] = 'hide';

        }else{
            $style_config['setscore']['label'] = '';
            $style_config['setscore']['visibility'] = true;
            $style_config['setscore']['visibility_class'] = '';
        }

        if($bowstyle_id==1){ // Recurve

            $style_config['gamescore']['label'] = 'Total';
            $style_config['gamescore']['visibility'] = false;
            $style_config['gamescore']['visibility_class'] = 'hide';

            $style_config['gamepoint']['label'] = 'Set pts';
            $style_config['gamepoint']['visibility'] = true;
            $style_config['gamepoint']['visibility_class'] = '';
        }
        else if( $bowstyle_id== 2 ){ // Compound

            $style_config['gamescore']['label'] = 'Total';
            $style_config['gamescore']['visibility'] = true;
            $style_config['gamescore']['visibility_class'] = '';

            $style_config['gamepoint']['label'] = 'Set pts';
            $style_config['gamepoint']['visibility'] = false;
            $style_config['gamepoint']['visibility_class'] = 'hide';
        }

        $style_config['description']['label'] = '';
        $style_config['description']['visibility'] = false;
        $style_config['description']['visibility_class'] = 'hide';

        return json_encode($style_config);
    }

    /**
     * Get Default Style Config
     *
     * @return array
     */
    public function get_default_style_config(){
        return array(
            'logo'  => array(
                'label'             => '',
                'visibility'        => true,
                'visibility_class'  => ''
            ),
            'team'  => array(
                'label'             => '',
                'visibility'        => true,
                'visibility_class'  => ''
            ),
            'player'  => array(
                'label'             => '',
                'visibility'        => true,
                'visibility_class'  => ''
            ),
            'timer'  => array(
                'label'             => '',
                'visibility'        => true,
                'visibility_class'  => ''
            ),
            'score1'  => array(
                'label'             => '',
                'visibility'        => true,
                'visibility_class'  => ''
            ),
            'score2'  => array(
                'label'             => '',
                'visibility'        => true,
                'visibility_class'  => ''
            ),
            'score3'  => array(
                'label'             => '',
                'visibility'        => true,
                'visibility_class'  => ''
            ),
            'score4'  => array(
                'label'             => '',
                'visibility'        => true,
                'visibility_class'  => ''
            ),
            'score5'  => array(
                'label'             => '',
                'visibility'        => true,
                'visibility_class'  => ''
            ),
            'score6'  => array(
                'label'             => '',
                'visibility'        => true,
                'visibility_class'  => ''
            ),
            'setpoint'  => array(
                'label'             => '',
                'visibility'        => true,
                'visibility_class'  => ''
            ),
            'gamepoint'  => array(
                'label'             => '',
                'visibility'        => true,
                'visibility_class'  => ''
            ),
            'setscore'  => array(
                'label'             => '',
                'visibility'        => true,
                'visibility_class'  => ''
            ),
            'gamescore'  => array(
                'label'             => '',
                'visibility'        => true,
                'visibility_class'  => ''
            ),
            'description'  => array(
                'label'             => '',
                'visibility'        => true,
                'visibility_class'  => ''
            )
        );
    }

    /**
     * Get Last Style Number by Bowstyle_id
     *
     * return [ status, new_num ]
     * @return array
     */
    public function get_new_num(){
        $res = array( 'status' => false);
        $query =
        "SELECT ( MAX(style) + 1) new_num
        FROM {$this->table_name}
        WHERE bowstyle_id={$this->bowstyle_id}";

        $res['new_num'] = 0;
        if( $result = $this->conn->query( $query ) ){
            $res['status'] = true;
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $res['new_num'] = $row['new_num'];
        }

        return $res;
    }

    /**
     * Delete Style Config
     *
     * @return boolean
     */
    public function delete(){
        $sql = "DELETE FROM {$this->table_name} WHERE id={$this->id}";

        return $this->conn->query($sql);
    }
}

?>