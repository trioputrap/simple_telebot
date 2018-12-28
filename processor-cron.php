<?php
    include "config.php";
    $n_sec = 60;
    $sec = 0;
    $starting_time = microtime(true);

    while($sec <= $n_sec){
        $time_start = microtime(true); 

        $sql = "SELECT * FROM inbox WHERE flag = 0 LIMIT 10";
        $qry = $conn->query($sql);
        $rows = array();
        $rows_id = array();
        while($data = $qry->fetch_assoc()){
            $rows[] = $data;
            $rows_id[] = $data['id'];
        }
        $sql2 = "UPDATE inbox SET flag=1 WHERE id in (".join(",",$rows_id).")";
        $qry2 = $conn->query($sql2);

        if($qry->num_rows > 0) {
            foreach($rows as $data){
                $sql2 = "SELECT `format`, `flag_file` FROM operation WHERE id='".$data['message']."'";
                $qry2 = $conn->query($sql2);
                
                if($qry2->num_rows > 0) { // operation id's found
                    $data2 = $qry2->fetch_object();
                    $reply = "";

                    if($data2->flag_file){
                        $sql3 = "SELECT `type`, `keyword` FROM file_type ft
                                INNER JOIN operation_file_type oft ON ft.id = oft.file_type_id
                                WHERE oft.operation_id='".$data['message']."'";
                        $qry3 = $conn->query($sql3);
                         // operation id's found
                        while($data3 = $qry3->fetch_object()){
                            $format = $data2->format;
                            $format = str_replace("#keyword", "#".$data3->keyword, $format);
                            $reply.="<b>".$data3->type."</b>".PHP_EOL;
                            $reply .= "<i>Masukkan perintah berikut:</i>".PHP_EOL;
                            $reply.="<pre>".$format."</pre>".PHP_EOL.PHP_EOL;
                        }
                    } else {
                        $reply = "<i>Masukkan perintah berikut:</i>".PHP_EOL;
                        $reply .= "<pre>".$data2->format."</pre>";
                    }
                } else { // operation id's not found
                    $messages = explode(" #", $data['message']);
                
                    $reply = "";

                    $sql2 = "SELECT `id`,`sql`, `sql_type`, `flag_file`, `filename` FROM operation WHERE keyword='".$messages[0]."'";
                    
                    $qry2 = $conn->query($sql2);

                    if($qry2->num_rows > 0) { // keyword's found
                        $data2 = $qry2->fetch_object();

                        if($data2->flag_file){
                            $sql3 = "SELECT `id` FROM operation_file_type 
                                    WHERE operation_id='". $data2->id ."' 
                                    AND keyword='". $messages[1] ."'";
                            $qry3 = $conn->query($sql3);

                             // operation fil id's found
                            $data3 = $qry3->fetch_object();
                            
                            $flag_file = 1;
                            $oft_id = $data3->id;
                        } else {
                            $sql2 = $data2->sql;
                            foreach($messages as $key => $val) {
                                if($key==0) continue;
                                $sql2 = substr_replace($sql2, $val, strpos($sql2, "?"), 1);
                            }

                            $qry2 = $conn->query($sql2);
                            switch($data2->sql_type){
                                case "insert":
                                    if($qry2){
                                        $reply = "Insert berhasil dilakukan";
                                    } else {
                                        $reply = "Insert gagal dilakukan";
                                    }
                                    break;
                                case "update":
                                    if($qry2){
                                        $reply = "Update berhasil dilakukan";
                                    } else {
                                        $reply = "Update gagal dilakukan";
                                    }
                                    break;
                                case "delete":
                                    if($qry2){
                                        $reply = "Delete berhasil dilakukan";
                                    } else {
                                        $reply = "Delete gagal dilakukan";
                                    }
                                    break;
                                default:
                                    $num_rows = $qry2->num_rows;
                                    if($num_rows > 0) {
                                        while($data2 = $qry2->fetch_assoc()){            
                                            foreach($data2 as $key => $val){
                                                $reply.=$key."\t: ".$val.PHP_EOL;
                                            }
                                            if($num_rows>1) $reply.=PHP_EOL;
                                        }
                                    } else {
                                        $reply = "Data tidak ditemukan";
                                    }
                            }
                        }
                    } else { // keyword's not found
                        $sql2 = "SELECT name  FROM operation";
                        $qry2 = $conn->query($sql2);
                        
                        $reply = "<b>List Operasi</b>".PHP_EOL.PHP_EOL;
                        $i = 0;
                        while($data2 = $qry2->fetch_object()) {
                            $i++;
                            $reply .= $i.".\t".$data2->name.PHP_EOL;
                        }
                    }
                }


                $sql2 = "INSERT INTO outbox (
                            message, 
                            chat_id, 
                            date_insert, 
                            flag,
                            flag_file,
                            operation_file_type_id)
                        VALUES (
                            '".urlencode($reply)."', 
                            '".$data['chat_id']."', 
                            '".date('Y-m-d H:i:s')."', 
                            0,
                            '".((isset($flag_file))?$flag_file:0)."',
                            '".((isset($oft_id))?$oft_id:'')."'
                            )";
                echo $sql2;
                $qry2 = $conn->query($sql2);
                
                echo $reply;
            }
        } else {
            echo "no new message to process...\n";
            sleep(1);
        }

        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);

        //execution time of the script
        echo '10 Qry Execution Time: '.$execution_time." sec\n";
        $sec+=$execution_time;

        if(($time_end - $starting_time) >= $n_sec) break;
    }
    echo 'Total Execution Time: '.$sec." sec\n";