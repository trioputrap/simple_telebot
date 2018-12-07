<?php
    require 'vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    define('BOT_TOKEN', '761669654:AAEflIkOxaOTeRlaUZdSnmXqzYdEI-NTSfA');
    define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'Hello World !');
    $sheet->setCellValue('A2', 'Hello World !');

    $writer = new Xlsx($spreadsheet);
    $writer->save('excel.xlsx');

    $base_url = "http://itccbot.herokuapp.com/";

    
    $sendto =API_URL."sendmessage?chat_id=400784474&document=".$base_url."/excel.xlsx&caption=excel";
    
    file_get_contents($sendto);
    echo $sendto;
    echo "\nMessage was sent";