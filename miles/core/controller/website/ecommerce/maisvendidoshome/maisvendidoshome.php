<?php
	$conn = Transacao::Get();

	$bloco_maisvendidos = tdClass::Criar("bloco");
	$bloco_maisvendidos->class = "col-md-6";

	$panel = tdClass::Criar("panel");
	$panel->head("Produtos Mais Vendidos");
	$panel->tipo = "default";

	$listaMaisVendidos = tdClass::Criar("div");
	$listaMaisVendidos->class = "list-group";

	$sql = "
		SELECT 
		count(td_produto) qtdevendas,
		ifnull(td_produto,0) td_produto,
		(SELECT CONCAT('Cod.: ',c.id,' - ', c.nome,' - Tam.: ',d.descricao) FROM td_ecommerce_produto c, td_ecommerce_tamanhoproduto d WHERE a.td_produto = d.id AND c.id = d.td_produto) nomeproduto
		FROM ".getEntidadeEcommercePedidoItem()." a, td_ecommerce_pedido b
		WHERE a.td_pedido = b.id
		AND b.td_status = 3
		GROUP BY a.td_produto
		ORDER BY qtdevendas DESC
		LIMIT 5;
	";

	$query = $conn->query($sql);
	while ($linha = $query->fetch()){
		$a = tdClass::Criar("hyperlink");
		$a->class="list-group-item";
		$a->href = "#";
		$a->add('<span class="badge">'.$linha["qtdevendas"].'</span>' . $linha["nomeproduto"]);
		$a->onclick = "addLoaderGeral();editarTDFormulario(".getEntidadeId("ecommerce_produto").",".tdc::p("td_ecommerce_tamanhoproduto",$linha["td_produto"])->td_produto.");";
		$listaMaisVendidos->add($a);
	}
	$panel->body($listaMaisVendidos);
	$bloco_maisvendidos->add($panel);
	$bloco_maisvendidos->mostrar();