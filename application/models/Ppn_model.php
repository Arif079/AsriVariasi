<?php
class Ppn_model extends CI_Model {
    function post($data) {
        $this->db->insert("ppn",$data);
    }

    function get($id = 0) {
        if(!$id) {
            $this->db->order_by("name","ASC");
            return $this->db->get("ppn");
        } else {
            return $this->db->get_where("ppn",['id' => $id]);
        }
    }

    function put($id,$data) {
        $this->db->where("id",$id);
        $this->db->update("ppn",$data);
    }

    function delete($id) {
        $this->db->delete("ppn",["id" => $id]);
    }
}