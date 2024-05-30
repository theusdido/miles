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
		count(produto) qtdevendas,
		ifnull(produto,0) produto,
		(SELECT CONCAT('Cod.: ',c.id,' - ', c.nome,' - Tam.: ',d.descricao) FROM td_ecommerce_produto c, td_ecommerce_tamanhoproduto d WHERE a.produto = d.id AND c.id = d.produto) nomeproduto
		FROM td_ecommerce_pedidoitem a, td_ecommerce_pedido b
		WHERE a.pedido = b.id
		AND b.status = 3
		GROUP BY a.produto
		ORDER BY qtdevendas DESC
		LIMIT 5;
	";

	$query = $conn->query($sql);
	while ($linha = $query->fetch()){
		$a = tdClass::Criar("hyperlink");
		$a->class="list-group-item";
		$a->href = "#";
		$a->add('<span class="badge">'.$linha["qtdevendas"].'</span>' . $linha["nomeproduto"]);
		$a->onclick = "addLoaderGeral();editarTDFormulario(".getEntidadeId("ecommerce_produto").",".tdc::p("td_ecommerce_tamanhoproduto",$linha["produto"])->produto.");";
		$listaMaisVendidos->add($a);
	}
	$panel->body($listaMaisVendidos);
	$bloco_maisvendidos->add($panel);
	$bloco_maisvendidos->mostrar();