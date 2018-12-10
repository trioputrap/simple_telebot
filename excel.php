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

    $base_url = "https://bot.itcc-udayana.com";

    $file = fopen("excel.xlsx","rb");
    
    $sendto =API_URL."sendDocument";
    
    $url = $sendto;

    //The data you want to send via POST
    $fields = [
        'caption' => 'Testing file',
        'chat_id' => 400784474,
        'document' => new \CURLFile(realpath('excel.xlsx')) 
    ];
    
    
    //open connection
    $ch = curl_init();
    
    //set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
    curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, count($fields));
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields);
    
    //So that curl_exec returns the contents of the cURL; rather than echoing it
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
    
    //execute post
    $result = curl_exec($ch);
    echo $result;