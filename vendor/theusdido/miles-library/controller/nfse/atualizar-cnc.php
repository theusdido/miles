<?php

    $sql = "
        SELECT 
            b.tomacnpj
        FROM td_erp_nfse_envio_log a
        LEFT JOIN td_erp_nfse_tomador b ON b.nfse = a.nfse
        WHERE error_code = 'E0120'
        GROUP BY b.tomacnpj;
    ";

    $query = $conn->query($sql);
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

        // Armazena o tomador no CNC
        $cnc = tdc::p('td_erp_nfse_tomador_cnc');
        $cnc->documento = $row['tomacnpj'];
        $cnc->armazenar();

    }