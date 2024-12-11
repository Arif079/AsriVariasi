<?php

class Jurnal_model extends CI_Model {
    function post($data) {
        $this->db->insert("jurnalumum",$data);
    }


    function get($id = 0) {
        if(!$id) {
            $this->db->order_by("kdjurnal","DESC");
            return $this->db->get("jurnalumum");
        } else {
            return $this->db->get_where("jurnalumum",['id' => $id]);
        }
    }

    function get_item($id) {
        if($id) {
            return $this->db->get_where("products",["id" => $id]);
        } else {
            return $this->db->get("products");
        }
    }

    function get_akunpaket($kditem) {
        $this->db->select("products.*,paket.hpp");
        $this->db->join("paket","paket.kdpaket = products.kditem","left");
        return $this->db->get_where("products",["kditem" => $kditem]);
    }

    function get_akunitem($kditem) {
        $this->db->select("products.*,paket.hpp");
        $this->db->join("paket","paket.kdpaket = products.kditem","left");
        return $this->db->get_where("products",["products" => $kditem]);
    }

    function CreateCode(){
        $this->db->select('RIGHT(jurnalumum.kdjurnal,8) as kdjurnal', FALSE);
        $this->db->order_by('kdjurnal','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('jurnalumum');
            if($query->num_rows() <> 0){      
                 $data = $query->row();
                 $kode = intval($data->kdjurnal) + 1; 
            }
            else{      
                 $kode = 1;  
            }
        $date = date("dmy");    
        $batas = str_pad($kode, 8, "0", STR_PAD_LEFT);    
        $kodetampil = "JMM".$date.$batas;
        return $kodetampil;  
    }
    

}