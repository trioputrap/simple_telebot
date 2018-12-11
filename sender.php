<?php
    include "config.php";
    include "ExcelFileHelper.php";
    
    // send reply
    while(true){
        $time_start = microtime(true); 

        $sql = "SELECT * FROM outbox WHERE flag = 0 LIMIT 10";
        $qry = $conn->query($sql);
        $rows = array();
        $rows_id = array();
        while($data = $qry->fetch_assoc()){
            $rows[] = $data;
            $rows_id[] = $data['id'];
        }
        $sql2 = "UPDATE outbox SET flag=1 WHERE id in (".join(",",$rows_id).")";
        $qry2 = $conn->query($sql2);

        if($qry->num_rows) {
            foreach($rows as $data){
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
                $sql2 = "UPDATE outbox SET date_sent='".date('Y-m-d H:i:s')."' WHERE id = ".$data['id'];
                $qry2 = $conn->query($sql2);
            }
        } else {
            echo "No new message to sent..\n";
        }
        
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);

        //execution time of the script
        echo '10 Qry Execution Time: '.$execution_time." sec\n";
    }