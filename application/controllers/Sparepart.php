<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sparepart extends CI_Controller {
    private $dataAdmin;

    function __construct() {
        parent::__construct();

        if(!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");
        $this->load->model("product_model");
        $this->load->model("Coa_model");
        $this->load->model("Satuan_model");


        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }


	public function index()
	{

        $push = [
            "pageTitle" => "Sparepart",
            "dataAdmin" => $this->dataAdmin,
            "coa" => $this->Coa_model->get()->result(),
            "satuan" => $this->Satuan_model->get()->result(),
        ];

		$this->load->view('header',$push);
		$this->load->view('sparepart',$push);
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
            '[rupiah=<get-price>]',
            '[rupiah=<get-priceqq>]',
            '<get-stock>',
            '<get-qtybesar>',
            '<get-satuanbesar>',
            '<get-qtykecil>',
            '<get-satuankecil>',
            '<div class="text-center">
            <button type="button" class="btn btn-primary btn-sm btn-edit" data-id="<get-id>" data-kdjob="<get-kdjob>" data-namajob="<get-namajob>" data-kditem="<get-kditem>" data-name="<get-name>" data-price="<get-price>" data-priceqq="<get-priceqq>"><i class="fa fa-edit"></i></button> 
            <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="<get-id>" data-name="<get-name>"><i class="fa fa-trash"></i></button></div>'
        ]);
        $this->datatables->setOrdering(["id","kdjob","namajob","kditem","name","price","priceqq","stock","qtybesar","satuanbesar","qtykecil","satuankecil",NULL]);
        $this->datatables->setWhere("type","sparepart");
        $this->datatables->setSearchField(["name","kditem"]);
        $this->datatables->generate();
    }

    function insert() {
        $this->process();
    }

    function update($id) {
        $this->process("edit",$id);
    }

    public function updatePrice($id) {
        $price = $this->input->post("price");
        $priceqq = $this->input->post("priceqq");
    
        if (!$price) {
            $response['status'] = FALSE;
            $response['msg'] = "Periksa kembali data yang anda masukkan";
        } else {
            $updateData = [
                "price" => $price,
                "priceqq" => $priceqq,
            ];
    
            $response['status'] = TRUE;
            $response['msg'] = "Harga berhasil diubah";
    
            $this->product_model->put($id, $updateData);
        }
    
        echo json_encode($response);
    }
    
    private function process($action = "add",$id = 0) {
        $kdjob = $this->input->post("kdjob");
        $namajob = $this->input->post("namajob");
        $kditem = $this->input->post("kditem");
        $name = $this->input->post("name");
        $price = $this->input->post("price");
        $priceqq = $this->input->post("priceqq");
        $qtybsr = $this->input->post("qtybesar");
        $stnbsr = $this->input->post("satuanbesar");
        $qtykcl = $this->input->post("qtykecil");
        $stnkcl = $this->input->post("satuankecil");
        $hpp = $this->input->post("coahpp");
        $persediaan = $this->input->post("coapersediaan");
        $penjualan = $this->input->post("coapenjualan");

        if(!$name) {
            $response['status'] = FALSE;
            $response['msg'] = "Periksa kembali data yang anda masukkan";
        } else {
            $insertData = [
                "id" => NULL,
                "kdjob" => $kdjob,
                "namajob" => $namajob,
                "kditem" => $kditem,
                "name" => $name,
                "price" => $price,
                "priceqq" => $priceqq,
                "type" => "sparepart",
                "stock" => 0,
                "qtybesar" => $qtybsr,
                "satuanbesar" => $stnbsr,
                "qtykecil" => $qtykcl,
                "satuankecil" => $stnkcl,
                "coahpp" => $hpp,
                "coapersediaan" => $persediaan,
                "coapenjualan" => $penjualan
            ];

            $response['status'] = TRUE;

            if($action == "add") {
                $response['msg'] = "Data berhasil ditambahkan";
                $this->product_model->post($insertData);
            } else {
                unset($insertData['id']);
                unset($insertData['type']);
                unset($insertData['stock']);

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
}