<?php
class retur_model extends CI_Model {
    function post($data) {
        $this->db->insert("retur",$data);
        return $this->db->insert_id();
    }

    function post_details($data) {
        $this->db->insert_batch("retur_details",$data);
    }

    function update_stock($data) {
        $this->db->update_batch("products",$data,"id");
    }

    function get_maxstock($w1, $w2) {
        // Step 1: Retrieve the current "max" value
        $this->db->select('max');
        $this->db->where('nop', $w1);
        $this->db->where('product_id', $w2);
        $query = $this->db->get('purchase_details');
    
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $currentMax = $row->max;
    
            // Step 2: Return the current "max" value
            return $currentMax;
        } else {
            // Return a default value or handle the case where no record is found
            return 0; // You can change this to an appropriate default value or error handling.
        }
    }
    
    
    
    public function update_status_by_nop($targetNop, $newStatus) {
        $data = array(
            'status' => $newStatus
        );

        $this->db->where('nop', $targetNop);
        $this->db->update('purchase', $data);
    }


    function get($id = 0) {
        $this->db->select("retur.*,suppliers.name,suppliers.address,suppliers.telephone");
        $this->db->join("suppliers","suppliers.id = retur.supplier_id","left");
        if($id) {
            return $this->db->get_where("retur",["retur.id" => $id]);
        } else {
            return $this->db->get("retur");
        }
    }

    function get_details($id) {
        $this->db->select("retur_details.*,products.name");
        $this->db->join("products","products.id = retur_details.product_id","left");
        return $this->db->get_where("retur_details",["purchase_id" => $id]);
    }

    function CreateCode(){
        $this->db->select('RIGHT(retur.nretur,4) as nretur', FALSE);
        $this->db->order_by('nretur','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('retur');
            if($query->num_rows() <> 0){      
                 $data = $query->row();
                 $kode = intval($data->nretur) + 1; 
            }
            else{      
                 $kode = 1;  
            }
        $date = date("dmy");    
        $batas = str_pad($kode, 4, "0", STR_PAD_LEFT);    
        $kodetampil = "RTR".$date.$batas;
        return $kodetampil;  
    }

    public function get_retur_by_id($purchase_id) {
        $query = $this->db->select('nretur')
                          ->from('retur')
                          ->where('id', $purchase_id)
                          ->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->nretur;
        } else {
            return NULL; // Handle the case where NOP is not found
        }
    }
    
}