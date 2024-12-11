<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paket extends CI_Controller {
    private $dataAdmin;

    function __construct() {
        parent::__construct();

        if(!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");
        $this->load->model("supplier_model");
        $this->load->model("Paket_model");
        $this->load->model("Coa_model");

        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }


	public function index()
	{

        $push = [
            "pageTitle" => "Pembuatan Paket",
            "dataAdmin" => $this->dataAdmin,
        ];

		$this->load->view('header',$push);
		$this->load->view('paket',$push);
		$this->load->view('footer',$push);
    }

    public function detail($id = 0) {
        $query = $this->Paket_model->get($id);
        if($query->num_rows() > 0) {
            $response = $query->row_array();
            $response["items"] = $this->Paket_model->get_details($id)->result_array();
            echo json_encode($response);
        }
    }

	public function new()
	{

        $push = [
            "pageTitle" => "Tambah Pembelian Stock",
            "dataAdmin" => $this->dataAdmin,
            "suppliers" => $this->supplier_model->get()->result(),
            "coa" => $this->Coa_model->get()->result(),
        ];

		$this->load->view('header',$push);
		$this->load->view('paket_compose',$push);
		$this->load->view('footer',$push);
    }

    public function json() {
        $this->load->model("datatables");
        $this->datatables->setSelect("paket.*,suppliers.name");
        $this->datatables->setTable("paket");
        $this->datatables->setJoin("suppliers","suppliers.id = paket.supplier_id","left");
        $this->datatables->setColumn([
            '<index>',
            '[reformat_date=<get-date>]',
            '<get-name>',
            '<get-kdpaket>',
            '<get-nama>',
            '[rupiah=<get-harga>]',
            '[rupiah=<get-hargaqq>]',
            '<div class="text-center">
                <button type="button" class="btn btn-sm btn-warning btn-view" data-id="<get-id>"><i class="fa fa-eye"></i></button>
                <a href="[base_url=paket/edit/<get-id>]" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
        </div>'
        ]);
        $this->datatables->setOrdering(["id","kdpaket","nama","date","name","harga","hargaqq",NULL]);
        $this->datatables->setSearchField(["name","kdpaket","nama"]);
        $this->datatables->generate();
    }
    
    public function json_product() {
        $this->load->model("datatables");
        $this->datatables->setTable("products");
        $this->datatables->setColumn([
            '<get-kditem>',
            '<get-name>',
            '<div class="text-center"><button type="button" class="btn btn-warning btn-sm btn-choose" data-id="<get-id>" data-name="<get-name>" data-stock="<get-stock>" data-price="<get-price>"><i class="fa fa-check"></i></button></div>'
        ]);
        $this->datatables->setOrdering(["name","kditem",NULL]);
        $this->datatables->setWhere("type","sparepart");
        $this->datatables->setSearchField(["name","kditem"]);
        $this->datatables->generate();
    }
    
    public function create() {
        $data = json_decode($this->input->raw_input_stream,TRUE);

        //OR !$data['total'] *ini ditambahkan di belakang supplier id supaya total tidak boleh 0
        if(!$data['supplier_id']){
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
                "kdpaket" => $data["kdpaket"],
                "nama" => $data["nama"],
                "date" => date("Y-m-d H:i:s"),
                "total" => $data["total"],
                "supplier_id" => $data["supplier_id"],
                "harga" => $data["harga"],
                "hargaqq" => $data["hargaqq"],
                "hpp" => $data["hpp"]
            ];

            $Paket_id = $this->Paket_model->post($insertData);

            $items_batch = [];
            $stock_batch = [];

            $tempStock = array();
            $tempStock["kdjob"] = $data["kdjob"];
            $tempStock["namajob"] = $data["namajob"];
            $tempStock["kditem"] = $data["kdpaket"];
            $tempStock["name"] = $data["nama"];
            $tempStock["price"] = $data["harga"];
            $tempStock["priceqq"] = $data["hargaqq"];
            $tempStock["type"] = "paket";
            $tempStock["stock"] = NULL;
            $tempStock["qtybesar"] = 1;
            $tempStock["satuanbesar"] = "paket";
            $tempStock["qtykecil"] = 1;
            $tempStock["satuankecil"] = "paket";
            $tempStock["coapersediaan"] = $data["coapersediaan"];
            $tempStock["coahpp"] = $data["coahpp"];
            $tempStock["coapenjualan"] = $data["coapenjualan"];
            


            $stock_batch[] = $tempStock;
            $this->Paket_model->insert_paket($stock_batch);
           

            foreach($data["details"] as $detail) {
                $temp = array();
                $temp["id"] = NULL;
                $temp["Paket_id"] = $Paket_id;
                $temp["product_id"] = $detail["product_id"];
                $temp["price"] = $detail["price"];
                $temp["qty"] = $detail["qty"];

                

                $items_batch[] = $temp;
              
            }
            

            
            $this->Paket_model->post_details($items_batch);
            
        }

        echo json_encode($response);
    }   

    public function edit($id = 0)
	{
        $query = $this->Paket_model->get($id);

        if($query->num_rows() > 0) {
            $fetch = $query->row();
        $push = [
            "pageTitle" => "Edit paket",
            "dataAdmin" => $this->dataAdmin,
            "suppliers" => $this->supplier_model->get()->result(),
            "fetch" => $fetch,
            "detail" => $this->Paket_model->get_details($id)->result(),
            "paket_id" => $id,
            
        ];

		$this->load->view('header',$push);
		$this->load->view('paket_edit',$push);
		$this->load->view('footer',$push);
     }
    }

    public function update() {
        $data = json_decode($this->input->raw_input_stream, TRUE);
       # log_message('debug', 'Data received: ' . print_r($data, true));
    
    
            // Prepare the data for updating the paket
            $updateData = [
                "kdpaket" => $data["kdpaket"],
                "nama" => $data["nama"],
                "date" => date("Y-m-d H:i:s"),
                "total" => $data["total"],
                "supplier_id" => $data["supplier_id"],
                "harga" => $data["harga"],
                "hargaqq" => $data["hargaqq"],
                "hpp" => $data["hpp"]
            ];
    
            // Update the paket in the database
            if (!$this->Paket_model->put($data['id'], $updateData)) {
             #   log_message('error', 'Failed to update paket: ' . print_r($updateData, true));
                echo json_encode(["status" => FALSE, "msg" => "Failed to update paket."]);
                return;
            }
    
            // Update stock data similar to create function
            $stock_batch = [];
            $tempStock = array();
            $tempStock["id"] = $data['prod_id'];
            $tempStock["kditem"] = $data["kdpaket"];
            $tempStock["name"] = $data["nama"];
            $tempStock["price"] = $data["harga"];
            $tempStock["priceqq"] = $data["hargaqq"];
            $tempStock["type"] = "paket";
            $tempStock["stock"] = NULL;
            $tempStock["qtybesar"] = 1;
            $tempStock["satuanbesar"] = "paket";
            $tempStock["qtykecil"] = 1;
            $tempStock["satuankecil"] = "paket";
    
            $stock_batch[] = $tempStock;
            #log_message('debug', 'Stock data prepared for update: ' . print_r($stock_batch, true));
    
            $this->Paket_model->update_paket($stock_batch);
            #log_message('debug', 'Stock data updated for paket ID: ' . $data['id']);
    
            // Prepare details for updating similar to create function
            $items_batch = [];
            foreach ($data["details"] as $detail) {
                $temp = [
                    "paket_id" => $data['id'],
                    "product_id" => $detail["product_id"],
                    "price" => $detail["price"],
                    "qty" => $detail["qty"]
                ];
                $items_batch[] = $temp;
            }
    
            #log_message('debug', 'Details prepared for updating: ' . print_r($items_batch, true));

    // Update paket details
    $this->Paket_model->update_details($data['id'], $items_batch);
    #log_message('debug', 'Paket details updated for paket ID: ' . $data['id']);

    echo json_encode(["status" => TRUE, "msg" => "Paket updated successfully."]);
        }
    
}
