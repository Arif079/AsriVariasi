<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends CI_Controller {
    private $dataAdmin;

    function __construct() {
        parent::__construct();

        if(!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");
        $this->load->model("supplier_model");
        $this->load->model("Coa_model");

        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }


	public function index()
	{

        $push = [
            "pageTitle" => "Supplier",
            "dataAdmin" => $this->dataAdmin,
            "coa" => $this->Coa_model->get()->result()
        ];

		$this->load->view('header',$push);
		$this->load->view('supplier',$push);
		$this->load->view('footer',$push);
    }
    
    public function json() {
        $this->load->model("datatables");
        $this->datatables->setTable("suppliers");
        $this->datatables->setColumn([
            '<index>',
            '<get-kdsupp>',
            '<get-name>',
            '<get-address>',
            '<get-telephone>',
            '<get-coak>',
            '<div class="text-center"><button type="button" class="btn btn-primary btn-sm btn-edit" data-id="<get-id>"><i class="fa fa-edit"></i></button>
            <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="<get-id>" data-name="<get-name>"><i class="fa fa-trash"></i></button></div>'
        ]);
        $this->datatables->setOrdering(["id","kdsupp","name","address","telephone","coak",NULL]);
        $this->datatables->setSearchField(["name"]);
        $this->datatables->generate();
    }

    function get($id = 0) {
        $query = $this->supplier_model->get($id);
        if($query->num_rows()) {
            echo json_encode($query->row_array());
        }
    }

    function insert() {
        $this->proccess();
    }

    function edit($id = 0) {
        $this->proccess("edit",$id);
    }

    function delete($id = 0) {
        if($id) {
            $response["status"] = TRUE;
            $response["msg"] = "Data berhasil dihapus";

            $this->supplier_model->delete($id);

            echo json_encode($response);
        }
    }

    private function proccess($action = "add",$id = 0) {
        $kode = $this->input->post("kdsupp");
        $name = $this->input->post("name");
        $address = $this->input->post("address");
        $telephone = $this->input->post("telephone");
        $coa = $this->input->post("coa");

        if(!$name OR !$address OR !$telephone) {
            $response = [
                "status" => FALSE,
                "msg" => "Periksa kembali data yang anda masukkan"
            ];
        } else {
            $insertData = [
                "id" => NULL,
                "kdsupp" => $kode,
                "name" => $name,
                "address" => $address,
                "telephone" => $telephone,
                "coad" => "1-1-301",
                "coak" => $coa,
            ];

            $response["status"] = TRUE;

            if($action == "add") {
                $response['msg'] = "Data berhasil ditambahkan";
    
                $this->supplier_model->post($insertData);
            } else {
                $response['msg'] = "Data berhasil diedit";

                unset($insertData["id"]);
    
                $this->supplier_model->put($id,$insertData);
            }

        }

        echo json_encode($response);
    }
}
