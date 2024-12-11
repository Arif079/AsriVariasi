<?php
class Paket_model extends CI_Model {
    function post($data) {
        $this->db->insert("paket",$data);
        return $this->db->insert_id();
    }

    function post_details($data) {
        $this->db->insert_batch("paket_details",$data);
    }

    function insert_paket($data) {
        $this->db->insert_batch("products",$data);
    }

    function update_paket($data) {
        $this->db->update_batch("products", $data,'id');
    }
    

    function get($id = 0) {
        $this->db->select("paket.*,suppliers.name,suppliers.address,suppliers.telephone,products.id as prod_id");
        $this->db->join("suppliers","suppliers.id = paket.supplier_id","left");
        $this->db->join("products","products.kditem = paket.kdpaket AND products.type = 'paket'","left");
        if($id) {
            return $this->db->get_where("paket",["paket.id" => $id]);
        } else {
            return $this->db->get("paket");
        }
    }


     // Update a paket
     function put($id, $data) {
        $this->db->where("id", $id);
        $this->db->update("paket", $data);
        return $this->db->affected_rows() > 0;
    }

    // Update paket details
    function update_details($paket_id, $details) {
        // First, delete existing details
        $this->db->delete("paket_details", ['paket_id' => $paket_id]);

        // Then, insert new details
        if (!empty($details)) {
            $this->post_details($details);
        }
    }

    function get_details($id) {
        $this->db->select("paket_details.*,products.name,products.kditem,products.stock");
        $this->db->join("products","products.id = paket_details.product_id","left");
        return $this->db->get_where("paket_details",["paket_id" => $id]);
    }
    function delete($id) {
        $this->db->delete("paket",['id' => $id]);
        return $this->db->affected_rows();
    }
}
