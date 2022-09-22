<?php

    $path_file_migracao = PATH_CURRENT_FILE_TEMP . LISTA . '.sql';
    $fp = fopen($path_file_migracao,'w');

    // Limpa a tabela destino
    fwrite($fp,"TRUNCATE TABLE " . LISTA . ';' . "\n");

    // Seleciona os dados da tabela de origem
    $sql        = "SELECT * FROM " . LISTA . ";";
    $query      = $connProd->query($sql);
    while ($de  = $query->fetch())
    {
        $campos     = array('id'        ,'entidadepai'      ,'entidadefilho'        ,'regpai'       ,'regfilho'         );
        $valores    = array($de['id']   ,$de['entidadepai'] ,$de['entidadefilho']   ,$de['regpai']  ,$de['regfilho']    );
        fwrite($fp,'INSERT INTO ' . LISTA . ' ('.implode(',',$campos).') VALUES ('.implode(',',$valores).');' ."\n");
    }

    $sql = '
        SELECT 
            l.entidadepai,l.entidadefilho,
            (SELECT e.nome FROM '.ENTIDADE.' e WHERE e.id = l.entidadepai) as pai,
            (SELECT e.nome FROM '.ENTIDADE.' e WHERE e.id = l.entidadefilho) as filho
        FROM '.LISTA.' l
        GROUP by l.entidadepai,l.entidadefilho;
    ';
    $query          = $connProd->query($sql);
    while ($linha   = $query->fetch())
    {
        $entidade_pai       = getEntidadeId($linha['pai']);
        $entidade_filho     = getEntidadeId($linha['filho']);

        $linha1 = '# ' . $linha['pai'] . '=>' . $entidade_pai . "\n";
        $linha2 = 'UPDATE ' . LISTA . ' SET entidadepai = ' . $entidade_pai . ' WHERE entidadepai = '.$linha['entidadepai'].';' . "\n";

        $linha3 = '# ' . $linha['filho'] . '=>' . $entidade_filho . "\n";
        $linha4 = 'UPDATE ' . LISTA . ' SET entidadefilho = ' . $entidade_filho . ' WHERE entidadefilho = '.$linha['entidadefilho'].';' . "\n";

        #echo $linha1 . $linha2 . $linha3 . $linha4;
        fwrite($fp,$linha1);
        fwrite($fp,$linha2);
        fwrite($fp,$linha3);
        fwrite($fp,$linha4);
    }
    fclose($fp);