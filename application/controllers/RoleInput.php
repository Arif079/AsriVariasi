<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RoleInput extends CI_Controller {
    private $dataAdmin;

    function __construct() {
        parent::__construct();

        if(!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");

        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }


	public function index()
	{

        $push = [
            "pageTitle" => "User",
            "dataAdmin" => $this->dataAdmin 
        ];

		$this->load->view('header',$push);
		$this->load->view('Role',$push);
		$this->load->view('footer',$push);
    }
    
    public function json() {
        $this->load->model("datatables");
        $this->datatables->setTable("roles");
        $this->datatables->setColumn([
            '<get-roleid>',
            '<get-rolename>',
            '<button type="button" class="btn btn-danger btn-sm btn-delete" data-id="<get-roleid>" data-rolename="<get-rolename>"><i class="fa fa-trash"></i></button></div>'
        ]);
        $this->datatables->setOrdering(["roleid","rolename",NULL]);
        //$this->datatables->setSearchField(["username"]);
        $this->datatables->generate();
    }

    function get($id = 0) {
        $query = $this->user_model->get_role_by_id($id);
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

            $this->user_model->delete_role($id);

            echo json_encode($response);
        }
    }

    private function proccess($action = "add",$id = 0) {
        $roleid = $this->input->post("roleid");
        $rolename = $this->input->post("rolename");

       
        if(!$roleid) {
            $response = [
                "status" => FALSE,
                "msg" => "Periksa kembali data yang anda masukkan"
            ];
        } else {
            $insertData = [
                "roleid" => $roleid,
                "rolename"=>$rolename
            ];

            $response["status"] = TRUE;

            if($action == "add") {
                $response['msg'] = "Data berhasil ditambahkan";
    
                $this->user_model->insert_role($insertData);
            } else {
                $response['msg'] = "Data berhasil diedit";

                unset($insertData["id"]);
    
                $this->user_model->update_role($id,$insertData);
            }

        }

        echo json_encode($response);
    }
}
