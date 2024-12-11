<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {
    private $dataAdmin;

    function __construct() {
        parent::__construct();

        if(!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");
        $this->load->model("report_model");
        $this->load->model("Datatables");
        $this->load->model("Coa_model");

        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }

    public function purchase() {

        $push = [
            "pageTitle" => "Laporan Pembelian",
            "dataAdmin" => $this->dataAdmin 
        ];

		$this->load->view('header',$push);
		$this->load->view('purchase_report',$push);
		$this->load->view('footer',$push);

    }

	public function sales()
	{
        $push = [
            "pageTitle" => "Laporan Penjualan",
            "dataAdmin" => $this->dataAdmin 
        ];

		$this->load->view('header',$push);
		$this->load->view('sales_report',$push);
		$this->load->view('footer',$push);
    }

	public function service()
	{
        $push = [
            "pageTitle" => "Laporan Service",
            "dataAdmin" => $this->dataAdmin 
        ];

		$this->load->view('header',$push);
		$this->load->view('service_report',$push);
		$this->load->view('footer',$push);
    }
    public function spk()
	{
        $push = [
            "pageTitle" => "Laporan Penjualan",
            "dataAdmin" => $this->dataAdmin 
        ];

		$this->load->view('header',$push);
		$this->load->view('spk_report',$push);
		$this->load->view('footer',$push);
    }

    public function hpp()
	{
        $push = [
            "pageTitle" => "Laporan HPP",
            "dataAdmin" => $this->dataAdmin 
        ];

		$this->load->view('header',$push);
		$this->load->view('hpp_report',$push);
		$this->load->view('footer',$push);
    }
    public function kas()
	{
        $push = [
            "pageTitle" => "Laporan Kas",
            "dataAdmin" => $this->dataAdmin 
        ];

		$this->load->view('header',$push);
		$this->load->view('kas_report',$push);
		$this->load->view('footer',$push);
    }

    public function jurnal()
	{
        $push = [
            "pageTitle" => "Buku Besar",
            "dataAdmin" => $this->dataAdmin,
            "coa" => $this->Coa_model->get()->result() 
        ];

		$this->load->view('header',$push);
		$this->load->view('jurnal',$push);
		$this->load->view('footer',$push);
    }

    public function neraca()
	{
        $push = [
            "pageTitle" => "Neraca Saldo",
            "dataAdmin" => $this->dataAdmin,
            "coa" => $this->Coa_model->get()->result()
        ];

		$this->load->view('header',$push);
		$this->load->view('neraca',$push);
		$this->load->view('footer',$push);
    }
    function json($type = "sparepart",$start = 0,$end = 0) {

        $this->Datatables->setSelect("details.*,products.type,DATE(transactions.date) as date,SUM(details.qty * details.price) as total,SUM(qty) as items,transactions.customer,transactions.plat");
        $this->Datatables->setTable("details");
        $this->Datatables->setJoin("products","`products`.`id` = `details`.`product_id`","left");
        $this->Datatables->setJoin("transactions","`transactions`.`id` = `details`.`transaction_id`","left");
        $this->Datatables->setWhere("products.type",$type);

        if($start AND $end) {
            $this->Datatables->setWhere("DATE(date) >=",$start);
            $this->Datatables->setWhere("DATE(date) <=",$end);
        } else {
            $this->Datatables->setWhere("MONTH(date)",date("m"));
            $this->Datatables->setWhere("YEAR(date)",date("Y"));
        }

        $this->Datatables->setGroup("transaction_id");

        if($type=="sparepart") {
            $this->Datatables->setColumn([
                '<index>',
                '<get-date>',
                '<get-items>',
                '[rupiah=<get-total>]'
            ]);
            $this->Datatables->setOrdering(["transaction_id","date","items","total"]);
        } else {
            $this->Datatables->setColumn([
                '<index>',
                '<get-customer>',
                '<get-plat>',
                '<get-date>',
                '[rupiah=<get-total>]'
            ]);
            $this->Datatables->setOrdering(["transaction_id","customer","plat","date","total"]);
        }

        $this->Datatables->setSearchField(["date"]);
        $this->Datatables->generate();
    }

    function purchase_json($start = 0,$end = 0) {
        $this->Datatables->setSelect("purchase_details.*,transaksi.date,btb.nbtb,btb.nop,transaksi_details.ntrn,products.name as prod,suppliers.name as supp,(purchase_details.qty * purchase_details.price) as subtotal, ((purchase_details.qty * purchase_details.price) * (purchase.ppn / 100)) as ppn, ((purchase_details.qty * purchase_details.price) + ((purchase_details.qty * purchase_details.price) * (purchase.ppn / 100))) as grntotal");
        $this->Datatables->setTable("purchase_details");
        $this->Datatables->setJoin("purchase","purchase.id=purchase_details.purchase_id","left");
        $this->Datatables->setJoin("btb","btb.nop=purchase.nop","left");
        $this->Datatables->setJoin("suppliers","suppliers.id=purchase.supplier_id","left");
        $this->Datatables->setJoin("products","products.id=purchase_details.product_id","left");
        $this->Datatables->setJoin("transaksi_details","transaksi_details.btb_id=btb.id","left");
        $this->Datatables->setJoin("transaksi","transaksi.id=transaksi_details.purchase_id","left");
        $this->Datatables->setWhere("purchase.status !=","BATAL");

        if($start AND $end) {
            $this->Datatables->setWhere("DATE(transaksi.date) >=",$start);
            $this->Datatables->setWhere("DATE(transaksi.date) <=",$end);
        } else {
            $this->Datatables->setWhere("MONTH(transaksi.date)",date("m"));
            $this->Datatables->setWhere("YEAR(transaksi.date)",date("Y"));
        }


       $this->Datatables->setColumn([
            "<index>",
            "<get-nop>",
            "<get-nbtb>",
            "<get-ntrn>",
            "<get-supp>",
            "<get-prod>",
            "[reformat_date=<get-date>]",
            "<get-qty>",
            "[rupiah=<get-price>]",
            "[rupiah=<get-ppn>]",
            "[rupiah=<get-grntotal>]"
             // Corrected line
        ]);
        
        $this->Datatables->setOrdering(["id","nop","nbtb","ntrn","supp","prod","date","qty","price","ppn","grntotal"]);
        $this->Datatables->setSearchField(["transaksi.date","transaksi_details.ntrn","btb.nop","btb.nbtb"]);
        $this->Datatables->generate();
    }

    
    function spk_json($start = 0,$end = 0) {
       $this->Datatables->setSelect("transactions.*,(transactions.total - transactions.sisa) as bayar,wo.noinvoice");
       $this->Datatables->setTable("transactions");
       $this->Datatables->setJoin("wo","wo.spkid=transactions.spkid","left");
       $this->Datatables->setWhere("transactions.status !=","BATAL");
        //$this->Datatables->setJoin("suppliers","suppliers.id=purchase.supplier_id","left");

        if($start AND $end) {
            $this->Datatables->setWhere("DATE(date) >=",$start);
            $this->Datatables->setWhere("DATE(date) <=",$end);
        } else {
            $this->Datatables->setWhere("MONTH(date)",date("m"));
            $this->Datatables->setWhere("YEAR(date)",date("Y"));
        }
        
        //$this->Datatables->setGroup("purchase_id");

        $this->Datatables->setColumn([
            "[reformat_date=<get-date>]",
            "<get-spkid>",
            "<get-noinvoice>",
            "<get-customer>",
            "<get-noka>",
            "[rupiah=<get-total>]",
            "[rupiah=<get-bayar>]",
            "[rupiah=<get-sisa>]"
        ]);
       
        $this->Datatables->setOrdering(["id","date","spkid","noinvoice","customer","noka","total","sisa"]);
        $this->Datatables->setSearchField(["date"]);
        $this->Datatables->generate();
    }


    function kas_json($start = 0, $end = 0) {
        
        if ($start == 0 && $end == 0) {
            $start = date('Y-m-01'); // First day of the current month
            $end = date('Y-m-t'); // Last day of the current month
        } elseif ($start != 0 && $end != 0) {
            $start = date('Y-m-d', strtotime($start));
            $end = date('Y-m-d', strtotime($end));
        }
        
        $this->Datatables->setSelect("ju1.kdtransaksi, ju1.date,COALESCE(bkk_log.bkkno,bkm_log.bkmno) AS buku,
         COALESCE(bkklain.uraian, bkmlain.uraian, bkk_details.uraian, bkm_details.uraian, memo.uraian) AS uraian, 
         ju1.coa,
         CASE 
         WHEN ju1.kdtransaksi LIKE 'npc%'  THEN ju1.kredit
         ELSE ju1.debet 
      END AS debet,
      CASE 
         WHEN ju1.kdtransaksi LIKE 'npc%'  THEN ju1.debet
         ELSE ju1.kredit 
      END AS kredit,
      COALESCE(bkklain.coad, bkmlain.coad, bkk_details.coad, bkm_details.coad,memo.coad) AS coalawan,
         (SELECT SUM(
            CASE 
                WHEN sub_ju.kdtransaksi LIKE 'npc%'  THEN sub_ju.kredit
                ELSE sub_ju.debet 
            END 
            - 
            CASE 
                WHEN sub_ju.kdtransaksi LIKE 'npc%'  THEN sub_ju.debet 
                ELSE sub_ju.kredit 
            END
        ) FROM jurnalumum sub_ju 
        WHERE 
            sub_ju.date <= ju1.date
            AND (sub_ju.coa = '1-1-101') -- Add additional conditions as per your filtering logic
            AND sub_ju.date BETWEEN '$start' AND '$end') AS saldo
          ");

        $this->Datatables->setTable("jurnalumum ju1");
        $this->Datatables->setJoin("bkklain", "bkklain.nbl=ju1.kdtransaksi", "left");
        $this->Datatables->setJoin("bkmlain", "bkmlain.npl=ju1.kdtransaksi", "left");
        $this->Datatables->setJoin("bkk", "bkk.nph = ju1.kdtransaksi", "left");
        $this->Datatables->setJoin("bkk_details", "bkk_details.nph = bkk.nph", "left");
        $this->Datatables->setJoin("bkm", "bkm.npc = ju1.kdtransaksi", "left");
        $this->Datatables->setJoin("bkm_details", "bkm_details.npc = bkm.npc", "left");
        $this->Datatables->setJoin("memo", "memo.nmm=ju1.kdtransaksi", "left");
        $this->Datatables->setJoin("bkk_log", "bkk_log.referensibkk=ju1.kdtransaksi", "left");
        $this->Datatables->setJoin("bkm_log", "bkm_log.referensibkm=ju1.kdtransaksi", "left");
    
        // Apply filters
        $this->Datatables->setWhere("ju1.coa", "1-1-101");
        if ($start && $end) {
            $this->Datatables->setWhere("DATE(ju1.date) >=", $start);
            $this->Datatables->setWhere("DATE(ju1.date) <=", $end);
        } else {
            $this->Datatables->setWhere("MONTH(ju1.date)", date("m"));
            $this->Datatables->setWhere("YEAR(ju1.date)", date("Y"));
        }
    
        // Set the column definitions
        $this->Datatables->setColumn([
            "[reformat_date=<get-date>]",
            "<get-buku>",
            "<get-kdtransaksi>",
            "<get-uraian>",
            "<get-coalawan>",
            "[rupiah=<get-debet>]",
            "[rupiah=<get-kredit>]",
            "[rupiah=<get-saldo>]"
            
        ]);
    
        // Adjust the ordering and search fields
        $this->Datatables->setOrdering(["ju1.date", "kdtransaksi", "uraian", "coa", "debet", "kredit", "saldo"]);
        $this->Datatables->setSearchField(["ju1.date", "coa", "kdtransaksi"]);
    
        // Generate the DataTables output
        $this->Datatables->generate();
    }
    
    function hpp_json($filter="4-0-301",$start = 0, $end = 0) {
        $this->Datatables->setSelect("details.*, transactions.spkid, transactions.date, transactions.customer, transactions.nama_qq, transactions.status,(details.qty * details.price) AS total, converthpp.hppecer,products.coahpp");
        $this->Datatables->setTable("details");
        $this->Datatables->setJoin("transactions", "transactions.id = details.transaction_id", "left");
        $this->Datatables->setJoin("converthpp", "converthpp.kditem = details.kditem", "left");
        $this->Datatables->setJoin("products", "products.kditem = details.kditem", "left");
        $this->Datatables->setWhere("transactions.status !=", "BATAL");
        $this->Datatables->setWhere("products.coahpp",$filter);
    
        if ($start AND $end) {
            $this->Datatables->setWhere("DATE(date) >=", $start);
            $this->Datatables->setWhere("DATE(date) <=", $end);
        } else {
            $this->Datatables->setWhere("MONTH(date)", date("m"));
            $this->Datatables->setWhere("YEAR(date)", date("Y"));
        }
    
        $this->Datatables->setColumn([
            "[reformat_date=<get-date>]",
            "<get-spkid>",
            "<get-customer>",
            "<get-qty>",
            "[rupiah=<get-hppecer>]",
            "[rupiah=<get-price>]",
            "[rupiah=<get-total>]"
        ]);
    
        $this->Datatables->setOrdering(["date", "spkid", "customer", "qty", "price", "total"]);
        $this->Datatables->setSearchField(["date", "spkid", "customer"]);
        $this->Datatables->generate();
    }
    
    


    function purchase_pdf($start = 0,$end = 0) {
        $query = $this->report_model->get_purchase($start,$end);

        if($query->num_rows() > 0) {
            $fetch = $query->result();

            $push = [
                "fetch" => $fetch
            ];

            if($start AND $end) {
                $push["subtitle"] = date("l, d F Y",strtotime($start." 00:00:00"))." - ".date("l, d F Y",strtotime($end." 00:00:00"));
            } else {
                $push["subtitle"] = date("F Y");
            }

            $title = "Laporan_pembelian";

            $this->load->library("pdf");

            $this->pdf->setPaper('legal', 'landscape');
            $this->pdf->filename = $title;
            $this->pdf->load_view('purchase_report_pdf',$push);
        }
    }

    function kas_pdf($start = 0,$end = 0) {
        $query = $this->report_model->get_kas($start,$end);

        if($query->num_rows() > 0) {
            $fetch = $query->result();

            $push = [
                "fetch" => $fetch
            ];

            if($start AND $end) {
                $push["subtitle"] = date("l, d F Y",strtotime($start." 00:00:00"))." - ".date("l, d F Y",strtotime($end." 00:00:00"));
            } else {
                $push["subtitle"] = date("F Y");
            }

            $title = "Laporan KAS";

            $this->load->library("pdf");

            $this->pdf->setPaper('A4', 'portrait');
            $this->pdf->filename = $title;
            $this->pdf->load_view('kas_report_pdf',$push);
        }
    }

    function jurnal_pdf($start = 0,$end = 0) {
        $query = $this->report_model->get_jurnal_all_coa($start,$end);

        if($query->num_rows() > 0) {
            $fetch = $query->result();

            $push = [
                "fetch" => $fetch
            ];

            if($start AND $end) {
                $push["subtitle"] = date("l, d F Y",strtotime($start." 00:00:00"))." - ".date("l, d F Y",strtotime($end." 00:00:00"));
            } else {
                $push["subtitle"] = date("F Y");
            }

            $title = "Laporan Jurnal";

            $this->load->library("pdf");
            
            $this->pdf->setPaper('A4', 'portrait');
            $this->pdf->filename = $title;
            $this->pdf->load_view('jurnal_pdf',$push);
        }
    }
    function hpp_jasa_pdf($start = 0,$end = 0) {
        $query = $this->report_model->get_hpp_jasa($start,$end);

        if($query->num_rows() > 0) {
            $fetch = $query->result();

            $push = [
                "fetch" => $fetch
            ];

            if($start AND $end) {
                $push["subtitle"] = date("l, d F Y",strtotime($start." 00:00:00"))." - ".date("l, d F Y",strtotime($end." 00:00:00"));
            } else {
                $push["subtitle"] = date("F Y");
            }

            $title = "Laporan HPP Jasa";

            $this->load->library("pdf");

            $this->pdf->setPaper('legal', 'landscape');
            $this->pdf->filename = $title;
            $this->pdf->load_view('hpp_report_pdf',$push);
        }
    }

    function hpp_variasi_pdf($start = 0,$end = 0) {
        $query = $this->report_model->get_hpp_variasi($start,$end);

        if($query->num_rows() > 0) {
            $fetch = $query->result();

            $push = [
                "fetch" => $fetch
            ];

            if($start AND $end) {
                $push["subtitle"] = date("l, d F Y",strtotime($start." 00:00:00"))." - ".date("l, d F Y",strtotime($end." 00:00:00"));
            } else {
                $push["subtitle"] = date("F Y");
            }

            $title = "Laporan HPP Variasi";

            $this->load->library("pdf");

            $this->pdf->setPaper('legal', 'landscape');
            $this->pdf->filename = $title;
            $this->pdf->load_view('hpp_report_pdf',$push);
        }
    }

    function hpp_ak_pdf($start = 0,$end = 0) {
        $query = $this->report_model->get_hpp_antikarat($start,$end);

        if($query->num_rows() > 0) {
            $fetch = $query->result();

            $push = [
                "fetch" => $fetch
            ];

            if($start AND $end) {
                $push["subtitle"] = date("l, d F Y",strtotime($start." 00:00:00"))." - ".date("l, d F Y",strtotime($end." 00:00:00"));
            } else {
                $push["subtitle"] = date("F Y");
            }

            $title = "Laporan HPP Antikarat";

            $this->load->library("pdf");

            $this->pdf->setPaper('legal', 'landscape');
            $this->pdf->filename = $title;
            $this->pdf->load_view('hpp_report_pdf',$push);
        }
    }
    function spk_pdf($start = 0,$end = 0) {
        $query = $this->report_model->get_spk($start,$end);

        if($query->num_rows() > 0) {
            $fetch = $query->result();

            $push = [
                "fetch" => $fetch
            ];

            if($start AND $end) {
                $push["subtitle"] = date("l, d F Y",strtotime($start." 00:00:00"))." - ".date("l, d F Y",strtotime($end." 00:00:00"));
            } else {
                $push["subtitle"] = date("F Y");
            }

            $title = "Laporan Spk";

            $this->load->library("pdf");

            $this->pdf->setPaper('legal', 'landscape');
            $this->pdf->filename = $title;
            $this->pdf->load_view('spk_report_pdf',$push);
        }
    }

    function report_pdf($type="sparepart",$start = 0,$end = 0) {
        $query = $this->report_model->get($type,$start,$end);

        if($query->num_rows() > 0) {
            $fetch = $query->result();

            $push = [
                "fetch" => $fetch,
                "type" => $type
            ];

            if($start AND $end) {
                $push["subtitle"] = date("l, d F Y",strtotime($start." 00:00:00"))." - ".date("l, d F Y",strtotime($end." 00:00:00"));
            } else {
                $push["subtitle"] = date("F Y");
            }

            $title = "Laporan_penjualan_".$type;

            $this->load->library("pdf");

            $this->pdf->setPaper('A4', 'portrait');
            $this->pdf->filename = $title;
            $this->pdf->load_view('report_pdf',$push);
        }
    }
}
