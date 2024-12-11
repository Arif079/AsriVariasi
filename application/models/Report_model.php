<?php
class Report_model extends CI_Model {
    function get($type = "sparepart",$start = 0,$end = 0) {
        $this->db->select("details.*,products.type,DATE(transactions.date) as date,SUM(details.qty * details.price) as total,SUM(qty) as items,transactions.customer,transactions.plat");
        $this->db->join("products","`products`.`id` = `details`.`product_id`","left");
        $this->db->join("transactions","`transactions`.`id` = `details`.`transaction_id`","left");
        $this->db->where("products.type",$type);

        if($start AND $end) {
            $this->db->where("DATE(date) >=",$start);
            $this->db->where("DATE(date) <=",$end);
        } else {
            $this->db->where("MONTH(date)",date("m"));
            $this->db->where("YEAR(date)",date("Y"));
        }

        $this->db->group_by("transaction_id");

        return $this->db->get("details");
    }
    
    function get_purchase($start = 0,$end = 0) {
        $this->db->select("purchase_details.*,transaksi.date,btb.nbtb,btb.nop,transaksi_details.ntrn,products.name as prod,suppliers.name as supp,(purchase_details.qty * purchase_details.price) as subtotal, ((purchase_details.qty * purchase_details.price) * (purchase.ppn / 100)) as ppn, ((purchase_details.qty * purchase_details.price) + ((purchase_details.qty * purchase_details.price) * (purchase.ppn / 100))) as grntotal");
        $this->db->join("purchase","purchase.id=purchase_details.purchase_id","left");
        $this->db->join("btb","btb.nop=purchase.nop","left");
        $this->db->join("suppliers","suppliers.id=purchase.supplier_id","left");
        $this->db->join("products","products.id=purchase_details.product_id","left");
        $this->db->join("transaksi_details","transaksi_details.btb_id=btb.id","left");
        $this->db->join("transaksi","transaksi.id=transaksi_details.purchase_id","left");
        $this->db->where("purchase.status !=","BATAL");

        if($start AND $end) {
            $this->db->where("DATE(transaksi.date) >=",$start);
            $this->db->where("DATE(transaksi.date) <=",$end);
        } else {
            $this->db->where("MONTH(transaksi.date)",date("m"));
            $this->db->where("YEAR(transaksi.date)",date("Y"));
        }

        return $this->db->get("purchase_details");
    }

    function get_spk($start = 0,$end = 0) {
       $this->db->select("transactions.*,(transactions.total - transactions.sisa) as bayar,wo.nowo,wo.noinvoice");
       $this->db->join("wo","wo.spkid=transactions.spkid","left");
       $this->db->where("transactions.status !=","BATAL");
       $this->db->where("wo.status !=","BATAL");
       $this->db->where("wo.noinvoice !=","BATAL");

        if($start AND $end) {
            $this->db->where("DATE(date) >=",$start);
            $this->db->where("DATE(date) <=",$end);
        } else {
            $this->db->where("MONTH(date)",date("m"));
            $this->db->where("YEAR(date)",date("Y"));
        }

        //$this->db->group_by("purchase_id");

        return $this->db->get("transactions");
    }

    function get_last_month_saldo() {
        // Get the first and last day of the last month
        $start = date('Y-m-01', strtotime('first day of last month'));
        $end = date('Y-m-t', strtotime('last day of last month'));
    
        // Call the existing function to get saldo
        $saldoakhir = $this->get_saldo($start, $end);

        return $saldoakhir;
    }
    
    function get_saldo($start = 0, $end = 0) {
        if ($start == 0 && $end == 0) {
            $start = date('Y-m-01'); // First day of the current month
            $end = date('Y-m-t'); // Last day of the current month
        } elseif ($start != 0 && $end != 0) {
            $start = date('Y-m-d', strtotime($start));
            $end = date('Y-m-d', strtotime($end));
        }
        $this->db->select("ju1.kdtransaksi, ju1.date,COALESCE(bkk_log.bkkno,bkm_log.bkmno) AS buku,
            COALESCE(bkklain.uraian, bkmlain.uraian, bkk_details.uraian, bkm_details.uraian, memo.uraian) AS uraian, 
            ju1.coa,
            CASE 
                WHEN ju1.kdtransaksi LIKE 'npc%' THEN ju1.kredit
                WHEN ju1.kdtransaksi LIKE 'nbl%' THEN ju1.kredit
                ELSE ju1.debet 
            END AS debet,
            CASE 
                WHEN ju1.kdtransaksi LIKE 'npc%' THEN ju1.debet
                WHEN ju1.kdtransaksi LIKE 'nbl%' THEN ju1.debet
                ELSE ju1.kredit 
            END AS kredit,
            COALESCE(bkklain.coad, bkmlain.coad, bkk_details.coad, bkm_details.coad, memo.coad) AS coalawan,
            (
                SELECT SUM(
                    CASE 
                        WHEN sub_ju.kdtransaksi LIKE 'npc%' THEN sub_ju.kredit
                        WHEN sub_ju.kdtransaksi LIKE 'nbl%' THEN sub_ju.kredit
                        ELSE sub_ju.debet 
                    END 
                    - 
                    CASE 
                        WHEN sub_ju.kdtransaksi LIKE 'npc%' THEN sub_ju.debet 
                        WHEN sub_ju.kdtransaksi LIKE 'nbl%' THEN sub_ju.debet 
                        ELSE sub_ju.kredit 
                    END
                ) 
                FROM jurnalumum sub_ju 
                WHERE 
                    sub_ju.date <= ju1.date
                    AND (sub_ju.coa = '1-1-101') -- Add additional conditions as per your filtering logic
                    AND sub_ju.date BETWEEN $start AND $end
            ) AS saldo
        ");
    
        $this->db->from("jurnalumum ju1");
        $this->db->join("bkklain", "bkklain.nbl=ju1.kdtransaksi", "left");
        $this->db->join("bkmlain", "bkmlain.npl=ju1.kdtransaksi", "left");
        $this->db->join("bkk", "bkk.nph = ju1.kdtransaksi", "left");
        $this->db->join("bkk_details", "bkk_details.nph = bkk.nph", "left");
        $this->db->join("bkm", "bkm.npc = ju1.kdtransaksi", "left");
        $this->db->join("bkm_details", "bkm_details.npc = bkm.npc", "left");
        $this->db->join("memo", "memo.nmm=ju1.kdtransaksi", "left");
        $this->db->join("bkk_log", "bkk_log.referensibkk=ju1.kdtransaksi", "left");
        $this->db->join("bkm_log", "bkm_log.referensibkm=ju1.kdtransaksi", "left");
        $this->db->where("ju1.coa", "1-1-101");
    
        $this->db->order_by('ju1.date', 'DESC');
        $this->db->limit(1);
    
        // Execute the query
        $query = $this->db->get();
        
        // Check if there are rows in the result
        if ($query->num_rows() > 0) {
            // Get the first (and only) row from the result
            $row = $query->row();
            // Extract and return the saldo
            return $row->saldo;
        }
    
        // Return null if there are no rows
        return null;
    }

    
    function get_kas($start = 0, $end = 0) {
        if ($start == 0 && $end == 0) {
            $start = date('Y-m-01'); // First day of the current month
            $end = date('Y-m-t'); // Last day of the current month
        } elseif ($start != 0 && $end != 0) {
            $start = date('Y-m-d', strtotime($start));
            $end = date('Y-m-d', strtotime($end));
        }
        $this->db->select("ju1.kdtransaksi, ju1.date,COALESCE(bkk_log.bkkno,bkm_log.bkmno) AS buku,
            COALESCE(bkklain.uraian, bkmlain.uraian, bkk_details.uraian, bkm_details.uraian, memo.uraian) AS uraian, 
            ju1.coa,
            CASE 
                WHEN ju1.kdtransaksi LIKE 'npc%' THEN ju1.kredit
                ELSE ju1.debet 
            END AS debet,
            CASE 
                WHEN ju1.kdtransaksi LIKE 'npc%'  THEN ju1.debet
                ELSE ju1.kredit 
            END AS kredit,
            COALESCE(bkklain.coad, bkmlain.coad, bkk_details.coad, bkm_details.coad, memo.coad) AS coalawan,
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
                    sub_ju.date <= ju1.date
                    AND (sub_ju.coa = '1-1-101') -- Add additional conditions as per your filtering logic
                    AND sub_ju.date BETWEEN $start AND $end
            ) AS saldo
        ");
    
        $this->db->from("jurnalumum ju1");
        $this->db->join("bkklain", "bkklain.nbl=ju1.kdtransaksi", "left");
        $this->db->join("bkmlain", "bkmlain.npl=ju1.kdtransaksi", "left");
        $this->db->join("bkk", "bkk.nph = ju1.kdtransaksi", "left");
        $this->db->join("bkk_details", "bkk_details.nph = bkk.nph", "left");
        $this->db->join("bkm", "bkm.npc = ju1.kdtransaksi", "left");
        $this->db->join("bkm_details", "bkm_details.npc = bkm.npc", "left");
        $this->db->join("memo", "memo.nmm=ju1.kdtransaksi", "left");
        $this->db->join("bkk_log", "bkk_log.referensibkk=ju1.kdtransaksi", "left");
        $this->db->join("bkm_log", "bkm_log.referensibkm=ju1.kdtransaksi", "left");
        $this->db->where("ju1.coa", "1-1-101");
    
        if ($start && $end) {
            $this->db->where("DATE(ju1.date) >=", $start);
            $this->db->where("DATE(ju1.date) <=", $end);
        } else {
            $this->db->where("MONTH(ju1.date)", date("m"));
            $this->db->where("YEAR(ju1.date)", date("Y"));
        }
    
        return $this->db->get();
    }
    
    function get_jurnal_all_coa($start = 0, $end = 0) {
        // Initialize an empty array to store results for all unique COA
        $result = array();
    
        // Retrieve distinct COA values
        $this->db->distinct();
        $this->db->select("coa");
        $this->db->from("jurnalumum");
        $coa_query = $this->db->get();
    
        // Loop through each distinct COA
        foreach ($coa_query->result() as $coa_row) {
            $coa_value = $coa_row->coa;
    
            // Call the get_jurnal function for the current COA
            $coa_result = $this->get_jurnal($start, $end, $coa_value);
    
            // Store the result in the array indexed by COA
            $result[$coa_value] = $coa_result;
        }
    
        // Return the array containing data for all unique COA values
        return $result;
    }
    
    function get_jurnal( $coa_value,$start = 0, $end = 0) {
        // Modify the existing get_jurnal function to accept a COA value parameter
        // Initialize an empty array to store results
        $result = array();
    
        // Query journal entries for the specified COA
        $this->db->select("IF(bkk_log.bkkno IS NOT NULL, bkk_log.bkkno, IF(bkm_log.bkmno IS NOT NULL, bkm_log.bkmno, j1.kdtransaksi)) AS kdtransaksi,j1.date,coa.nama,j1.coa, j1.debet, j1.kredit, j2.coa coalawan, j2.debet AS lawandebet, j2.kredit AS lawankredit");
        $this->db->from("jurnalumum AS j1");
        $this->db->join("jurnalumum AS j2", "j1.kdtransaksi = j2.kdtransaksi AND j1.coa!= j2.coa", "left");
        $this->db->join("coa", "j1.coa = coa.coa", "left");
        $this->db->join("bkk_log", "j1.kdtransaksi = bkk_log.referensibkk", "left");
        $this->db->join("bkm_log", "j1.kdtransaksi = bkm_log.referensibkm", "left");
        $this->db->where("j1.coa", $coa_value);
    
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
    
    
    

   
    function get_hpp_jasa($start = 0,$end = 0) {
        $this->db->select("details.*, transactions.spkid, transactions.date, transactions.customer, transactions.nama_qq, transactions.status,(details.qty * details.price) AS total, converthpp.hppecer","products.coahpp");
        $this->db->join("transactions", "transactions.id = details.transaction_id", "left");
        $this->db->join("converthpp", "converthpp.kditem = details.kditem", "left");
        $this->db->join("products", "products.kditem = details.kditem", "left");
        $this->db->where("transactions.status !=", "BATAL");
        $this->db->where("products.coahpp","4-0-301");

        if($start AND $end) {
            $this->db->where("DATE(date) >=",$start);
            $this->db->where("DATE(date) <=",$end);
        } else {
            $this->db->where("MONTH(date)",date("m"));
            $this->db->where("YEAR(date)",date("Y"));
        }



        return $this->db->get("details");
    }

    function get_hpp_variasi($start = 0,$end = 0) {
        $this->db->select("details.*, transactions.spkid, transactions.date, transactions.customer, transactions.nama_qq, transactions.status,(details.qty * details.price) AS total, converthpp.hppecer","products.coahpp");
        $this->db->join("transactions", "transactions.id = details.transaction_id", "left");
        $this->db->join("converthpp", "converthpp.kditem = details.kditem", "left");
        $this->db->join("products", "products.kditem = details.kditem", "left");
        $this->db->where("transactions.status !=", "BATAL");
        $this->db->where("products.coahpp","4-0-302");

        if($start AND $end) {
            $this->db->where("DATE(date) >=",$start);
            $this->db->where("DATE(date) <=",$end);
        } else {
            $this->db->where("MONTH(date)",date("m"));
            $this->db->where("YEAR(date)",date("Y"));
        }



        return $this->db->get("details");
    }

    function get_hpp_antikarat($start = 0,$end = 0) {
        $this->db->select("details.*, transactions.spkid, transactions.date, transactions.customer, transactions.nama_qq, transactions.status,(details.qty * details.price) AS total, converthpp.hppecer","products.coahpp");
        $this->db->join("transactions", "transactions.id = details.transaction_id", "left");
        $this->db->join("converthpp", "converthpp.kditem = details.kditem", "left");
        $this->db->join("products", "products.kditem = details.kditem", "left");
        $this->db->where("transactions.status !=", "BATAL");
        $this->db->where("products.coahpp","4-0-303");

        if($start AND $end) {
            $this->db->where("DATE(date) >=",$start);
            $this->db->where("DATE(date) <=",$end);
        } else {
            $this->db->where("MONTH(date)",date("m"));
            $this->db->where("YEAR(date)",date("Y"));
        }

    

        return $this->db->get("details");
    }

    function get_today_income($filter = "daily", $start_date = 0, $end_date = 0) {
        $this->db->select("SUM(total) as income, DATE(date)");
    
        // Apply date range filter
        if ($filter == "daily") {
            $this->db->where("DATE(date)", date("Y-m-d"));
        } elseif ($filter == "weekly") {
            $this->db->where("WEEK(date) = WEEK(NOW())");
        } elseif ($filter == "monthly") {
            $this->db->where("MONTH(date) = MONTH(NOW())");
        } elseif ($filter == "date_range" && $start_date && $end_date) {
            $this->db->where("DATE(date) BETWEEN '$start_date' AND '$end_date'");
        }
    
        $this->db->group_by("date");
        $income = $this->db->get_where("transactions", ["DATE(date)" => date("Y-m-d")])->row();
    
        if ($income) {
            return $income->income;
        } else {
            return 0;
        }
    }
    

    function get_today_transaction($type = "sparepart") {
        $date = date("Y-m-d");

        $this->db->select("SUM(qty) as count,DATE(date) as date,products.type");
        $this->db->join("products","products.id=details.product_id","left");
        $this->db->join("transactions","transactions.id=details.transaction_id","left");
        $this->db->group_by("date");
        $count = $this->db->get_where("details",["DATE(date)" => $date,"products.type" => $type])->row();
        if($count) {
            return $count->count;
        } else {
            return 0;
        }
    }

    
    function get_spkid_count($filter = "daily", $start_date = 0, $end_date = 0) {
        $this->db->select("COUNT(spkid) as count");
        
        // Apply date range filter
        if ($filter == "daily") {
            $this->db->where("DATE(date)", date("Y-m-d"));
        } elseif ($filter == "weekly") {
            $this->db->where("YEARWEEK(date, 1) = YEARWEEK(NOW(), 1)");  // ISO week with Monday as first day
        } elseif ($filter == "monthly") {
            $this->db->where("MONTH(date) = MONTH(NOW())");
            $this->db->where("YEAR(date) = YEAR(NOW())");  // Ensure current year
        } elseif ($filter == "date_range" && $start_date && $end_date) {
            $this->db->where("DATE(date) BETWEEN '$start_date' AND '$end_date'");
        }
    
        $count = $this->db->get("transactions")->row();
    
        return $count ? $count->count : 0;
    }
    
    

    function get_sold_out() {
        return $this->db->get_where("products",["stock" => 0,"type" => "sparepart"])->num_rows();
    }

    function get_graph($month,$type = "sparepart") {

        $count = count($month) - 1;
        $start_month = $month[0];
        $end_month = $month[$count];

        if($start_month > $end_month) {
            $date = (date("Y") - 1)."-".$start_month."-01 00:00:00";
        } else {
            $date = date("Y")."-".$start_month."-01 00:00:00";
        }

        $this->db->select("SUM(details.qty * details.price) as total,MONTH(date) as date,products.type");
        $this->db->join("products","products.id=details.product_id","left");
        $this->db->join("transactions","transactions.id=details.transaction_id","left");
        $this->db->group_by("MONTH(date)");
        $this->db->where("products.type",$type);
        $this->db->where("transactions.date >=",$date);
        $this->db->where_in("MONTH(date)",$month);

        return $this->db->get("details");
        
    }

    function get_graph2($month,$type = "sparepart") {

        $count = count($month) - 1;
        $start_month = $month[0];
        $end_month = $month[$count];

        if($start_month > $end_month) {
            $date = (date("Y") - 1)."-".$start_month."-01 00:00:00";
        } else {
            $date = date("Y")."-".$start_month."-01 00:00:00";
        }

        $this->db->select("SUM(services.qty * services.price) as total,MONTH(date) as date,products.type");
        $this->db->join("products","products.id=services.product_id","left");
        $this->db->join("transactions","transactions.id=services.transaction_id","left");
        $this->db->group_by("MONTH(date)");
        $this->db->where("products.type",$type);
        $this->db->where("transactions.date >=",$date);
        $this->db->where_in("MONTH(date)",$month);

        return $this->db->get("services");
        
    }
}

