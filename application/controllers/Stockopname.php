<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stockopname extends CI_Controller {
    private $dataAdmin;

    function __construct() {
        parent::__construct();

        if(!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");
        $this->load->model("product_model");
        $this->load->model("hpp_model");

        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }


	public function index()
	{

        $push = [
            "pageTitle" => "Stock Opname",
            "dataAdmin" => $this->dataAdmin 
        ];

		$this->load->view('header',$push);
		$this->load->view('stockopname',$push);
		$this->load->view('footer',$push);
    }
    
    public function json() {
        $this->load->model("datatables");
        $this->datatables->setTable("products");
        $this->datatables->setColumn([
            '<index>',
            '<get-kdjob>',
            '<get-namajob>',
            '<get-kditem>',
            '<get-name>',
            '<get-stock>',
            '<div class="text-center">
            <button type="button" class="btn btn-primary btn-sm btn-edit" data-id="<get-id>" data-kditem="<get-kditem>" data-name="<get-name>" data-kecil="<get-satuankecil>" data-stock="<get-stock>"><i class="fa fa-balance-scale"></i></button>
            <button type="button" class="btn btn-primary btn-sm btn-edit2" data-id="<get-id>" data-kditem="<get-kditem>" data-name="<get-name>" data-price="<get-price>" data-priceqq="<get-priceqq>"><i class="fa fa-money"></i></button>
            </div>'

        ]);
    
        $this->datatables->setOrdering(["id","kdjob","namajob","kditem","name","stock","price","priceqq",NULL]);
        $this->datatables->setWhere("type !=","service");
        $this->datatables->setWhere("type !=","paket");
        $this->datatables->setSearchField(["name","kditem"]);
        $this->datatables->generate();
    }


    function insert() {
        $this->process();
    }

    function update($id) {
        $this->process("edit",$id);
    }

    function update_price($id) {
        $this->process2("edit",$id);
    }

    private function process($action = "add",$id = 0) {
        $kditem = $this->input->post("kditem");
        $name = $this->input->post("name");
        $awal = $this->input->post("awal");
        $stock = $this->input->post("stock");
        $satuan = $this->input->post("satuan");

        if(!$name) {
            $response['status'] = FALSE;
            $response['msg'] = "Periksa kembali data yang anda masukkan";
        } else {
            $insertData = [
                "id" => NULL,
                "kditem" => $kditem,
                "name" => $name,
                "type" => "sparepart",
                "stock" => $stock
            ];

            $insertlog = [
                "id" => NULL,
                "product_id" => $id,
                "kditem" => $kditem,
                "name" => $name,
                "qty_awal" => $awal,
                "qty_akhir" => $stock
                
            ];
            
            $kdtransaksi = $this->generateTransactionCode();

            $hppdata  = [
                'id' => NULL,
                'kdhpp' => $this->hpp_model->CreateCode(),
                'tgltransaksi' => date("Y-m-d H:i:s"),
                'tipe' => "Adjust Stock",
                'kdtransaksi'=> $kdtransaksi,
                'kdreferensi'=> $kdtransaksi,
                'kditem' => $kditem,
                'kdsatuan'=> $satuan,
                'ppn' => 0,
                'ppnecer' => 0,
                'dpp' => 0,
                'dppecer'=> 0,
                'grandtotal'=> 0,
                'grandtotalecer'=>0,
                'hpp'=> 0,
                'hppecer'=> 0,
                'stok'=> 0,
                'stokecer' => $stock
            ];

            $response['status'] = TRUE;

            if($action == "add") {
                $response['msg'] = "Data berhasil ditambahkan";
                $this->product_model->post($insertData);
            } else {
                unset($insertData['id']);
                unset($insertData['type']);

                $response['msg'] = "Data berhasil diedit";
                $this->product_model->put($id,$insertData);
                $this->product_model->postlog($insertlog);
                $this->hpp_model->post($hppdata);
            }

        }

        echo json_encode($response);
    }

    private function process2($action = "add",$id = 0) {
        $kditem = $this->input->post("kditem");
        $name = $this->input->post("name");
        $price = $this->input->post("price");
        $priceqq = $this->input->post("priceqq");

        if(!$name) {
            $response['status'] = FALSE;
            $response['msg'] = "Periksa kembali data yang anda masukkan";
        } else {
            $insertData = [
                "id" => NULL,
                "kditem" => $kditem,
                "name" => $name,
                "price" => $price,
                "priceqq" => $priceqq
            ];

            
            $response['status'] = TRUE;

            if($action == "add") {
                $response['msg'] = "Data berhasil ditambahkan";
                $this->product_model->post($insertData);
            } else {
                unset($insertData['id']);

                $response['msg'] = "Data berhasil diedit";
                $this->product_model->put($id,$insertData);
            }

        }

        echo json_encode($response);
    }

    function delete($id) {
        $response = [
            'status' => FALSE,
            'msg' => "Data gagal dihapus"
        ];

        if($this->product_model->delete($id)) {
            $response = [
                'status' => TRUE,
                'msg' => "Data berhasil dihapus"
            ];
        }

        echo json_encode($response);
    }
    public function generateTransactionCode() {
        // 'STOdmy' format: 'STO' + dd + m + y
        $prefix = 'STO';
        $dd = date('d');
        $m = date('m');
        $y = date('y');

        $transactionCode = $prefix . $dd . $m . $y;

        return $transactionCode;
    }
}