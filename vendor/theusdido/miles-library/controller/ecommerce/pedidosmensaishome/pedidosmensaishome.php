<?php

	$conn = Transacao::Get();
	$bloco_maisvendidos = tdClass::Criar("bloco");
	$bloco_maisvendidos->class = "col-md-6";

	$panel = tdClass::Criar("panel");
	$panel->head("Vendas Mensais");
	$panel->tipo = "default";
	$panel->id="panel-vendas-mensais";

	$graficoVendasMensais = tdClass::Criar("canvas");
	$graficoVendasMensais->id = 'myChart';

	#$graficoVendasMensais->class = "list-group";

	$quantidades 	= array();
	$meses 			= array();

	$sql = "
		SELECT 
			COUNT(*) quantidade,
			MONTH(datahoraenvio) mes,
			YEAR(datahoraenvio) ano
		FROM td_ecommerce_pedido
		GROUP BY mes,ano
		ORDER BY ano DESC,mes DESC
		LIMIT 3;
	";

	$query = $conn->query($sql);
	while ($linha = $query->fetch()){
		#$a = tdClass::Criar("hyperlink");
		#$a->class="list-group-item";
		#$a->href = "#";
		#$a->add('<span class="badge">'.$linha["qtdevendas"].'</span>' . $linha["nomeproduto"]);
		#$a->onclick = "addLoaderGeral();editarTDFormulario(".getEntidadeId("ecommerce_produto").",".tdc::p("td_ecommerce_tamanhoproduto",$linha["produto"])->produto.");";
		#$graficoVendasMensais->add($a);
		array_push($quantidades, (int)$linha['quantidade']);
		array_push($meses,"'".retornaMesExtenso($linha['mes'])."'");
	}

	$script_maisvendidos = tdc::html('script');
	$script_maisvendidos->add("
		if (typeof ctx !== 'object'){
			const ctx = new Chart(document.getElementById('myChart'), {
				type: 'bar',
				data: {
					labels: [".implode(',',$meses)."],
					datasets: [
						{
							label: 'Gráfico de Vendas Semestral',
							data: [".implode(',',$quantidades)."],
							borderWidth: 1
						}
					]
				},
				options: {
					indexAxis: 'y'
				}
			});
		}
	");

	$panel->body($graficoVendasMensais);
	$bloco_maisvendidos->add($panel,$script_maisvendidos);
	$bloco_maisvendidos->mostrar();