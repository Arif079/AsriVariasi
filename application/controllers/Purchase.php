<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends CI_Controller {
    private $dataAdmin;

    function __construct() {
        parent::__construct();

        if(!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");
        $this->load->model("supplier_model");
        $this->load->model("Purchase_model");
        $this->load->model("Ppn_model");


        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }


	public function index()
	{

        $push = [
            "pageTitle" => "Purchase Order",
            "dataAdmin" => $this->dataAdmin
        ];

		$this->load->view('header',$push);
		$this->load->view('purchase',$push);
		$this->load->view('footer',$push);
    }

    public function detail($id = 0) {
        $query = $this->Purchase_model->get($id);
        if($query->num_rows() > 0) {
            $response = $query->row_array();
            $response["items"] = $this->Purchase_model->get_details($id)->result_array();
            echo json_encode($response);
        }
    }

	public function new()
	{

        $push = [
            "pageTitle" => "Tambah PO",
            "dataAdmin" => $this->dataAdmin,
            "suppliers" => $this->supplier_model->get()->result(),
            "ppn" => $this->Ppn_model->get()->result()
        ];

		$this->load->view('header',$push);
		$this->load->view('purchase_compose',$push);
		$this->load->view('footer',$push);
    }

    public function edit($id = 0)
	{
        $query = $this->Purchase_model->get($id);

        if($query->num_rows() > 0) {
            $fetch = $query->row();
        $push = [
            "pageTitle" => "Edit PO",
            "dataAdmin" => $this->dataAdmin,
            "suppliers" => $this->supplier_model->get()->result(),
            "fetch" => $fetch,
            "details" => $this->Purchase_model->get_details($id)->result(),
            "ppn" => $this->Ppn_model->get()->result(),
            "purchase_id" => $id
            
        ];

		$this->load->view('header',$push);
		$this->load->view('purchase_edit',$push);
		$this->load->view('footer',$push);
     }
    }

    public function json() {
        $this->load->model("datatables");
        $this->datatables->setSelect("purchase.*,suppliers.name");
        $this->datatables->setTable("purchase");
        $this->datatables->setJoin("suppliers","suppliers.id = purchase.supplier_id","left");
        $this->datatables->setColumn([
            '<index>',
            '<get-nop>',
            '[reformat_date=<get-date>]',
            '<get-name>',
            '[rupiah=<get-total>]',
            '<div class="text-center">
                <a href="[base_url=purchase/edit/<get-id>]" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                <button type="button" class="btn btn-sm btn-warning btn-view" data-id="<get-id>"><i class="fa fa-eye"></i></button>
                <a href="[base_url=purchase/print/<get-id>]" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-print"></i></a>
            </div>'
        ]);
        $this->datatables->setOrdering(["id","nop","date","name","total",NULL]);
        $this->datatables->setSearchField(["name","nop"]);
        $this->datatables->setWhere("status","PO");
        $this->datatables->generate();
    }
    
    public function json_product() {
        $this->load->model("datatables");
        $this->datatables->setTable("products");
        $this->datatables->setColumn([
            '<get-kditem>',
            '<get-name>',
            '<div class="text-center"><button type="button" class="btn btn-warning btn-sm btn-choose" data-id="<get-id>" data-name="<get-name>" data-stock="<get-stock>"><i class="fa fa-check"></i></button></div>'
        ]);
        $this->datatables->setOrdering(["kditem","name",NULL]);
        $this->datatables->setWhere("type !=","service");
        $this->datatables->setWhere("type !=","paket");
        $this->datatables->setSearchField(["name","kditem"]);
        $this->datatables->generate();
    }
    
    public function create() {
        $data = json_decode($this->input->raw_input_stream,TRUE);
        $nop = $this->Purchase_model->CreateCode();
        //OR !$data['total'] *ini ditambahkan di belakang supplier id supaya total tidak boleh 0
        if(!$data['supplier_id']) {
            $response = [
                "status" => FALSE,
                "msg" => "Harap periksa kembali data Supplier anda"
            ];
        } else {
            $response = [
                "status" => TRUE,
                "msg" => "Data pembelian telah ditambahkan"
            ];
            
            
            $insertData = [
                "id" => NULL,
                "date" => date("Y-m-d H:i:s"),
                "total" => $data["total"],
                "supplier_id" => $data["supplier_id"],
                "nop" => $nop,
                "ppn" => $data["ppn"],
                "status" => "PO",
                "tgl_kirim" => $data["date"],
                "bayar" => $data["bayar"],
                "ket" => $data["keterangan"]
            ];

            $purchase_id = $this->Purchase_model->post($insertData);
            $generated_nop = $this->Purchase_model->get_nop_by_id($purchase_id);

            $items_batch = [];
            $stock_batch = [];

            foreach($data["details"] as $detail) {
                $temp = array();
                $temp["id"] = NULL;
                $temp["nop"] = $generated_nop;
                $temp["purchase_id"] = $purchase_id;
                $temp["product_id"] = $detail["product_id"];
                $temp["price"] = $detail["price"];
                $temp["qty"] = $detail["qty"];
                $temp["max"] = $detail["qty"];

                $tempStock = array();
                $tempStock["id"] = $detail["product_id"];
                $tempStock["stock"] = $detail["product_stock"]; //+ $detail["qty"];

                $items_batch[] = $temp;
                $stock_batch[] = $tempStock;
            }
            
            $this->Purchase_model->post_details($items_batch);
            $this->Purchase_model->update_stock($stock_batch);

        }

        echo json_encode($response);
    }

    public function create2() {
        $data = json_decode($this->input->raw_input_stream,TRUE);
        $nop = $this->Purchase_model->CreateCode();
        //OR !$data['total'] *ini ditambahkan di belakang supplier id supaya total tidak boleh 0
        if(!$data['supplier_id']) {
            $response = [
                "status" => FALSE,
                "msg" => "Harap periksa kembali data Supplier anda"
            ];
        } else {
            $response = [
                "status" => TRUE,
                "msg" => "Data pembelian telah ditambahkan"
            ];
            
            
            $insertData = [
                "id" => NULL,
                "date" => date("Y-m-d H:i:s"),
                "total" => $data["total"],
                "supplier_id" => $data["supplier_id"],
                "nop" => $nop,
                "ppn" => $data["ppn"],
                "status" => "PO",
                "tgl_kirim" => $data["date"],
                "bayar" => $data["bayar"],
                "ket" => $data["keterangan"]
            ];

            $purchase_id = $this->Purchase_model->post($insertData);
            $generated_nop = $this->Purchase_model->get_nop_by_id($purchase_id);

            $items_batch = [];
            $stock_batch = [];

            foreach($data["details"] as $detail) {
                $temp = array();
                $temp["id"] = NULL;
                $temp["nop"] = $generated_nop;
                $temp["purchase_id"] = $purchase_id;
                $temp["product_id"] = $detail["product_id"];
                $temp["price"] = $detail["price"];
                $temp["qty"] = $detail["qty"];
                $temp["max"] = $detail["qty"];

                $tempStock = array();
                $tempStock["id"] = $detail["product_id"];
                $tempStock["stock"] = $detail["product_stock"]; //+ $detail["qty"];

                $items_batch[] = $temp;
                $stock_batch[] = $tempStock;
            }
            
            $this->Purchase_model->post_details($items_batch);
            $this->Purchase_model->update_stock($stock_batch);

            $pid = $data["purchase_id"];
            if (!empty($pid)) {
                $newStatus = "BATAL"; // replace with the desired new status
                $this->Purchase_model->update_status($pid, $newStatus);
            } else {
                // Perform actions when $pid is not empty
                // For example: echo "Purchase ID is not empty: " . $pid;
            }
        }

        echo json_encode($response);
    }

    function print($id = 0) {
        $query = $this->Purchase_model->get($id);

        if($query->num_rows() > 0) {
            $fetch = $query->row();

            $push = [
                "fetch" => $fetch,
                "details" => $this->Purchase_model->get_details($id)->result()
            ];

            $title = "Invoice";

            $this->load->library("pdf");

            $this->pdf->setPaper('A4', 'portrait');
            $this->pdf->filename = $title;
            $this->pdf->load_view('purchase_pdf',$push);
        }
    }

    public function close() {
        $purchaseId = $this->input->post("id");
        if ($purchaseId) {
            // Update the status to "closed" in your Purchase_model
            $this->Purchase_model->update_status($purchaseId, "closed");
    
            // Return a response to indicate success
            echo json_encode(["status" => "success"]);
        } else {
            // Return a response to indicate failure
            echo json_encode(["status" => "error"]);
        }
    }
    
}
