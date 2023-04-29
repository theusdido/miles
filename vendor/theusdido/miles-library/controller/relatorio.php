<?php
	set_time_limit(7200);
?>
<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8" />
		<style>
			#cabecalho{
				float:left;
				width:100%;
			}
			#cabecalho #empresa,#rodape #datahora{
				float:right;
			}
			#cabecalho #descricaorelatorio,#rodape #usuario{
				float:left;
			}
			.separadorcabecalho{
				float:left;
				clear:both;
				width:100%;
				border-color:#FEFEFE;
				margin:1px 0;
			}
			table tr th {
				text-align:left;
			}
			.nenhumregistro{
				text-align:center;
				background-color:#F3F3F3;
				padding:5px;
				margin-top:10px;
			}
			.rodape-linha-somatorio{
				border-top:1px solid #000;
				font-weight:bold;
			}

			#rodape{
				position: fixed;
				bottom: 0;
				z-index: 1000px;
				text-align: center;
				font-size: 12px;
				height: 15px;
				margin: 20px auto !important;
				padding: 15px 0 !important;
				display:contents;		
			}

			.corpo-relatorio tr td {
				border-bottom:1px solid #EBEBEB;
			}
		</style>
	</head>
	<body>	
		<?php
			$_campos 			= tdc::r('campos');
			$_relatorio  		= tdc::p(RELATORIO,tdc::r('relatorio_id'));
			$_atributos_config 	= tdc::d('td_relatoriocoluna',tdc::f('relatorio','=',$_relatorio->id));
			$_entidade			= tdClass::Criar("persistent",array(ENTIDADE,tdc::r('entidade')))->contexto;

			if (sizeof($_atributos_config) <= 0){
				if ($_campos == ''){
					showMessage('Não foram encontrado campos para montagem do relatório.');
					exit;
				}
				$colunas 			= explode(",",$_campos);
				$qtdeColunas 		= sizeof($colunas);
			}else{
				$criterio			= tdc::f();
				$criterio->addFiltro('relatorio','=',$_relatorio->id);
				$criterio->setPropriedade('order','ordem ASC');
				$colunas 			= tdc::d('td_relatoriocoluna',$criterio);
				$qtdeColunas 		= sizeof($colunas);
			}
		

		// *** CABEÇALHO *** //
		$empresa 		= tdClass::Criar("span");
		$empresa->id 	= "empresa";
		$empresa->add("Empresa: " . PROJETO_DESC);

		$descricaorelatorio = tdClass::Criar("span");
		$descricaorelatorio->id = "descricaorelatorio";
		$descricaorelatorio->add("Relat&oacute;rio de " . tdc::utf8($_relatorio->descricao));

		$hr = tdClass::Criar("hr");
		$hr->class = "separadorcabecalho";

		$cabecalho = tdClass::Criar("div");
		$cabecalho->id = "cabecalho";
		$cabecalho->add($empresa,$descricaorelatorio,$hr);

		$thCabecalho = tdClass::Criar("tabelahead");
		$thCabecalho->add($cabecalho);
		$thCabecalho->colspan = $qtdeColunas;
		$trCabecalho = tdClass::Criar("tabelalinha");
		$trCabecalho->add($thCabecalho);
		
		$is_linha_somatorio = false;
		$camposNome 		= array();
		$camposSomatorio	= array();		

		$trTitulo = tdClass::Criar("tabelalinha");
		foreach($colunas as $coluna){
			$_atributo 			= tdc::a($coluna->atributo);
			$thTitulo 			= tdClass::Criar("tabelahead");
			$thTitulo->style 	= 'text-align:' . $coluna->alinhamento;
			$_display_coluna	= $coluna->descricao != '' ? $coluna->descricao : $_atributo->descricao;
			$thTitulo->add(tdc::utf8($_display_coluna));
			$trTitulo->add($thTitulo);
			array_push($camposNome,$_atributo->nome);

			if ($coluna->is_somatorio){				
				$is_linha_somatorio = true;
				array_push($camposSomatorio,'SUM('.$_atributo->nome.') somatorio_' . $_atributo->nome);
			}
		}

		$thead = tdClass::Criar("thead");
		$thead->add($trCabecalho,$trTitulo);

		// Critério Geral
		$where = tdClass::Criar("sqlcriterio");

		// *** FILTROS *** //
		$filtrosP = isset($_GET["filtros"])?$_GET["filtros"]:""; // Atributo^Operador^Valor^Tipo
		
		if ($filtrosP != ""){
			$filtroParams = tdc::f();
			$filtros = explode("~",$filtrosP);
			foreach($filtros as $ft){
				$f 			= explode("^",$ft);
				$campo_a	= explode(" ",$f[0]);
				$camponome 	= $campo_a[0];

				$_filtro_operador 	= $f[1];
				$_filtro_valor		= tdc::utf8($f[2]);

				if ($_filtro_operador == "%" && $f[3] == "varchar"){
					$filtroParams->addFiltro($camponome,"like",'%' . $_filtro_valor . '%');
				}else if ($_filtro_operador == '!'){
					$filtroParams->addFiltro($camponome,"<>",$_filtro_valor);
				}else{
					$filtroParams->addFiltro($camponome,$_filtro_operador,$_filtro_valor);
				}
			}
			// Adiciona filtros selecionado na geração do relatório
			$where->add($filtroParams);			
		}

		// *** RESTRIÇÕES *** //
		$filtroRestricao 	= tdc::f();
		$sqlRestricao 		= "SELECT * FROM td_relatoriorestricao WHERE relatorio = {$_relatorio->id};";
		$queryRestricoes 	= $conn->query($sqlRestricao);
		if ($queryRestricoes->rowCount() > 0){
			foreach($queryRestricoes->fetchAll(PDO::FETCH_OBJ) as $r){
				$filtroRestricao->addFiltro(tdc::a($r->atributo)->nome,$r->operador,$r->valor);
			}
			// Adiciona a restrição inicial
			$where->add($filtroRestricao);			
		}

		$_where = $where->dump() != '' ? " WHERE ". $where->dump() : '';

		// *** CORPO *** //
		$tbody 			= tdClass::Criar("tbody");
		$tbody->class 	= 'corpo-relatorio';
		if ($conn = Transacao::Get()){
			$sql = "SELECT id," . implode(",",$camposNome) . " FROM " . $_entidade->nome . $_where;
			$query = $conn->query($sql);
			if ($query->rowCount() <= 0){
				$tr = tdClass::Criar("tabelalinha");
				$td = tdClass::Criar("tabelacelula");
				$td->add("Nenhum Registro Encontrado");
				$td->class 		= "nenhumregistro";
				$td->colspan 	= $qtdeColunas;
				$tr->add($td);
				$tbody->add($tr);
			}else{
				while ($linha = $query->fetch()){
					$somatorio = 0;
					$tr = tdClass::Criar("tabelalinha");					
					foreach($colunas as $coluna){
						$td 		= tdClass::Criar("tabelacelula");
						$_atributo	= tdc::a($coluna->atributo);
						$campo_nome = '';
						$fk_display = '';
						$campo_valor= $linha[$_atributo->nome];						
						if ($_atributo->chaveestrangeira > 0){ // Campo de chave estrangeira
							$_entidade_fk 	= tdc::e($_atributo->chaveestrangeira);							
							if (is_numeric($_entidade_fk->campodescchave)){
								$_atributo_fk	= tdc::a($_entidade_fk->campodescchave);
								if ($_atributo_fk){
									$valor = new sqlCriterio();
									$display_id = $coluna->exibirid ? completaString($linha['id'],3) . ' - '  : '';
									$fk_display = $display_id . tdc::p($_entidade_fk->nome,$linha['id'])->{$_atributo_fk->nome};
								}
							}
						}else{
							$tipohtml 	= tdClass::Criar("persistent",array(ATRIBUTO,getAtributoId($_entidade->nome,$_atributo->nome,$conn)))->contexto->tipohtml;
							$fk_display = getHTMLTipoFormato($tipohtml,$campo_valor,$entidade=0,getAtributoId($_entidade->nome,$_atributo->nome,$conn),$id=0);
						}

						if ($_atributo->tipohtml == 7)
						{
							$fk_display = $campo_valor ? 'Sim' : 'Não';
						}

						$td->align = $coluna->alinhamento;
						$td->add($fk_display);
						$tr->add($td);
					}
					$tbody->add($tr);
				}
			}
		}

		// *** RODAPE *** //
		$tfoot = tdClass::Criar("tfoot");

		if ($is_linha_somatorio && sizeof($camposSomatorio) > 0){
			$sql 	= "SELECT id," . implode(",",$camposSomatorio) . " FROM " . $_entidade->nome . " WHERE ". $where->dump();
			$query 	= $conn->query($sql);
			if ($query->rowCount() > 0){
				$linha = $query->fetch();	
				$trRodape 	= tdClass::Criar("tabelalinha");
				foreach($colunas as $coluna){
					
					$tdRodape 	= tdClass::Criar("tabelacelula");
					if ($coluna->is_somatorio){
						$tdRodape->add(number_format($linha['somatorio_valortotal'],2,',','.'));
					}else{
						$tdRodape->add('&nbsp;');
					}
					$tdRodape->align = $coluna->alinhamento;
					$tdRodape->class = 'rodape-linha-somatorio';
					$trRodape->add($tdRodape);
					
				}
				$tfoot->add($trRodape);
			}
		}

		$usuario = tdClass::Criar("span");
		$usuario->id = "usuario";
		$usuario->add("Usu&aacute;rio: " . Session::Get()->username);

		$datahora = tdClass::Criar("span");
		$datahora->id = "datahora";
		$datahora->add("Data e Hora: " . date("d/m/Y H:i:s"));

		$hr = tdClass::Criar("hr");
		$hr->class = "separadorcabecalho";

		$rodape = tdClass::Criar("div");
		$rodape->id = "rodape";
		$rodape->add($hr,$usuario,$datahora);

		$tdRodape = tdClass::Criar("tabelacelula");
		$tdRodape->add($rodape);
		$tdRodape->colspan = $qtdeColunas;
		$trRodape = tdClass::Criar("tabelalinha");
		$trRodape->add($tdRodape);

		
		$tfoot->add($trRodape);
		
		//  Tabela do Relatório 
		$trel 				= tdClass::Criar("tabela");
		$trel->width 		= "100%";
		$trel->cellspacing	= 0;
		$trel->cellpadding 	= 0;
		$trel->add($thead,$tbody,$tfoot);
		$trel->mostrar();
		?>
	</body>
</html>	