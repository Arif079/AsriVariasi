<?php
class Combo_model extends CI_Model {
    function post($data) {
        $this->db->insert("combo",$data);
        return $this->db->insert_id();
    }

    function post_details($data) {
        $this->db->insert_batch("combo_details",$data);
    }

    function insert_combo($data) {
        $this->db->insert_batch("products",$data);
    }

    function get($id = 0) {
        $this->db->select("combo.*,suppliers.name,suppliers.address,suppliers.telephone");
        $this->db->join("suppliers","suppliers.id = combo.supplier_id","left");
        if($id) {
            return $this->db->get_where("combo",["combo.id" => $id]);
        } else {
            return $this->db->get("combo");
        }
    }

    function get_details($id) {
        $this->db->select("combo_details.*,products.name");
        $this->db->join("products","products.id = combo_details.product_id","left");
        return $this->db->get_where("combo_details",["combo_id" => $id]);
    }

    function put($id,$data) {
        $this->db->where("id",$id);
        $this->db->update("combo",$data);
    }

    function delete($id) {
        $this->db->delete("combo",['id' => $id]);
        return $this->db->affected_rows();
    }
}
