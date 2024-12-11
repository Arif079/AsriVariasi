<?php

class Wo_model extends CI_Model {
    function post($data) {
        $this->db->insert("wo",$data);
    }

    function delete($id) {
        $this->db->delete("wo",['id' => $id]);
        return $this->db->affected_rows();
    }

    function put($id,$data) {
        $this->db->where("id",$id);
        $this->db->update("wo",$data);
    }
    function put_detail($nowo,$data) {
        $this->db->where("nowo",$nowo);
        $this->db->update("teknisi",$data);
        if ($this->db->affected_rows() === 0) {
            // "nowo" was not found in the database, so do nothing and return
            return;
        }
    }
    function put_spk($spkid,$data) {
        $this->db->where("spkid",$spkid);
        $this->db->update("transactions",$data);
    }

    function post_details($data) {
        $this->db->insert_batch("teknisi",$data);
    }

    function CreateCode(){
        $this->db->select('RIGHT(wo.nowo,4) as nowo', FALSE);
        $this->db->order_by('nowo','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('wo');
            if($query->num_rows() <> 0){      
                 $data = $query->row();
                 $kode = intval($data->nowo) + 1; 
            }
            else{      
                 $kode = 1;  
            }
        $date = date("dmy");    
        $batas = str_pad($kode, 4, "0", STR_PAD_LEFT);    
        $kodetampil = "WO".$date.$batas;
        return $kodetampil;  
    }
    
    
    function get($id) {
        if($id) {
            return $this->db->get_where("wo",["id" => $id]);
        } else {
            return $this->db->get("wo");
        }
    }
    function get_details($id) {
        $this->db->select("details.*,products.kdjob,products.namajob");
        $this->db->join("products","products.id = details.product_id","left");
        return $this->db->get_where("details",["transaction_id" => $id]);
    }
    
    function get_wo($id = 0) {
        if(!$id) {
            $this->db->order_by("nowo","ASC");
            return $this->db->get("wo");
        } else {
            return $this->db->get_where("wo",['id' => $id]);
        }
    }
    function CreateInv(){
        $date = date("m"); 
        $this->db->select('RIGHT(wo.noinvoice,4) as noinvoice', FALSE);
        $this->db->where('wo.status !=', 'BATAL');
        $this->db->where('MONTH(wo_date)', $date);
        $this->db->order_by('noinvoice','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('wo');
            if($query->num_rows() <> 0){      
                 $data = $query->row();
                 $kode = intval($data->noinvoice) + 1; 
            }
            else{      
                 $kode = 1;  
            }
        $date = date("dmy");    
        $batas = str_pad($kode, 4, "0", STR_PAD_LEFT);    
        $kodetampil = "INV".$date.$batas;
        return $kodetampil;  
    }
    function get_teknisi($nowo) {
        return $this->db->get_where("teknisi",["nowo" => $nowo]);
    }

    function get_spk($spkid) {
        $query = $this->db->select('id')
                         ->from('transactions')
                         ->where('spkid', $spkid)
                         ->get();
    
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->id;
        } else {
            return NULL; // Handle the case where 'kodepaket' is not found
        }
    }
    function get_spkid($id) {
        if($id) {
            return $this->db->get_where("transactions",["id" => $id]);
        } else {
            return $this->db->get("transactions");
        }
    }
}