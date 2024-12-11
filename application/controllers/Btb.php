<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class btb extends CI_Controller {
    private $dataAdmin;

    function __construct() {
        parent::__construct();

        if(!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");
        $this->load->model("Btb_model");
        $this->load->model("purchase_model");
        $this->load->model("hpp_model");
        $this->load->model("jurnal_model");

        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }


	public function index()
	{

        $push = [
            "pageTitle" => "Bukti Terima Barang",
            "dataAdmin" => $this->dataAdmin
        ];

		$this->load->view('header',$push);
		$this->load->view('btb',$push);
		$this->load->view('footer',$push);
    }

    public function detail($id = 0) {
        $query = $this->Btb_model->get($id);
        if($query->num_rows() > 0) {
            $response = $query->row_array();
            $response["items"] = $this->Btb_model->get_details($id)->result_array();
            echo json_encode($response);
        }
    }

	public function new()
	{

        $push = [
            "pageTitle" => "Bukti Terima Barang",
            "dataAdmin" => $this->dataAdmin,
            "purchase" => $this->purchase_model->get_nop()->result()
        ];

		$this->load->view('header',$push);
		$this->load->view('btb_compose',$push);
		$this->load->view('footer',$push);
    }

    public function json() {
        $this->load->model("datatables");
        $this->datatables->setSelect("btb.*,suppliers.name");
        $this->datatables->setTable("btb");
        $this->datatables->setJoin("suppliers","suppliers.id = btb.supplier_id","left");
        $this->datatables->setColumn([
            '<index>',
            '<get-nbtb>',
            '<get-nop>',
            '[reformat_date=<get-date>]',
            '<get-name>',
            '[rupiah=<get-total>]',
            '<div class="text-center">
                <button type="button" class="btn btn-sm btn-warning btn-view" data-id="<get-id>"><i class="fa fa-eye"></i></button>
                <a href="[base_url=Btb/print/<get-id>]" class="btn btn-primary btn-sm" target=_blank><i class="fa fa-print"></i></a>
            </div>'
        ]);
        $this->datatables->setOrdering(["id","nbtb","nop","date","name","total",NULL]);
        $this->datatables->setwhere("status","BTB");
        $this->datatables->setSearchField(["name","nbtb","nop"]);
        $this->datatables->generate();
    }
    
    public function json_product() {
        $this->load->model("datatables");
        $selectedNop = $this->input->get("nop");

        // Set the NOP filter if it's provided
        if ($selectedNop !== null) {
           $this->datatables->setWhere("nop", $selectedNop);
        }
        $this->datatables->setSelect("products.*,purchase_details.nop,purchase_details.max,purchase_details.price");
        $this->datatables->setTable("products");
        $this->datatables->setJoin("purchase_details","purchase_details.product_id = products.id","left");
        $this->datatables->setColumn([
            '<get-kditem>',
            '<get-name>',
            '<div class="text-center"><button type="button" class="btn btn-warning btn-sm btn-choose" data-id="<get-id>" data-kditem="<get-kditem>" data-name="<get-name>" data-stock="<get-stock>" data-price="<get-price>" data-max="<get-max>" data-kecil="<get-qtykecil>" data-satuan="<get-satuankecil>"><i class="fa fa-check"></i></button></div>'
        ]);
        $this->datatables->setOrdering(["kditem","name","qtykecil",NULL]);
        $this->datatables->setSearchField(["name","kditem"]);
        $this->datatables->generate();
    }
    
    public function create() {
        $data = json_decode($this->input->raw_input_stream,TRUE);
        $nbtb = $this->Btb_model->CreateCode();
        //OR !$data['total'] *ini ditambahkan di belakang supplier id supaya total tidak boleh 0
        if(!$data['supplier_id']) {
            $response = [
                "status" => FALSE,
                "msg" => "Harap periksa kembali data anda"
            ];
        } else {
            $response = [
                "status" => TRUE,
                "msg" => "Data pembelian telah ditambahkan"
            ];
            
            $insertData = [
                "id" => NULL,
                "nbtb" => $nbtb,
                "nop" => $data["nop"],
                "date" => date("Y-m-d H:i:s"),
                "total" => $data["total"],
                "supplier_id" => $data["supplier_id"],
                "surat" => $data["surat"],
                "status" => "BTB"
                

            ];

            $purchase_id = $this->Btb_model->post($insertData);
            $generated_btb = $this->Btb_model->get_btb_by_id($purchase_id);
            $nopValue = $data["nop"];
            $result = $this->Btb_model->getppn($nopValue);
            $ppnValue = $result;
            $items_batch = [];
            $stock_batch = [];
            $jumlah = 0;

            foreach($data["details"] as $detail) {
                $temp = array();
                $temp["id"] = NULL;
                $temp["nbtb"] = $generated_btb;
                $temp["purchase_id"] = $purchase_id;
                $temp["product_id"] = $detail["product_id"];
                $temp["price"] = $detail["price"];
                $temp["qty"] = $detail["qty"];
                $total = $detail["price"] * $detail["qty"];
                $qtyecer =  $detail["qty"]*$detail["kecil"];
                $dppecer = $total/$qtyecer;
                
                if ($ppnValue == 0) {
                    $ppn = 0;
                    $ppnecer = 0;
                } else {
                    // Calculate ppn when it's not 0
                   $ppn = $total * ($ppnValue / 100);
                   $ppnecer = $dppecer * ($ppnValue/100);
                };

                $jumlah += $total + $ppn;
                $hpp = $total/$detail["qty"];
                $hppecer = $dppecer; 
                $stock = $detail["qty"];
                $stockecer = $detail["qty"]*$detail["kecil"];

                $tempStock = array();
                $tempStock["id"] = $detail["product_id"];
                $tempStock["stock"] = $detail["product_stock"] + ($detail["qty"]*$detail["kecil"]);

                $w1 = $data["nop"];
                $w2 = $detail["product_id"];
                $newqty = $detail["product_max"] - $detail["qty"];
                $update  = [
                    "max" => $newqty
                ];
                
                $hppdata  = [
                    'id' => NULL,
                    'kdhpp' => $this->hpp_model->CreateCode(),
                    'tgltransaksi' => date("Y-m-d H:i:s"),
                    'tipe' => "Pembelian",
                    'kdtransaksi'=> $data["nop"],
                    'kdreferensi'=> $generated_btb,
                    'kditem' => $detail["product_name"],
                    'kdsatuan'=> $detail["satuan"],
                    'ppn' => $ppn,
                    'ppnecer' => $ppnecer,
                    'dpp' => $total,
                    'dppecer'=> $dppecer,
                    'grandtotal'=> $total,
                    'grandtotalecer'=>$dppecer,
                    'hpp'=> $hpp,
                    'hppecer'=> $hppecer,
                    'stok'=> $stock,
                    'stokecer' => $stockecer
                ];
            

                $this->Btb_model->update_maxstock($w1,$w2,$update);
                $this->hpp_model->post($hppdata);
                $items_batch[] = $temp;
                $stock_batch[] = $tempStock;
                }

                $debet = [
                    "id" => NULL,
                    "kdjurnal" => $this->jurnal_model->CreateCode(),
                    "date" => date("Y-m-d H:i:s"),
                    "kdtransaksi" => $generated_btb,
                    "kdbukti" => $generated_btb,
                    "tipe" => "Persediaan",
                    "coa" => "1-1-301",
                    "debet" => $jumlah,
                    "kredit"=> 0,
                    "status"=> "Terproses"
                ];
                
                $kredit = [
                    "id" => NULL,
                    "kdjurnal" => $this->jurnal_model->CreateCode(),
                    "date" => date("Y-m-d H:i:s"),
                    "kdtransaksi" => $generated_btb,
                    "kdbukti" => $generated_btb,
                    "tipe" => "Hutang",
                    "coa" => "2-1-101",
                    "debet" => 0,
                    "kredit"=> $jumlah,
                    "status"=> "Terproses"

                ];
                $this->jurnal_model->post($debet);
                $this->jurnal_model->post($kredit);
            $this->Btb_model->post_details($items_batch);
            $this->Btb_model->update_stock($stock_batch);
                
           
        }

        echo json_encode($response);
    }

    function print($id = 0) {
        $query = $this->Btb_model->get($id);

        if($query->num_rows() > 0) {
            $fetch = $query->row();

            $push = [
                "fetch" => $fetch,
                "details" => $this->Btb_model->get_details($id)->result()
            ];

            $title = "Bukti Terima Barang";

            $this->load->library("pdf");

            $this->pdf->setPaper('A4', 'portrait');
            $this->pdf->filename = $title;
            $this->pdf->load_view('btb_pdf',$push);
        }
    }
}
