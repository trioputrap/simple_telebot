<?php
    include "config.php";
    define('BOT_TOKEN', '761669654:AAEflIkOxaOTeRlaUZdSnmXqzYdEI-NTSfA');
    define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

    // send reply
    while(true){
        $sql = "SELECT * FROM outbox WHERE flag = 0";

        if($qry = $conn->query($sql)) {
            while($data = $qry->fetch_object()){
                $sendto =API_URL."sendmessage?chat_id=".$data->chat_id."&text=".$data->message;
                file_get_contents($sendto);
                echo "message was sent to ".$data->chat_id."\n";
                
                $sql2 = "UPDATE outbox SET flag=1 WHERE id = ".$data->id;
                echo $sql2;
                $qry2 = $conn->query($sql2);
            }
        } else {
            echo "no new message to sent..";
        }
        sleep(5);
    }