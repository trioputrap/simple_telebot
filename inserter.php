<?php
    include "config.php";
    $chatIds = [572335681,400784474,354036146,258261955];

    function getOperations(){
        $nama = ["Trio", "Wira", "Bayu", "Krisna", "Savita", "Pandika"];
        $prodi = ["Teknologi Informasi", "Teknik Mesin", "Teknik Elektro", "Teknik Arsitektur", "Teknik Sipil"];
        $operations = [
            "cari dosen #".$nama[rand(0,5)]." #".$prodi[rand(0,4)],
            "cari mahasiswa #".$nama[rand(0,5)]." #".$prodi[rand(0,4)],
            "lihat semua dosen", 
            "lihat semua mahasiswa"
        ];
        return $operations[rand(0,3)];
    }

    while(true){
        $sql = "INSERT INTO inbox (id, message, chat_id, date) VALUES";
        for($i=0; $i<10; $i++){
            if($i) $sql.=",";
            $sql.=" (NULL, '".getOperations()."', '".$chatIds[rand(0,3)]."', '".date('Y-m-d H:i:s')."')";
        }
        $sql.=";";
        $conn->query($sql);
        echo $sql."\n";
        sleep(5);
    }
