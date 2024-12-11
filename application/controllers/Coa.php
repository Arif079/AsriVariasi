<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coa extends CI_Controller {
    private $dataAdmin;

    function __construct() {
        parent::__construct();

        if(!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");
        $this->load->model("Coa_model");

        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }


	public function index()
	{

        $push = [
            "pageTitle" => "COA",
            "dataAdmin" => $this->dataAdmin,
            "coa" => $this->Coa_model->get()->result() 
        ];

		$this->load->view('header',$push);
		$this->load->view('Coa',$push);
		$this->load->view('footer',$push);
    }
    
    public function json() {
        $this->load->model("datatables");
        $this->datatables->setTable("coa");
        $this->datatables->setColumn([
            '<index>',
            '<get-coa>',
            '<get-nama>',
            '<get-header>',
            '<get-grup>',
            '<get-isdetail>',
            '<get-urutanlevel>',
            '<get-status>',
            '<div class="text-center"><button type="button" class="btn btn-primary btn-sm btn-edit" data-id="<get-id>" data-coa="<get-coa>" data-nama="<get-nama>" data-header="<get-header>" data-grup="<get-grup>" data-level="<get-level>" data-urutan="<get-urutanlevel>" data-status="<get-status>"><i class="fa fa-edit"></i></button>
            <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="<get-id>" data-coa="<get-coa>"><i class="fa fa-trash"></i></button></div>'
        ]);

        $this->datatables->setOrdering(["id","coa","nama","header","grup","isdetail","urutanlevel","status",NULL]);
        //$this->datatables->setWhere("status","Checked");
        $this->datatables->setSearchField(["nama","coa"]);
        $this->datatables->generate();
    }

    function insert() {
        $this->process();
    }

    function update($id) {
        $this->process("edit",$id);
    }

    private function process($action = "add",$id = 0) {
        $coa = $this->input->post("coa");
        $nama = $this->input->post("nama");
        $header = $this->input->post("header");
        $grup = $this->input->post("grup"); 
        $level = $this->input->post("level");
        $isdetail = 0; 
        $urutan = $this->input->post("urutan");
        $status = $this->input->post("status");

        if(!$coa) {
            $response['status'] = FALSE;
            $response['msg'] = "Periksa kembali data yang anda masukkan";
        } else {

            if ($level === 'H') {
                $isdetail = 0;
            } elseif ($level === 'D') {
                $isdetail = 1;
            } else {
                // Handle other cases if needed
                $isdetail = 0; // Default value
            }

            $insertData = [
                "id" => NULL,
                "coa" => $coa,
                "nama" => $nama,
                "header" => $header,
                "grup" => $grup,
                "level" => $level,
                "isdetail" => $isdetail,
                "urutanlevel" => $urutan,
                "status" => $status,
            ];

            $response['status'] = TRUE;

            if($action == "add") {
                $response['msg'] = "Data berhasil ditambahkan";
                $this->Coa_model->post($insertData);
            } else {
                unset($insertData['id']);

                $response['msg'] = "Data berhasil diedit";
                $this->Coa_model->put($id,$insertData);
            }

        }
        
        echo json_encode($response);
    }

    function delete($id) {
        $response = [
            'status' => FALSE,
            'msg' => "Data gagal dihapus"
        ];

        if($this->Coa_model->delete($id)) {
            $response = [
                'status' => TRUE,
                'msg' => "Data berhasil dihapus"
            ];
        }
        
        echo json_encode($response);
    }
}