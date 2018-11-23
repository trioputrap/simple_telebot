<?php
    include "config.php";
    define('BOT_TOKEN', '761669654:AAEflIkOxaOTeRlaUZdSnmXqzYdEI-NTSfA');
    define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
    	
    // read incoming info and grab the chatID
    $content = file_get_contents("php://input");
    $update = json_decode($content, true);
    $chatID = $update["message"]["chat"]["id"];
    $messageText = $update["message"]["text"];

    $sql = "INSERT INTO inbox (id, message, chat_id, date)
            VALUES (NULL, '".$messageText."', '".$chatID."', CURDATE())";
    $conn->query($sql);

    // compose reply
    $reply =  $messageText;
    		
    // send reply
    $sendto =API_URL."sendmessage?chat_id=".$chatID."&text=".$reply;
    file_get_contents($sendto);
    
    function sendMessage(){
        $message = "I am a baby bot.";
        return $message;
    }
    
    checkJSON($chatID,$update);

	function checkJSON($chatID,$update){
	
		$myFile = "log.txt";
		$updateArray = print_r($update,TRUE);
		$fh = fopen($myFile, 'a') or die("can't open file");
		fwrite($fh, $chatID ."\n\n");
		fwrite($fh, $updateArray."\n\n");
		fclose($fh);
	}