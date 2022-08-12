<?php

    require "../config/config.default.php";

    $level     = $_GET['level'];
    $parent_id     = $_GET['parent_id'];

    $result;

    if($level == 'provinsi'){
        $query = mysqli_query($koneksi, "SELECT * FROM wilayahs WHERE level_label='" . $level . "'");
    }else{
        $query = mysqli_query($koneksi, "SELECT * FROM wilayahs WHERE level_label='" . $level . "' AND parent_id=" . $parent_id) ;
    }

    while($row = $query->fetch_assoc()) {
        $result[] = $row;
    }
    echo json_encode($result);
?>