<?php
class satuan_model extends CI_Model {
    function post($data) {
        $this->db->insert("satuan",$data);
    }

    function get($id = 0) {
        if(!$id) {
            $this->db->order_by("name","ASC");
            return $this->db->get("satuan");
        } else {
            return $this->db->get_where("satuan",['id' => $id]);
        }
    }

    function put($id,$data) {
        $this->db->where("id",$id);
        $this->db->update("satuan",$data);
    }

    function delete($id) {
        $this->db->delete("satuan",["id" => $id]);
    }
}