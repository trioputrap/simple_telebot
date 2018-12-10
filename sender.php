<?php
    include "config.php";
    include "ExcelFileHelper.php";
    define('BOT_TOKEN', '761669654:AAEflIkOxaOTeRlaUZdSnmXqzYdEI-NTSfA');
    define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

    // send reply
    while(true){
        $sql = "SELECT * FROM outbox WHERE flag = 0 LIMIT 10";
        $qry = $conn->query($sql);
        if($qry->num_rows) {
            while($data = $qry->fetch_assoc()){
                if($data['flag_file']){
                    $sql2 = "SELECT `file_type_id`, `sql`, `filename` FROM operation_file_type oft 
                    INNER JOIN operation o
                    ON oft.operation_id = o.id
                    WHERE oft.id='".$data['operation_file_type_id']."'";
                    $qry2 = $conn->query($sql2);
                    $data2 = $qry2->fetch_object();

                    $sql3 = $data2->sql;
                    $qry3 = $conn->query($sql3);
                    $rows=array();
                    while($data3 = $qry3->fetch_assoc()){
                        $rows[]=$data3;
                    }
                    
                    $helper = new ExcelFileHelper();
                    switch($data2->file_type_id){
                        case 1:
                            $url = $helper->exportToPdf($rows);
                            $ext = ".pdf";
                            break;
                        case 2:
                            $url = $helper->create($rows);
                            $ext = ".xlsx";
                            break;
                    }


                    $document = new \CURLFile(realpath($url));
                    $document->setPostFilename($data2->filename.$ext);
                    $fields = [
                        'caption' => 'File',
                        'chat_id' => $data['chat_id'],
                        'document' => $document
                    ];
                    $helper->send(API_URL."sendDocument", $fields);

                    echo "File was sent to ".$data['chat_id']."\n";
                } else {
                    $sendto =API_URL."sendmessage?chat_id=".$data['chat_id']."&text=".$data['message'];
                    file_get_contents($sendto);
                    echo "Message was sent to ".$data['chat_id']."\n";
                }
                
                $sql2 = "UPDATE outbox SET flag=1, date_sent='".date('Y-m-d H:i:s')."' WHERE id = ".$data['id'];
                $qry2 = $conn->query($sql2);
            }
        } else {
            echo "No new message to sent..\n";
        }
        sleep(1);
    }