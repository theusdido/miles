<?php

    $entidade_nome      = RELATORIO;
    $path_file_migracao = PATH_CURRENT_FILE_TEMP . $entidade_nome . '.sql';
    $fp = fopen($path_file_migracao,'w');

    // Limpa a tabela destino
    fwrite($fp,"TRUNCATE TABLE " . $entidade_nome . ';' . "\n");

    // Seleciona os dados da tabela de origem
    $sql        = "SELECT * FROM " . $entidade_nome . ";";
    $query      = $connProd->query($sql);
    while ($de  = $query->fetch())
    {
        $campos     = array(
            'id',
            'descricao',
            'entidade',
            'urlpersonalizada'
        );

        $valores    = array(
            $de['id'],
            '"' . utf8charset($de['descricao'],5) . '"',
            $de['td_entidade'],
            '"' . $de['urlpersonalizada'] . '"',
        );
        $insert = 'INSERT INTO ' . $entidade_nome . ' ('.implode(',',$campos).') VALUES ('.implode(',',$valores).');' ."\n";
        echo $insert;
        fwrite($fp,$insert);
    }

    $sql            = '
        SELECT 
            c.td_entidade entidade_id,
            (SELECT e.nome FROM '. ENTIDADE .' e WHERE e.id = c.td_entidade) as entidade_nome
        FROM '. $entidade_nome. ' c;
    ';
    $query          = $connProd->query($sql);
    while ($linha   = $query->fetch())
    {
        $entidade_de_id             = $linha['entidade_id'];
        $entidade_de_nome           = $linha['entidade_nome'];
        $entidade_para_id           = getEntidadeId($entidade_de_nome);

        $linha1 = '# DE: (' . $entidade_de_id . ')[' . $entidade_de_nome . '] => PARA: ' . $entidade_para_id . "\n";
        $linha2 = 'UPDATE ' . $entidade_nome . ' SET entidade = ' . $entidade_para_id . ' WHERE entidade = '.$entidade_de_id.';' . "\n";
        echo $linha1 . $linha2;
        fwrite($fp,$linha1 . $linha2);
    }
    fclose($fp);