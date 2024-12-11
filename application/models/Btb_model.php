<?php
class btb_model extends CI_Model {
    function post($data) {
        $this->db->insert("btb",$data);
        return $this->db->insert_id();
    }

    function post_details($data) {
        $this->db->insert_batch("btb_details",$data);
    }

    function update_stock($data) {
        $this->db->update_batch("products",$data,"id");
    }

    function update_status($data) {
        $this->db->update_batch("purchase",$data,"nop");
    }

    function update_maxstock($w1,$w2,$data){
        $this->db->where('nop', $w1);
        $this->db->where('product_id', $w2);
                // Update the column with the new data
        $this->db->update('purchase_details', $data);
     
 
    }

    function getppn($nop) {
        if (!$nop) {
            $result = $this->db->select('ppn')->get("purchase")->row();
        } else {
            $result = $this->db->select('ppn')->get_where("purchase", ['nop' => $nop])->row();
        }
    
        if ($result) {
            return (int)$result->ppn;
        } else {
            return 0; // Return 0 or handle the case when there is no result as per your requirement.
        }
    }
    
    function get($id = 0) {
        $this->db->select("btb.*,suppliers.name,suppliers.address,suppliers.telephone");
        $this->db->join("suppliers","suppliers.id = btb.supplier_id","left");
        if($id) {
            return $this->db->get_where("btb",["btb.id" => $id]);
        } else {
            return $this->db->get("btb");
        }
    }

    function get_details($id) {
        $this->db->select("btb_details.*,products.name,products.kditem");
        $this->db->join("products","products.id = btb_details.product_id","left");
        return $this->db->get_where("btb_details",["purchase_id" => $id]);
    }

    function CreateCode(){
        $this->db->select('RIGHT(btb.nbtb,4) as nbtb', FALSE);
        $this->db->order_by('nbtb','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('btb');
            if($query->num_rows() <> 0){      
                 $data = $query->row();
                 $kode = intval($data->nbtb) + 1; 
            }
            else{      
                 $kode = 1;  
            }
        $date = date("dmy");    
        $batas = str_pad($kode, 4, "0", STR_PAD_LEFT);    
        $kodetampil = "BTB".$date.$batas;
        return $kodetampil;  
    }
    

    public function get_btb_by_id($purchase_id) {
        $query = $this->db->select('nbtb')
                          ->from('btb')
                          ->where('id', $purchase_id)
                          ->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->nbtb;
        } else {
            return NULL; // Handle the case where NOP is not found
        }
    }
    
    function get_nop($id = 0) {
        $this->db->select("btb.*, suppliers.name");
        $this->db->join("suppliers", "suppliers.id = btb.supplier_id", "left");
        if(!$id) {
            $this->db->order_by("nbtb","ASC");
            return $this->db->get("btb");
        } else {
            return $this->db->get_where("btb",['id' => $id]);
        }
    }
}