<?php
class Purchase_model extends CI_Model {
    function post($data) {
        $this->db->insert("purchase",$data);
        return $this->db->insert_id();
    }

    function post_details($data) {
        $this->db->insert_batch("purchase_details",$data);
    }

    function update_stock($data) {
        $this->db->update_batch("products",$data,"id");
    }

    function get($id = 0) {
        $this->db->select("purchase.*,suppliers.name,suppliers.address,suppliers.telephone");
        $this->db->join("suppliers","suppliers.id = purchase.supplier_id","left");
        if($id) {
            return $this->db->get_where("purchase",["purchase.id" => $id]);
        } else {
            return $this->db->get("purchase");
        }
    }

    function close($id = 0) {
        $this->db->select("purchase.*,suppliers.name,suppliers.address,suppliers.telephone");
        $this->db->join("suppliers","suppliers.id = purchase.supplier_id","left");
        if($id) {
            return $this->db->get_where("purchase",["purchase.id" => $id]);
        } else {
            return $this->db->get("purchase");
        }
    }

    function get_details($id) {
        $this->db->select("purchase_details.*,products.name,products.kditem,products.stock");
        $this->db->join("products","products.id = purchase_details.product_id","left");
        return $this->db->get_where("purchase_details",["purchase_id" => $id]);
    }

    public function get_nop_by_id($purchase_id) {
        $query = $this->db->select('nop')
                          ->from('purchase')
                          ->where('id', $purchase_id)
                          ->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->nop;
        } else {
            return NULL; // Handle the case where NOP is not found
        }
    }
    
    function get_nop($id = 0) {
        $this->db->select("purchase.*, suppliers.name");
        $this->db->join("suppliers", "suppliers.id = purchase.supplier_id", "left");
        if(!$id) {
            $this->db->order_by("nop","ASC");
            return $this->db->get("purchase");
        } else {
            return $this->db->get_where("purchase",['id' => $id]);
        }
    }

    function CreateCode(){
        $date = date("m"); 
        $this->db->select('RIGHT(purchase.nop,4) as nop', FALSE);
        $this->db->where('MONTH(date)', $date);    
        $this->db->order_by('nop','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('purchase');
            if($query->num_rows() <> 0){      
                 $data = $query->row();
                 $kode = intval($data->nop) + 1; 
            }
            else{      
                 $kode = 1;  
            }
        $date = date("dmy");    
        $batas = str_pad($kode, 4, "0", STR_PAD_LEFT);    
        $kodetampil = "NOP".$date.$batas;
        return $kodetampil;  
    }

    public function update_status($purchaseId, $status) {
        $data = ["status" => $status];
        $this->db->where("id", $purchaseId);
        $this->db->update("purchase", $data);
    }
    
}