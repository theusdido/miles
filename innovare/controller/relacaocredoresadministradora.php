<?php
	if (isset($_POST["op"])){
		if ($_POST["op"] != "salvar_relacaocredoresadm") return false;
		if ($conn = Transacao::get()){
			$farein_array = explode("-",$_POST["farein"]);
			$processo 		= $farein_array[0];
			$farein 		= $farein_array[1];		
			$tipo_farein 	= tdClass::Criar("persistent",array("td_processo",$processo))->tipoprocesso;
			
			$sqlArquivosAssembleia = "SELECT id FROM td_relacaocredores a WHERE a.td_processo = $processo AND farein = $farein AND a.td_origemcredor = 4 AND EXISTS (SELECT 1 FROM td_arquivosassembleia b WHERE a.id = b.td_credor);";
			$queryArquivosAssembleia = $conn->query($sqlArquivosAssembleia);
			$credoresArquivosEnviados = "";
			while ($linhaArquivosAssembleia = $queryArquivosAssembleia->fetch()){
				$credoresArquivosEnviados .= ($credoresArquivosEnviados == ""?"":",") . $linhaArquivosAssembleia["id"];
			}
			
			$where_excluirCredoresArquivosEnviados = "";
			if ($credoresArquivosEnviados != ""){
				$where_excluirCredoresArquivosEnviados = "AND id NOT IN($credoresArquivosEnviados)";
			}
			$sqlExcluirCredoresRelacaoAdministrador = "DELETE FROM td_relacaocredores WHERE td_processo = $processo AND farein = $farein AND td_origemcredor = 4 " . $where_excluirCredoresArquivosEnviados;
			$queryExcluirCredoresRelacaoAdministrador = $conn->query($sqlExcluirCredoresRelacaoAdministrador);			
			
			$sql_credor = "SELECT id,nome,td_tipo,cpf,cnpj,td_classificacao,td_moeda,td_natureza,td_tipoempresa,email,td_processo,logradouro,numero,bairro,cidade,td_estado,valor,td_origemcredor FROM td_relacaocredores WHERE id IN ({$_POST["credores"]})";
			$query_credor = $conn->query($sql_credor);

			while ($linha_credor = $query_credor->fetch()){
				$nome 			= addslashes($linha_credor["nome"]);
				$tipo			= $linha_credor["td_tipo"];
				$cpf			= $linha_credor["cpf"];	
				$cnpj			= $linha_credor["cnpj"];							
				$natureza		= $linha_credor["td_natureza"];							
				$email			= addslashes($linha_credor["email"]);
				$processo		= $linha_credor["td_processo"];
				$logradouro		= addslashes($linha_credor["logradouro"]);
				$numero			= $linha_credor["numero"];
				$bairro			= addslashes($linha_credor["bairro"]);
				$cidade			= addslashes($linha_credor["cidade"]);
				$estado			= $linha_credor["td_estado"];

				// Seta valores para Divergencia/Habilitação
				if ($linha_credor["td_origemcredor"] == 2 || $linha_credor["td_origemcredor"] == 3){
					$sql_divhab = "	SELECT id,parecervalor,td_parecermoeda,td_parecerclassificacao FROM td_habilitacaodivergencia WHERE td_credor = {$linha_credor["id"]} AND decisao = 1";
					$query_divhab = $conn->query($sql_divhab);
					while ($linha_divhab = $query_divhab->fetch()){
						
						$sqlParecer = "SELECT legitimidade,valor,td_moeda,td_classificacao,cpfj FROM td_habilitacaodivergenciaparecer WHERE td_habilitacaodivergencia = {$linha_divhab["id"]} AND valor > 0";
						$queryParecer = $conn->query($sqlParecer);
						while ($linhaParecer = $queryParecer->fetch()){
						
							$valor = $linhaParecer["valor"];
							$moeda = $linhaParecer["td_moeda"];
							$classificacao = $linhaParecer["td_classificacao"];
						
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
							$sql = "INSERT INTO td_relacaocredores (id,nome,td_tipo,cpf,cnpj,td_classificacao,td_natureza,email,td_processo,logradouro,numero,bairro,cidade,td_estado,td_origemcredor,valor,td_moeda,farein) VALUES(
							{$prox[0]},'{$nome}',{$tipo},'{$cpf}','{$cnpj}',{$classificacao},{$natureza},'{$email}',{$processo},'{$logradouro}','{$numero}','{$bairro}','{$cidade}',{$estado},4,{$valor},{$moeda},{$farein}
							);";
							$conn->exec($sql);
						}						
					}
				}else if($linha_credor["td_origemcredor"] == 1){
					$valor			= $linha_credor["valor"];	
					$moeda			= $linha_credor["td_moeda"];
					$classificacao	= $linha_credor["td_classificacao"];

					$query 			= $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM td_relacaocredores");
					$prox 			= $query->fetch();

					$sql = "INSERT INTO td_relacaocredores (id,nome,td_tipo,cpf,cnpj,td_classificacao,td_natureza,email,td_processo,logradouro,numero,bairro,cidade,td_estado,td_origemcredor,valor,td_moeda,farein) VALUES(
					{$prox[0]},'{$nome}',{$tipo},'{$cpf}','{$cnpj}',{$classificacao},{$natureza},'{$email}',{$processo},'{$logradouro}','{$numero}','{$bairro}','{$cidade}',{$estado},4,{$valor},{$moeda},{$farein}
					);";
					$query = $conn->query($sql);
					if (!$query){	
						var_dump($conn->errorInfo());
					}
				}
				
			}
		}	
		Transacao::fechar();
		exit;
	}
	
	// Salvar Motivo Exclusão
	if (isset($_GET["op"])){
		if ($_GET["op"] == "salvar_motivo"){
			$credor = tdClass::Criar("persistent",array("td_relacaocredores",$_GET["credor"]))->contexto;
			$credor->motivoexclusao = $_GET["motivo"];
			$credor->armazenar();
			Transacao::fechar();
			exit;
		}
	}
	// Tela Modal
	if (isset($_GET["idpk"])){
				
		$btn_gerar = tdClass::Criar("button");
		$btn_gerar->class = "btn btn-primary";
		$btn_gerar->add('Gerar');
		$btn_gerar->id = "btn-gerar-relacao-administradora";
		$btn_gerar->style = "float:right;width:100px;";				
		$btn_gerar->onclick = "
			var credores = '';
			$('.check-credor').each(function(){
				if (!$(this).prop('checked')){
					credores += (credores=='')?$(this).data('id'):',' + $(this).data('id');
				}
			});
			$.ajax({
				url:session.urlsystem,
				type:'POST',
				data:{
					controller:'relacaocredoresadministradora',
					op:'salvar_relacaocredoresadm',
					credores:credores,
					farein:'{$_GET["idpk"]}',
					currentproject:session.projeto
				},
				complete:function(){
					window.open(getURLProject('index.php?controller=impressaorelacaocredoresadministradora&farein={$_GET["idpk"]}') ,'_blank');
				}
			});
		";
		$btn_gerar->mostrar();
		
		//LISTA DE CREDORES HABILITAÇÃO/DIVERGENCIA
		$thCodigo = tdClass::Criar("tabelahead");
		$thCodigo->add(utf8_decode("Código"));
		$thCodigo->width = "15%";
		$thCodigo->align = "center";
		
		$thCPFJ = tdClass::Criar("tabelahead");
		$thCPFJ->add("CNPJ / CPF");
		$thCPFJ->width = "25%";
		$thCPFJ->align = "left";
		
		$thNome = tdClass::Criar("tabelahead");
		$thNome->add(utf8_decode("Nome / Razão Social"));
		$thNome->width = "45%";
		$thNome->align = "left";		

		$thValor = tdClass::Criar("tabelahead");
		$thValor->add(utf8_decode("Valor"));
		$thValor->width = "10%";
		$thValor->align = "left";		
		
		$check = tdClass::Criar("span");
		$check->class = "glyphicon glyphicon-check";
		$check->aria_hidden = "true";

		$thCheck = tdClass::Criar("tabelahead");
		$thCheck->add($check);
		$thCheck->width = "5%";
		
		$thead = tdClass::Criar("thead");
		$thead->add($thCodigo,$thCPFJ,$thNome,$thValor,$thCheck);
		
		$caption = tdClass::Criar("caption");
		$caption->add(utf8_decode("LISTA DE RELAÇÃO - HABILITAÇÃO/DIVERGÊNCIA"));
		
		$table = tdClass::Criar("tabela");
		$table->class = "table table-hover";
		$table->add($caption,$thead);
		
		$tbody = tdClass::Criar("tbody");
		
		$pk_id = explode("-",$_GET["idpk"]);
		$sqlHabDiv = tdClass::Criar("sqlcriterio");		
		$sqlHabDiv->addFiltro("td_processo","=",$pk_id[0]);
		$sqlHabDiv->add(new sqlFiltro("decisao","=",1));

		$dsHabDiv = tdClass::Criar("repositorio",array("td_habilitacaodivergencia"))->carregar($sqlHabDiv);
		$credoresHABDIV = array();
		foreach($dsHabDiv as $habdiv){
			if ($habdiv->td_credor != null && $habdiv->td_credor != "" && is_numeric($habdiv->td_credor)){
				
				$tr = tdClass::Criar("tabelalinha");
				
				$credor = tdClass::Criar("persistent",array("td_relacaocredores",$habdiv->td_credor));
				//echo $habdiv->td_credor . " - ".$credor->contexto->id."<br/>";				
				if ($credor->contexto->farein == $pk_id[1] && ($credor->contexto->td_origemcredor == 2 || $credor->contexto->td_origemcredor == 3)){
					
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
					$checkbox->data_motivoexclusao = $credor->contexto->motivoexclusao;
					
					$checkbox->data_id = $credor->contexto->id;
					
					$sqlParecer = tdClass::Criar("sqlcriterio");
					$sqlParecer->addFiltro("td_habilitacaodivergencia","=",$habdiv->id);
					$habdivparecer = tdClass::Criar("repositorio",array("td_habilitacaodivergenciaparecer"))->carregar($sqlParecer);
					$valorparecer = 0;
					foreach ($habdivparecer as $v){
						$valorparecer += $v->valor;
					}
					
					$tdValor = tdClass::Criar("tabelacelula");
					$tdValor->add(moneyToFloat($valorparecer,true));

					$tdCheck = tdClass::Criar("tabelacelula");
					$tdCheck->add($checkbox);
					
					$tr->add($tdCodigo,$tdCPFJ,$tdNome,$tdValor,$tdCheck);
					$tbody->add($tr);
				}
			}	
		}
		
		$table->add($tbody);
		$table->mostrar();		
		
		// LISTA DE CREDORES INICIAL
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
		$check->class = "glyphicon glyphicon-check";
		$check->aria_hidden = "true";

		$thCheck = tdClass::Criar("tabelahead");
		$thCheck->add($check);
		$thCheck->width = "5%";
		
		$thead = tdClass::Criar("thead");
		$thead->add($thCodigo,$thCPFJ,$thNome,$thCheck);
		
		$caption = tdClass::Criar("caption");
		$caption->add(utf8_decode("LISTA DE RELAÇÃO - INICIAL"));
		
		$table = tdClass::Criar("tabela");
		$table->class = "table table-hover";
		$table->add($caption,$thead);
		
		$tbody = tdClass::Criar("tbody");
				
		$sqlCredores = tdClass::Criar("sqlcriterio");
		$sqlCredores->addFiltro("td_processo","=",$pk_id[0]);
		$sqlCredores->addFiltro("farein","=",$pk_id[1]);
		$sqlCredores->addFiltro("td_origemcredor","=",1);
		$sqlCredores->setPropriedade("order","td_classificacao,codigo");		
		
		$dsCredores = tdClass::Criar("repositorio",array("td_relacaocredores"))->carregar($sqlCredores);
		foreach($dsCredores as $credor){
			$tr = tdClass::Criar("tabelalinha");
			$cpfj = strlen($credor->cnpj)>11?$credor->cnpj:$credor->cpf;

			if (!in_array($cpfj, $credoresHABDIV) || $cpfj == "000.000.000-0#"){
				$tdCodigo = tdClass::Criar("tabelacelula");			
				$tdCodigo->add(completaString($credor->codigo,5));
				
				$tdCPFJ = tdClass::Criar("tabelacelula");
				$tdCPFJ->add($cpfj);
				
				$tdNome = tdClass::Criar("tabelacelula");
				$tdNome->add($credor->nome);
				
				$tdValor = tdClass::Criar("tabelacelula");
				$tdValor->add(moneyToFloat($credor->valor,true));
					
				$checkbox = tdClass::Criar("input");
				$checkbox->type = "checkbox";
				$checkbox->class = "check-credor";
				
				$checkbox->data_id = $credor->id;
				$checkbox->data_motivoexclusao = $credor->motivoexclusao;
				
				$tdCheck = tdClass::Criar("tabelacelula");
				$tdCheck->add($checkbox);
				
				$tr->add($tdCodigo,$tdCPFJ,$tdNome,$tdValor,$tdCheck);
				$tbody->add($tr);
			}
		}
		
		$table->add($tbody);
		$table->mostrar();
		
		$js = tdClass::Criar("script");
		$js->type = "text/javascript";
		$js->add('
			$(".check-credor").click(function(){
				var obj = $(this);
				if (obj.prop("checked")){
					bootbox.prompt("Motivo para Exclusão", function(result){
						obj.attr("data-motivoexclusao",result);
						destacar(obj,true);
						$.ajax({
							url:session.urlsystem,
							data:{
								controller:"relacaocredoresadministradora",
								op:"salvar_motivo",
								credor:obj.data("id"),
								motivo:result,
								currentproject:session.projeto
							}
						});
					});
				}else{
					destacar(obj,false);
					$.ajax({
						url:session.urlsystem,
						data:{
							controller:"relacaocredoresadministradora",
							op:"salvar_motivo",
							credor:obj.data("id"),
							motivo:"",
							currentproject:session.projeto
						}
					});
				}
			});
			function destacar(credorChecked,destacar = true){
				var tr = credorChecked.parents("tr").first();
				if (destacar){
					tr.css("background-color","#fff600");
				}else{
					tr.css("background-color","#FFFFFF");
				}
			}
			$(".check-credor").each(function(){
				if ($(this).data("motivoexclusao") != "" && $(this).data("motivoexclusao") != undefined){
					$(this).prop("checked",true);
					destacar($(this),true);
				}
			});
		');
		$js->mostrar();
		exit;
	}
	
	// Bloco
	$bloco = tdClass::Criar("bloco");
	$bloco->class="col-md-12";	
	
	$titulo = tdClass::Criar("p");
	$titulo->class = "titulo-pagina";
	$titulo->add(utf8_decode("Relação de Credores ( Administradora )"));
	
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
		$sql_farein->addFiltro("td_processo","=",$processo->id);
		$entidade = tdClass::Criar("persistent",array(ENTIDADE,$processo->tipoprocesso));
		if ($entidade->contexto->nome == '' || $entidade->contexto->nome == null) continue;
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
			$a->add($cpfj . " - " . $nome);
			
			$js = tdClass::Criar("script");
			$js->add('
				$("#'.$PK_ID.'").click(function(){
					$("#'.$modalName.' .modal-body").load(getURLProject("index.php?controller=relacaocredoresadministradora&idpk='.$PK_ID.'"));
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