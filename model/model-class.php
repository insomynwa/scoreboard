<?php
namespace scoreboard\model;

abstract class Model_Class {

    abstract protected function merge_data($new_data);

    /**
     * Check if table has default value
     *
     * @return boolean
     */
    public function has_default() {
        $sql = "SELECT COUNT({$this->id_col}) as nDefault FROM {$this->table_name}";

        $result = $this->connection->query($sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if ($row['nDefault'] == 0) {
            return false;
        }
        return true;
    }
}
