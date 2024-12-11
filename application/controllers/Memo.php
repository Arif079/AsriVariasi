<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class memo extends CI_Controller {
    private $dataAdmin;

    function __construct() {
        parent::__construct();

        if(!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");
        $this->load->model("Coa_model");
        $this->load->model("Memo_model");
        $this->load->model("Jurnal_model");

        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }


	public function index()
	{

        $push = [
            "pageTitle" => "Internal Memo",
            "dataAdmin" => $this->dataAdmin,
            "coa" => $this->Coa_model->get()->result() 
        ];

		$this->load->view('header',$push);
		$this->load->view('memo',$push);
		$this->load->view('footer',$push);
    }
    
    public function json() {
        $this->load->model("datatables");
        $this->datatables->setTable("memo");
        $this->datatables->setColumn([
            '<index>',
            '<get-nmm>',
            '<get-memo>',
            '<get-uraian>',
            '[rupiah=<get-jumlah>]',
            '<get-date>',
            '<div class="text-center"><button type="button" class="btn btn-primary btn-sm btn-edit" data-id="<get-id>" data-nmm="<get-nmm>" data-memo="<get-memo>" data-coad="<get-coad>" data-coak="<get-coak>" data-uraian="<get-uraian>" data-jumlah="<get-jumlah>"><i class="fa fa-edit"></i></button>
            <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="<get-id>" data-uraian="<get-uraian>"><i class="fa fa-trash"></i></button>
            <a href="[base_url=memo/print/<get-id>]" class="btn btn-sm btn-primary" target=_blank><i class="fa fa-print"></i></a>
            </div>'
        ]);
        $this->datatables->setOrdering(["id","nmm","memo","uraian","jumlah","date",NULL]);
        $this->datatables->setSearchField(["nmm","memo"]);
        $this->datatables->generate();
    }

    function insert() {
        $this->process();
    }

    function update($id) {
        $this->process("edit",$id);
    }

    private function process($action = "add",$id = 0) {
        $nmm = $this->Memo_model->CreateCode();
        $memo = $this->input->post("memo");
        $coad = $this->input->post("debet");
        $coak = $this->input->post("kredit");
        $uraian = $this->input->post("uraian");
        $jumlah = $this->input->post("jumlah");
        $date_str = $this->input->post("date");
        $nama_coad = $this->input->post("namacoad");
        $nama_coak = $this->input->post("namacoak");

        $date = new DateTime($date_str);

        if(!$memo) {
            $response['status'] = FALSE;
            $response['msg'] = "Periksa kembali data yang anda masukkan";
        } else {
            $insertData = [
                "id" => NULL,
                "nmm"=> $nmm,
                "memo"=> $memo,
                "coad" => $coad,
                "coak" => $coak,
                "nama_coad" => $nama_coad,
                "nama_coak" => $nama_coak,
                "uraian" => $uraian,
                "jumlah" => $jumlah,
                "date" => $date->format('Y-m-d H:i:s'),
            ];

            $debet = [
                "id" => NULL,
                "kdjurnal" => $this->Jurnal_model->CreateCode(),
                "date" => $date->format('Y-m-d H:i:s'),
                "kdtransaksi" => $nmm,
                "kdbukti" => $nmm,
                "tipe" => "Memorial",
                "coa" => $coad,
                "debet" => $jumlah,
                "kredit"=> 0,
                "status"=> "Terproses"

            ];

            $kredit = [
                "id" => NULL,
                "kdjurnal" => $this->Jurnal_model->CreateCode(),
                "date" => $date->format('Y-m-d H:i:s'),
                "kdtransaksi" => $nmm,
                "kdbukti" => $nmm,
                "tipe" => "Memorial",
                "coa" => $coak,
                "debet" => 0,
                "kredit"=> $jumlah,
                "status"=> "Terproses"

            ];
            
            
            
            $response['status'] = TRUE;

            if($action == "add") {
                $response['msg'] = "Data berhasil ditambahkan";
                $this->Memo_model->post($insertData);
                $this->Jurnal_model->post($debet);
                $this->Jurnal_model->post($kredit);
            } else {
                unset($insertData['id']);
                unset($insertData['nmm']);

                $response['msg'] = "Data berhasil diedit";
                $this->Memo_model->put($id,$insertData);
                $this->Jurnal_model->post($debet);
                $this->Jurnal_model->post($kredit);
            }

        }

        echo json_encode($response);
    }

    function delete($id) {
        $response = [
            'status' => FALSE,
            'msg' => "Data gagal dihapus"
        ];

        if($this->Memo_model->delete($id)) {
            $response = [
                'status' => TRUE,
                'msg' => "Data berhasil dihapus"
            ];
        }

        echo json_encode($response);
    }
    
    public function print($id = 0) {
        $query = $this->Memo_model->get($id);
        if($query->num_rows() > 0) {
            $push["fetch"] = $query->row();
            $push["details"] = $this->Memo_model->get_details($id)->result();

            $title = "Memorial";

            $this->load->library("pdf");

            $this->pdf->setPaper('A4', 'portrait');
            $this->pdf->filename = $title;

            $this->pdf->load_view("memo_pdf",$push);
        }
    }
}