<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {
    private $dataAdmin;

    function __construct() {
        parent::__construct();

        if(!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");
        $this->load->model("transaksi_model");
        $this->load->model("purchase_model");

        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }


	public function index()
	{

        $push = [
            "pageTitle" => "Invoice",
            "dataAdmin" => $this->dataAdmin
        ];

		$this->load->view('header',$push);
		$this->load->view('transaksi',$push);
		$this->load->view('footer',$push);
    }

    public function detail($id = 0) {
        $query = $this->transaksi_model->get($id);
        if($query->num_rows() > 0) {
            $response = $query->row_array();
            $response["items"] = $this->transaksi_model->get_details($id)->result_array();
            echo json_encode($response);
        }
    }

	public function new()
	{

        $push = [
            "pageTitle" => "Tambah Invoice",
            "dataAdmin" => $this->dataAdmin,
            "purchase" => $this->purchase_model->get_nop()->result(),
        ];

		$this->load->view('header',$push);
		$this->load->view('transaksi_compose',$push);
		$this->load->view('footer',$push);
    }

    public function json() {
        $this->load->model("datatables");
        $this->datatables->setSelect("transaksi.*,suppliers.name");
        $this->datatables->setTable("transaksi");
        $this->datatables->setJoin("suppliers","suppliers.id = transaksi.supplier_id","left");
        $this->datatables->setColumn([
            '<index>',
            '<get-ntrn>',
            '[reformat_date=<get-date>]',
            '<get-name>',
            '[rupiah=<get-total>]',
            '<div class="text-center">
                <button type="button" class="btn btn-sm btn-warning btn-view" data-id="<get-id>"><i class="fa fa-eye"></i></button>
                <a href="[base_url=transaksi/print/<get-id>]" class="btn btn-primary btn-sm" target=_blank><i class="fa fa-print"></i></a>
            </div>'
        ]);
        $this->datatables->setOrdering(["id","ntrn","date","name","total",NULL]);
        $this->datatables->setSearchField(["name","ntrn"]);
        $this->datatables->generate();
    }
    
    public function json_product() {
        $this->load->model("datatables");
        $selectedNop = $this->input->get("nop");

        // Set the NOP filter if it's provided
        if ($selectedNop !== null) {
           $this->datatables->setWhere("nop", $selectedNop);
        }
        $this->datatables->setTable("btb");
        $this->datatables->setColumn([
            '<get-nbtb>',
            '<get-total>',
            '<div class="text-center"><button type="button" class="btn btn-warning btn-sm btn-choose" data-id="<get-id>" data-nbtb="<get-nbtb>" data-price="<get-total>"><i class="fa fa-check"></i></button></div>'
        ]);
        $this->datatables->setOrdering(["nbtb","total",NULL]);
        $this->datatables->setWhere("status","BTB");
        $this->datatables->setSearchField(["nbtb"]);
        $this->datatables->generate();
    }
    
    public function create() {
        $data = json_decode($this->input->raw_input_stream,TRUE);
        $ntrn = $this->transaksi_model->CreateCode();
        //OR !$data['total'] *ini ditambahkan di belakang supplier id supaya total tidak boleh 0
        if(!$data['supplier_id']) {
            $response = [
                "status" => FALSE,
                "msg" => "Harap periksa kembali data anda"
            ];
        } else {
            $response = [
                "status" => TRUE,
                "msg" => "Data invoice telah dibuat"
            ];

            $insertData = [
                "id" => NULL,
                "ntrn" => $ntrn,
                "date" => date("Y-m-d H:i:s"),
                "total" => $data["total"],
                "supplier_id" => $data["supplier_id"],
                "sisa" => $data["total"],
                "inv" => $data["invoice"]
            ];
           
            $purchase_id = $this->transaksi_model->post($insertData);
            $generated_ntrn = $this->transaksi_model->get_ntrn_by_id($purchase_id);

            $items_batch = [];
            $stock_batch = [];

            foreach($data["details"] as $detail) {
                $temp = array();
                $temp["id"] = NULL;
                $temp["ntrn"] = $generated_ntrn;
                $temp["purchase_id"] = $purchase_id;
                $temp["btb_id"] = $detail["btb_id"];
                $temp["price"] = $detail["price"];
                



                $tempStatus = array();
                $tempStatus["id"] = $detail["btb_id"];
                $tempStatus["status"] = "INV";

                $items_batch[] = $temp;
                $status_batch[] = $tempStatus;
            }
            $this->transaksi_model->update_status($data["nop"],"INV");     
            $this->transaksi_model->post_details($items_batch);
            $this->transaksi_model->update_stock($status_batch);
        }

        echo json_encode($response);
    }

    function print($id = 0) {
        $query = $this->transaksi_model->get($id);

        if($query->num_rows() > 0) {
            $push ["fetch"] = $query->row();
            $details = $this->transaksi_model->get_details($id)->row(); // Fetch a single row
            $get_btb= $this->transaksi_model->get_btb($id)->result();
            if ($details) {
                $btbid = $details->btb_id;
                $push["details"] = $get_btb; // Push the 'Details' object into the array
                $order = $this->transaksi_model->get_purchase_details($btbid)->row();
                $push["order"] = $order;
                $nopid = $order->purchase_id; // Ensure $order is defined elsewhere
                $push["order_detail"] = $this->transaksi_model->get_item($nopid)->result();
                $faktur = $this->transaksi_model->CreateFaktur();
                $push["faktur"] = $faktur;
            } else {
                // Handle the case where no details were found for the given $id
            }
        }

            $title = "Invoice";

            $this->load->library("pdf");

            $this->pdf->setPaper('A4', 'portrait');
            $this->pdf->filename = $title;
            $this->pdf->load_view('transaksi_pdf',$push);
        }
    }
