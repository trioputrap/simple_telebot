<?php
    require 'vendor/autoload.php';
    require 'DownloadableFile.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

    class ExcelFileHelper extends DownloadableFile {
        private $spreadsheet;

        public function __construct(){
            parent::__construct("", "xlsx");
        }

        public function createSpreadsheet($data){
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $keys = array_keys($data[0]);
            
            foreach($keys as $key=>$val){
                $col = chr(ord('A') + $key);
                $cell = $col . '1';
                $sheet->getColumnDimension($col)->setAutoSize(true);
                $sheet->setCellValue($cell, $val);
            }

            foreach($data as $key => $val){
                print_r($key);
                $col_n=0;
                foreach($val as $key2 => $val2){
                    $cell = chr(ord('A') + $col_n) . ($key + 2);
                    $sheet->setCellValue($cell, ucfirst($val2));
                    $col_n++;
                }
            }
            $this->spreadsheet = $spreadsheet;
            return $spreadsheet;
        }

        public function exportToPdf($data){
            $spreadsheet = $this->createSpreadsheet($data);
            $writer = new Mpdf($spreadsheet);
            $writer->save($this->getDirFile("pdf"));
            return $this->getDirFile("pdf");
        }

        public function create($data){
            $spreadsheet = $this->createSpreadsheet($data);
        
            $writer = new Xlsx($spreadsheet);
            $writer->save($this->getDirFile());
            return $this->getDirFile();
        }
    }