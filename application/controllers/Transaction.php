<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends CI_Controller {
    private $dataAdmin;

    function __construct() {
        parent::__construct();

        if(!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");
        $this->load->model("transaction_model");
        $this->load->model("customer_model");
        $this->load->model("marketing_model");
        $this->load->model("hpp_model");
        $this->load->model("jurnal_model");


        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }


	public function index()
	{

        $push = [
            "pageTitle" => "Transaksi",
            "dataAdmin" => $this->dataAdmin,
            "customers" => $this->customer_model->get()->result(),
            "sales"     => $this->marketing_model->get()->result(),
        ];

		$this->load->view('header',$push);
		$this->load->view('transaction',$push);
		$this->load->view('footer',$push);
    }

    function insert($action = "sparepart") {
        $data = json_decode($this->input->raw_input_stream,TRUE);

        
        $customer = NULL;
        $plat = NULL;
        $sales = NULL;
        $alamat = NULL;
        $telp = NULL;
        $diskon = NULL;
        

        if($action == "service") {
            $customer = $data['customer'];
            $coad = $data['coad'];
            $nama_qq = $data['nama_qq'];
            $plat = $data['plat'];
            $sales = $data['sales'];
            $alamat = $data['alamat'];
            $npwp = $data['npwp'];
            $telp = $data['telp'];
            $tipe = $data['tipe'];
            $merk = $data['merk'];
            $noka = $data['noka'];
            $nosin = $data['nosin'];
            $tahun = $data['tahun'];
            $warna = $data['warna'];
            $diskon = $data['diskon'];
        }
        $spkid = $this->transaction_model->CreateCode();
        $push = [
            "id" => NULL,
            "spkid" => $spkid,
            "type" => $action,
            "total" => $data['total'],
            "date" => date("Y-m-d H:i:s"),
            "customer" => $customer,
            "nama_qq" => $nama_qq,
            "sales" => $sales,
            "notelp" => $telp,
            "alamat" => $alamat,
            "npwp" => $npwp, 
            "plat" => $plat,
            "tipe" => $tipe,
            "merk" => $merk,
            "noka" => $noka,
            "nosin" => $nosin,
            "tahun" => $tahun,
            "warna" => $warna,
            "diskon" => $diskon,
            "sisa" => $data['total']
        ];

        $transaction_id = $this->transaction_model->create($push);

        $sparepart_batch = [];
        $service_batch = [];
        $paket_batch = [];
        $stockpaket_batch = [];
        $stock_batch = [];
        $getitem= [];
        $itempaket_batch= [];

        foreach($data['paket'] as $itempkt) {
                $temp = array();
                $temp["id"] = NULL;
                $temp["kditem"] = $itempkt["kditem"];
                $temp["transaction_id"] = $transaction_id;
                $temp["product_id"] = $itempkt["id"];
                $temp["name"] = $itempkt["name"];
                if ($nama_qq == "") {
                    $temp["price"] = $itempkt["price"];
                    $jumlah = $itempkt["price"] * $itempkt["qty"];
                } else {
                    $temp["price"] = $itempkt["priceqq"];
                    $jumlah = $itempkt["priceqq"] * $itempkt["qty"];
                }     
                $temp["qty"] = $itempkt["qty"];
                
                $kdpaket = $itempkt["kditem"];
                $query = $this->jurnal_model->get_akunpaket($kdpaket);
                $row = $query->row();
                $hpp = $row->hpp;

                $hppdata  = [
                    "id" => NULL,
                    "kdhpp" => $this->hpp_model->CreateCode(),
                    "tgltransaksi" => date("Y-m-d H:i:s"),
                    "tipe" => "Penjualan",
                    "kdtransaksi"=> $spkid,
                    "kdreferensi"=> $spkid,
                    "kditem" => $itempkt["kditem"],
                    "kdsatuan"=> "Paket",
                    "dpp" => $jumlah,
                    "dppecer"=> $temp["price"],
                    "grandtotal"=>$jumlah,
                    "grandtotalecer"=>$jumlah,
                    "hpp"=> $hpp,
                    "hppecer"=> $hpp,
                    "stokecer" => $itempkt["qty"]
    
                ];
                $debetpkt = [
                    "id" => NULL,
                    "kdjurnal" => $this->jurnal_model->CreateCode(),
                    "date" => date("Y-m-d H:i:s"),
                    "kdtransaksi" => $itempkt["kditem"],
                    "kdbukti" => $spkid,
                    "tipe" => "HPP",
                    "coa" => $itempkt["hpp"],
                    "debet" => $hpp,
                    "kredit"=> 0,
                    "status"=> "Terproses"
                ];
                $kreditpkt = [
                    "id" => NULL,
                    "kdjurnal" => $this->jurnal_model->CreateCode(),
                    "date" => date("Y-m-d H:i:s"),
                    "kdtransaksi" => $itempkt["kditem"],
                    "kdbukti" => $spkid,
                    "tipe" => "Persediaan",
                    "coa" => $itempkt["persediaan"],
                    "debet" => 0,
                    "kredit"=>  $hpp,
                    "status"=> "Terproses"
    
                ];
                $debetjual = [
                    "id" => NULL,
                    "kdjurnal" => $this->jurnal_model->CreateCode(),
                    "date" => date("Y-m-d H:i:s"),
                    "kdtransaksi" => $itempkt["kditem"],
                    "kdbukti" => $spkid,
                    "tipe" => "Piutang",
                    "coa" => $coad,
                    "debet" => $jumlah,
                    "kredit"=> 0,
                    "status"=> "Terproses"
                ];
                $kreditjual = [
                    "id" => NULL,
                    "kdjurnal" => $this->jurnal_model->CreateCode(),
                    "date" => date("Y-m-d H:i:s"),
                    "kdtransaksi" => $itempkt["kditem"],
                    "kdbukti" => $spkid,
                    "tipe" => "Penjualan",
                    "coa" => $itempkt["jual"],
                    "debet" => 0,
                    "kredit"=>  $jumlah,
                    "status"=> "Terproses"
    
                ];
                $this->hpp_model->post($hppdata);
                $this->jurnal_model->post($debetpkt);
                $this->jurnal_model->post($kreditpkt);
                $this->jurnal_model->post($debetjual);
                $this->jurnal_model->post($kreditjual);

                $paket_batch[] = $temp;

                $kodepaket = $itempkt["kditem"];
                $id = $this->transaction_model->get_paket($kodepaket);
                $getitem = $this->transaction_model->get_component($id)->result_array();

                    foreach($getitem as $itemPk) {
                        $temp = array();
            
                        $temp["id"] = NULL;
                        $temp["transaction_id"] = $transaction_id;
                        $temp["kdpaket"] = $kodepaket;
                        $temp["product_id"] = $itemPk["product_id"];
                        $temp["kditem"] = $itemPk["kditem"];
                        $temp["name"] = $itemPk["name"];
                        $temp["qty"] = $itemPk["qty"];
                        
                        $itempaket_batch[] = $temp;
                        
                        $stocktmp = array();
                        $stocktmp["id"] = $itemPk["product_id"];
                        $stocktmp["stock"] = $itemPk["stock"] - $itemPk["qty"];
            
                        $stockpaket_batch[] = $stocktmp;
                    }
                    
                }

            $fifo_stock_sparepart = 0;
         $associated_hpp_sparepart = 0;
         foreach($data['sparepart'] as $itemSp) {
            $temp = array();
        
            $temp["id"] = NULL;
            $temp["transaction_id"] = $transaction_id;
            $temp["product_id"] = $itemSp["id"];
            $temp["kdjob"] = $itemSp["kdjob"];
            $temp["namajob"] = $itemSp["namajob"];
            $temp["kditem"] = $itemSp["kditem"];
            $temp["name"] = $itemSp["name"];
        
            if ($nama_qq == "") {
                $temp["price"] = $itemSp["price"];
                $jumlah = $itemSp["price"] * $itemSp["qty"];
            } else {
                $temp["price"] = $itemSp["priceqq"];
                $jumlah = $itemSp["priceqq"] * $itemSp["qty"];
            }     
            $temp["qty"] = $itemSp["qty"];
            
            $id = $itemSp["id"];
            $kditem = $itemSp["kditem"];
            $quantity_needed = $itemSp["qty"];
        
            // Initialize HPP-related variables
            $itemSp["fifo_stock"] = 0; // Default value for FIFO stock
            $itemSp["associated_hpp"] = 0; // Default value for associated HPP
        
            // Check if the product type is not "jasa"
            if ($itemSp["type"] !== "jasa") {
                // Calculate FIFO stock and associated HPP only for non-jasa products
                $result = $this->hpp_model->calculate_fifo_stock_with_hpp($kditem, $quantity_needed);
                
                $itemSp["fifo_stock"] = $result["fifo_stock"];
                $itemSp["associated_hpp"] = $result["associated_hpp"];
        
                $hppdata  = [
                    "id" => NULL,
                    "kdhpp" => $this->hpp_model->CreateCode(),
                    "tgltransaksi" => date("Y-m-d H:i:s"),
                    "tipe" => "Penjualan",
                    "kdtransaksi"=> $spkid,
                    "kdreferensi"=> $spkid,
                    "kditem" => $itemSp["kditem"],
                    "kdsatuan"=> $itemSp["satuan"],
                    "dpp" => $jumlah,
                    "dppecer"=> $temp["price"],
                    "grandtotal"=>$jumlah,
                    "grandtotalecer"=>$jumlah,
                    "hpp"=> $itemSp["associated_hpp"],
                    "hppecer"=> $itemSp["associated_hpp"],
                    "stokecer" => $itemSp["qty"]
                ];
        
                // Create HPP and Persediaan journal entries
                $debet = [
                    "id" => NULL,
                    "kdjurnal" => $this->jurnal_model->CreateCode(),
                    "date" => date("Y-m-d H:i:s"),
                    "kdtransaksi" => $itemSp["kditem"],
                    "kdbukti" => $spkid,
                    "tipe" => "HPP",
                    "coa" => $itemSp["hpp"],
                    "debet" => $itemSp["associated_hpp"],
                    "kredit"=> 0,
                    "status"=> "Terproses"
                ];
                $kredit = [
                    "id" => NULL,
                    "kdjurnal" => $this->jurnal_model->CreateCode(),
                    "date" => date("Y-m-d H:i:s"),
                    "kdtransaksi" => $itemSp["kditem"],
                    "kdbukti" => $spkid,
                    "tipe" => "Persediaan",
                    "coa" => $itemSp["sedia"],
                    "debet" => 0,
                    "kredit"=> $itemSp["associated_hpp"],
                    "status"=> "Terproses"
                ];
        
                // Post HPP data and journal entries
                $this->jurnal_model->post($debet);
                $this->jurnal_model->post($kredit);
                $this->hpp_model->post($hppdata);
                $stocktmp = array();
                $stocktmp["id"] = $itemSp["id"];
                $stocktmp["stock"] = $itemSp["stock"] - $itemSp["qty"];
            
                $sparepart_batch[] = $temp;
                $stock_batch[] = $stocktmp;
            }
        
            // Continue to handle the sales journal entries for all products
            $debetjual = [
                "id" => NULL,
                "kdjurnal" => $this->jurnal_model->CreateCode(),
                "date" => date("Y-m-d H:i:s"),
                "kdtransaksi" => $itemSp["kditem"],
                "kdbukti" => $spkid,
                "tipe" => "Piutang",
                "coa" => $coad,
                "debet" => $jumlah,
                "kredit"=> 0,
                "status"=> "Terproses"
            ];
            $kreditjual = [
                "id" => NULL,
                "kdjurnal" => $this->jurnal_model->CreateCode(),
                "date" => date("Y-m-d H:i:s"),
                "kdtransaksi" => $itemSp["kditem"],
                "kdbukti" => $spkid,
                "tipe" => "Penjualan",
                "coa" => $itemSp["jual"],
                "debet" => 0,
                "kredit"=> $jumlah,
                "status"=> "Terproses"
            ];
        
            $this->jurnal_model->post($debetjual);
            $this->jurnal_model->post($kreditjual);
            
        }

        if($action == "service") {
            foreach($data['service'] as $itemSrv) {
                $temp = array();
                $temp["id"] = NULL;
                $temp["kditem"] = $itemSrv["kditem"];
                $temp["transaction_id"] = $transaction_id;
                $temp["product_id"] = $itemSrv["id"];
                $temp["name"] = $itemSrv["name"];
                $temp["price"] = $itemSrv["price"];
                $temp["qty"] = $itemSrv["qty"];

                $service_batch[] = $temp;
            }
        }


        if($sparepart_batch) {
            $this->transaction_model->post_details($sparepart_batch);
            $this->transaction_model->sparepart_update($stock_batch);
        }

        if($service_batch) {
            $this->transaction_model->post_services($service_batch);
        }
        if($paket_batch) {
            $this->transaction_model->post_details($paket_batch);
            $this->transaction_model->post_paket_log($itempaket_batch);
            $this->transaction_model->sparepart_update($stockpaket_batch);
        }
        $response = [
            "status" => TRUE,
            "type" => $action,
            "msg" => "Transaksi sukses",
            "id" => $transaction_id
        ];

        echo json_encode($response);
    }
    
    public function json_service() {
        $addFunc = "addServiceCart({id:<get-id>,kditem:'<get-kditem>',name:'<get-name>',price:<get-price>,priceqq:<get-priceqq>})";

        $this->load->model("datatables");
        $this->datatables->setTable("products");
        $this->datatables->setColumn([
            '<get-kdjob>',
            '<get-namajob>',
            '-',
            '-',
            '<div class="text-center">
                <button type="button" class="btn btn-sm btn-success btn-add" onclick="'.$addFunc.'"><i class="fa fa-plus"></i></button>
            </div>'
        ]);
        $this->datatables->setOrdering(["kditem","name","price","priceqq",NULL]);
        $this->datatables->setWhere("type","service");
        $this->datatables->setSearchField(["name","kditem"]);
        $this->datatables->generate();
    }

    public function json_sparepart() {
        $addFunc = "addSparepartCart({id:<get-id>,kditem:'<get-kditem>',kdjob:'<get-kdjob>',namajob:'<get-namajob>',name:'<get-name>',satuan:'<get-satuankecil>',price:<get-price>,priceqq:<get-priceqq>,stock:<get-stock>,hpp:'<get-coahpp>',jual:'<get-coapenjualan>',sedia:'<get-coapersediaan>',type:'<get-type>'})";
        
        $serviceKDitems = $this->input->get("serviceKDitems"); // Get the serviceKDitems from the query parameter
        
        $this->load->model("datatables");
        $this->datatables->setTable("products");
        $this->datatables->setColumn([
            '<get-kditem>',
            '<get-name>',
            '[rupiah=<get-price>]',
            '[rupiah=<get-priceqq>]',
            '<div class="text-center">
                <button type="button" class="btn btn-sm btn-success" onclick="'.$addFunc.'"><i class="fa fa-plus"></i></button>
            </div>'
        ]);
        $this->datatables->setOrdering(["kditem","name","price","priceqq",NULL]);
        $this->datatables->setWhere("type","sparepart");
        $this->datatables->setSearchField(["name","kditem"]);
       
        if (!empty($serviceKDitems)) {
            $this->datatables->setWhereIn("kdjob", explode(",", $serviceKDitems));
        }
       
        $this->datatables->generate();
    }
    
    public function json_paket() {
        $addFunc = "addPaketCart({id:<get-id>,kditem:'<get-kditem>',name:'<get-name>',price:<get-price>,priceqq:<get-priceqq>,jual:'<get-coapenjualan>',hpp:'<get-coahpp>',persediaan:'<get-coapersediaan>'})";

        $this->load->model("datatables");
        $this->datatables->setTable("products");
        $this->datatables->setColumn([
            '<get-kditem>',
            '<get-name>',
            '[rupiah=<get-price>]',
            '[rupiah=<get-priceqq>]',
            '<div class="text-center">
            <button type="button" class="btn btn-sm btn-success btn-add" onclick="'.$addFunc.'"><i class="fa fa-plus"></i></button>
            </div>'
        ]);
        $this->datatables->setOrdering(["kditem","name","price","priceqq",NULL]);
        $this->datatables->setWhere("type","paket");
        $this->datatables->setSearchField(["name","kditem"]);
        $this->datatables->generate();
    }
    public function json_kacafilm() {
        $addFunc = "addSparepartCart({id:<get-id>,kditem:'<get-kditem>',kdjob:'<get-kdjob>',namajob:'<get-namajob>',name:'<get-name>',satuan:'<get-satuankecil>',price:<get-price>,priceqq:<get-priceqq>,stock:<get-stock>,hpp:'<get-coahpp>',jual:'<get-coapenjualan>',sedia:'<get-coapersediaan>',type:'<get-type>'})";
        
        $this->load->model("datatables");
        $this->datatables->setTable("products");
        $this->datatables->setColumn([
            '<get-kditem>',
            '<get-name>',
            '[rupiah=<get-price>]',
            '[rupiah=<get-priceqq>]',
            '<div class="text-center">
                <button type="button" class="btn btn-sm btn-success" onclick="'.$addFunc.'"><i class="fa fa-plus"></i></button>
            </div>'
        ]);
        $this->datatables->setOrdering(["kditem","name","price","priceqq",NULL]);
        $this->datatables->setWhere("type","kacafilm");
        $this->datatables->setSearchField(["name","kditem"]);
       
       
        $this->datatables->generate();
    }

    public function json_jasa() {
        $addFunc = "addSparepartCart({id:<get-id>,kditem:'<get-kditem>',kdjob:'<get-kdjob>',namajob:'<get-namajob>',name:'<get-name>',satuan:'<get-satuankecil>',price:<get-price>,priceqq:<get-priceqq>,stock:<get-stock>,hpp:'<get-coahpp>',jual:'<get-coapenjualan>',sedia:'<get-coapersediaan>',type:'<get-type>'})";
        
        $this->load->model("datatables");
        $this->datatables->setTable("products");
        $this->datatables->setColumn([
            '<get-kditem>',
            '<get-name>',
            '[rupiah=<get-price>]',
            '[rupiah=<get-priceqq>]',
            '<div class="text-center">
                <button type="button" class="btn btn-sm btn-success" onclick="'.$addFunc.'"><i class="fa fa-plus"></i></button>
            </div>'
        ]);
        $this->datatables->setOrdering(["kditem","name","price","priceqq",NULL]);
        $this->datatables->setWhere("type","jasa");
        $this->datatables->setSearchField(["name","kditem"]);
       
       
        $this->datatables->generate();
    }
}


