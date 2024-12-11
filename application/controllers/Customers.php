<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends CI_Controller {
    private $dataAdmin;

    function __construct() {
        parent::__construct();

        if(!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");
        $this->load->model("Customer_model");
        $this->load->model("Coa_model");
        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }


	public function index()
	{

        $push = [
            "pageTitle" => "Customer",
            "dataAdmin" => $this->dataAdmin,
            "coa" => $this->Coa_model->get()->result()
        ];

		$this->load->view('header',$push);
		$this->load->view('customer',$push);
		$this->load->view('footer',$push);
    }
    
    public function json() {
        $this->load->model("datatables");
        $this->datatables->setTable("customers");
        $this->datatables->setColumn([
            '<index>',
            '<get-kdcust>',
            '<get-name>',
            '<get-address>',
            '<get-telephone>',
            '<get-coad>',
            '<div class="text-center"><button type="button" class="btn btn-primary btn-sm btn-edit" data-id="<get-id>"><i class="fa fa-edit"></i></button>
            <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="<get-id>" data-name="<get-name>" ><i class="fa fa-trash"></i></button></div>'
        ]);
        $this->datatables->setOrdering(["id","kdcust","name","address","telephone","coad",NULL]);
        $this->datatables->setSearchField(["name"]);
        $this->datatables->generate();
    }

    function get($id = 0) {
        $query = $this->Customer_model->get($id);
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

            $this->Customer_model->delete($id);

            echo json_encode($response);
        }
    }

    private function proccess($action = "add",$id = 0) {
        $kdcust = $this->input->post("kdcust");
        $name = $this->input->post("name");
        $address = $this->input->post("address");
        $telephone = $this->input->post("telephone");
        $coa =  $this->input->post("coa");

        if(!$name OR !$address OR !$telephone) {
            $response = [
                "status" => FALSE,
                "msg" => "Periksa kembali data yang anda masukkan"
            ];
        } else {
            $insertData = [
                "id" => NULL,
                "kdcust"=>$kdcust,
                "name" => $name,
                "address" => $address,
                "telephone" => $telephone,
                "coad" => $coa,
                "coak" => $coa
            ];

            $response["status"] = TRUE;

            if($action == "add") {
                $response['msg'] = "Data berhasil ditambahkan";
    
                $this->Customer_model->post($insertData);
            } else {
                $response['msg'] = "Data berhasil diedit";

                unset($insertData["id"]);
    
                $this->Customer_model->put($id,$insertData);
            }

        }

        echo json_encode($response);
    }
}
