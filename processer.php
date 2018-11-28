<?php
    include "config.php";

    // send reply
    while(true){
        $sql = "SELECT * FROM inbox WHERE flag = 0";

        if($qry = $conn->query($sql)) {
            while($data = $qry->fetch_object()){
                
                //PROCESSING

                $sql2 = "INSERT INTO outbox (
                            id, 
                            message, 
                            chat_id, 
                            date_insert, 
                            flag)
                        VALUES (
                            '".$data->id."',
                            '".$data->message."', 
                            '".$data->chat_id."', 
                            NOW(), 
                            0)";
                echo $sql2;
                $qry2 = $conn->query($sql2);
                
                //update flag inbox true
                $sql2 = "UPDATE inbox SET flag=1 WHERE id = ".$data->id;
                echo $sql2;
                $qry2 = $conn->query($sql2);
            }
        } else {
            echo "no new message to process...";
        }
        sleep(5);
    }