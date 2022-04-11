<?php

    $path_file_migracao = PATH_CURRENT_FILE_TEMP . FILTROCONSULTA . '.sql';
    $fp = fopen($path_file_migracao,'w');

    // Limpa a tabela destino
    fwrite($fp,"TRUNCATE TABLE " . FILTROCONSULTA . ';' . "\n");

    // Seleciona os dados da tabela de origem
    $sql        = "SELECT * FROM " . FILTROCONSULTA . ";";
    $query      = $connProd->query($sql);
    while ($de  = $query->fetch())
    {
        $campos     = array(
            'id',
            'consulta',
            'atributo',
            'operador',
            'legenda'
        );

        $valores    = array(
            $de['id'],
            $de['td_consulta'],
            $de['td_atributo'],
            '"' . $de['operador'] . '"',
            '"' . $de['legenda']  . '"'
        );
        $insert = 'INSERT INTO ' . FILTROCONSULTA . ' ('.implode(',',$campos).') VALUES ('.implode(',',$valores).');' ."\n";
        echo $insert;
        fwrite($fp,$insert);
    }

    $sql            = '
        SELECT 
            a.td_entidade entidade_id,
            (SELECT nome FROM '.ENTIDADE.' e WHERE e.id = a.td_entidade) entidade_nome,
            a.nome atributo_nome,
            a.id atributo_id
        FROM '.FILTROCONSULTA.' c
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
        $linha2 = 'UPDATE ' . FILTROCONSULTA . ' SET atributo = ' . $atributo_para_id . ' WHERE atributo = '.$atributo_de_id.';' . "\n";
        echo $linha1 . $linha2;
        fwrite($fp,$linha1 . $linha2);
    }
    fclose($fp);