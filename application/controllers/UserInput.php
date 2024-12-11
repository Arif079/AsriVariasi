<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserInput extends CI_Controller {
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
            "dataAdmin" => $this->dataAdmin,
            "role" => $this->user_model->get_role()->result()
        ];

		$this->load->view('header',$push);
		$this->load->view('User',$push);
		$this->load->view('footer',$push);
    }
    
    public function json() {
        $this->load->model("datatables");
        $this->datatables->setTable("users");
        $this->datatables->setColumn([
            '<index>',
            '<get-username>',
            '<get-roleid>',
            '<button type="button" class="btn btn-danger btn-sm btn-delete" data-id="<get-id>" data-username="<get-username>"><i class="fa fa-trash"></i></button></div>'
        ]);
        $this->datatables->setOrdering(["id","username","roleid",NULL]);
        //$this->datatables->setSearchField(["username"]);
        $this->datatables->generate();
    }

    function get($id = 0) {
        $query = $this->user_model->get_user_by_id($id);
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

            $this->user_model->delete_user($id);

            echo json_encode($response);
        }
    }

    private function proccess($action = "add",$id = 0) {
        $username = $this->input->post("username");
        $password = $this->input->post("password");
        $role = $this->input->post("role");
       
        if(!$username OR !$password OR !$role) {
            $response = [
                "status" => FALSE,
                "msg" => "Periksa kembali data yang anda masukkan"
            ];
        } else {
            $insertData = [
                "id" => NULL,
                "username"=> $username,
                "password" => password_hash($password,PASSWORD_BCRYPT),
                "roleid" => $role,
            ];

            $response["status"] = TRUE;

            if($action == "add") {
                $response['msg'] = "Data berhasil ditambahkan";
    
                $this->user_model->insert_user($insertData);
            } else {
                $response['msg'] = "Data berhasil diedit";

                unset($insertData["id"]);
    
                $this->user_model->update_user($id,$insertData);
            }

        }

        echo json_encode($response);
    }
}
