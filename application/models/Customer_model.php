<?php
class Customer_model extends CI_Model {
    function post($data) {
        $this->db->insert("customers",$data);
    }

    function get($id = 0) {
        if(!$id) {
            $this->db->order_by("name","ASC");
            return $this->db->get("customers");
        } else {
            return $this->db->get_where("customers",['id' => $id]);
        }
    }

    function put($id,$data) {
        $this->db->where("id",$id);
        $this->db->update("customers",$data);
    }

    function delete($id) {
        $this->db->delete("customers",["id" => $id]);
    }
}