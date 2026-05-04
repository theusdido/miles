<?php

    $op         = tdc::r('op');
    switch($op){
        case 'betha':
            require PATH_MVC_CONTROLLER . 'integracao/betha/nfse/consulta.php';
        break;
        default:
            $rps            = tdc::r('rps');
            $data           = tdc::r('data');
            $situacao       = tdc::r('situacao');
            $tomador        = tdc::r('tomador');
            $documento      = tdc::r('documento');
            $referencia     = tdc::r('referencia');

            $where          = "1=1";

            if ($rps != ''){
                $where .= " AND a.rpsnumero = '$rps'";
            }

            if ($data != ''){
                $where .= " AND a.demis = DATE_FORMAT(STR_TO_DATE('$data', '%d/%m/%Y'), '%Y-%m-%d')";
            }

            if ($situacao != ''){
                
                if ($situacao == 'N'){
                    $where .= " AND (a.situacao = '$situacao' OR a.situacao IS NULL)";
                }else{
                    $where .= " AND a.situacao = '$situacao'";
                }

            }

            if ($tomador != ''){
                $where .= " AND b.tomarazaosocial LIKE '%$tomador%'";
            }

            if ($documento != ''){
                if (strlen($documento) > 11){
                    $where .= " AND b.tomacnpj = '$documento'";
                }else{
                    $where .= " AND b.tomacpf = '$documento'";
                }
            }

            if ($referencia != ''){
                $where .= " AND a.mesano = '".str_replace("/","",$referencia)."'";
            }

            $where .= " AND a.demis > DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 30 DAY), '%Y-%m-%d')";
            $where .= " AND (a.inativo <> 1 OR a.inativo IS NULL)";

            $sql = "
                SELECT 
                    a.id,
                    a.rpsnumero,
                    a.rpsserie,
                    a.rpstipo,
                    a.situacao,
                    DATE_FORMAT(a.demis,'%d/%m/%Y') dataemissao,
                    b.tomarazaosocial,
                    a.mesano referencia
                FROM td_erp_nfse_nota a
                LEFT JOIN td_erp_nfse_tomador b ON b.nfse = a.id
                WHERE $where
                ORDER BY a.rpsnumero ASC;    
            ";

            $query = $conn->query($sql);
            $rows = $query->fetchAll(PDO::FETCH_OBJ);
            $retorno = array();

            foreach ($rows as $d){              
                array_push($retorno,array(
                    "id"            => $d->id,
                    "rpsnumero"     => $d->rpsnumero,
                    "rpsserie"      => $d->rpsserie,
                    "rpstipo"       => $d->rpstipo,
                    "situacao"      => $d->situacao == 'E' ? 'Enviada' : 'Não Enviada',
                    "tomador"       => $d->tomarazaosocial,
                    'dataemissao'   => $d->dataemissao,
                    'referencia'    => $d->referencia
                ));
            }

            echo json_encode($retorno);            
        break;
    }