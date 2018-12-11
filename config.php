<?php
    date_default_timezone_set('Asia/Jakarta');
    define('BOT_TOKEN', '761669654:AAEflIkOxaOTeRlaUZdSnmXqzYdEI-NTSfA');
    define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

    $host = "itcc-udayana.com";
    $user = "itccuday_itccbot";
    $pass = "12341234";
    $db = "itccuday_itccbot";

    $conn = new mysqli($host, $user, $pass, $db);