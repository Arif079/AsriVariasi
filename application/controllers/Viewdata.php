<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Viewdata extends CI_Controller {
    private $dataAdmin;

    function __construct() {
        parent::__construct();

        if(!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");
        $this->load->model("hpp_model");
        $this->load->model("jurnal_model");

        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }


	public function index()
	{

        $push = [
            "pageTitle" => "Data View",
            "dataAdmin" => $this->dataAdmin 
        ];

		$this->load->view('header',$push);
		$this->load->view('viewport',$push);
		$this->load->view('footer',$push);
    }
    
    public function json() {
        $this->load->model("datatables");
        $this->datatables->setSelect("converthpp.*,(converthpp.dpp+converthpp.ppn) as grandtotal");
        $this->datatables->setTable("converthpp");
        $this->datatables->setColumn([
            '<index>',
            '<get-kdhpp>',
            '<get-tgltransaksi>',
            '<get-tipe>',
            '<get-kdtransaksi>',
            '<get-kdreferensi>',
            '<get-kditem>',
            '<get-qty>',
            '<get-qtyecer>',
            '[rupiah=<get-ppn>]',
            '[rupiah=<get-dpp>]',
            '[rupiah=<get-dppecer>]',
            '[rupiah=<get-grandtotal>]',
            '[rupiah=<get-hpp>]',
            '[rupiah=<get-hppecer>]',
                                
        ]);
        $this->datatables->setOrdering(["kdhpp","tgltransaksi","tipe","kdtransaksi","kdreferensi","kditem","qty","qtyecer","ppn","dpp","dppecer","grandtotal","hpp","hppecer"]);
        $this->datatables->setSearchField(["tgltransaksi","kdtransaksi","kdreferensi","kditem"]);
        $this->datatables->generate();
    }

    public function jsonjurnal() {
        $this->load->model("datatables");
        $this->datatables->setTable("jurnalumum");
        $this->datatables->setColumn([
            '<index>',
            '<get-kdjurnal>',
            '<get-date>',
            '<get-kdtransaksi>',
            '<get-tipe>',
            '<get-coa>',
            '[rupiah=<get-debet>]',
            '[rupiah=<get-kredit>]',
            '<get-status>'
        ]);
        $this->datatables->setOrdering(["id","kdjurnal","date","kdtransaksi","tipe","coa","debet","kredit","status",NULL]);
        $this->datatables->setSearchField(["date","kdtransaksi","coa"]);
        $this->datatables->generate();
    }


}