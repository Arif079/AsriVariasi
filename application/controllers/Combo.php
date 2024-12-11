<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Combo extends CI_Controller {
    private $dataAdmin;

    function __construct() {
        parent::__construct();

        if(!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");
        $this->load->model("supplier_model");
        $this->load->model("Combo_model");
        $this->load->model("Coa_model");

        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }


	public function index()
	{

        $push = [
            "pageTitle" => "Pembuatan Combo",
            "dataAdmin" => $this->dataAdmin,
        ];

		$this->load->view('header',$push);
		$this->load->view('combo',$push);
		$this->load->view('footer',$push);
    }

    public function detail($id = 0) {
        $query = $this->Combo_model->get($id);
        if($query->num_rows() > 0) {
            $response = $query->row_array();
            $response["items"] = $this->Combo_model->get_details($id)->result_array();
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
		$this->load->view('combo_compose',$push);
		$this->load->view('footer',$push);
    }

    public function json() {
        $this->load->model("datatables");
        $this->datatables->setSelect("combo.*,suppliers.name");
        $this->datatables->setTable("combo");
        $this->datatables->setJoin("suppliers","suppliers.id = combo.supplier_id","left");
        $this->datatables->setColumn([
            '<index>',
            '[reformat_date=<get-date>]',
            '<get-name>',
            '<get-kdcombo>',
            '<get-nama>',
            '[rupiah=<get-harga>]',
            '[rupiah=<get-hargaqq>]',
            '<div class="text-center">
                <button type="button" class="btn btn-sm btn-warning btn-view" data-id="<get-id>"><i class="fa fa-eye"></i></button>
            </div>'
        ]);
        $this->datatables->setOrdering(["id","kdcombo","nama","date","name","harga","hargaqq",NULL]);
        $this->datatables->setSearchField(["name","kdcombo","nama"]);
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
                "kdcombo" => $data["kdcombo"],
                "nama" => $data["nama"],
                "date" => date("Y-m-d H:i:s"),
                "total" => $data["total"],
                "supplier_id" => $data["supplier_id"],
                "harga" => $data["harga"],
                "hargaqq" => $data["hargaqq"],
                "hpp" => $data["hpp"]
            ];

            $Combo_id = $this->Combo_model->post($insertData);

            $items_batch = [];
            $stock_batch = [];

            $tempStock = array();
            $tempStock["kdjob"] = "CMB";
            $tempStock["namajob"] = "Item Combo";
            $tempStock["kditem"] = $data["kdcombo"];
            $tempStock["name"] = $data["nama"];
            $tempStock["price"] = $data["harga"];
            $tempStock["priceqq"] = $data["hargaqq"];
            $tempStock["type"] = "combo";
            $tempStock["stock"] = NULL;
            $tempStock["qtybesar"] = 1;
            $tempStock["satuanbesar"] = "combo";
            $tempStock["qtykecil"] = 1;
            $tempStock["satuankecil"] = "combo";
            $tempStock["coapersediaan"] = $data["coapersediaan"];
            $tempStock["coahpp"] = $data["coahpp"];
            $tempStock["coapenjualan"] = $data["coapenjualan"];
            


            $stock_batch[] = $tempStock;
            $this->Combo_model->insert_combo($stock_batch);
           

            foreach($data["details"] as $detail) {
                $temp = array();
                $temp["id"] = NULL;
                $temp["Combo_id"] = $Combo_id;
                $temp["product_id"] = $detail["product_id"];
                $temp["price"] = $detail["price"];
                $temp["qty"] = $detail["qty"];

                

                $items_batch[] = $temp;
              
            }
            

            
            $this->Combo_model->post_details($items_batch);
            
        }

        echo json_encode($response);
    }   

}
