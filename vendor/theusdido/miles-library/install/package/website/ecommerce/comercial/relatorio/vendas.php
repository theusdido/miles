<?php
    /*
        * Relatório de Vendas de E-Commerce
        * Data de Criacao: 10/01/2023
        * Author: @theusdido
    */

    $_pedido_id     = tdc::e('ecommerce_pedido');
    $relatorio_id    = criarRelatorio(
        'relatorio_ecommerce_vendas',
        'Relatório de Vendas',
        $_pedido_id->id
    );
    
    addRestricaoRelatorio($relatorio_id,getAtributoId($_pedido_id->nome,'isfinalizado'),1);
    addRestricaoRelatorio($relatorio_id,getAtributoId($_pedido_id->nome,'status'),'3,4',',');