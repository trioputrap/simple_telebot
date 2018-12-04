<?php
    include "config.php";
    while(true) {
        $sql = "SELECT * FROM inbox WHERE flag = 0 LIMIT 10";
        $qry = $conn->query($sql);

        if($qry->num_rows > 0) {
            while($data = $qry->fetch_object()){
                $sql2 = "SELECT `format` FROM operation WHERE id='".$data->message."'";
                $qry2 = $conn->query($sql2);
                if($qry2->num_rows > 0) {
                    $data2 = $qry2->fetch_object();
                    $reply = $data2->format;
                } else {
                    $messages = explode(" #", $data->message);
                
                    $reply = "";

                    $sql2 = "SELECT `sql` FROM operation WHERE keyword='".$messages[0]."'";
                    
                    $qry2 = $conn->query($sql2);
                    
                    if($qry2->num_rows > 0) {
                        $data2 = $qry2->fetch_object();

                        $sql2 = $data2->sql;

                        foreach($messages as $key => $val) {
                            if($key==0) continue;
                            $sql2 = substr_replace($sql2, $val, strpos($sql2, "?"), 1);
                        }


                        $qry2 = $conn->query($sql2);
                        $num_rows = $qry2->num_rows;
                        if($num_rows > 0) {
                            while($data2 = $qry2->fetch_assoc()){            
                                foreach($data2 as $key => $val){
                                    $reply.=$key."\t: ".$val."\n";
                                }
                                if($num_rows>1) $reply.="\n";
                            }
                        } else {
                            $reply = "Data tidak ditemukan";
                        }
                    } else {
                        $sql2 = "SELECT name  FROM operation";
                        $qry2 = $conn->query($sql2);
                        
                        $i = 0;
                        while($data2 = $qry2->fetch_object()) {
                            $i++;
                            $reply .= $i.".\t".$data2->name."\n";
                        }
                    }
                }


                $sql2 = "INSERT INTO outbox (
                            message, 
                            chat_id, 
                            date_insert, 
                            flag)
                        VALUES (
                            '".urlencode($reply)."', 
                            '".$data->chat_id."', 
                            '".date('Y-m-d H:i:s')."', 
                            0)";
                $qry2 = $conn->query($sql2);
                
                //update flag inbox true
                $sql2 = "UPDATE inbox SET flag=1 WHERE id = ".$data->id;
                $qry2 = $conn->query($sql2);
                echo $reply;
            }
        } else {
            echo "no new message to process...\n";
        }
        sleep(1);
    }