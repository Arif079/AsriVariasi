<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use OpenSpout\Writer\Common\Creator\WriterEntityFactory;
use OpenSpout\Writer\Common\Creator\Style\StyleBuilder;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Cell;

class Excel_exports extends CI_Controller {

    function __construct() {
        parent::__construct();

        if (!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        $this->load->model("user_model");
        $this->load->model("excel_export_model");

        $this->dataAdmin = $this->user_model->get(["id" => $this->session->auth['id']])->row();
    }

    function action() {
        try {
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            $selectedCOA = json_decode($this->input->post('selectedCOA'));

            if (empty($start)) {
                $start = date('Y-m-01'); // First day of current month
            }
            if (empty($end)) {
                $end = date('Y-m-t'); // Last day of current month
            }

            $writer = WriterEntityFactory::createXLSXWriter();
            $writer->openToFile('php://output');  // Ensure output is sent to browser

            // Set HTTP headers for download
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="ujicobabukubesar.xlsx"');
            header('Cache-Control: max-age=0');
            header('Expires: 0');
            header('Pragma: public');

            // Title and Date Range
            $titleStyle = (new StyleBuilder())
                ->setFontBold()
                ->setFontSize(16)
                ->setFontColor('FFFFFF')
                ->setBackgroundColor('4F81BD')
                ->setShouldWrapText(false)
                ->build();

            $writer->addRow(WriterEntityFactory::createRow([
                WriterEntityFactory::createCell('BUKU BESAR')
            ], $titleStyle));

            $writer->addRow(WriterEntityFactory::createRow([
                WriterEntityFactory::createCell(date('M Y', strtotime($start)) . ' - ' . date('M Y', strtotime($end)))
            ], $titleStyle));

            $this->generateReport($writer, $start, $end, $selectedCOA);

            $writer->close();
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            show_error($e->getMessage());
        }
    }

    private function generateReport($writer, $start, $end, $selectedCOA) {
        foreach ($selectedCOA as $coa_value) {
            $coa_details = $this->excel_export_model->get_coa($coa_value); // Implement this method in your model

            $writer->addRow(WriterEntityFactory::createRow([
                WriterEntityFactory::createCell('COA : ' . $coa_details->coa . '-' . $coa_details->nama)
            ]));

            $this->setTableColumns($writer);

            $data_transaksi = $this->excel_export_model->get_jurnal($coa_value, $start, $end);

            $total_debet = 0;
            $total_kredit = 0;
            $saldo = 0;

            foreach ($data_transaksi as $data) {
                $row = [
                    WriterEntityFactory::createCell($data->kdjurnal),
                    WriterEntityFactory::createCell($data->kdbukti),
                    WriterEntityFactory::createCell(date('d M Y', strtotime($data->date))),
                    WriterEntityFactory::createCell($data->uraian),
                    WriterEntityFactory::createCell($data->coalawan),
                    WriterEntityFactory::createCell(''),
                    WriterEntityFactory::createCell($data->debet),
                    WriterEntityFactory::createCell($data->kredit)
                ];

                $total_debet += $data->debet;
                $total_kredit += $data->kredit;
                $saldo = $total_debet - $total_kredit;

                $row[] = WriterEntityFactory::createCell($saldo);
                $writer->addRow(WriterEntityFactory::createRow($row));
            }

            $writer->addRow(WriterEntityFactory::createRow([
                WriterEntityFactory::createCell(''),
                WriterEntityFactory::createCell(''),
                WriterEntityFactory::createCell(''),
                WriterEntityFactory::createCell('Total'),
                WriterEntityFactory::createCell(''),
                WriterEntityFactory::createCell(''),
                WriterEntityFactory::createCell($total_debet),
                WriterEntityFactory::createCell($total_kredit)
            ]));

            $writer->addRow(WriterEntityFactory::createRow([
                WriterEntityFactory::createCell(''),
                WriterEntityFactory::createCell(''),
                WriterEntityFactory::createCell(''),
                WriterEntityFactory::createCell(''),
                WriterEntityFactory::createCell(''),
                WriterEntityFactory::createCell($saldo)
            ]));
        }
    }

    private function setTableColumns($writer) {
        $table_columns = ["KODE JURNAL","KODE TRANSAKSI","DATE","URAIAN", "COA LAWAN","SALDO AWAL", "DEBIT", "KREDIT", "SALDO AKHIR"];
        $row = [];
        foreach ($table_columns as $field) {
            $row[] = WriterEntityFactory::createCell($field);
        }
        $writer->addRow(WriterEntityFactory::createRow($row));
    }

    function neraca() {
        try {
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            $selectedCOA = json_decode($this->input->post('selectedCOA'));

            if (empty($start)) {
                $start = date('Y-m-01'); // First day of current month
            }
            if (empty($end)) {
                $end = date('Y-m-t'); // Last day of current month
            }

            $writer = WriterEntityFactory::createXLSXWriter();
            $writer->openToFile('php://output');  // Ensure output is sent to browser

            // Set HTTP headers for download
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="ujicobaneraca.xlsx"');
            header('Cache-Control: max-age=0');
            header('Expires: 0');
            header('Pragma: public');

            // Title and Date Range
            $titleStyle = (new StyleBuilder())
                ->setFontBold()
                ->setFontSize(16)
                ->setFontColor('FFFFFF')
                ->setBackgroundColor('4F81BD')
                ->setShouldWrapText(false)
                ->build();

            $writer->addRow(WriterEntityFactory::createRow([
                WriterEntityFactory::createCell('NERACA SALDO')
            ], $titleStyle));

            $writer->addRow(WriterEntityFactory::createRow([
                WriterEntityFactory::createCell(date('M Y', strtotime($start)) . ' - ' . date('M Y', strtotime($end)))
            ], $titleStyle));

            // Add a blank row for spacing
            $writer->addRow(WriterEntityFactory::createRow([WriterEntityFactory::createCell('')]));

            $this->generateNewReport($writer, $start, $end,$selectedCOA);

            $writer->close();
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            show_error($e->getMessage());
        }
    }

    private function generateNewReport($writer, $start, $end, $selectedCOA) {
        $total_saldo_awal = 0;
        $total_debet = 0;
        $total_kredit = 0;
        $total_saldo_akhir = 0;
    
        // Set table columns
        $this->setNewTableColumns($writer);
    
        foreach ($selectedCOA as $coa_value) {
            // Fetch aggregated data for each COA
            $coa_details = $this->excel_export_model->get_coa($coa_value);
            $grouped_data = $this->excel_export_model->get_neraca($coa_value, $start, $end);
    
            // Initialize default values for the case where no data is returned
            $saldo_awal = 0;
            $total_debet_transaksi = 0;
            $total_kredit_transaksi = 0;
    
            // Check if grouped data is not null and is an array
            if (!empty($grouped_data) && is_array($grouped_data)) {
                foreach ($grouped_data as $data) {
                    // Extract the aggregated sums
                  // Keep values as float
                $saldo_awal = (float)$data->saldo_awal;
                $total_debet_transaksi = (float)$data->total_debet;
                $total_kredit_transaksi = (float)$data->total_kredit;
                }
            }
    
            
            // Calculate saldo akhir without rounding
            $saldo_akhir = $saldo_awal + $total_debet_transaksi - $total_kredit_transaksi;
    
            // Create the row
            $row = [
                WriterEntityFactory::createCell($coa_details->coa),
                WriterEntityFactory::createCell($coa_details->nama),
                WriterEntityFactory::createCell($saldo_awal),
                WriterEntityFactory::createCell($total_debet_transaksi),
                WriterEntityFactory::createCell($total_kredit_transaksi),
                WriterEntityFactory::createCell($saldo_akhir)
            ];
    
            // Add the row to the report
            $writer->addRow(WriterEntityFactory::createRow($row));
    
            // Accumulate totals
            $total_saldo_awal += $saldo_awal;
            $total_debet += $total_debet_transaksi;
            $total_kredit += $total_kredit_transaksi;
            $total_saldo_akhir += $saldo_akhir;
        }
    
        // Add totals row at the end of the report
        $writer->addRow(WriterEntityFactory::createRow([
            WriterEntityFactory::createCell(''),
            WriterEntityFactory::createCell('Total'),
            WriterEntityFactory::createCell($total_saldo_awal),
            WriterEntityFactory::createCell($total_debet),
            WriterEntityFactory::createCell($total_kredit),
            WriterEntityFactory::createCell($total_saldo_akhir)
        ]));
    }
private function setNewTableColumns($writer) {
    $table_columns = ["KODE COA","NAMA COA","SALDO AWAL", "DEBIT", "KREDIT", "SALDO AKHIR"];
    $row = [];
    foreach ($table_columns as $field) {
        $row[] = WriterEntityFactory::createCell($field);
    }
    $writer->addRow(WriterEntityFactory::createRow($row));
}

}