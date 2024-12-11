<?php

class Coa_model extends CI_Model {
    function post($data) {
        $this->db->insert("coa",$data);
    }

    function get($id = 0) {
        if(!$id) {
            $this->db->order_by("coa","ASC");
            return $this->db->get("coa");
        } else {
            return $this->db->get_where("coa",['id' => $id]);
        }
    }

    function delete($id) {
        $this->db->delete("coa",['id' => $id]);
        return $this->db->affected_rows();
    }

    function put($id,$data) {
        $this->db->where("id",$id);
        $this->db->update("coa",$data);
    }

   

}