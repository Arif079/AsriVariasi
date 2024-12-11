<?php
class Marketing_model extends CI_Model {
    function post($data) {
        $this->db->insert("marketing",$data);
    }

    function get($id = 0) {
        if(!$id) {
            $this->db->order_by("name","ASC");
            return $this->db->get("marketing");
        } else {
            return $this->db->get_where("marketing",['id' => $id]);
        }
    }

    function put($id,$data) {
        $this->db->where("id",$id);
        $this->db->update("marketing",$data);
    }

    function delete($id) {
        $this->db->delete("marketing",["id" => $id]);
    }
}