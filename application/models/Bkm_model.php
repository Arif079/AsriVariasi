<?php

class Bkm_model extends CI_Model {
    function post($data) {
        $this->db->insert("bkmlain",$data);
    }

    function post2($data) {
        $this->db->insert("bkm",$data);
        return $this->db->insert_id();
    }

    function post3($data) {
        $this->db->insert("bkm_log",$data);
    }


    function post_details($data) {
        $this->db->insert_batch("bkm_details",$data);
    }

    function delete($id) {
        $this->db->delete("bkmlain",['id' => $id]);
        return $this->db->affected_rows();
    }

    function deletejurnal($npl) {
        $this->db->delete("jurnalumum",['kdtransaksi' => $npl]);
        return $this->db->affected_rows();
    }

    function put($id,$data) {
        $this->db->where("id",$id);
        $this->db->update("bkmlain",$data);
    }

    function put2($spkid,$data) {
        $this->db->where("spkid",$spkid);
        $this->db->update("transactions",$data);
    }
    
    function get2($id) {
        $this->db->select("bkm.*,customers.name,customers.address,customers.telephone");
        $this->db->join("customers","customers.id = bkm.customer_id","left");
        if($id) {
            return $this->db->get_where("bkm",["bkm.id" => $id]);
        } else {
            return $this->db->get("bkm");
        }
    }

    function get($id) {
        if($id) {
            return $this->db->get_where("bkmlain",["id" => $id]);
        } else {
            return $this->db->get("bkmlain");
        }
    }

    function get_details($id) {
        return $this->db->get_where("bkm_details",["bkm_id" => $id]);
    }

    function get_bkmno($bkmno) {
        return $this->db->get_where("bkm_log",["referensibkm" => $bkmno]);
    }

    function get_spk($spkid) {
        return $this->db->get_where("wo",["spkid" => $spkid]);
    }
    
    function CreateCode(){
        $this->db->select('RIGHT(bkmlain.npl,4) as npl', FALSE);
        $this->db->order_by('npl','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('bkmlain');
            if($query->num_rows() <> 0){      
                 $data = $query->row();
                 $kode = intval($data->npl) + 1; 
            }
            else{      
                 $kode = 1;  
            }
        $date = date("dmy");    
        $batas = str_pad($kode, 4, "0", STR_PAD_LEFT);    
        $kodetampil = "NPL".$date.$batas;
        return $kodetampil;  
    }

    function CreateCode2(){
        $this->db->select('RIGHT(bkm.npc,4) as npc', FALSE);
        $this->db->order_by('npc','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('bkm');
            if($query->num_rows() <> 0){      
                 $data = $query->row();
                 $kode = intval($data->npc) + 1; 
            }
            else{      
                 $kode = 1;  
            }
        $date = date("dmy");    
        $batas = str_pad($kode, 4, "0", STR_PAD_LEFT);    
        $kodetampil = "NPC".$date.$batas;
        return $kodetampil;  
    }

    function CreateCode3(){
        $this->db->select('RIGHT(bkm_log.bkmno,4) as bkmno', FALSE);
        $this->db->order_by('bkmno','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('bkm_log');
            if($query->num_rows() <> 0){      
                 $data = $query->row();
                 $kode = intval($data->bkmno) + 1; 
            }
            else{      
                 $kode = 1;  
            }
        $date = date("dmy");    
        $batas = str_pad($kode, 4, "0", STR_PAD_LEFT);    
        $kodetampil = "BKM".$date.$batas;
        return $kodetampil;  
    }
    
    public function get_bkm_by_id($bkm_id) {
        $query = $this->db->select('npc')
                          ->from('bkm')
                          ->where('id', $bkm_id)
                          ->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->npc;
        } else {
            return NULL; // Handle the case where NOP is not found
        }
    }
}