<?php

    $path_file_migracao = PATH_CURRENT_FILE_TEMP . 'ecommerce_bairro.sql';
    $fp                 = fopen($path_file_migracao,'w');

    // Limpa a tabela destino
    fwrite($fp,'TRUNCATE TABLE td_ecommerce_bairro;' . "\n");

    // Seleciona os dados da tabela de origem
    $sql        = "SELECT * FROM td_ecommerce_bairro;";
    $query      = $connProd->query($sql);
    while ($de  = $query->fetch())
    {
        $campos     = array(
            'id',
            'projeto',
            'empresa',
            'inativo',
            'nome',
            'cidade'
        );

        $valores    = array(
            $de['id'],
            ($de['td_projeto']==''?'NULL':$de['td_projeto']),
            ($de['td_empresa']==''?'NULL':$de['td_empresa']),
            ($de['inativo']==''?'NULL':$de['inativo']),
            '"'. utf8charset($de['nome'],$charset) . '"',
            ($de['td_cidade']==''?0:$de['td_cidade'])
        );
        $insert = 'INSERT INTO td_ecommerce_bairro ('.implode(',',$campos).') VALUES ('.implode(',',$valores).');' ."\n";
        echo $insert . "\n<br/>";
        fwrite($fp,$insert);
    }
    fclose($fp);