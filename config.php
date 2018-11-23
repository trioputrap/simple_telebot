<?php
    $host = "sql12.freemysqlhosting.net";
    $user = "sql12263773";
    $pass = "bCMI9sxD86";
    $db = "sql12263773";

    $conn = new mysqli($host, $user, $pass, $db);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }