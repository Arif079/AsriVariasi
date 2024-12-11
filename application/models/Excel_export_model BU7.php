<?php
class Excel_export_model extends CI_Model
{   
    // Function to get journal entries for a specified COA within a date range
    public function get_coa($coa_value){
        $this->db->where('coa', $coa_value);
        $query = $this->db->get('coa'); // replace 'your_coa_table' with the actual table name
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return null;
        }
    }
    
    public function get_all_coa() {
        $query = $this->db->get('coa'); // Fetch all records from the 'coa' table
        if ($query->num_rows() > 0) {
            return $query->result(); // Return all records as an array of objects
        } else {
            return null; // Return null if no records are found
        }
    }
    
    public function get_jurnal($coa_value, $start = 0, $end = 0) {
        // Initialize an empty array to store results
        $result = array();
    
        // Query journal entries for the specified COA
        $this->db->select("
            j1.kdjurnal,
            j1.kdtransaksi,
            j1.kdbukti, 
            j1.date, 
            COALESCE(bkklain.uraian, bkmlain.uraian, bkk_details.uraian, bkm_details.uraian, memo.uraian,CONCAT(transactions.customer,' ', transactions.nama_qq),suppliers.name) AS uraian, 
            j1.coa,
            CASE 
                WHEN j1.kdtransaksi LIKE 'npc%'  THEN j1.kredit
                ELSE j1.debet 
            END AS debet,
            CASE 
                WHEN j1.kdtransaksi LIKE 'npc%'  THEN j1.debet
                ELSE j1.kredit 
            END AS kredit,
            COALESCE(
             CASE 
            WHEN j1.tipe = 'hpp' THEN j2.coa
            WHEN j1.tipe = 'persediaan' THEN j5.coa
            WHEN j1.tipe = 'piutang' THEN j4.coa
            WHEN j1.tipe = 'penjualan' THEN j3.coa
            END,
            j6.coa,
            j7.coa,
            j8.coa,
            j9.coa
            ) AS coalawan,
            (
                SELECT SUM(
                    CASE 
                        WHEN sub_ju.kdtransaksi LIKE 'npc%'  THEN sub_ju.kredit
                        ELSE sub_ju.debet 
                    END 
                    - 
                    CASE 
                        WHEN sub_ju.kdtransaksi LIKE 'npc%'  THEN sub_ju.debet 
                        ELSE sub_ju.kredit 
                    END
                ) 
                FROM jurnalumum sub_ju 
                WHERE 
                    sub_ju.date <= j1.date
                    AND sub_ju.coa = 'j1.coa' -- Add additional conditions as per your filtering logic
                    AND sub_ju.date BETWEEN '$start' AND '$end'
            ) AS saldo
    
        ");
        $this->db->from("jurnalumum AS j1");
        $this->db->join("bkklain", "bkklain.nbl=j1.kdtransaksi", "left");
        $this->db->join("bkmlain", "bkmlain.npl=j1.kdtransaksi", "left");
        $this->db->join("bkk", "bkk.nph = j1.kdtransaksi", "left");
        $this->db->join("bkk_details", "bkk_details.nph = bkk.nph", "left");
        $this->db->join("bkm", "bkm.npc = j1.kdtransaksi", "left");
        $this->db->join("bkm_details", "bkm_details.npc = bkm.npc", "left");
        $this->db->join("memo", "memo.nmm=j1.kdtransaksi", "left");
        $this->db->join("btb", "btb.nbtb=j1.kdtransaksi", "left");
        $this->db->join("suppliers", "suppliers.id=btb.supplier_id", "left");
        $this->db->join("coa", "j1.coa = coa.coa", "left");
        $this->db->join("transactions", "j1.kdbukti = transactions.spkid", "left");
        $this->db->join("jurnalumum AS j2", "j1.kdbukti = j2.kdbukti AND j1.kdtransaksi = j2.kdtransaksi AND j2.tipe = 'persediaan' AND j2.kdbukti LIKE 'spk%'", "left");
        $this->db->join("jurnalumum AS j3", "j1.kdbukti = j3.kdbukti AND j1.kdtransaksi = j3.kdtransaksi AND j3.tipe = 'piutang' AND j3.kdbukti LIKE 'spk%'", "left");
        $this->db->join("jurnalumum AS j4", "j1.kdbukti = j4.kdbukti AND j1.kdtransaksi = j4.kdtransaksi AND j4.tipe = 'penjualan' AND j4.kdbukti LIKE 'spk%'", "left");
        $this->db->join("jurnalumum AS j5", "j1.kdbukti = j5.kdbukti AND j1.kdtransaksi = j5.kdtransaksi AND j5.tipe = 'hpp' AND j5.kdbukti LIKE 'spk%'", "left");
        $this->db->join("jurnalumum AS j6", "j1.kdbukti = j6.kdbukti AND j6.coa != j1.coa AND j6.kdbukti LIKE 'btb%'", "left");
        $this->db->join("jurnalumum AS j7", "j1.kdbukti = j7.kdbukti AND j7.tipe != j1.tipe AND j7.kdbukti LIKE 'bkk%'", "left");
        $this->db->join("jurnalumum AS j8", "j1.kdbukti = j8.kdbukti AND j8.tipe != j1.tipe  AND j8.kdbukti LIKE 'bkm%'", "left");
        $this->db->join("jurnalumum AS j9", "j1.kdbukti = j9.kdbukti AND j9.id != j1.id AND j9.kdbukti LIKE 'nmm%'", "left");

        
    
        $this->db->where("j1.coa", $coa_value);
    
        // Apply date filtering
        if ($start && $end) {
            $this->db->where("DATE(j1.date) >=", $start);
            $this->db->where("DATE(j1.date) <=", $end);
        } else {
            $this->db->where("MONTH(j1.date)", date("m"));
            $this->db->where("YEAR(j1.date)", date("Y"));
        }
    
        // Execute the query
        $query = $this->db->get();
    
        // Store the result in the array
        $result = $query->result();
    
        // Return the result
        return $result;
    }

    public function get_neraca($coa_value, $start = 0, $end = 0) {
        // Initialize an empty array to store results
        $result = array();
    
        // Start from the COA table
        $this->db->select("
            coa.coa,
            coa.nama,
            COALESCE(SUM(
                CASE 
                    WHEN j1.kdtransaksi LIKE 'slw%' THEN j1.debet
                    ELSE 0
                END
            ), 0) AS saldo_awal,
            COALESCE(SUM(
                CASE 
                    WHEN j1.kdtransaksi LIKE 'npc%' THEN j1.kredit
                    ELSE j1.debet 
                END
            ), 0) AS total_debet,
            COALESCE(SUM(
                CASE 
                    WHEN j1.kdtransaksi LIKE 'npc%' THEN j1.debet
                    ELSE j1.kredit 
                END
            ), 0) AS total_kredit
        ");
    
        // Select from the COA table
        $this->db->from("coa");
        
        // Left join with jurnalumum
        $this->db->join("jurnalumum AS j1", "j1.coa = coa.coa", "left");
    
        // Apply where conditions for COA
        if ($coa_value) {
            $this->db->where("coa.coa", $coa_value);
        }
    
        // Apply date filtering
        if ($start && $end) {
            $this->db->where("DATE(j1.date) >=", $start);
            $this->db->where("DATE(j1.date) <=", $end);
        } else {
            $this->db->where("MONTH(j1.date)", date("m"));
            $this->db->where("YEAR(j1.date)", date("Y"));
        }
    
        // Group by COA and nama
        $this->db->group_by(['coa.coa', 'coa.nama']);
    
        // Execute the query
        $query = $this->db->get();
    
        // Store the result in the array
        $result = $query->result();
    
        // Return the result
        return $result;
    }
}
