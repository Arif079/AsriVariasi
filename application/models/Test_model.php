<?php
class Test_model extends CI_Model {
    public function getTableColumns($table_name) {
        $query = $this->db->query("SHOW COLUMNS FROM $table_name");
        return $query->result_array();
    }
}
