<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


class Excel {

    private $spreadsheet;
    private $sheet;

    public function __construct() {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
    }

    public function load($filePath) {
        $this->spreadsheet = IOFactory::load($filePath);
        $this->sheet = $this->spreadsheet->getActiveSheet();
    }

    public function setActiveSheet($index) {
        $this->sheet = $this->spreadsheet->setActiveSheetIndex($index);
    }

    public function getActiveSheet() {
        return $this->sheet;
    }

    public function save($filename) {
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($filename);
    }

    public function output($filename) {
        if (ob_get_length()) { // Check if output buffering is active
            ob_end_clean(); // Clean the buffer to ensure no previous output
        }

        $writer = new Xlsx($this->spreadsheet);
        
        // Ensure no prior output
        if (!headers_sent()) {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"{$filename}\"");
            header('Cache-Control: max-age=0');
            
            $writer->save('php://output');
            exit(); // Ensure the script stops after outputting the file
        } else {
            throw new Exception('Headers have already been sent.');
        }
    }
    
    // Additional methods to interact with the spreadsheet
    public function setCellValue($cell, $value) {
        $this->sheet->setCellValue($cell, $value);
    }

    public function setCellValueByColumnAndRow($column, $row, $value) {
        $this->sheet->setCellValueByColumnAndRow($column, $row, $value);
    }

    public function mergeCells($range) {
        $this->sheet->mergeCells($range);
    }

    public function applyStyle($range, $styleArray) {
        $this->sheet->getStyle($range)->applyFromArray($styleArray);
    }
}
