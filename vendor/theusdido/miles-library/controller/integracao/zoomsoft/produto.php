<?php
    $op = tdc::r('op');
    $connZoom = Conexao::Abrir('zoomsoft');

    switch($op){
        case 'importar':
            $sql  = 'SELECT * FROM estoque_cadprod;';
            $query = $connZoom->query($sql);
            while ($linha = $query->fetch()){

                $produto                = tdc::p('td_ecommerce_produto')->newNotExists('sku','=',$linha['PR_COD']);
                $produto->sku           = $linha['PR_COD'];
                $produto->nome          = tdc::utf8($linha['PR_DESC']);
                $produto->marca         = tdc::p('td_ecommerce_marca')->append('descricao','=',strtoupper($linha['PR_MODELO']))->id;
                $produto->unidademedida = tdc::p('td_ecommerce_unidademedida')->append('descricao','=',strtoupper($linha['PR_UNID']))->id;
                $produto->preco         = $linha['PR_PRECOV'];
                $produto->armazenar();

            }
        break;
    }
