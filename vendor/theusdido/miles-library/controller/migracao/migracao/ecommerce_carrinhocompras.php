<?php

    $path_file_migracao = PATH_CURRENT_FILE_TEMP . 'ecommerce_carrinhocompras.sql';
    $fp                 = fopen($path_file_migracao,'w');

    // Limpa a tabela destino
    fwrite($fp,'TRUNCATE TABLE td_ecommerce_carrinhocompras;' . "\n");

    // Seleciona os dados da tabela de origem
    $sql        = "SELECT * FROM td_ecommerce_carrinhocompras;";
    $query      = $connProd->query($sql);
    while ($de  = $query->fetch())
    {
        $campos     = array(
            'id',
            'cliente',
            'datahoracriacao',
            'sessionid',
            'representante',
            'isrepresentante',
            'inativo',
            'valortotal',
            'qtdetotalitens',
            'datahoraultimoacesso',
            'valorfrete',
            'transportadora'
        );

        $valores    = array(
            $de['id'],
            ($de['td_cliente']==''?'NULL':$de['td_cliente']),
            '"' . $de['datahoracriacao'] . '"',
            '"' . $de['sessionid'] . '"',
            'NULL',
            'NULL',
            ($de['inativo']==''?'NULL':$de['inativo']),
            ($de['valortotal']==''?0:$de['valortotal']),
            ($de['qtdetotalitens']==''?0:$de['qtdetotalitens']),
            (($de['datahoraultimoacesso']==''||$de['datahoraultimoacesso']=='0000-00-00 00:00:00')?'"' . $de['datahoraultimoacesso'] . '"':'NULL'),
            ($de['valorfrete']==''?0:$de['valorfrete']),
            ($de['td_transportadora']==''?'NULL':$de['td_transportadora'])
        );
        $insert = 'INSERT INTO td_ecommerce_carrinhocompras ('.implode(',',$campos).') VALUES ('.implode(',',$valores).');' ."\n";
        echo $insert;
        fwrite($fp,$insert);
    }
    fclose($fp);