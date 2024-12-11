<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class wo extends CI_Controller {
    private $dataAdmin;

    function __construct() {
        parent::__construct();

        if(!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");
        $this->load->model("wo_model");
        $this->load->model("transaction_model");

        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }


	public function index()
	{

        $push = [
            "pageTitle" => "Working Order",
            "dataAdmin" => $this->dataAdmin,
            "spk" => $this->transaction_model->get_spk()->result() 
        ];

		$this->load->view('header',$push);
		$this->load->view('wo',$push);
		$this->load->view('footer',$push);
    }
    
    public function new()
	{

        $push = [
            "pageTitle" => "Tambah Invoice",
            "dataAdmin" => $this->dataAdmin,
            "nowo" => $this->wo_model->get_wo()->result(),
        ];

		$this->load->view('header',$push);
		$this->load->view('wo_compose',$push);
		$this->load->view('footer',$push);
    }

    public function json() {
        $this->load->model("datatables");
        $this->datatables->setTable("wo");
        $this->datatables->setColumn([
            '<index>',
            '<get-nowo>',
            '<get-spkid>',
            '<get-tipe>',
            '<get-merk>',
            '<get-noka>',
            '<get-nosin>',
            '<get-warna>',
            '<get-tahun>',
            '[reformat_date=<get-tglmasuk>]',
            '<get-jammasuk>',
            '<get-kmmasuk>',
            '[reformat_date=<get-tglkeluar>]',
            '<get-jamkeluar>',
            '<get-kmkeluar>',
            '<div class="text-center"><button type="button" class="btn btn-primary btn-sm btn-edit" data-id="<get-id>" data-spk="<get-spkid>" data-tipe="<get-tipe>" data-merk="<get-merk>" data-noka="<get-noka>" data-nosin="<get-nosin>" data-warna="<get-warna>" data-tahun="<get-tahun>" data-tglmasuk="<get-tglmasuk>" data-jammasuk="<get-jammasuk>" data-kmmasuk="<get-kmmasuk>" data-tglkeluar="<get-tglkeluar>" data-jamkeluar="<get-jamkeluar>" data-kmkeluar="<get-kmkeluar>"><i class="fa fa-edit"></i></button>
            <button class="btn btn-sm btn-success btn-close" data-id="<get-id>" data-nowo="<get-nowo>" ><i class="fa fa-book"></i></button>
            <a href="[base_url=wo/print/<get-id>]" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-print"></i></a>
            <button class="btn btn-sm btn-danger btn-batal" data-id="<get-id>" data-nowo="<get-nowo>" ><i class="fa fa-close"></i></button>'
            
        ]);
        $this->datatables->setOrdering(["id","nowo","spkid","tipe","merk","noka","nosin","warna","tahun","tglmasuk","jammasuk","kmmasuk","tglkeluar","jamkeluar","kmkeluar",NULL]);
        $this->datatables->setWhere("status","WO");
        $this->datatables->setSearchField(["spkid","nowo","noka","nosin"]);
        $this->datatables->generate();
    }

    public function json_teknisi() {
        $this->load->model("datatables");
        $this->datatables->setTable("teknisi");
        $this->datatables->setColumn([
            '<index>',
            '<get-nowo>',
            '<get-nama>',
            '<get-masuk>',
            '<get-pause>',
            '<get-keluar>'
        ]);
        $this->datatables->setOrdering(["nowo","nama","masuk","pause","keluar",NULL]);
        $this->datatables->setWhere("status","WO");
        $this->datatables->setSearchField(["nowo","nama"]);
        $this->datatables->generate();
    }
    
    public function json_mekanik() {
        $this->load->model("datatables");
        $this->datatables->setTable("marketing");
        $this->datatables->setColumn([
            '<get-kdsls>',
            '<get-name>',
            '<div class="text-center"><button type="button" class="btn btn-warning btn-sm btn-choose" data-id="<get-id>" data-kdsls="<get-kdsls>" data-name="<get-name>"><i class="fa fa-check"></i></button></div>'
        ]);
        $this->datatables->setOrdering(["kdsls","name",NULL]);
        $this->datatables->setWhere("type","SVC");
        $this->datatables->setSearchField(["kdsls","name"]);
        $this->datatables->generate();
    }

    public function json_job() {
        $this->load->model("datatables");
        $selectedNop = $this->input->get("transaction_id");

        // Set the NOP filter if it's provided
        if ($selectedNop !== null) {

           $this->datatables->setWhere("transaction_id", $selectedNop);
        }
        $this->datatables->setTable("services");
        $this->datatables->setColumn([
            '<get-kditem>',
            '<get-name>',
            '<div class="text-center"><button type="button" class="btn btn-warning btn-sm btn-choose2" data-id="<get-id>" data-kditem="<get-kditem>" data-name="<get-name>"><i class="fa fa-check"></i></button></div>'
        ]);
        $this->datatables->setOrdering(["kditem","name",NULL]);
        $this->datatables->setSearchField(["kditem","name"]);
        $this->datatables->generate();
    }

    public function json_invoice() {
        $this->load->model("datatables");
        $this->datatables->setTable("wo");
        $this->datatables->setColumn([
            '<index>',
            '<get-nowo>',
            '<get-spkid>',
            '<get-noinvoice>',
           '<div class="text-center">
            <a href="[base_url=wo/print_inv/<get-id>]" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-print"></i></a>
            <div>'
        ]);
        $this->datatables->setOrdering(["id","nowo","spkid","noinvoice",NULL]);
        $this->datatables->setWhere("status","INV");
        $this->datatables->setSearchField(["nowo","noinvoice"]);
        $this->datatables->generate();
    }

    function insert() {
        $this->process();
    }

    function update($id) {
        $this->process("edit",$id);
    }

    private function process($action = "add",$id = 0) {
        $nowo = $this->wo_model->CreateCode();
        $spkid = $this->input->post("spk");
        $tid = $this->input->post("id");
        $tipe = $this->input->post("tipe");
        $merk = $this->input->post("merk");
        $noka = $this->input->post("noka");
        $nosin = $this->input->post("nosin");
        $tahun = $this->input->post("tahun");
        $warna = $this->input->post("warna");
        $tglin = $this->input->post("tglmasuk");
        $jamin = $this->input->post("jammasuk");
        $kmin = $this->input->post("kmmasuk");
        $tglout = $this->input->post("tglkeluar");
        $jamout = $this->input->post("jamkeluar");
        $kmout = $this->input->post("kmkeluar");
        

        if(!$spkid) {
            $response['status'] = FALSE;
            $response['msg'] = "Periksa kembali data yang anda masukkan";
        } else {
            $insertData = [
                "id" => NULL,
                "wo_date" => date("Y-m-d"),
                "nowo" => $nowo,
                "transaction_id"=> $tid,
                "spkid"=> $spkid,
                "tipe" => $tipe,
                "merk" => $merk,
                "noka" => $noka,
                "nosin" => $nosin,
                "tahun" => $tahun,
                "warna" => $warna,
                "tglmasuk" => $tglin,
                "jammasuk" => $jamin,
                "kmmasuk" => $kmin,
                "tglkeluar" => $tglout,
                "jamkeluar" => $jamout,
                "kmkeluar" => $kmout,
                "status" => "WO",
                
            ];

            $response['status'] = TRUE;

            if($action == "add") {
                $response['msg'] = "Data berhasil ditambahkan";
                $this->wo_model->post($insertData);
                $data = [
                    "status" => "WO" 
                ];
                $this->wo_model->put_spk($spkid,$data);
            } else {
                unset($insertData['id']);
                unset($insertData['nowo']);
                unset($insertData['spkid']);
                

                $response['msg'] = "Data berhasil diedit";
                $this->wo_model->put($id,$insertData);
            }

        }

        echo json_encode($response);
    }

    public function create() {
        $data = json_decode($this->input->raw_input_stream,TRUE);

        if(!$data['wo']) {
            $response = [
                "status" => FALSE,
                "msg" => "Harap periksa kembali data anda"
            ];
        } else {
            $response = [
                "status" => TRUE,
                "msg" => "Data mekanik telah dibuat"
            ];
            

            $items_batch = [];

            foreach($data["details"] as $detail) {
                $temp = array();
                $temp["id"] = NULL;
                $temp["nowo"] = $data["wo"];
                $temp["kdsls"] = $detail["kdsls"];
                $temp["nama"] = $detail["nama"];
                $temp["kdjob"] = $detail["kdjob"];
                $temp["masuk"] = $detail["in"];
                $temp["pause"] = $detail["pause"];
                $temp["keluar"] = $detail["out"];
                $temp["ttd"] = NULL;
                $temp["inspeksi"] = NULL;
                $temp["status"] = "WO";


                $items_batch[] = $temp;
            }

            $this->wo_model->post_details($items_batch);
        }

        echo json_encode($response);
    }

    function delete($id) {
        $response = [
            'status' => FALSE,
            'msg' => "Data gagal dihapus"
        ];

        if($this->wo_model->delete($id)) {
            $response = [
                'status' => TRUE,
                'msg' => "Data berhasil dihapus"
            ];
        }

        echo json_encode($response);
    }
    
    public function batal() {
        $woId = $this->input->post("id");
        $query = $this->wo_model->get($woId);
        $row = $query->row();
        $nowo = $row->nowo;
          $inv = "BATAL";  
        $insertData=[
            "noinvoice" => $inv,
            "status" => "BATAL",
        ];
        $data = [
            "status" => "BATAL",
        ];
        $this->wo_model->put($woId,$insertData);
        $this->wo_model->put_detail($nowo,$data);
    
    }
    public function invoice() {
        $woId = $this->input->post("id");
        $query = $this->wo_model->get($woId);
        $row = $query->row();
            $nowo = $row->nowo;
            $spkid = $row->spkid;
        $inv= $this->wo_model->CreateInv();
        $insertData=[
            "noinvoice" => $inv,
            "status" => "INV",
        ];
        $data = [
            "status" => "INV",
        ];
        $dataspk = [
            "status" => "INV" 
        ];
        $this->wo_model->put_spk($spkid,$dataspk);
        $this->wo_model->put($woId,$insertData);
        $this->wo_model->put_detail($nowo,$data);
    
    }

    public function print($id = 0) {
        $query = $this->wo_model->get($id);
        if($query->num_rows() > 0) {
            $push["fetch"] = $query->row();
            $row = $query->row();
            $spkid = $row->spkid;
            $nowo = $row->nowo;
            $push["teknisi"] = $this->wo_model->get_teknisi($nowo)->result();
            $transaction_id = $this->wo_model->get_spk($spkid);
            $push["spk"] = $this->wo_model->get_spkid($transaction_id)->row();
            $push["details"] = $this->wo_model->get_details($transaction_id)->result();

            $title = "WorkOrder";

            $this->load->library("pdf");

            $this->pdf->setPaper('legal', 'portrait');
            $this->pdf->filename = $title;

            $this->pdf->load_view("wo_pdf",$push);
        }   
    }
    
    public function print_inv($id = 0) {
        $query = $this->wo_model->get($id);
        if($query->num_rows() > 0) {
            $push["fetch"] = $query->row();
            $row = $query->row();
            $spkid = $row->spkid;
            $nowo = $row->nowo;
            $push["teknisi"] = $this->wo_model->get_teknisi($nowo)->result();
            $transaction_id = $this->wo_model->get_spk($spkid);
            $push["spk"] = $this->wo_model->get_spkid($transaction_id)->row();
            $push["details"] = $this->wo_model->get_details($transaction_id)->result();
            
           

            $title = "Invoice";

            $this->load->library("pdf");

            $this->pdf->setPaper('legal', 'portrait');
            $this->pdf->filename = $title;

            $this->pdf->load_view("service_sales_pdf",$push);
            

       
        }
    }
}