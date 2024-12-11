<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GLproses extends CI_Controller {
    private $dataAdmin;

    function __construct() {
        parent::__construct();

        if(!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");
        $this->load->model("Coa_model");
        $this->load->model("Gl_model");
        $this->load->model("Jurnal_model");

        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }


	public function index()
	{

        $push = [
            "pageTitle" => "Saldo Awal",
            "dataAdmin" => $this->dataAdmin,
            "coa" => $this->Coa_model->get()->result() 
        ];

		$this->load->view('header',$push);
		$this->load->view('gl',$push);
		$this->load->view('footer',$push);
    }
    
    public function json() {
        $this->load->model("datatables");
        $this->datatables->setTable("kas_log");
        $this->datatables->setColumn([
            '<index>',
            '<get-date>',
            '<get-sld>',
            '[rupiah=<get-jumlah>]',
            '<get-bulan>',
            '<get-tahun>',
            
        ]);
        $this->datatables->setOrdering(["id","sld","jumlah","date","bulan","tahun",NULL]);
        $this->datatables->setSearchField(["sld","date"]);
        $this->datatables->generate();
    }

    function insert() {
        $this->process();
    }

    function update($id) {
        $this->process("edit",$id);
    }

    private function process($action = "add",$id = 0) {
        $sld = $this->Gl_model->CreateCode();
        $coad = $this->input->post("debet");
        $bulan = $this->input->post("month");
        $tahun = $this->input->post("year");
        $jumlah = $this->input->post("jumlah");
        if ($jumlah !== null) {
            // Do something with $saldo
            $response['status'] = True;
            $response['msg'] = "Saldo $bulan-$tahun $jumlah ";
        } else {
            $response['status'] = FALSE;
            $response['msg'] = "Saldo Kosong";
        };
        $date = $date = date("Y-m-01", strtotime("$tahun-$bulan-01"));
        $nama_coad = $this->input->post("namacoad");


        if(!$sld) {
            $response['status'] = FALSE;
            $response['msg'] = "Periksa kembali data yang anda masukkan";
        } else {
            $insertData = [
                "id" => NULL,
                "sld"=> $sld,
                "coad" => $coad,
                "nama_coad" => $nama_coad,
                "jumlah" => $jumlah,
                "date" => date("Y-m-d"),
            ];

            $debet = [
                "id" => NULL,
                "kdjurnal" => $this->Jurnal_model->CreateCode(),
                "date" => $date,
                "kdtransaksi" => $sld,
                "tipe" => "Saldo Awal",
                "coa" => $coad,
                "debet" => $jumlah,
                "kredit" => 0,
                "status"=> "Terproses"

            ];
            
            
            
            $response['status'] = TRUE;

            if($action == "add") {
                $response['msg'] = "Data berhasil ditambahkan";
                $this->Gl_model->post($insertData);
                $this->Jurnal_model->post($debet);
            } else {
                unset($insertData['id']);
                unset($insertData['nmm']);

                $response['msg'] = "Data berhasil diedit";
                $this->Gl_model->put($id,$insertData);
                $this->Jurnal_model->post($debet);
            }

        }

        echo json_encode($response);
    }

    function delete($id) {
        $response = [
            'status' => FALSE,
            'msg' => "Data gagal dihapus"
        ];

        if($this->Gl_model->delete($id)) {
            $response = [
                'status' => TRUE,
                'msg' => "Data berhasil dihapus"
            ];
        }

        echo json_encode($response);
    }
    
   
}