<?php
    include "config.php";
    define('BOT_TOKEN', '505934618:AAE_DeMhq1ztYF6R-NAhvsYcpn4AvxfTZ2g');
    define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

    // send reply
    while(true){
        $sql = "SELECT * FROM outbox WHERE flag_sent = 0";
        $qry = $conn->query($sql);

        if($qry) {
            while($data = $qry->fetch_object()){
                $sendto =API_URL."sendmessage?chat_id=".$data->chat_id."&text=".$data->message;
                file_get_contents($sendto);
                echo "message was sent to ".$data->chat_id."\n";
                
                $sql = "UPDATE outbox SET flag_sent=1 WHERE id = ".$data->id;
                $qry = $conn->query($sql);
            }
        } else {
            echo "no new message to reply.";
        }
        sleep(5);
    }