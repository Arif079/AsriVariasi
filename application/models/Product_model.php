<?php

class Product_model extends CI_Model {
    function post($data) {
        $this->db->insert("products",$data);
    }

    function delete($id) {
        $this->db->delete("products",['id' => $id]);
        return $this->db->affected_rows();
    }

    function put($id,$data) {
        $this->db->where("id",$id);
        $this->db->update("products",$data);
    }
    function get($id = 0) {
        if(!$id) {
            $this->db->order_by("kditem","ASC");
            return $this->db->get("products");
        } else {
            return $this->db->get_where("products",['id' => $id]);
        }
    }

    function postlog($data) {
        $this->db->insert("adjust_log",$data);
    }
}