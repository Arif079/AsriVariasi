<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bkm extends CI_Controller {
    private $dataAdmin;

    function __construct() {
        parent::__construct();

        if(!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");
        $this->load->model("Coa_model");
        $this->load->model("Bkm_model");
        $this->load->model("Customer_model");
        $this->load->model("Jurnal_model");

        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }


	public function index()
	{

        $push = [
            "pageTitle" => "Bukti Kas Masuk",
            "dataAdmin" => $this->dataAdmin,
            "coa" => $this->Coa_model->get()->result(),
        ];

		$this->load->view('header',$push);
		$this->load->view('Bkm',$push);
		$this->load->view('footer',$push);
    }

    public function new()
	{

        $push = [
            "pageTitle" => "Tambah Bkm",
            "dataAdmin" => $this->dataAdmin,
            "coa" => $this->Coa_model->get()->result(),
            "cust" => $this->Customer_model->get()->result(),
        ];

		$this->load->view('header',$push);
		$this->load->view('bkm_compose',$push);
		$this->load->view('footer',$push);
    }
    
    public function json() {
        $this->load->model("datatables");
        $this->datatables->setTable("bkmlain");
        $this->datatables->setColumn([
            '<index>',
            '<get-npl>',
            '<get-customer>',
            '<get-coad>',
            '<get-coak>',
            '[rupiah=<get-jumlah>]',
            '<get-date>',
            '<div class="text-center"><button type="button" class="btn btn-primary btn-sm btn-edit" data-id="<get-id>" data-customer="<get-customer>" data-alamat="<get-alamat>" data-coad="<get-coad>" data-coak="<get-coak>" data-uraian="<get-uraian>" data-jumlah="<get-jumlah>"><i class="fa fa-edit"></i></button>
            <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="<get-id>" data-npl="<get-npl>"><i class="fa fa-trash"></i></button>
            <a href="[base_url=Bkm/print/<get-id>]" class="btn btn-sm btn-primary" target=_blank><i class="fa fa-print"></i></a>
            </div>'
        ]);
        $this->datatables->setOrdering(["id","npl","customer","coad","coak","jumlah","date",NULL]);
        $this->datatables->setSearchField(["npl","customer"]);
        $this->datatables->generate();
    }

    public function json_Bkm() {
        $this->load->model("datatables");
        $this->datatables->setSelect("bkm.*,bkm_log.bkmno");
        $this->datatables->setTable("bkm");
        $this->datatables->setJoin("bkm_log","bkm_log.referensibkm = bkm.npc","left");
        $this->datatables->setColumn([
            '<index>',
            '<get-bkmno>',
            '<get-npc>',
            '<get-customer>',
            '<get-date>',
            '[rupiah=<get-jumlah>]',
            '[rupiah=<get-bayar>]',
            '[rupiah=<get-sisa>]',
            '<button type="button" class="btn btn-danger btn-sm btn-delete" data-id="<get-id>" data-npc="<get-npc>"><i class="fa fa-trash"></i></button>
            <a href="[base_url=Bkm/print2/<get-id>]" class="btn btn-sm btn-primary" target=_blank><i class="fa fa-print"></i></a>
            </div>'
        ]);
        $this->datatables->setOrdering(["id","npc","customer","date","jumlah","bayar","sisa",NULL]);
        $this->datatables->setSearchField(["npc","customer"]);
        $this->datatables->generate();
    }

    public function json_product() {
        $this->load->model("datatables");
        $selectedNop = $this->input->get("nop");

        // Set the NOP filter if it's provided
        if ($selectedNop !== null) {
           $this->datatables->setWhere("customer", $selectedNop);
        }
        $this->datatables->setWhere("status", "INV");
        $this->datatables->setWhere("lunas", 0);
        $this->datatables->setTable("transactions");
        $this->datatables->setColumn([
            '<get-spkid>',
            '<get-date>',
            '[rupiah=<get-total>]',
            '<div class="text-center"><button type="button" class="btn btn-warning btn-sm btn-choose" data-id="<get-id>" data-spkid="<get-spkid>" data-date="<get-date>" data-total="<get-total>" data-sisa="<get-sisa>"><i class="fa fa-check"></i></button></div>'
        ]);
        $this->datatables->setOrdering(["spkid","date","total",NULL]);
        $this->datatables->setSearchField(["spkid","date"]);
        $this->datatables->generate();
    }

    function insert() {
        $this->process();
    }

    function update($id) {
        $this->process("edit",$id);
    }

    private function process($action = "add",$id = 0) {
        $npl = $this->Bkm_model->CreateCode();
        $bkmno = $this->Bkm_model->CreateCode3();
        $customer = $this->input->post("customer");
        $alamat = $this->input->post("alamat");
        $coad = $this->input->post("coad");
        $coak = $this->input->post("coak");
        $uraian = $this->input->post("uraian");
        $jumlah = $this->input->post("jumlah");
        $date = $this->input->post("date");
       
        

        if(!$coad) {
            $response['status'] = FALSE;
            $response['msg'] = "Periksa kembali data yang anda masukkan";
        } else {
            
            $insertData = [
                "id" => NULL,
                "npl" => $npl,
                "customer"=> $customer,
                "alamat"=> $alamat,
                "coad" => $coad,
                "coak" => $coak,
                "uraian" => $uraian,
                "jumlah" => $jumlah,
                "date" => $date,
            ];

            $debet = [
                "id" => NULL,
                "kdjurnal" => $this->Jurnal_model->CreateCode(),
                "date" => date("Y-m-d H:i:s"),
                "kdtransaksi" => $npl,
                "kdbukti" => $bkmno,
                "tipe" => "Penerimaan Lain Lain",
                "coa" => $coad,
                "debet" => $jumlah,
                "kredit"=> 0,
                "status"=> "Terproses"
            ];
            
            $kredit = [
                "id" => NULL,
                "kdjurnal" => $this->Jurnal_model->CreateCode(),
                "date" => date("Y-m-d H:i:s"),
                "kdtransaksi" => $npl,
                "kdbukti" => $bkmno,
                "tipe" => "Piutang lain-lain",
                "coa" => "$coak",
                "debet" => 0,
                "kredit"=> $jumlah,
                "status"=> "Terproses"

            ];

            $bkmdata = [
                "id" => NULL,
                "tglbkm" => date("Y-m-d H:i:s"),
                "bkmno"=> $bkmno,
                "referensibkm" => $npl,
                "coak" => $coak,
                "coad" => $coad,
                "jumlah" => $jumlah,
            ];

            $response['status'] = TRUE;

            if($action == "add") {
                $response['msg'] = "Data berhasil ditambahkan";
                $this->Bkm_model->post($insertData);
                $this->Jurnal_model->post($debet);
                $this->Jurnal_model->post($kredit);
                $this->Bkm_model->post3($bkmdata);
            } else {
                unset($insertData['id']);
                #unset($insertData['date']);

                $response['msg'] = "Data berhasil diedit";
                $this->Bkm_model->put($id,$insertData);
                $this->Jurnal_model->post($debet);
                $this->Jurnal_model->post($kredit);
                $this->Bkm_model->post3($bkmdata);
            }

        }

        echo json_encode($response);
    }

    public function create() {
        $data = json_decode($this->input->raw_input_stream,TRUE);
        $npc = $this->Bkm_model->CreateCode2();
        $bkmno = $this->Bkm_model->CreateCode3();
        //OR !$data['total'] *ini ditambahkan di belakang supplier id supaya total tidak boleh 0
        if(!$npc) {
            $response = [
                "status" => FALSE,
                "msg" => "Harap periksa kembali data anda"
            ];
        } else {
            $response = [
                "status" => TRUE,
                "msg" => "Data BKM telah ditambahkan"
            ];
            
            $insertData = [
                "id" => NULL,
                "npc" => $npc,
                "customer_id" => $data["cid"],
                "customer" => $data["customer"],
                "date" => date("Y-m-d H:i:s"),
                "coak" => $data["kredit"],
                "jumlah" => $data["total"],
                "bayar" => $data["bayar"],
                "sisa" => $data["sisa"]         

            ];


            $kredit = [
                "id" => NULL,
                "kdjurnal" => $this->Jurnal_model->CreateCode(),
                "date" => date("Y-m-d H:i:s"),
                "kdtransaksi" => $npc,
                "kdbukti" => $bkmno,
                "tipe" => "Piutang Customer",
                "coa" => $data["kredit"],
                "debet" => 0,
                "kredit"=> $data["bayar"],
                "status"=> "Terproses"

            ];

            $this->Jurnal_model->post($kredit);
            $sisa = $data["sisa"];
            $Bkm_id = $this->Bkm_model->post2($insertData);
            
            $items_batch = [];

            $first_detail = $data["details"][0];
            $bkmdata = [
                "id" => NULL,
                "tglbkm" => date("Y-m-d H:i:s"),
                "bkmno"=> $bkmno,
                "referensibkm" => $npc,
                "jumlah" => $data["total"],
                "coad" => $first_detail["debet"],
                "coak" => $data["kredit"],
            ];   

            $this->Bkm_model->post3($bkmdata); 

            foreach($data["details"] as $detail) {
                $temp = array();
                $temp["id"] = NULL;
                $temp["npc"] = $npc;
                $temp["bkm_id"] = $Bkm_id;
                $temp["spkid"] = $detail["spkid"];
                $temp["uraian"] = $detail["text"];
                $temp["coad"] = $detail["debet"];
                $temp["total"] = $detail["price"];
                $temp["bayar"] = $detail["bayar"];
                $temp["sisa"] = $detail["sisa"];
               
                $items_batch[] = $temp;
               
                if ($sisa == 0) {
                    $data = [
                        "lunas" => 1,
                        "sisa" => $sisa,
                    ];
                    $this->Bkm_model->put2($detail["spkid"],$data);
                }else if ($sisa != 0){
                    $data = [
                        "sisa" => $sisa,
                    ];
                    $this->Bkm_model->put2($detail["spkid"],$data);
                }
                
                $debet = [
                    "id" => NULL,
                    "kdjurnal" => $this->Jurnal_model->CreateCode(),
                    "date" => date("Y-m-d H:i:s"),
                    "kdtransaksi" => $npc,
                    "kdbukti" => $bkmno,
                    "tipe" => "Pendapatan Usaha",
                    "coa" => $detail["debet"],
                    "debet" => $detail["bayar"],
                    "kredit"=> 0,
                    "status"=> "Terproses"
    
                ];
               
              
            $this->Jurnal_model->post($debet);
            
            }

            $this->Bkm_model->post_details($items_batch);

        }

        echo json_encode($response);
    }

    function delete($id) {
        $response = [
            'status' => FALSE,
            'msg' => "Data gagal dihapus"
        ];

        if($this->Bkm_model->delete($id)) {
            
            $response = [
                'status' => TRUE,
                'msg' => "Data berhasil dihapus"
            ];
        }

        echo json_encode($response);
    }
    
    public function print($id = 0) {
        $query = $this->Bkm_model->get($id);
        if($query->num_rows() > 0) {
            $push["fetch"] = $query->row();
            $row = $query->row();
            $bkmno = $row->npl;
            $push["details"] = $this->Bkm_model->get_details($id)->result();
            $bkm = $this->Bkm_model->get_bkmno($bkmno)->row(); // Use ->row() instead of ->result()
            $push["bkmno"] = $bkm;

            $title = "Bukti Kas Masuk Lain";

            $this->load->library("pdf");

            $this->pdf->setPaper('A4', 'portrait');
            $this->pdf->filename = $title;

            $this->pdf->load_view("Bkm_lain_pdf",$push);
        }
    }
    
    public function print2($id = 0) {
    $query = $this->Bkm_model->get2($id);
    if ($query->num_rows() > 0) {
        $push["fetch"] = $query->row();
        $row = $query->row();
        $bkmno = $row->npc;
        $push["details"] = $this->Bkm_model->get_details($id)->result();
        $bkm = $this->Bkm_model->get_bkmno($bkmno)->row();
        
        // Collect spkid values from details
        $spkid_array = array();
        foreach ($push["details"] as $detail) {
            $spkid_array[] = $detail->spkid;
        }
        
        // Retrieve inv using spkid
        // You might need to decide how to handle multiple spkid values here
        // For now, I'm just taking the first one
        $spkid = isset($spkid_array[0]) ? $spkid_array[0] : null;
        if ($spkid) {
            $inv = $this->Bkm_model->get_spk($spkid)->row();
        } else {
            $inv = null;
        }
        
        $push["bkmno"] = $bkm;
        $push["inv"] = $inv;

        $title = "Bukti Kas Masuk";

        $this->load->library("pdf");

        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->filename = $title;

        $this->pdf->load_view("Bkm_pdf", $push);
    }
}
}