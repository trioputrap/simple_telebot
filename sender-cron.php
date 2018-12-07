<?php
    include "config.php";
    define('BOT_TOKEN', '761669654:AAEflIkOxaOTeRlaUZdSnmXqzYdEI-NTSfA');
    define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

    $sql = "SELECT * FROM outbox WHERE flag = 0 LIMIT 10";
    $qry = $conn->query($sql);
    if($qry->num_rows) {
        while($data = $qry->fetch_object()){
            $sendto =API_URL."sendmessage?chat_id=".$data->chat_id."&text=".$data->message;
            file_get_contents($sendto);
            echo "Message was sent to ".$data->chat_id."\n";
            
            $sql2 = "UPDATE outbox SET flag=1, date_sent='".date('Y-m-d H:i:s')."' WHERE id = ".$data->id;
            $qry2 = $conn->query($sql2);
        }
    } else {
        echo "No new message to sent..\n";
    }