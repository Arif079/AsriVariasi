<?php
class Transaction_model extends CI_Model {
    function create($data) {
        $this->db->insert("transactions",$data);
        return $this->db->insert_id();
    }

    function get_items($array) {
        $this->db->where_in("id",$array);
        return $this->db->get("products");
    }

    function post_details($data) {
        $this->db->insert_batch("details",$data);
    }

    function post_services($data) {
        $this->db->insert_batch("services",$data);
    }

    function post_paket_log($data) {
        $this->db->insert_batch("paket_log",$data);
    }

    function sparepart_update($data) {
        $this->db->update_batch("products",$data,"id");
    }

    function put($id,$data) {
        $this->db->where("id",$id);
        $this->db->update("transactions",$data);
    }
    function get($id) {
        if($id) {
            return $this->db->get_where("transactions",["id" => $id]);
        } else {
            return $this->db->get("transactions");
        }
    }

    function get_spk($id = 0) {
        if(!$id) {
            $this->db->order_by("spkid","ASC");
            return $this->db->get("transactions");
        } else {
            return $this->db->get_where("transactions",['id' => $id]);
        }
    }


    function get_details($id) {
        $this->db->select("details.*,products.kdjob,products.namajob");
        $this->db->join("products","products.id = details.product_id","left");
        return $this->db->get_where("details",["transaction_id" => $id]);
    }

    function get_component($id) {
        $this->db->select("paket_details.*,products.name,products.kditem,products.stock");
        $this->db->join("products","products.id = paket_details.product_id","left");
        return $this->db->get_where("paket_details",["paket_id" => $id]);
    }

    

    function get_paket($kodepaket) {
        $query = $this->db->select('id')
                         ->from('paket')
                         ->where('kdpaket', $kodepaket)
                         ->get();
    
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->id;
        } else {
            return NULL; // Handle the case where 'kodepaket' is not found
        }
    }
    
    function CreateCode(){
        $this->db->select('RIGHT(transactions.spkid,4) as spkid', FALSE);
        $this->db->order_by('spkid','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('transactions');
            if($query->num_rows() <> 0){      
                 $data = $query->row();
                 $kode = intval($data->spkid) + 1; 
            }
            else{      
                 $kode = 1;  
            }
        $date = date("dmy");    
        $batas = str_pad($kode, 4, "0", STR_PAD_LEFT);    
        $kodetampil = "SPK".$date.$batas;
        return $kodetampil;  
    }      

    

    
}