<?php
    switch($op){
        case 'indicadores-propostas':

            $loja       = $dados['loja'] == '' ? 0 : $dados['loja'];
            $retorno    = array();

            $sql = "
                SELECT
                    COUNT(n.id) qtdade_negociacoes,
                    (
                        SELECT IFNULL(COUNT(p.id),0) FROM td_ecommerce_proposta p
                        WHERE p.oportunidade = n.oportunidade
                    ) qtdade_propostas,
                    DATE_FORMAT(n.datahoraabertura,'%m/%Y') referencia
                FROM td_ecommerce_negociacao n
                WHERE n.loja = {$loja}
                GROUP BY
                    referencia
                ORDER BY
                    referencia DESC;
            ";
            $query          = $conn->query($sql);
            while ($linha = $query->fetch()){
                array_push($retorno,array(
                    'negociacoes'   => $linha['qtdade_negociacoes'],
                    'propostas'     => $linha['qtdade_propostas'],
                    'referencia'    => tdc::utf8($linha['referencia'])
                ));
            }
            echo json_encode($retorno);
        break;
        case 'consumo':
            $loja       = $dados['loja'] == '' ? 0 : $dados['loja'];
            $retorno    = array();
            $sql = "
                SELECT 
                (
                    SELECT IFNULL(SUM(m.valor),0) 
                    FROM td_carteiradigital_movimentacao m 
                    WHERE m.transacao = t.id
                    AND m.loja = {$loja}
                ) total,
                t.descricao transacao_desc
                FROM td_carteiradigital_transacao t;
            ";
            $query          = $conn->query($sql);
            while ($linha = $query->fetch()){
                array_push($retorno,array(
                    'total'             => $linha['total'],
                    'transacao_desc'    => tdc::utf8($linha['transacao_desc'])
                ));
            }
            echo json_encode($retorno);  
        break;
    }