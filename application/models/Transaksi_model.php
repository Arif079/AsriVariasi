<?php
class Transaksi_model extends CI_Model {
    function post($data) {
        $this->db->insert("transaksi",$data);
        return $this->db->insert_id();
    }

    function post_details($data) {
        $this->db->insert_batch("transaksi_details",$data);
    }

    function update_stock($data) {
        $this->db->update_batch("btb",$data,"id");
    }

    function update_status($id, $newStatus) {
        $data = array('status' => $newStatus);
        $this->db->where('id', $id);
        $this->db->update('purchase', $data);
    }

    function get($id = 0) {
        $this->db->select("transaksi.*,suppliers.name,suppliers.address,suppliers.telephone");
        $this->db->join("suppliers","suppliers.id = transaksi.supplier_id","left");
        if($id) {
            return $this->db->get_where("transaksi",["transaksi.id" => $id]);
        } else {
            return $this->db->get("transaksi");
        }
    }

    function get_details($id) {
        $this->db->select("transaksi_details.*,btb.nbtb,btb.surat,btb.date");
        $this->db->join("btb","btb.id = transaksi_details.btb_id","left");
        return $this->db->get_where("transaksi_details",["purchase_id" => $id]);
    }
    
    function get_btb($id) {
        $this->db->select("transaksi_details.*,btb.nbtb,btb.surat,btb.date");
        $this->db->join("btb","btb.id = transaksi_details.btb_id","left");
        return $this->db->get_where("transaksi_details",["purchase_id" => $id]);
    }


    
    function get_purchase_details($id) {
        $this->db->select("btb.*, purchase_details.purchase_id,purchase.total,purchase.ppn");
        $this->db->join("purchase_details", "purchase_details.nop = btb.nop", "left");
        $this->db->join("purchase", "purchase.nop = btb.nop", "left");
        $this->db->where("btb.id", $id); // Specify the table name and the condition
        return $this->db->get("btb"); // Specify the table name
    }
    
    function get_item($id) {
        $this->db->select("purchase_details.*, products.name, products.kditem");
        $this->db->join("products", "products.id = purchase_details.product_id", "left");
        $this->db->where("purchase_details.purchase_id", $id); // Specify the table name and the condition
        return $this->db->get("purchase_details"); // Specify the table name
    }
    
    public function get_ntrn_by_id($purchase_id) {
        $query = $this->db->select('ntrn')
                          ->from('transaksi')
                          ->where('id', $purchase_id)
                          ->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->ntrn;
        } else {
            return NULL; // Handle the case where NOP is not found
        }
    }

    function CreateCode(){
        $this->db->select('RIGHT(transaksi.ntrn,4) as ntrn', FALSE);
        $this->db->order_by('ntrn','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('transaksi');
            if($query->num_rows() <> 0){      
                 $data = $query->row();
                 $kode = intval($data->ntrn) + 1; 
            }
            else{      
                 $kode = 1;  
            }
        $date = date("dmy");    
        $batas = str_pad($kode, 4, "0", STR_PAD_LEFT);    
        $kodetampil = "NTP".$date.$batas;
        return $kodetampil;  
    }   
    
    function CreateFaktur(){
        $this->db->select('RIGHT(transaksi.ntrn,4) as ntrn', FALSE);
        $this->db->order_by('ntrn','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('transaksi');
            if($query->num_rows() <> 0){      
                 $data = $query->row();
                 $kode = intval($data->ntrn); 
            }
            else{      
                 $kode = 1;  
            }
        $date = date("dmy");    
        $batas = str_pad($kode, 4, "0", STR_PAD_LEFT);    
        $kodetampil = "NFB".$date.$batas;
        return $kodetampil;  
    }
}