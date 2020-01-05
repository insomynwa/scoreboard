<?php
namespace scoreboard\model;

class Team_Model_Class {

    private $connection;
    private $table_name;
    private $id_col;

    public function __construct($connection) {
        $this->connection = $connection;
        $this->table_name = 'team';
        $this->id_col = 'team_id';
    }

    /**
     * Create Team
     *
     * @param string logo filename
     * @param string team name
     * @param string team initial
     * @param string team description
     * @return boolean
     */
    public function insert($logo = '', $name = '', $initial = '', $description = '') {
        $sql = "INSERT INTO {$this->table_name} (team_name,team_logo,team_initial,team_desc) VALUES ('{$name}','{$logo}', '{$initial}', '{$description}' )";

        return $this->connection->query($sql);
    }

    public function update_team($team_id = 0, $team_logo = '', $team_name = '', $team_initial = '', $team_description = '') {
        if ($team_id == 0) {
            return false;
        }

        $query = "UPDATE {$this->table_name} SET
        team_logo='{$team_logo}', team_name='{$team_name}', team_initial='{$team_initial}', team_desc='{$team_description}'
        WHERE team_id={$team_id}";

        return $this->connection->query($query);
    }

    /**
     * Table Data
     *
     *
     * @return array [ 'id, logo, name ]
     */
    public function table_data() {
        $res = array();

        $query = "SELECT team_id, team_logo, team_name
        FROM {$this->table_name}
        ORDER BY team_name ASC";

        if ($result = $this->connection->query($query)) {
            if ($result->num_rows > 0) {

                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $res[$i]['id'] = $row['team_id'];
                    $res[$i]['logo'] = $row['team_logo'] == '' ? 'no-image.png' : $row['team_logo'];
                    $res[$i]['name'] = $row['team_name'];

                    $i++;
                }
            }
        }

        return $res;
    }

    /**
     * Option Data
     *
     *
     * @return array [ 'id, name ]
     */
    public function option_data() {
        $res = array();

        $query = "SELECT team_id, team_name
        FROM {$this->table_name}
        ORDER BY team_name ASC";

        if ($result = $this->connection->query($query)) {
            if ($result->num_rows > 0) {

                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $res[$i]['id'] = $row['team_id'];
                    $res[$i]['name'] = $row['team_name'];

                    $i++;
                }
            }
        }

        return $res;
    }

    /**
     * Team Logo
     *
     * @param integer $team_id Team ID
     * @return string no-image.png | filename.png
     */
    public function logo($team_id=0) {
        $query = "SELECT team_logo FROM {$this->table_name} WHERE team_id={$team_id} LIMIT 1";

        if ($result = $this->connection->query($query)) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            return $row['team_logo'];
        }

        return 'no-image.png';
    }

    /**
     * Delete Team
     *
     * @param integer Team ID
     * @return boolean
     */
    public function delete_team($team_id) {
        $sql = "DELETE FROM {$this->table_name} WHERE team_id = {$team_id}";
        return $this->connection->query($sql);
    }

    /**
     * Team Logo
     *
     * @param integer Team ID
     * @return string Logo filename
     */
    public function team_logo($team_id = 0) {
        if ($team_id == 0) {
            return 'no-image.png';
        }

        $query = "SELECT team_logo FROM {$this->table_name} WHERE team_id={$team_id} LIMIT 1";

        if ($result = $this->connection->query($query)) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            if ($result->num_rows > 0) {
                return $row['team_logo'];
            }
        }
        return 'no-image.png';
    }

    /**
     * Data
     *
     *
     * @param integer $team_id Team ID
     * @return mixed false | [team_logo, team_name]
     */
    public function scoreboard_form_data($team_id=0) {
        $query = "SELECT team_logo, team_name FROM {$this->table_name} WHERE team_id={$team_id} LIMIT 1";

        if ($result = $this->connection->query($query)) {
            $res = array();
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $res['team_logo'] = $row['team_logo']=='' ? 'no-image.png' : $row['team_logo'];
            $res['team_name'] = $row['team_name'];
            return $res;
        }
        return false;
    }

    /**
     * Modal Form Data
     *
     * @param integer $team_id Team ID
     * @return array empty | [ id, logo, name, initial, desc ]
     */
    public function modal_form_data($team_id=0){
        $res = array();
        $query = "SELECT team_id, team_logo, team_name, team_initial, team_desc FROM {$this->table_name} WHERE team_id={$team_id} LIMIT 1";

        if ($result = $this->connection->query($query)) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $res['id'] = $row['team_id'];
            $res['logo'] = $row['team_logo'];
            $res['name'] = $row['team_name'];
            $res['initial'] = $row['team_initial'];
            $res['desc'] = $row['team_desc'];
        }

        return $res;
    }
}
?>