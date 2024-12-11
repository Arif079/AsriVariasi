<?php

class Hpp_model extends CI_Model {
    function post($data) {
        $this->db->insert("converthpp",$data);
    }


    function get($id = 0) {
        if(!$id) {
            $this->db->order_by("kdhpp","DESC");
            return $this->db->get("converthpp");
        } else {
            return $this->db->get_where("converhpp",['id' => $id]);
        }
    }

    function CreateCode(){
        $this->db->select('RIGHT(converthpp.kdhpp,4) as kdhpp', FALSE);
        $this->db->order_by('kdhpp','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('converthpp');
            if($query->num_rows() <> 0){      
                 $data = $query->row();
                 $kode = intval($data->kdhpp) + 1; 
            }
            else{      
                 $kode = 1;  
            }
        $date = date("dmy");    
        $batas = str_pad($kode, 4, "0", STR_PAD_LEFT);    
        $kodetampil = "HPP".$date.$batas;
        return $kodetampil;  
    }

    function calculate_fifo_stock_with_hpp($kditem, $quantity_needed) {
        $this->db->select('*');
        $this->db->from('converthpp');
        $this->db->where('kditem', $kditem);
        $this->db->where('stokecer >', 0); // Filter for non-zero quantities
        $this->db->order_by('tgltransaksi', 'ASC');
        
        $query = $this->db->get();
        $fifo_data = $query->result();
    
        $fifo_stock = 0;
        $associated_hpp = 0;
    
        foreach ($fifo_data as $item) {
            if ($quantity_needed <= 0) {
                break; // We have enough stock, stop processing
            }
    
            $available_quantity = $item->stokecer;
    
            // Calculate the stock to use (either the remaining stock or quantity_needed)
            $stock_to_use = min($available_quantity, $quantity_needed);
    
            // Deduct the used stock from remaining quantity and quantity_needed
            $available_quantity -= $stock_to_use;
            $quantity_needed -= $stock_to_use;
    
            // Add the used stock to the FIFO stock
            $fifo_stock += $stock_to_use;
    
            // Calculate the cost associated with the used stock and add it to the total
            if ($item->hppecer == 0) {
                // Log a warning if cost per unit is zero
                log_message('warning', 'Item ' . $item->kditem . ' has zero cost per unit (hppecer). Stock used: ' . $stock_to_use);
                $associated_hpp += 0; // Explicitly add 0
            } else {
                $associated_hpp += $stock_to_use * $item->hppecer;
            }
    
            // Update the remaining stock in the database (set to 0 if fully depleted)
            $this->db->where('id', $item->id)->update('converthpp', array('stokecer' => max(0, $available_quantity)));
        }
    
        return array('fifo_stock' => $fifo_stock, 'associated_hpp' => $associated_hpp);
    }
    
}