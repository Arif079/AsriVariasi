<?php

class Memo_model extends CI_Model {
    function post($data) {
        $this->db->insert("memo",$data);
    }

    function delete($id) {
        $this->db->delete("memo",['id' => $id]);
        return $this->db->affected_rows();
    }

    function put($id,$data) {
        $this->db->where("id",$id);
        $this->db->update("memo",$data);
    }
    
    function get($id) {
        if($id) {
            return $this->db->get_where("memo",["id" => $id]);
        } else {
            return $this->db->get("memo");
        }
    }
    
    function get_details($id) {
        return $this->db->get_where("memo", ["memo.id" => $id]);
    }
    

    function CreateCode(){
        $this->db->select('RIGHT(memo.nmm,4) as nmm', FALSE);
        $this->db->order_by('nmm','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('memo');
            if($query->num_rows() <> 0){      
                 $data = $query->row();
                 $kode = intval($data->nmm) + 1; 
            }
            else{      
                 $kode = 1;  
            }
        $date = date("dmy");    
        $batas = str_pad($kode, 4, "0", STR_PAD_LEFT);    
        $kodetampil = "NMM".$date.$batas;
        return $kodetampil;  
    }
    
}