<?php

class Bkk_model extends CI_Model {
    function post($data) {
        $this->db->insert("bkklain",$data);
    }

    function post2($data) {
        $this->db->insert("bkk",$data);
        return $this->db->insert_id();
    }

    function post3($data) {
        $this->db->insert("bkk_log",$data);
    }

    function post_details($data) {
        $this->db->insert_batch("bkk_details",$data);
    }

    function delete($id) {
        $this->db->delete("bkklain",['id' => $id]);
        return $this->db->affected_rows();
    }

    function put($id,$data) {
        $this->db->where("id",$id);
        $this->db->update("bkklain",$data);
    }

    function put2($id,$data) {
        $this->db->where("id",$id);
        $this->db->update("transaksi",$data);
    }
    
    function get2($id) {
        $this->db->select("bkk.*,suppliers.name,suppliers.address,suppliers.telephone");
        $this->db->join("suppliers","suppliers.id = bkk.supplier_id","left");
        if($id) {
            return $this->db->get_where("bkk",["bkk.id" => $id]);
        } else {
            return $this->db->get("bkk");
        }
    }

    function get($id) {
        if($id) {
            return $this->db->get_where("bkklain",["id" => $id]);
        } else {
            return $this->db->get("bkklain");
        }
    }

    function get_details($id) {
        return $this->db->get_where("bkk_details",["bkk_id" => $id]);
    }

    function get_bkkno($bkkno) {
        return $this->db->get_where("bkk_log",["referensibkk" => $bkkno]);
    }


    
    function CreateCode(){
        $this->db->select('RIGHT(bkklain.nbl,4) as nbl', FALSE);
        $this->db->order_by('nbl','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('bkklain');
            if($query->num_rows() <> 0){      
                 $data = $query->row();
                 $kode = intval($data->nbl) + 1; 
            }
            else{      
                 $kode = 1;  
            }
        $date = date("dmy");    
        $batas = str_pad($kode, 4, "0", STR_PAD_LEFT);    
        $kodetampil = "NBL".$date.$batas;
        return $kodetampil;  
    }

    function CreateCode2(){
        $this->db->select('RIGHT(bkk.nph,4) as nph', FALSE);
        $this->db->order_by('nph','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('bkk');
            if($query->num_rows() <> 0){      
                 $data = $query->row();
                 $kode = intval($data->nph) + 1; 
            }
            else{      
                 $kode = 1;  
            }
        $date = date("dmy");    
        $batas = str_pad($kode, 4, "0", STR_PAD_LEFT);    
        $kodetampil = "NPH".$date.$batas;
        return $kodetampil;  
    }
    
    function CreateCode3(){
        $this->db->select('RIGHT(bkk_log.bkkno,4) as bkkno', FALSE);
        $this->db->order_by('bkkno','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('bkk_log');
            if($query->num_rows() <> 0){      
                 $data = $query->row();
                 $kode = intval($data->bkkno) + 1; 
            }
            else{      
                 $kode = 1;  
            }
        $date = date("dmy");    
        $batas = str_pad($kode, 4, "0", STR_PAD_LEFT);    
        $kodetampil = "BKK".$date.$batas;
        return $kodetampil;  
    }
    public function get_bkk_by_id($bkk_id) {
        $query = $this->db->select('nph')
                          ->from('bkk')
                          ->where('id', $bkk_id)
                          ->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->nph;
        } else {
            return NULL; // Handle the case where NOP is not found
        }
    }
}