<?php
    $rps_inicial    = 74203;
    $rps_final      = 74992;
    $id             = 1;

    for($i = $rps_inicial; $i <= $rps_final; $i++) {
        echo "UPDATE td_erp_nfse_nota SET rpsnumero = {$i} WHERE id = {$id}; </br>";
        $id++;
    }