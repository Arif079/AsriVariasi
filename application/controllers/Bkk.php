<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bkk extends CI_Controller {
    private $dataAdmin;

    function __construct() {
        parent::__construct();

        if(!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");
        $this->load->model("Coa_model");
        $this->load->model("Bkk_model");
        $this->load->model("supplier_model");
        $this->load->model("Jurnal_model");

        
        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }


	public function index()
	{

        $push = [
            "pageTitle" => "Bukti Kas Keluar",
            "dataAdmin" => $this->dataAdmin,
            "coa" => $this->Coa_model->get()->result(),
        ];

		$this->load->view('header',$push);
		$this->load->view('Bkk',$push);
		$this->load->view('footer',$push);
    }

    public function new()
	{

        $push = [
            "pageTitle" => "Tambah BKK",
            "dataAdmin" => $this->dataAdmin,
            "coa" => $this->Coa_model->get()->result(),
            "supp" => $this->supplier_model->get()->result(),
        ];

		$this->load->view('header',$push);
		$this->load->view('bkk_compose',$push);
		$this->load->view('footer',$push);
    }
    
    public function json() {
        $this->load->model("datatables");
        $this->datatables->setTable("bkklain");
        $this->datatables->setColumn([
            '<index>',
            '<get-nbl>',
            '<get-customer>',
            '<get-coad>',
            '<get-coak>',
            '[rupiah=<get-jumlah>]',
            '<get-date>',
            '<div class="text-center"><button type="button" class="btn btn-primary btn-sm btn-edit" data-id="<get-id>" data-customer="<get-customer>" data-alamat="<get-alamat>" data-coad="<get-coad>" data-coak="<get-coak>" data-uraian="<get-uraian>" data-jumlah="<get-jumlah>"><i class="fa fa-edit"></i></button>
            <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="<get-id>" data-nbl="<get-nbl>"><i class="fa fa-trash"></i></button>
            <a href="[base_url=Bkk/print/<get-id>]" class="btn btn-sm btn-primary" target=_blank><i class="fa fa-print"></i></a>
            </div>'
        ]);
        $this->datatables->setOrdering(["id","nbl","customer","coad","coak","jumlah","date",NULL]);
        $this->datatables->setSearchField(["nbl","customer"]);
        $this->datatables->generate();
    }

    public function json_bkk() {
        $this->load->model("datatables");
        $this->datatables->setSelect("bkk.*,bkk_log.bkkno");
        $this->datatables->setTable("bkk");
        $this->datatables->setJoin("bkk_log","bkk_log.referensibkk = bkk.nph","left");
        $this->datatables->setColumn([
            '<index>',
            '<get-bkkno>',
            '<get-nph>',
            '<get-supplier>',
            '<get-date>',
            '[rupiah=<get-jumlah>]',
            '[rupiah=<get-bayar>]',
            '[rupiah=<get-sisa>]',
            '<button type="button" class="btn btn-danger btn-sm btn-delete" data-id="<get-id>" data-nph="<get-nph>"><i class="fa fa-trash"></i></button>
            <a href="[base_url=Bkk/print2/<get-id>]" class="btn btn-sm btn-primary" target=_blank><i class="fa fa-print"></i></a>
            </div>'
        ]);
        $this->datatables->setOrdering(["id","nph","supplier","date","jumlah","bayar","sisa",NULL]);
        $this->datatables->setSearchField(["nph","supplier"]);
        $this->datatables->generate();
    }

    public function json_product() {
        $this->load->model("datatables");
        $selectedNop = $this->input->get("nop");

        // Set the NOP filter if it's provided
        if ($selectedNop !== null) {
           $this->datatables->setWhere("supplier_id", $selectedNop);
        }
        $this->datatables->setWhere("status",0);
        $this->datatables->setTable("transaksi");
        $this->datatables->setColumn([
            '<get-ntrn>',
            '<get-date>',
            '[rupiah=<get-total>]',
            '<div class="text-center"><button type="button" class="btn btn-warning btn-sm btn-choose" data-id="<get-id>" data-ntrn="<get-ntrn>" data-date="<get-date>" data-total="<get-total>" data-sisa="<get-sisa>" data-inv="<get-inv>"><i class="fa fa-check"></i></button></div>'
        ]);
        $this->datatables->setOrdering(["ntrn","date","total",NULL]);
        $this->datatables->setSearchField(["ntrn","date"]);
        $this->datatables->generate();
    }

    function insert() {
        $this->process();
    }

    function update($id) {
        $this->process("edit",$id);
    }

    private function process($action = "add",$id = 0) {
        $nbl = $this->Bkk_model->CreateCode();
        $bkkno = $this->Bkk_model->CreateCode3();
        $customer = $this->input->post("customer");
        $alamat = $this->input->post("alamat");
        $coad = $this->input->post("coad");
        $coak = $this->input->post("coak");
        $uraian = $this->input->post("uraian");
        $jumlah = $this->input->post("jumlah");
        $date = $this->input->post("date");
       
        $dateString = $date;

        // Create a DateTime object from the string
        $dateObject = DateTime::createFromFormat('d-m-Y', $dateString);
        
        // Format the date to 'Y-m-d H:i:s'
        $formattedDate = $dateObject->format('Y-m-d H:i:s');
        

        if(!$coad) {
            $response['status'] = FALSE;
            $response['msg'] = "Periksa kembali data yang anda masukkan";
        } else {
            
            $insertData = [
                "id" => NULL,
                "nbl" => $nbl,
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
                "date" => $formattedDate,
                "kdtransaksi" => $nbl,
                "kdbukti" => $bkkno,
                "tipe" => "Biaya Lain Lain",
                "coa" => $coad,
                "debet" => $jumlah,
                "kredit"=> 0,
                "status"=> "Terproses"
            ];
            
            $kredit = [
                "id" => NULL,
                "kdjurnal" => $this->Jurnal_model->CreateCode(),
                "date" => $formattedDate,
                "kdtransaksi" => $nbl,
                "kdbukti" => $bkkno,
                "tipe" => "Kredit lain-lain",
                "coa" => "$coak",
                "debet" => 0,
                "kredit"=> $jumlah,
                "status"=> "Terproses"

            ];

            $bkkdata = [
                "id" => NULL,
                "tglbkk" => date("Y-m-d H:i:s"),
                "bkkno"=> $bkkno,
                "referensibkk" => $nbl,
                "jumlah" => $jumlah,
                "coad" => $coad,
                "coak" => $coak,
            ];

            $response['status'] = TRUE;

            if($action == "add") {
                $response['msg'] = "Data berhasil ditambahkan";
                $this->Bkk_model->post($insertData);
                $this->Jurnal_model->post($debet);
                $this->Jurnal_model->post($kredit);
                $this->Bkk_model->post3($bkkdata);
            } else {
                unset($insertData['id']);
                #unset($insertData['date']);

                $response['msg'] = "Data berhasil diedit";
                $this->Bkk_model->put($id,$insertData);
                $this->Jurnal_model->post($debet);
                $this->Jurnal_model->post($kredit);
                $this->Bkk_model->post3($bkkdata);
            }

        }

        echo json_encode($response);
    }

    public function create() {
        $data = json_decode($this->input->raw_input_stream, TRUE);
        $NPH = $this->Bkk_model->CreateCode2();
        $bkkno = $this->Bkk_model->CreateCode3();
        //OR !$data['total'] *ini ditambahkan di belakang supplier id supaya total tidak boleh 0
        if(!$data['supplier_id']) {
            $response = [
                "status" => FALSE,
                "msg" => "Harap periksa kembali data anda"
            ];
        } else {
            $response = [
                "status" => TRUE,
                "msg" => "Data pembelian telah ditambahkan"
            ];
            
            $insertData = [
                "id" => NULL,
                "nph" => $NPH,
                "supplier_id" => $data["supplier_id"],
                "supplier" => $data["supplier"],
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
                "kdtransaksi" => $NPH,
                "kdbukti" => $bkkno,
                "tipe" => "Pembayaran Hutang",
                "coa" => $data["kredit"],
                "debet" => 0,
                "kredit"=> $data["bayar"],
                "status"=> "Terproses"
            ];
    
            $sisa = $data["sisa"];
            $this->Jurnal_model->post($kredit);
            $bkk_id = $this->Bkk_model->post2($insertData);
            $items_batch = [];
    
            // Process first input for bkkdata
            $first_detail = $data["details"][0];
            $bkkdata = [
                "id" => NULL,
                "tglbkk" => date("Y-m-d H:i:s"),
                "bkkno"=> $bkkno,
                "referensibkk" => $NPH,
                "jumlah" => $data["total"],
                "coad" => $first_detail["debet"],
                "coak" => $data["kredit"],
            ];
            $this->Bkk_model->post3($bkkdata);
    
            foreach($data["details"] as $detail) {
                $temp = array();
                $temp["id"] = NULL;
                $temp["nph"] = $NPH;
                $temp["bkk_id"] = $bkk_id;
                $temp["ntrn"] = $detail["ntrn"];
                $temp["uraian"] = $detail["text"];
                $temp["coad"] = $detail["debet"];
                $temp["total"] = $detail["price"];
                $temp["bayar"] = $detail["bayar"];
                $temp["sisa"] = $detail["sisa"];
                $temp["inv"] = $detail["inv"];
    
                $items_batch[] = $temp;
                $id = $detail["id"];
                $id = $detail["id"];
                if ($sisa == 0) {
                    $data = [
                        "status" => 1,
                        "sisa" => $sisa,
                    ];
                    $this->Bkk_model->put2($id,$data);
                }else if ($sisa != 0){
                    $data = [
                        "sisa" => $sisa,
                    ];
                    $this->Bkk_model->put2($id,$data);
                }
                
                $debet = [
                    "id" => NULL,
                    "kdjurnal" => $this->Jurnal_model->CreateCode(),
                    "date" => date("Y-m-d H:i:s"),
                    "kdtransaksi" => $NPH,
                    "kdbukti" => $bkkno,
                    "tipe" => "Pelunasan Pembayaran",
                    "coa" => $detail["debet"],
                    "debet" => $detail["bayar"],
                    "kredit"=> 0,
                    "status"=> "Terproses"
                ];
    
                $this->Jurnal_model->post($debet);
    
            }
            $this->Bkk_model->post_details($items_batch);
            
        }
    
        echo json_encode($response);
    }
    

    function delete($id) {
        $response = [
            'status' => FALSE,
            'msg' => "Data gagal dihapus"
        ];

        if($this->Bkk_model->delete($id)) {
            $response = [
                'status' => TRUE,
                'msg' => "Data berhasil dihapus"
            ];
        }

        echo json_encode($response);
    }
    
    public function print($id = 0) {
        $query = $this->Bkk_model->get($id);
        if($query->num_rows() > 0) {
            $push["fetch"] = $query->row();
            $row = $query->row();
            $bkkno = $row->nbl;
            $push["details"] = $this->Bkk_model->get_details($id)->result();
            $bkk = $this->Bkk_model->get_bkkno($bkkno)->row(); // Use ->row() instead of ->result()
            $push["bkkno"] = $bkk;

            $title = "Bukti Kas Keluar Lain";

            $this->load->library("pdf");

            $this->pdf->setPaper('A4', 'portrait');
            $this->pdf->filename = $title;

            $this->pdf->load_view("Bkk_lain_pdf",$push);
        }
    }

    public function print2($id = 0) {
        $query = $this->Bkk_model->get2($id);
        if($query->num_rows() > 0) {
            $push["fetch"] = $query->row();
            $row = $query->row();
            $bkkno = $row->nph;
            $push["details"] = $this->Bkk_model->get_details($id)->result();
            $bkk = $this->Bkk_model->get_bkkno($bkkno)->row(); // Use ->row() instead of ->result()
            $push["bkkno"] = $bkk;

            $title = "Bukti Kas Keluar";

            $this->load->library("pdf");

            $this->pdf->setPaper('A4', 'portrait');
            $this->pdf->filename = $title;

            $this->pdf->load_view("Bkk_pdf",$push);
        }
    }
}