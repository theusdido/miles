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
				margin-right:5px;
			}
			#cabecalho #descricaorelatorio,#rodape #usuario{
				float:left;
				margin-left:5px;
			}
			.separadorcabecalho{
				float:left;
				clear:both;
				width:100%;
				border-color:#FEFEFE;
				margin-top:1px;
			}
			table tr th{
				text-align:left;
			}
			.nenhumregistro{
				text-align:center;
				background-color:#F3F3F3;
				padding:5px;
				margin-top:10px;
			}
		</style>
	</head>
	<body>	
		<?php

		$colunas = explode(",",$_GET["campos"]);
		$qtdeColunas = sizeof($colunas);
		$entidadePrincipal = tdClass::Criar("persistent",array(ENTIDADE,$_GET["entidade"]))->contexto;

		$camposNome = $camposDesc = $camposFK = array();
		foreach ($colunas as $c){
			$camposE = explode("^",$c);
			array_push($camposNome,$camposE[0]);
			array_push($camposDesc,$camposE[1]);
			array_push($camposFK,$camposE[2]);
		}

		// *** CABEÇALHO *** //
		$empresa 		= tdClass::Criar("span");
		$empresa->id 	= "empresa";
		$empresa->add("Empresa: " . PROJETO_DESC);

		$descricaorelatorio = tdClass::Criar("span");
		$descricaorelatorio->id = "descricaorelatorio";
		$descricaorelatorio->add("Relat&oacute;rio de " . tdc::utf8($entidadePrincipal->descricao));

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

		$trTitulo = tdClass::Criar("tabelalinha");
		for($i=0;$i<$qtdeColunas;$i++){
			$thTitulo = tdClass::Criar("tabelahead");
			$thTitulo->add(tdc::utf8($camposDesc[$i]));
			$trTitulo->add($thTitulo);
		}

		$thead = tdClass::Criar("thead");
		$thead->add($trCabecalho,$trTitulo);

		// *** FILTROS *** //
		$filtrosP = isset($_GET["filtros"])?$_GET["filtros"]:""; //Atributo^Operador^Valor^Tipo
		$where = tdClass::Criar("sqlcriterio");
		$where->addFiltro(1,"=",1);
		if ($filtrosP != ""){
			$filtros = explode("~",$filtrosP);
			foreach($filtros as $ft){
				$f = explode("^",$ft);
				$campo_a = explode(" ",$f[0]);
				$camponome = $campo_a[0];

				if ($f[1] == "%" && $f[3] == "varchar"){
					$where->addFiltro($camponome,"like",'%' . tdc::utf8($f[2]) . '%');
				}else{
					$where->addFiltro($camponome,$f[1],tdc::utf8($f[2]));
				}
			}
		}

		// *** CORPO *** //
		$tbody = tdClass::Criar("tbody");
		if ($conn = Transacao::Get()){
			$sql = "SELECT " . implode(",",$camposNome) . " FROM " . $entidadePrincipal->nome . " WHERE ". $where->dump();
			$query = $conn->query($sql);
			if ($query->rowCount() <= 0){
				$tr = tdClass::Criar("tabelalinha");
				$td = tdClass::Criar("tabelacelula");
				$td->add("Nenhum Registro Encontrado");
				$td->class = "nenhumregistro";
				$td->colspan = $qtdeColunas;
				$tr->add($td);
				$tbody->add($tr);
			}else{
				while ($linha = $query->fetch()){
					$tr = tdClass::Criar("tabelalinha");
					for($i=0;$i<$qtdeColunas;$i++){
						$td = tdClass::Criar("tabelacelula");
						if ($camposFK[$i] != ""){ // Campo de chave estrangeira
							$sqlFK = tdClass::Criar("sqlcriterio");
							$sqlFK->addFiltro("nome","=",$camposFK[$i]);
							$fkEntidade = tdClass::Criar("repositorio",array(ENTIDADE))->carregar($sqlFK);
							foreach ($fkEntidade as $cFK){
								$campoDescCahve = "";
								if ($cFK->campodescchave != null){
									$campoDescCahve = tdClass::Criar("persistent",array(ATRIBUTO,$cFK->campodescchave))->contexto->nome;
								}
							}
							if ($campoDescCahve != ""){
								$valor = new sqlCriterio();
								$td->add(tdClass::Criar("persistent",array($camposFK[$i],$linha[$camposNome[$i]]))->contexto->{$campoDescCahve});
							}
						}else{
							$tipohtml = tdClass::Criar("persistent",array(ATRIBUTO,getAtributoId($entidadePrincipal->nome,$camposNome[$i],$conn)))->contexto->tipohtml;
							$td->add(getHTMLTipoFormato($tipohtml,$linha[$camposNome[$i]],$entidade=0,getAtributoId($entidadePrincipal->nome,$camposNome[$i],$conn),$id=0));
						}
						$tr->add($td);
					}
					$tbody->add($tr);
				}
			}
		}

		// *** RODAPE *** //
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

		$tfoot = tdClass::Criar("tfoot");
		$tfoot->add($trRodape);
		
		//  Tabela do Relatório 
		$trel = tdClass::Criar("tabela");
		$trel->width = "100%";
		$trel->add($thead,$tbody,$tfoot);
		$trel->mostrar();
		?>
	</body>
</html>	