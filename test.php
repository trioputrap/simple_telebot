<?php
    include "config.php";
    require 'ExcelFileHelper.php';
    define('BOT_TOKEN', '761669654:AAEflIkOxaOTeRlaUZdSnmXqzYdEI-NTSfA');
    define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

    $sql = "SELECT * FROM mahasiswa";
    $qry = $conn->query($sql);
    $rows = array();
    while($data = $qry->fetch_assoc()){
        $rows[]=$data;
    }

    $helper = new ExcelFileHelper();

    $helper->create($rows);
    
    $realpath = realpath('download/'.$helper->getFilename().".xlsx");
    echo $realpath;

    $fields = [
        'caption' => 'File',
        'chat_id' => 400784474,
        'document' => new \CURLFile($realpath) 
    ];
    //echo $helper->send(API_URL."sendDocument", $fields);

    $url = API_URL."sendDocument";
    echo $url;

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
    