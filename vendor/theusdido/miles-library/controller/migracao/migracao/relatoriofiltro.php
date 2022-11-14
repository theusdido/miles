<?php

    $entidade_nome      = FILTRORELATORIO;
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
            'relatorio',
            'atributo',
            'operador',
            'legenda'
        );

        $valores    = array(
            $de['id'],
            $de['td_relatorio'],
            $de['td_atributo'],
            '"' . $de['operador'] . '"',
            '"' . $de['legenda']  . '"'
        );
        $insert = 'INSERT INTO ' . $entidade_nome . ' ('.implode(',',$campos).') VALUES ('.implode(',',$valores).');' ."\n";
        echo $insert;
        fwrite($fp,$insert);
    }

    $sql            = '
        SELECT 
            a.td_entidade entidade_id,
            (SELECT nome FROM '.ENTIDADE.' e WHERE e.id = a.td_entidade) entidade_nome,
            a.nome atributo_nome,
            a.id atributo_id
        FROM '.$entidade_nome.' c
        INNER JOIN '.ATRIBUTO.' a ON (a.id = c.td_atributo);
    ';
    $query          = $connProd->query($sql);
    while ($linha   = $query->fetch())
    {
        $entidade_de_id             = $linha['entidade_id'];
        $entidade_de_nome           = $linha['entidade_nome'];
        $atributo_de_id             = $linha['atributo_id'];
        $atributo_de_nome           = str_replace('td_','',$linha['atributo_nome']);
        $atributo_para_id           = getAtributoId($entidade_de_nome,$atributo_de_nome);

        $linha1 = '# DE: ['.$entidade_de_nome.'](' . $atributo_de_id . ' -> '. $atributo_de_nome . ')[' . $atributo_de_nome . '] => PARA: ' . $atributo_para_id . "\n";
        $linha2 = 'UPDATE ' . $entidade_nome . ' SET atributo = ' . $atributo_para_id . ' WHERE atributo = '.$atributo_de_id.';' . "\n";
        echo $linha1 . $linha2;
        fwrite($fp,$linha1 . $linha2);
    }
    fclose($fp);