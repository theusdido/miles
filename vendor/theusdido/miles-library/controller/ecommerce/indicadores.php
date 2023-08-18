<?php

$totalAcessos = $visitantesOnline = $carrinhosAtivos = $carrinhosAbandonados = $pedidosDevolvidos = $produtosEsgotados = 0;
if ($conn = Transacao::get()){

    if (SQL::entity_exists('td_ecommerce_visitantes')){
        
        // Visitantes ONLINE
        $sqlVisitantesOnline = "SELECT 1 FROM td_ecommerce_visitantes WHERE inativo = false GROUP BY sessao,ip,cliente;";
        $queryVisitantesOnline = $conn->query($sqlVisitantesOnline);
        $visitantesOnline = $queryVisitantesOnline->rowCount();
        $queryVisitantesOnline->closeCursor();

        // Total de Acessos
        $sqlTotalAcessos = "SELECT 1 FROM td_ecommerce_visitantes GROUP BY sessao,ip,cliente;";
        $queryTotalAcessos = $conn->query($sqlTotalAcessos);
        $totalAcessos = $queryTotalAcessos->rowCount();
        $queryTotalAcessos->closeCursor();

        // Carrinhos Ativos
        $sqlCarrinhosAtivos = "SELECT 1 FROM td_ecommerce_carrinhocompras a 
                                WHERE EXISTS( SELECT 1 FROM td_ecommerce_carrinhoitem b WHERE a.id = b.carrinho ) 
                                AND (inativo = false OR inativo IS NULL)
                                AND datahoracriacao BETWEEN NOW() - INTERVAL 30 DAY AND NOW()  
                                ";
        $queryCarrinhosAtivos = $conn->query($sqlCarrinhosAtivos);
        $carrinhosAtivos = $queryCarrinhosAtivos->rowCount();
        $queryCarrinhosAtivos->closeCursor();

        // Carrinhos abandonados
        $sqlCarrinhosAbandonados = "SELECT 1 FROM td_ecommerce_carrinhocompras a 
                                WHERE EXISTS( SELECT 1 FROM td_ecommerce_carrinhoitem b WHERE a.id = b.carrinho ) 
                                AND (inativo = false OR inativo IS NULL)
                                AND datahoracriacao < NOW() - INTERVAL 30 DAY;";
        $queryCarrinhosAbandonados = $conn->query($sqlCarrinhosAbandonados);
        $carrinhosAbandonados = $queryCarrinhosAbandonados->rowCount();
        $queryCarrinhosAbandonados->closeCursor();

        // Pedidos Devolvidos
        $sqlPedidosDevolvidos = "SELECT * FROM td_ecommerce_pedido WHERE status = 6;";
        $queryPedidosDevolvidos = $conn->query($sqlPedidosDevolvidos);
        $pedidosDevolvidos = $queryPedidosDevolvidos->rowCount();
        $queryPedidosDevolvidos->closeCursor();

        // Produtos Esgotados
        $produtosEsgotados = Estoque::qtdeProdutosEsgotados();
    }
}