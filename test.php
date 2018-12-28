<?php
   include "config.php";

   if ($conn->connect_error){
       $bussy_msg = "Please wait, will reply in a few moments";
       $sendto =API_URL."sendmessage?chat_id=".$chatID."&text=".$bussy_msg;
       
       while ($conn->connect_error) {
               echo("Connection failed: " . $conn->connect_error);
               echo("Retrying in 10 secs .. .");
               sleep(10);
               $conn = new mysqli($host, $user, $pass, $db);
       }
   }