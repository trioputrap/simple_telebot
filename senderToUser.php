<?php
    include "config.php";
    
    $sql = "SELECT chat_id FROM inbox GROUP BY chat_id";
    $qry = $conn->query($sql);

    while($data = $qry->fetch_object()){
        $sendto =API_URL."sendmessage?chat_id=".$data->chat_id."&text=Font bot sudah diperbarui, silahkan chat kembali :)";
        file_get_contents($sendto);
        echo "Message was sent to ".$data->chat_id."\n";
    }