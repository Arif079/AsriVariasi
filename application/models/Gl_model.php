<?php

class Gl_model extends CI_Model {
    function post($data) {
        $this->db->insert("kas_log",$data);
    }

    function delete($id) {
        $this->db->delete("kas_log",['id' => $id]);
        return $this->db->affected_rows();
    }

    function put($id,$data) {
        $this->db->where("id",$id);
        $this->db->update("kas_log",$data);
    }
    
    function get($id) {
        if($id) {
            return $this->db->get_where("kas_log",["id" => $id]);
        } else {
            return $this->db->get("kas_log");
        }
    }
    
    function get_details($id) {
        return $this->db->get_where("kas_log", ["kas_log.id" => $id]);
    }
    

    function CreateCode(){
        $this->db->select('RIGHT(kas_log.sld,4) as sld', FALSE);
        $this->db->order_by('sld','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('kas_log');
            if($query->num_rows() <> 0){      
                 $data = $query->row();
                 $kode = intval($data->sld) + 1; 
            }
            else{      
                 $kode = 1;  
            }
        $date = date("dmy");    
        $batas = str_pad($kode, 4, "0", STR_PAD_LEFT);    
        $kodetampil = "SLD".$date.$batas;
        return $kodetampil;  
    }
    
    
}