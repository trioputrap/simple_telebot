<?php
    require 'vendor/autoload.php';
    require 'DownloadableFile.php';
    require 'ExcelFileHelper.php';

    use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

    class ExcelFileHelper extends DownloadableFile {
        private $excelHelper;

        public function __construct(){
            parent::__construct("", "pdf");
        }

        public function create($excelHelper){
            $writer = new Mpdf($excelHelper->createSpreadsheet());
        }
    }