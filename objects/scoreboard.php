<?php

class ScoreBoard{

    public function GetDefaultScoreBoardMode( $mode, $style){
        $result = array();
        $result['scoreboard']['set'] = "Set 0";
        $result['scoreboard']['backgroud'] = $this->GetBackgroundMode( $mode, $style);

        $result['scoreboard']['logo_a'] = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/uploads/no-team.png";
        $result['scoreboard']['contestant_a'] = "Player A";
        $result['scoreboard']['timer_a'] = "0s";
        $result['scoreboard']['point_1a'] = 0;
        $result['scoreboard']['point_2a'] = 0;
        $result['scoreboard']['point_3a'] = 0;
        $result['scoreboard']['point_4a'] = 0;
        $result['scoreboard']['point_5a'] = 0;
        $result['scoreboard']['point_6a'] = 0;
        $result['scoreboard']['total_a'] = 0;
        $result['scoreboard']['setpoints_a'] = 0;
        $result['scoreboard']['desc_a'] = "";

        $result['scoreboard']['logo_b'] = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/uploads/no-team.png";
        $result['scoreboard']['contestant_b'] = "Player A";
        $result['scoreboard']['timer_b'] = "0s";
        $result['scoreboard']['point_1b'] = 0;
        $result['scoreboard']['point_2b'] = 0;
        $result['scoreboard']['point_3b'] = 0;
        $result['scoreboard']['point_4b'] = 0;
        $result['scoreboard']['point_5b'] = 0;
        $result['scoreboard']['point_6b'] = 0;
        $result['scoreboard']['total_b'] = 0;
        $result['scoreboard']['setpoints_b'] = 0;
        $result['scoreboard']['desc_b'] = "";

        return $result;
    }

    public function GetBackgroundMode( $mode, $style){
        $background = "";
        switch ($mode) {
            case 1:
                switch($style){
                    case 1:
                        // A Desc
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan1_A_desc.png";
                        break;
                    case 2:
                        // A Timeout B Desc
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan1_A_timeout_B_desc.png";
                        break;
                    case 3:
                        // A Timeout Desc
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan1_A_timeout_desc.png";
                        break;
                    case 4:
                        // A Timeout
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan1_A_timeout.png";
                        break;
                    case 5:
                        // AB Timeout A Desc
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan1_AB_timeout_A_desc.png";
                        break;
                    case 6:
                        // AB Timeout B Desc
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan1_AB_timeout_B_desc.png";
                        break;
                    case 7:
                        // AB Timeout Desc
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan1_AB_timeout_desc.png";
                        break;
                    case 8:
                        // AB Timeout
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan1_AB_timeout.png";
                        break;
                    case 9:
                        // B Desc
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan1_B_desc.png";
                        break;
                    case 10:
                        // B Timeout A Desc
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan1_B_timeout_A_desc.png";
                        break;
                    case 11:
                        // B Timeout Desc
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan1_B_timeout_desc.png";
                        break;
                    case 12:
                        // B Timeout
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan1_B_timeout.png";
                        break;
                    case 13:
                        // A Timeout Desc
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan1_default.png";
                        break;
                    default:
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan1_default.png";
                        break;
                }
                break;
            case 2:
                switch($style){
                    case 1:
                        // A Desc
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan2_A_desc.png";
                        break;
                    case 2:
                        // AB Desc
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan2_AB_desc.png";
                        break;
                    case 3:
                        // B Desc
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan2_B_desc.png";
                        break;
                    case 4:
                        // Default
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan2_default.png";
                        break;
                    default:
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan2_default.png";
                        break;
                }
                break;
            case 3:
                switch($style){
                    case 1:
                        // A Desc
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan3_A_desc.png";
                        break;
                    case 2:
                        // AB Desc
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan3_AB_desc.png";
                        break;
                    case 3:
                        // B Desc
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan3_B_desc.png";
                        break;
                    case 4:
                        // Default
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan3_default.png";
                        break;
                    default:
                        $background = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/images/panahan3_default.png";
                        break;
                }
                break;
            default:
                $background = "";
                break;
        }
        return $background;
    }

    public function GetDefaultDataMode( $mode){
        $result = array();
        $result['scoreboard']['set'] = "Set 0";
        $result['scoreboard']['backgroud'] = $this->GetBackgroundMode( $mode, 13);

        $result['scoreboard']['logo_a'] = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/uploads/no-team.png";
        $result['scoreboard']['contestant_a'] = "Player A";
        $result['scoreboard']['setpoints_a'] = 0;
        $result['scoreboard']['desc_a'] = "";

        $result['scoreboard']['logo_b'] = "http://" . $_SERVER['SERVER_NAME'] . "/scoreboard/uploads/no-team.png";
        $result['scoreboard']['contestant_b'] = "Player A";
        $result['scoreboard']['setpoints_b'] = 0;
        $result['scoreboard']['desc_b'] = "";
        if( $mode == 1){

            $result['scoreboard']['timer_a'] = "0s";
            $result['scoreboard']['total_a'] = 0;

            $result['scoreboard']['timer_b'] = "0s";
            $result['scoreboard']['total_b'] = 0;
        }else if ($mode == 2){
            $result['scoreboard']['point_1a'] = 0;
            $result['scoreboard']['point_2a'] = 0;
            $result['scoreboard']['point_3a'] = 0;
            $result['scoreboard']['point_4a'] = 0;
            $result['scoreboard']['point_5a'] = 0;
            $result['scoreboard']['point_6a'] = 0;
            $result['scoreboard']['total_a'] = 0;

            $result['scoreboard']['point_1b'] = 0;
            $result['scoreboard']['point_2b'] = 0;
            $result['scoreboard']['point_3b'] = 0;
            $result['scoreboard']['point_4b'] = 0;
            $result['scoreboard']['point_5b'] = 0;
            $result['scoreboard']['point_6b'] = 0;
            $result['scoreboard']['total_b'] = 0;
        }

        return $result['scoreboard'];
    }
}
?>