<?php
	
	if (isset($_POST["op"])){
		if ($_POST["op"] != "salvar_quadrogeral") return false;
		if ($conn = Transacao::get()){
			$farein_array = explode("-",$_POST["farein"]);
			$processo 		= $farein_array[0];
			$farein 		= $farein_array[1];		
			$tipo_farein 	= tdClass::Criar("persistent",array("td_processo",$processo))->tipoprocesso;
			
			$conn->exec("DELETE FROM td_relacaocredores WHERE processo = $processo AND farein = $farein AND origemcredor = 7");
			$sql_credor = "SELECT id,nome,tipo,cpf,cnpj,classificacao,moeda,natureza,tipoempresa,email,processo,logradouro,numero,bairro,cidade,estado,valor,origemcredor FROM td_relacaocredores WHERE id IN ({$_POST["credores"]})";
			$query_credor = $conn->query($sql_credor);
			while ($linha_credor = $query_credor->fetch()){
				$nome 			= $linha_credor["nome"];
				$tipo			= $linha_credor["tipo"];
				$cpf			= $linha_credor["cpf"];	
				$cnpj			= $linha_credor["cnpj"];							
				$natureza		= $linha_credor["natureza"];							
				$email			= $linha_credor["email"];
				$processo		= $linha_credor["processo"];
				$logradouro		= $linha_credor["logradouro"];
				$numero			= $linha_credor["numero"];
				$bairro			= $linha_credor["bairro"];
				$cidade			= $linha_credor["cidade"];
				$estado			= $linha_credor["estado"];
				
				$valor 			= $linha_credor["valor"];
				$moeda 			= $linha_credor["moeda"];
				$classificacao 	= $linha_credor["classificacao"];				
				

				
				// Seta valores para Divergencia/Habilitação
				if ($linha_credor["origemcredor"] == 5 || $linha_credor["origemcredor"] == 6){
					$sql_imphab = "	SELECT id,parecervalor,parecermoeda,parecerclassificacao FROM td_habilitacaoimpugnacao WHERE credor = {$linha_credor["id"]} AND decisao = 1";
					$query_imphab = $conn->query($sql_imphab);
					while ($linha_imphab = $query_imphab->fetch()){
						
						$sqlParecer = "SELECT legitimidade,valor,moeda,classificacao,cpfj FROM td_habilitacaoimpugnacaoparecer WHERE habilitacaoimpugnacao = {$linha_imphab["id"]} AND valor > 0";
						$queryParecer = $conn->query($sqlParecer);
						
						if ($queryParecer->rowcount() > 0){
							while ($linhaParecer = $queryParecer->fetch()){
							
								$valor = $linhaParecer["valor"];
								$moeda = $linhaParecer["moeda"];
								$classificacao = $linhaParecer["classificacao"];
							
								if ($linhaParecer["legitimidade"] != ""){
									$nome = $linhaParecer["legitimidade"];
								}	
								if ($linhaParecer["cpfj"] != ""){
									if ($tipo == 1){
										$cnpj = $linhaParecer["cnpj"];
									}else{
										$cpf = $linhaParecer["cpf"];
									}
								}
							
								$query = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM td_relacaocredores");
								$prox = $query->fetch();
								$sql = "INSERT INTO td_relacaocredores (id,nome,tipo,cpf,cnpj,classificacao,natureza,email,processo,logradouro,numero,bairro,cidade,estado,origemcredor,valor,moeda,farein) VALUES(
								{$prox[0]},'{$nome}',{$tipo},'{$cpf}','{$cnpj}',{$classificacao},{$natureza},'{$email}',{$processo},'{$logradouro}','{$numero}','{$bairro}','{$cidade}',{$estado},7,{$valor},{$moeda},{$farein}
								);";
								$conn->exec($sql);
							}
						}else{
							$query = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM td_relacaocredores");
							$prox = $query->fetch();
							$sql = "INSERT INTO td_relacaocredores (id,nome,tipo,cpf,cnpj,classificacao,natureza,email,processo,logradouro,numero,bairro,cidade,estado,origemcredor,valor,moeda,farein) VALUES(
							{$prox[0]},'{$nome}',{$tipo},'{$cpf}','{$cnpj}',{$classificacao},{$natureza},'{$email}',{$processo},'{$logradouro}','{$numero}','{$bairro}','{$cidade}',{$estado},7,{$valor},{$moeda},{$farein}
							);";
							$conn->exec($sql);							
						}	
					}
				}else if($linha_credor["origemcredor"] == 4){
					$valor			= $linha_credor["valor"];	
					$moeda			= $linha_credor["moeda"];
					$classificacao	= $linha_credor["classificacao"];
					
					$query = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM td_relacaocredores");
					$prox = $query->fetch();
					$nome = mysql_real_escape_string($nome);
					$sql = "INSERT INTO td_relacaocredores (id,nome,tipo,cpf,cnpj,classificacao,natureza,email,processo,logradouro,numero,bairro,cidade,estado,origemcredor,valor,moeda,farein) VALUES(
					{$prox[0]},'{$nome}',{$tipo},'{$cpf}','{$cnpj}',{$classificacao},{$natureza},'{$email}',{$processo},'{$logradouro}','{$numero}','{$bairro}','{$cidade}',{$estado},7,{$valor},{$moeda},{$farein}
					);";
					$conn->exec($sql);
				}
				
			}
		}	
		Transacao::fechar();
		exit;
	}
	// Tela Modal
	if (isset($_GET["idpk"])){
				
		$btn_gerar = tdClass::Criar("button");
		$btn_gerar->class = "btn btn-primary";
		$btn_gerar->add('Gerar');
		$btn_gerar->id = "btn-gerar-quadro-geral";
		$btn_gerar->style = "float:right;width:100px;";				
		$btn_gerar->onclick = "
			var credores = '';
			$('.check-credor').each(function(){
				credores += (credores=='')?$(this).data('id'):',' + $(this).data('id');				
			});
			$.ajax({
				url:session.urlsystem,
				type:'POST',
				data:{
					controller:'quadrogeralcredores',
					op:'salvar_quadrogeral',
					credores:credores,
					farein:'{$_GET["idpk"]}'
				},
				success:function(){
					window.open(getURLProject('index.php?controller=impressaoquadrogeralcredores&farein={$_GET["idpk"]}'),'_blank');
				}
			});		
		";
		$btn_gerar->mostrar();
		
		//LISTA DE CREDORES HABILITAÇÃO/IMPUGNAÇÃO
		$thCodigo = tdClass::Criar("tabelahead");
		$thCodigo->add(utf8_decode("Código"));
		$thCodigo->width = "15%";
		$thCodigo->align = "center";
		
		$thCPFJ = tdClass::Criar("tabelahead");
		$thCPFJ->add("CNPJ / CPF");
		$thCPFJ->width = "30%";
		$thCPFJ->align = "left";
		
		$thNome = tdClass::Criar("tabelahead");
		$thNome->add(utf8_decode("Nome / Razão Social"));
		$thNome->width = "50%";
		$thNome->align = "left";		
		
		$check = tdClass::Criar("span");
		$check->class = "fas fa-check";
		$check->aria_hidden = "true";

		$thCheck = tdClass::Criar("tabelahead");
		$thCheck->add($check);
		$thCheck->width = "5%";
		
		$thead = tdClass::Criar("thead");
		$thead->add($thCodigo,$thCPFJ,$thNome,$thCheck);
		
		$caption = tdClass::Criar("caption");
		$caption->add(utf8_decode("LISTA DE RELAÇÃO - HABILITAÇÃO/IMPUGNAÇÃO"));
		
		$table = tdClass::Criar("tabela");
		$table->class = "table table-hover";
		$table->add($caption,$thead);
		
		$tbody = tdClass::Criar("tbody");
		
		$pk_id = explode("-",$_GET["idpk"]);
		$sqlHabDiv = tdClass::Criar("sqlcriterio");		
		$sqlHabDiv->addFiltro("processo","=",$pk_id[0]);
		$sqlHabDiv->add(new sqlFiltro("decisao","=",1));
		$dsHabDiv = tdClass::Criar("repositorio",array("td_habilitacaoimpugnacao"))->carregar($sqlHabDiv);
		$credoresHABDIV = array();
		foreach($dsHabDiv as $habdiv){
			if ($habdiv->credor != null && $habdiv->credor != "" && is_numeric($habdiv->credor)){
				
				$tr = tdClass::Criar("tabelalinha");
				
				$credor = tdClass::Criar("persistent",array("td_relacaocredores",$habdiv->credor));
				if ($credor->contexto->farein == $pk_id[1] && ($credor->contexto->origemcredor == 5 || $credor->contexto->origemcredor == 6)){
					
					$cpfj = strlen($credor->contexto->cnpj)>11?$credor->contexto->cnpj:$credor->contexto->cpf;
					array_push($credoresHABDIV,$cpfj);
					
					$tdCodigo = tdClass::Criar("tabelacelula");
					$tdCodigo->add(completaString($credor->contexto->codigo,5));
					
					$tdCPFJ = tdClass::Criar("tabelacelula");
					$tdCPFJ->add($cpfj);
					
					$tdNome = tdClass::Criar("tabelacelula");
					$tdNome->add($credor->contexto->nome);
					
					$checkbox = tdClass::Criar("input");
					$checkbox->type = "checkbox";
					//$checkbox->class = "check-credor-" . implode("-",$pk_id);
					$checkbox->class = "check-credor";
					

					$checkbox->data_id = $credor->contexto->id;
					
					$tdCheck = tdClass::Criar("tabelacelula");
					$tdCheck->add($checkbox);
					
					$tr->add($tdCodigo,$tdCPFJ,$tdNome,$tdCheck);
					$tbody->add($tr);					
				}
			}	
		}
		
		$table->add($tbody);
		$table->mostrar();		
		
		// RELAÇÃO DE CREDORES ( ADMINISTRADORA )
		$thCodigo = tdClass::Criar("tabelahead");
		$thCodigo->add(utf8_decode("Código"));
		$thCodigo->width = "15%";
		$thCodigo->align = "center";
		
		$thCPFJ = tdClass::Criar("tabelahead");
		$thCPFJ->add("CNPJ / CPF");
		$thCPFJ->width = "30%";
		$thCPFJ->align = "left";
		
		$thNome = tdClass::Criar("tabelahead");
		$thNome->add(utf8_decode("Nome / Razão Social"));
		$thNome->width = "50%";
		$thNome->align = "left";		
		
		$check = tdClass::Criar("span");
		$check->class = "fas fa-check";
		$check->aria_hidden = "true";

		$thCheck = tdClass::Criar("tabelahead");
		$thCheck->add($check);
		$thCheck->width = "5%";
		
		$thead = tdClass::Criar("thead");
		$thead->add($thCodigo,$thCPFJ,$thNome,$thCheck);
		
		$caption = tdClass::Criar("caption");
		$caption->add(utf8_decode("RELAÇÃO CREDORES DA ADMINISTRADORA JUDICIAL"));
		
		$table = tdClass::Criar("tabela");
		$table->class = "table table-hover";
		$table->add($caption,$thead);
		
		$tbody = tdClass::Criar("tbody");
				
		$sqlCredores = tdClass::Criar("sqlcriterio");
		$sqlCredores->addFiltro("processo","=",$pk_id[0]);
		$sqlCredores->addFiltro("farein","=",$pk_id[1]);
		$sqlCredores->addFiltro("origemcredor","=",4);
		$sqlCredores->setPropriedade("order","classificacao,codigo");
		
		$dsCredores = tdClass::Criar("repositorio",array("td_relacaocredores"))->carregar($sqlCredores);
		foreach($dsCredores as $credor){
			$tr = tdClass::Criar("tabelalinha");
			$cpfj = strlen($credor->cnpj)>11?$credor->cnpj:$credor->cpf;
			if (!in_array($cpfj, $credoresHABDIV)) { 				

				$tdCodigo = tdClass::Criar("tabelacelula");			
				$tdCodigo->add(completaString($credor->codigo,5));
				
				$tdCPFJ = tdClass::Criar("tabelacelula");
				$tdCPFJ->add($cpfj);
				
				$tdNome = tdClass::Criar("tabelacelula");
				$tdNome->add($credor->nome);
				
				$checkbox = tdClass::Criar("input");
				$checkbox->type = "checkbox";
				$checkbox->class = "check-credor";
				
				$checkbox->data_id = $credor->id;
				
				$tdCheck = tdClass::Criar("tabelacelula");
				$tdCheck->add($checkbox);
				
				$tr->add($tdCodigo,$tdCPFJ,$tdNome,$tdCheck);
				$tbody->add($tr);
			}	
		}
		
		$table->add($tbody);
		$table->mostrar();

		exit;
	}
	
	// Bloco
	$bloco = tdClass::Criar("bloco");
	$bloco->class="col-md-12";	
	
	$titulo = tdClass::Criar("p");
	$titulo->class = "titulo-pagina";
	$titulo->add(utf8_decode("Quadro Geral de Credores"));
	
	$sql = tdClass::Criar("sqlcriterio");
	$sql->setPropriedade("order","id desc");
	$dataset = tdClass::Criar("repositorio",array("td_processo"))->carregar($sql);
	
	// Nome do Modal (id)
	$modalName = "listascredores";
	
	// Collapse
	$collapse = tdClass::Criar("collapse");
	
	foreach($dataset as $processo){
		$listGroup = tdClass::Criar("div");
		$listGroup->class = "list-group";
		
		$sql_farein = tdClass::Criar("sqlcriterio");
		$sql_farein->addFiltro("processo","=",$processo->id);
		$entidade = tdClass::Criar("persistent",array(ENTIDADE,$processo->tipoprocesso));
		if ($entidade->contexto->nome == null) continue;
		$ds_farein = tdClass::Criar("repositorio",array($entidade->contexto->nome))->carregar($sql_farein);
		foreach($ds_farein as $farein){
			$PK_ID = $processo->id ."-".$farein->id;
			$a = tdClass::Criar("hyperlink");
			$a->href = "#";
			$a->id = $PK_ID;
			$a->class = "list-group-item";
			if ($processo->tipoprocesso == 18){
				$nome = $farein->nome;
				$cpfj = $farein->cpf;
			}else{
				$nome = $farein->razaosocial;
				$cpfj = $farein->cnpj;				
			}
			$a->add("<b>[ ".$farein->id." ] </b>" . $nome . " - <small>" . $cpfj . "</small>");
			
			$js = tdClass::Criar("script");
			$js->add('
				$("#'.$PK_ID.'").click(function(){
					$("#'.$modalName.' .modal-body").load(getURLProject("index.php?controller=quadrogeralcredores&idpk='.$PK_ID.'"));
					$("#'.$modalName.'").modal("show");
				});
			');
			$listGroup->add($a,$js);
		}
		
		$collapse->addTab("[ " . $processo->id . " ] [ " . $processo->numeroprocesso . " ]",$listGroup);
	}
	
	$modal = tdClass::Criar("modal");
	$modal->nome = $modalName;
	$modal->tamanho = "modal-lg";
	$modal->addHeader("Listas de Credores",null);
	$modal->addBody("");
	
	$bloco->add($titulo,$collapse,$modal);
	$bloco->mostrar();
?>