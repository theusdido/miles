<?php

$totalAcessos = $visitantesOnline = $carrinhosAtivos = $carrinhosAbandonados = $edidosDevolvidos = $edidosDevolvidos = 0;
if ($conn = Transacao::get()){

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
    $sqlCarrinhosAtivos = "SELECT 1 FROM td_ecommerce_carrinhodecompras a 
                            WHERE EXISTS( SELECT 1 FROM td_ecommerce_itenscarrinho b WHERE a.id = b.carrinho ) 
                            AND (inativo = false OR inativo IS NULL)
                            AND datahoracriacao BETWEEN NOW() - INTERVAL 30 DAY AND NOW()  
                            ";
    $queryCarrinhosAtivos = $conn->query($sqlCarrinhosAtivos);
    $carrinhosAtivos = $queryCarrinhosAtivos->rowCount();
    $queryCarrinhosAtivos->closeCursor();

    // Carrinhos abandonados
    $sqlCarrinhosAbandonados = "SELECT 1 FROM td_ecommerce_carrinhodecompras a 
                            WHERE EXISTS( SELECT 1 FROM td_ecommerce_itenscarrinho b WHERE a.id = b.carrinho ) 
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
    $sqlProdutosEsgotados = "SELECT * FROM td_ecommerce_posicaogeralestoque WHERE saldo <= 0;";
    $queryProdutosEsgotados = $conn->query($sqlProdutosEsgotados);
    $produtosEsgotados = $queryProdutosEsgotados->rowCount();
    $queryProdutosEsgotados->closeCursor();

}