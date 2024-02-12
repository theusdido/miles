<?php

    switch(tdc::r('op')){
        case 'pesquisar':
            $tipo       = tdc::r('tipo');
            $termo      = tdc::r('termo');
            $where_re   = $where_fa = $where_in  = '';
            $order      = 'ORDER BY nome ASC,id DESC';

            if ($termo != ''){
                $where_processo = " OR (id = '$termo') OR EXISTS ( 
                    SELECT 1 FROM td_processo b 
                    WHERE (
                        b.numeroprocesso LIKE '%$termo%'
                        OR
                        b.descricao LIKE '%$termo%'
                        OR 
                        b.escrivao LIKE '%$termo%'
                        OR EXISTS ( SELECT 1 FROM td_juizo c WHERE c.id = b.juizo AND c.descricao LIKE '%$termo%')
                        OR EXISTS ( SELECT 1 FROM td_comarca d WHERE d.id = b.comarca AND d.descricao LIKE '%$termo%')
                        OR EXISTS ( SELECT 1 FROM td_magistrado e WHERE e.id = b.magistrado AND e.nome LIKE '%$termo%')
                        OR EXISTS ( SELECT 1 FROM td_cidade f WHERE f.id = b.cidade AND f.nome LIKE '%$termo%')
                        OR EXISTS ( SELECT 1 FROM td_estado g WHERE g.id = b.estado AND g.nome LIKE '%$termo%')
                        OR EXISTS ( SELECT 1 FROM td_estado h WHERE h.id = b.estado AND h.sigla LIKE '%$termo%')
                    )
                    AND b.id = a.processo 
                )";

                $where_re = "WHERE a.razaosocial LIKE '%$termo%' OR a.cnpj LIKE '%$termo%' $where_processo";
                $where_fa = "WHERE a.razaosocial LIKE '%$termo%' OR a.cnpj LIKE '%$termo%' $where_processo";
                $where_in = "WHERE a.nome LIKE '%$termo%' OR a.cpf LIKE '%$termo%' $where_processo";
            }

            $campos_re          = "a.id,a.razaosocial nome,a.cnpj documento,'Recuperanda' tipo_descricao,'td_recuperanda' farein_tabela,'RE' tipo";
            $campos_fa          = "a.id,a.razaosocial nome,a.cnpj documento,'Falida' tipo_descricao,'td_falencia' farein_tabela,'FA' tipo";
            $campos_in          = "a.id,a.nome,a.cpf documento,'Insolvente' tipo_descricao,'td_insolvencia' farein_tabela,'IN' tipo";

            $sql_recuperanda    = "SELECT $campos_re FROM td_recuperanda a $where_re";
            $sql_falida         = "SELECT $campos_fa FROM td_falencia a $where_fa ";
            $sql_insolvente     = "SELECT $campos_in FROM td_insolvente a $where_in $order";

            if ($tipo == ''){
                $sql = '
                    '.$sql_recuperanda.'
                    UNION
                    '.$sql_falida.'
                    UNION
                    '.$sql_insolvente.'
                    ;
                ';
            }else{
                switch($tipo){
                    case 'RE': $sql = $sql_recuperanda; break;
                    case 'FA': $sql = $sql_falida; break;
                    case 'IN': $sql = $sql_insolvente; break;
                }
            }

            $query = $conn->query($sql);
            tdc::wj($query->fetchAll(PDO::FETCH_ASSOC));
        break;
        case 'get':
            tdc::wj( tdc::pa(tdc::r('farein'),tdc::r('id')) );
        break;
    }