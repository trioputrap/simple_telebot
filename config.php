<?php
    date_default_timezone_set('Asia/Jakarta');
    define('BOT_TOKEN', '716246309:AAHFvIEolHqU9lnJ18T7NcvIsTr4FVYzj7g');
    define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

    $host = "localhost";
    $user = "root";
    $pass = "newpass";
    $db = "hmti_bot";

    $conn = new mysqli($host, $user, $pass, $db);