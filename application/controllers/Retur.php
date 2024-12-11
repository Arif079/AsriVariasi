<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class retur extends CI_Controller {
    private $dataAdmin;

    function __construct() {
        parent::__construct();

        if(!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");
        $this->load->model("retur_model");
        $this->load->model("Btb_model");

        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }


	public function index()
	{

        $push = [
            "pageTitle" => "Retur barang",
            "dataAdmin" => $this->dataAdmin,
        ];

		$this->load->view('header',$push);
		$this->load->view('retur',$push);
		$this->load->view('footer',$push);
    }

    public function detail($id = 0) {
        $query = $this->retur_model->get($id);
        if($query->num_rows() > 0) {
            $response = $query->row_array();
            $response["items"] = $this->retur_model->get_details($id)->result_array();
            echo json_encode($response);
        }
    }

	public function new()
	{

        $push = [
            "pageTitle" => "Bukti Retur",
            "dataAdmin" => $this->dataAdmin,
            "btb" => $this->Btb_model->get_nop()->result()
        ];

		$this->load->view('header',$push);
		$this->load->view('retur_compose',$push);
		$this->load->view('footer',$push);
    }

    public function json() {
        $this->load->model("datatables");
        $this->datatables->setSelect("retur.*,suppliers.name");
        $this->datatables->setTable("retur");
        $this->datatables->setJoin("suppliers","suppliers.id = retur.supplier_id","left");
        $this->datatables->setColumn([
            '<index>',
            '<get-nretur>',
            '<get-btb>',
            '[reformat_date=<get-date>]',
            '<get-name>',
            '[rupiah=<get-total>]',
            '<div class="text-center">
                <button type="button" class="btn btn-sm btn-warning btn-view" data-id="<get-id>"><i class="fa fa-eye"></i></button>
                <a href="[base_url=retur/print/<get-id>]" class="btn btn-primary btn-sm"><i class="fa fa-print"></i></a>
            </div>'
        ]);
        $this->datatables->setOrdering(["id","nretur","btb","date","name","total",NULL]);
        $this->datatables->setSearchField(["name","nretur","btb"]);
        $this->datatables->generate();
    }
    
    public function json_product() {
        $this->load->model("datatables");
        $selectedNop = $this->input->get("nop");

        // Set the NOP filter if it's provided
        if ($selectedNop !== null) {
           $this->datatables->setWhere("nbtb", $selectedNop);
        }
        log_message('debug', 'selectedNop: ' . json_encode($selectedNop));
        $this->datatables->setSelect("products.*,btb_details.nbtb,btb_details.qty,btb_details.price");
        $this->datatables->setTable("products");
        $this->datatables->setJoin("btb_details","btb_details.product_id = products.id","left");
        $this->datatables->setColumn([
            '<get-kditem>',
            '<get-name>',
            '<div class="text-center"><button type="button" class="btn btn-warning btn-sm btn-choose" data-id="<get-id>" data-name="<get-name>" data-stock="<get-stock>" data-price="<get-price>" data-qty="<get-qty>"><i class="fa fa-check"></i></button></div>'
        ]);
        $this->datatables->setOrdering(["kditem","name",NULL]);
        $this->datatables->setSearchField(["name","kditem"]);
        $this->datatables->generate();
    }
    
    public function create() {
        $data = json_decode($this->input->raw_input_stream,TRUE);
        $nretur = $this->retur_model->CreateCode();
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
                "nretur" => $nretur,
                "btb" => $data["nop"],
                "date" => date("Y-m-d H:i:s"),
                "total" => $data["total"],
                "supplier_id" => $data["supplier_id"],
                "status" => "BTB"
                

            ];

            
            $purchase_id = $this->retur_model->post($insertData);
            $generated_retur = $this->retur_model->get_retur_by_id($purchase_id);
            
            $items_batch = [];
            $stock_batch = [];


            foreach($data["details"] as $detail) {
                $temp = array();
                $temp["id"] = NULL;
                $temp["nretur"] = $generated_retur;
                $temp["purchase_id"] = $purchase_id;
                $temp["product_id"] = $detail["product_id"];
                $temp["price"] = $detail["price"];
                $temp["qty"] = $detail["qty"];

                $tempStock = array();
                $tempStock["id"] = $detail["product_id"];
                $tempStock["stock"] = $detail["product_stock"] - $detail["qty"];
                
                
                $w1 = $data["npo"];
                $w2 = $detail["product_id"];
                $currentmax = $this->retur_model->get_maxstock($w1,$w2);
                $newqty = $currentmax + $detail["qty"];
                $update  = [
                    "max" => $newqty
                ];

                $this->Btb_model->update_maxstock($w1,$w2,$update);


                $items_batch[] = $temp;
                $stock_batch[] = $tempStock;
            }

            $this->retur_model->post_details($items_batch);
            $this->retur_model->update_stock($stock_batch);
        }

        echo json_encode($response);
    }

    function print($id = 0) {
        $query = $this->retur_model->get($id);

        if($query->num_rows() > 0) {
            $fetch = $query->row();

            $push = [
                "fetch" => $fetch,
                "details" => $this->retur_model->get_details($id)->result()
            ];

            $title = "Invoice";

            $this->load->library("pdf");

            $this->pdf->setPaper('A4', 'portrait');
            $this->pdf->filename = $title;
            $this->pdf->load_view('retur_pdf',$push);
        }
    }
}
