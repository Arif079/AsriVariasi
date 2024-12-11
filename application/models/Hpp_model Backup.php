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
        $first_iteration = true;
    
        foreach ($fifo_data as $item) {
            if ($quantity_needed <= 0) {
                break; // We have enough stock, no need to process further
            }
            
            $available_quantity = $item->stokecer;
    
            // Calculate the stock to use (either the remaining quantity or quantity_needed)
            $stock_to_use = min($available_quantity, $quantity_needed);
    
            // Deduct the used stock from the remaining quantity
            $available_quantity -= $stock_to_use;
    
            // Deduct the used stock from quantity_needed
            $quantity_needed -= $stock_to_use;
    
            // Add the used stock to the FIFO stock
            $fifo_stock += $stock_to_use;
    
            // Calculate the cost associated with the used stock and add it to the total
            if ($item->hppecer == 0) {
                // Handle the case where hppecer is zero
                log_message('warning', 'Item ' . $item->kditem . ' has zero cost per unit (hppecer). Stock used: ' . $stock_to_use);
                // Set associated HPP to 0 for this stock used
                $associated_hpp += 0; // Explicitly add 0
            } else {
                // Calculate the cost associated with the used stock and add it to the total
                $associated_hpp += $stock_to_use * $item->hppecer;
            }
    
            // If there's still some remaining quantity for this item, update the record in the database
            if ($available_quantity > 0) {
                $this->db->where('id', $item->id)->update('converthpp', array('stokecer' => $available_quantity));
            } else {
                if (!$first_iteration) {
                    // If this is the second iteration and there's no more stock, set quantity to zero
                    $this->db->where('id', $item->id)->update('converthpp', array('stokecer' => 0));
                }
            }
            $first_iteration = false;
        }
        
        return array('fifo_stock' => $fifo_stock, 'associated_hpp' => $associated_hpp);
    }
    
    

}