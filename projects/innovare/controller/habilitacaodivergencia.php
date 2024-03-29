<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

	if (isset($_POST["op"])){

		// Excluir
		if ($_POST["op"] == "excluir"){
			$divhab = tdClass::Read("id");
			
			$dv = tdClass::Criar("persistent",array("td_habilitacaodivergencia",$divhab));
			$credor = tdClass::Criar("persistent",array("td_relacaocredores",$dv->contexto->credor));
			
			$sql = tdClass::Criar("sqlcriterio");
			$sql->addFiltro("td_relacaocredores","=",$dv->contexto->credor);
			$arquivos = tdClass::Criar("repositorio",array("td_arquivos_credor"))->deletar($sql);
			
			$credor->contexto->deletar();
			$dv->contexto->deletar();				
			
			Transacao::fechar();
			exit;
			
		}
		// Enviar Parecer
		if ($_POST["op"] == "enviar_parecer"){
			
			if ($conn = Transacao::get()){
				$obj = tdClass::Criar("persistent",array("td_habilitacaodivergencia",$_POST["id"]));
				$credor = tdClass::Criar("persistent",array("td_relacaocredores"));
				if ($credor->contexto->email==""){
					$credor->contexto->email = $_POST["email"];
					$credor->contexto->armazenar();
				}

				include(PATH_LIB . "phpmailer/PHPMailerAutoload.php");

				$mail = new PHPMailer();
				$mail->SetLanguage("br","../sistema/" . PATH_LIB . "phpmailer/language/");
				$mail->IsSMTP();
				$mail->SMTPDebug = 0;
				$mail->SMTPAuth = true;
				$mail->SMTPSecure = 'tls';
				$mail->Host = "smtp.gmail.com";
				$mail->Port = 587;
				$mail->Username = "innovare@innovareadministradora.com.br";
				$mail->Password = "inn@teia#2020";
				$mail->FromName = "Innovare Administradora Judicial";

				//Enderecos que devem ser enviadas as mensagens
				$mail->AddAddress($_POST["email"],"E-Mail do Credor");
				$mail->AddAddress("mcf13511@gmail.com","Administrador");

				$mail->WordWrap = 50;

				//anexando arquivos no email
				if (file_exists('projects/2/arquivos/' . $obj->contexto->parecerarquivo)){
					$mail->AddAttachment('projects/2/arquivos/' . $obj->contexto->parecerarquivo);
				}

				//$mail->AddAttachment("imagem/foto.jpg");
				$mail->IsHTML(true); //enviar em HTML
				$mail->Subject = tdc::utf8("Decisão Proferida");
				$mail->Body = tdc::utf8("
					<img src='http://www.innovareadministradora.com.br/sistema/imagens/logo_nova.png' />
					<p>Caríssimo credor.</p>
					<p>Segue, anexada, a decisão proferida pela administradora judicial pertinente ao pedido de habilitação/divergência protocolado.</p>
					<p>Atenciosamente,</p>
					<br /><br /><br />
					<p><b>MAURICIO COLLE DE FIGUEIREDO - OAB/SC 42.506<br />
						INNOVARE - ADMINISTRADORA EM RECUPERAÇÃO E FALÊNCIA ME - SS</b><br />
						(48) 34138211 | 99757977 | 99783115<br />
						Travessa Germano Magrin, nº 100, sala 407, Edifício Parthenon, Centro, <br />
						88802-090 - Criciúma - Santa Catarina<br />
						<a href='http://www.innovareadministradora.com.br'>http://www.innovareadministradora.com.br</a><br />
					</p>	
				");
				if(!$mail->Send()){
					echo '<center><h4 style="color:#FF0000;font-weight:bold;font-size:16px;">Erro ao enviar E-Mail. Motivo: '.$mail->ErrorInfo.'</h4></center>';
					exit;
				}else{
					$obj->contexto->parecerenviado = 1;
					$obj->contexto->armazenar();
					$conn->commit();
					echo 1;
				}
			}
			exit;
		}
		// Recupera Documento
		if ($_POST["op"] == "recupera_documento"){
			if ($conn = Transacao::get()){
				$obj = tdClass::Criar("persistent",array("td_habilitacaodivergencia",$_POST["id"]));
				echo $obj->contexto->documento;
				exit;
			}	
		}
		// Salva Decisao
		if ($_POST["op"] == "salva_decisao"){
			if ($conn = Transacao::get()){
				$obj = tdClass::Criar("persistent",array("td_habilitacaodivergencia",$_POST["id"]));
				$obj->contexto->decisao = $_POST["status"];
				$obj->contexto->armazenar();
				$conn->commit();
				exit;
			}			
		}
		// Salva Analise
		if ($_POST["op"] == "salva_analise"){						
			if ($conn = Transacao::get()){
				$obj = tdClass::Criar("persistent",array("td_habilitacaodivergencia",$_POST["id"]));
				$obj->contexto->analisado = $_POST["status"];
				$obj->contexto->armazenar();
				$conn->commit();
				exit;
			}
		}
		// Salvar Parecer
		if ($_POST["op"] == "salvar_parecer"){		
			if ($conn = Transacao::get()){
				
				$obj = tdClass::Criar("persistent",array("td_habilitacaodivergenciaparecer"));
				$obj->id = $obj->contexto->proximoID();
				$obj->contexto->valor = ($_POST["parecervalor"]==null||$_POST["parecervalor"]=="")?0:moneyToFloat($_POST["parecervalor"]);
				$obj->contexto->classificacao = $_POST["parecerclassificacao"];
				$obj->contexto->moeda = $_POST["parecermoeda"];
				if ($_POST["legitimidade"]!=""){
					$obj->contexto->legitimidade = $_POST["legitimidade"];	
				}				
				$obj->contexto->projeto = 1;
				$obj->contexto->empresa = 1;
				$obj->contexto->habilitacaodivergencia = $_POST["id"];
				$obj->contexto->armazenar();
				$conn->commit();
			}			
			exit;
		}
		// Carregar Listar Parecer 
		if ($_POST["op"] == "carregar_lista_parecer"){
			$sql = tdClass::Criar("sqlcriterio");
			$sql->addFiltro("habilitacaodivergencia","=",$_POST["id"]);
			$dataset = tdClass::Criar("repositorio",array("td_habilitacaodivergenciaparecer"))->carregar($sql);
			foreach($dataset as $d){
				echo '	<tr>
							<td>'.$d->legitimidade.'</td>
							<td>'.tdc::utf8(tdClass::Criar("persistent",array("td_classificacao",$d->classificacao))->contexto->descricao).'</td>
							<td>'.tdc::utf8(tdClass::Criar("persistent",array("td_moeda",$d->moeda))->contexto->descricao).'</td>
							<td>'.moneyToFloat($d->valor,true).'</td>
							<td><button onclick="excluirDivHabParecer('.$d->id.',this);" aria-label="Excluir Parecer" class="btn btn-dafault" type="button"><span aria-hidden="true" class="fas fa-trash"></span></button></td>
						</tr>	
				';
			}
			exit;
		}
		// Excluir Parecer
		if ($_POST["op"] == "excluir_parecer"){			
			tdClass::Criar("persistent",array("td_habilitacaodivergenciaparecer",$_POST["id"]))->contexto->deletar();
			Transacao::Fechar();
			echo 1;
			exit;
		}
	}
	
	if (isset($_GET["op"])){
		
		if ($_GET["op"] == "transferirprotocoloprocesso"){
			$protocolo 	= tdc::p("td_habilitacaodivergencia",$_GET["protocolo"]);
			$credor		= $protocolo->credor;
			$dados		= explode("^",$_GET["farein"]);
			$farein 	= $dados[0];
			$processo 	= $dados[1];

			$conn = Transacao::Get();
			$conn->exec("UPDATE td_habilitacaodivergencia SET processo = {$processo} WHERE id = {$protocolo->id};");
			$conn->exec("UPDATE td_relacaocredores SET processo = {$processo},farein = {$farein} WHERE id = {$credor};");
			Transacao::Fechar();
			echo 1;
			exit;
		}

		if ($_GET["op"] == "alterarrelacao"){

			$numerorelacao 	= $_GET["relacao"];
			$protocolo		= $_GET["protocolo"];
			$credorid 		= tdClass::Criar("persistent",array("td_habilitacaodivergencia",$protocolo))->contexto->credor;
			
			$conn 		= Transacao::Get();
			$instrucao 	= "UPDATE td_relacaocredores SET numerorelacao = {$numerorelacao} WHERE id = {$credorid};";
			$result 	= $conn->exec($instrucao);
			if ($result){
				echo 1;
			}else{
				echo 0;
			}
			Transacao::Fechar();
			exit;
		}

		if ($_GET["op"] == "excluir_arquivo_credor"){
			$arquivofile = tdClass::Criar("persistent",array("td_arquivos_credor",$_GET["idarquivo"]))->contexto->deletar();
			Transacao::Get()->commit();
			echo 1;
			exit;
		}
		// Enviando arquivos avulso
		if ($_GET["op"] == "addfile"){

			$conn = Transacao::Get();
			$credor = $_GET["credor"];
			
			$arquivofile = tdClass::Criar("persistent",array("td_arquivos_credor"))->contexto;
			$proxID = $arquivofile->proximoID();
			$arquivofile->id = $proxID;
			$filename = $_FILES["add-input-arquivo-" . $_GET["habdiv"]]["name"];
			$arquivofile->descricao = $filename;
			$arquivofile->nome = $proxID . ".pdf";
			$arquivofile->relacaocredores = $credor;
			$arquivofile->origem = 9;
			$arquivofile->armazenar();
			$conn->commit();
			
			move_uploaded_file($_FILES["add-input-arquivo-" . $_GET["habdiv"]]["tmp_name"],"../site/enviodocumentos/arquivos_temp/" .  $proxID . ".pdf");
			
			echo $proxID . "^" . tdc::utf8($filename);
			exit;
		}		

		if ($_GET["op"] == "reenvioarquivo"){

			$divergencia = tdClass::Criar("persistent",array("td_habilitacaodivergencia",$_GET["divergencia"]))->contexto;
			$divergencia->reenviararquivo = $_GET["opc"]=="sim"?1:0;
			$divergencia->armazenar();
			Transacao::Fechar();
			exit;

		}

		if ($_GET["op"] == "converter_parecer"){
			$sqlDivHab = tdClass::Criar("sqlcriterio");
			$sqlDivHab->addFiltro('id','=',471);
			$data_divhab = tdClass::Criar("repositorio",array("td_habilitacaodivergencia"))->carregar($sqlDivHab);
			foreach($data_divhab as $d){
				$parecer = tdClass::Criar("persistent",array("td_habilitacaodivergenciaparecer"))->contexto;
				if ($d->parecervalor != ""){
					$parecer->valor = $d->parecervalor;
				}
				if ($d->parecerclassificacao != ""){
					$parecer->classificacao = $d->parecerclassificacao;
				}
				if ($d->parecermoeda != ""){
					$parecer->moeda = $d->parecermoeda;
				}
				
				$parecer->legitimidade = "";
				$parecer->projeto = 1;
				$parecer->empresa = 1;
				$parecer->habilitacaodivergencia = $d->id;
				$parecer->armazenar();
			}
			Transacao::fechar();
			exit;
		}
		if ($_GET["op"] == "transferencia"){
			$divHabilitacao = tdClass::Criar("div");
			$divHabilitacao->style = "width:45%;float:left;";
			$divBotaoTransferencia = tdClass::Criar("div");
			$divBotaoTransferencia->style = "width:10%;float:left;height:215px;";
			$divDivergencia = tdClass::Criar("div");		
			$divDivergencia->style = "width:45%;float:left;";
			
			$titulo  = tdClass::Criar("h",array(4));
			$titulo->add("Habilitações");
			$divHabilitacao->add($titulo);

			$titulo  = tdClass::Criar("h",array(4));
			$titulo->add("Divergências");
			$divDivergencia->add($titulo);
			
			if ($conn = Transacao::get()){
				$selectHabilitacoes = tdClass::Criar("select");
				$selectHabilitacoes->size = 10;
				$selectHabilitacoes->style = "width:100%;float:left;";
				$selectHabilitacoes->id = "lista-habilitacoes";
				$sqlHab = tdClass::Criar("sqlcriterio");
				$sqlHab->addFiltro("processo","=",$_GET["processo"]);
				$dataset = tdClass::Criar("repositorio",array("td_habilitacaodivergencia"))->carregar($sqlHab);
				foreach($dataset as $data){
					$opt = tdClass::Criar("option");
					$opt->value = $data->id;
					$credor = tdClass::Criar("persistent",array("td_relacaocredores",$data->credor))->contexto;
					if ($credor->origemcredor == 3){
						$opt->add(completaString($data->id,5) . " - " . $credor->nome);
						$selectHabilitacoes->add($opt);
					}	
				}
				$divHabilitacao->add($selectHabilitacoes);
				
				$selectDivergencias = tdClass::Criar("select");
				$selectDivergencias->style = "width:100%;float:left;";
				$selectDivergencias->size = 10;
				$selectDivergencias->id = "lista-divergencias";
				$sqlDiv = tdClass::Criar("sqlcriterio");
				$sqlDiv->addFiltro("processo","=",$_GET["processo"]);
				$dataset = tdClass::Criar("repositorio",array("td_habilitacaodivergencia"))->carregar($sqlDiv);
				foreach($dataset as $data){
					$opt = tdClass::Criar("option");
					$opt->value = $data->id;
					$credor = tdClass::Criar("persistent",array("td_relacaocredores",$data->credor))->contexto;
					if ($credor->origemcredor == 2){					
						$opt->add(completaString($data->id,5) . " - " . tdClass::Criar("persistent",array("td_relacaocredores",$data->credor))->contexto->nome);
						$selectDivergencias->add($opt);
					}	
				}
				$divDivergencia->add($selectDivergencias);				
			}
			
			$habToDiv = tdClass::Criar("button");
			$habToDiv->class = "btn btn-default";
			$habToDiv->aria_label = "Habilitação para Divergência";
			$habToDiv->style = "float:left;clear:left;margin:90px 0 15px 25%;";
			$habToDiv->id = "habtodiv";
			$spanHabToDiv = tdClass::Criar("span");
			$spanHabToDiv->class = "fas fa-arrow-right";
			$spanHabToDiv->aria_true = "true";
			$habToDiv->add($spanHabToDiv);
			
			$divToHab = tdClass::Criar("button");
			$divToHab->class = "btn btn-default";
			$divToHab->aria_label = "Divergência para Habilitação";
			$divToHab->style = "float:left;clear:left;margin-left:25%;";
			$divToHab->id = "divtohab";
			$spanDivToHab = tdClass::Criar("span");
			$spanDivToHab->class = "fas fa-arrow-left";
			$spanDivToHab->aria_true = "true";
			$divToHab->add($spanDivToHab);
			
			$divBotaoTransferencia->add($habToDiv,$divToHab);

			// *** Inicio - Carregar lista de processos para transferencia *** //
			$divProcessos = tdClass::Criar("div");
			$divProcessos->style = "width:100%;float:left;";

			$hrProcessos = tdc::o("hr");
			$hrProcessos->class = "after-load-transferencia";

			$divFarein = tdc::o("div");
			$divFarein->id = "div-farein";
			
			$botaoTransferirFarein = tdc::o("button");
			$botaoTransferirFarein->id = "btn-transferir-farein";
			$botaoTransferirFarein->add("Transferir");
			$botaoTransferirFarein->class = "btn btn-info after-load-transferencia";
	
			$divProcessos->add($hrProcessos,$divFarein,$botaoTransferirFarein);
			// *** Fim - Carregar lista de processos para transferencia *** //
			
			$divHabilitacao->mostrar();
			$divBotaoTransferencia->mostrar();
			$divDivergencia->mostrar();
			$divProcessos->mostrar();
			
			$habSel = tdClass::Criar("input");
			$habSel->type = "hidden";
			$habSel->id = "habsel";
			$habSel->mostrar();

			$divSel = tdClass::Criar("input");
			$divSel->type = "hidden";
			$divSel->id = "divsel";
			$divSel->mostrar();

			$js = tdClass::Criar("script");
			$js->type = "text/javascript";
			$js->add('
				$("#lista-habilitacoes").click(function(){
					$("#habsel").val($(this).val());
				});
				$("#lista-divergencias").click(function(){
					$("#divsel").val($(this).val());
				});
				$("#habtodiv").click(function(){
					var protocolo = $("#habsel").val();
					if (protocolo == ""){
						alert("Nenhuma habiltação selecionada.");
					}else{
						transferir(protocolo,2);
					}
				});
				$("#divtohab").click(function(){
					var protocolo = $("#divsel").val();
					if (protocolo == ""){
						alert("Nenhuma divergência selecionada.");
					}else{
						transferir(protocolo,3);
					}
				});
				function transferir(protocolo,habdiv){
					$.ajax({
						type:"GET",
						url:session.urlmiles,
						data:{
							controller:"habilitacaodivergencia",
							op:"transferir",
							protocolo:protocolo,
							habdiv:habdiv
						},
						complete:function(retorno){
							abrirModalTransferencia('.$_GET["processo"].');
						}
					});
				}
			');
			$js->mostrar();
			exit;
		}
		if ($_GET["op"] == "transferir"){
			$divhab = tdClass::Criar("persistent",array("td_habilitacaodivergencia",$_GET["protocolo"]))->contexto;
			$credor = tdClass::Criar("persistent",array("td_relacaocredores",$divhab->credor))->contexto;
			$credor->origemcredor = $_GET["habdiv"];
			$credor->armazenar();
			Transacao::fechar();	
			/*
			if ($_GET["habdiv"] == 3){ #Habilitação
				
			}else if($_GET["habdiv"] == 2){ #Divergência
			
			}
			*/
			exit;
		}
	}
	
	$css = tdClass::Criar("style");
	$css->type = "text/css";
	$css->add('
		.parecerclassificacao{
			width:100% !important;
		}
		.linha-listarelacaocredores{
			margin-bottom:5px;
		}
		.btn-group-analise label , .btn-group-decisao label{
			float:none !important;
		}
		.after-load-transferencia{
			display:none;
		}
		#btn-transferir-farein{
			float: right;
			margin-top: 10px;
			width: 125px;
		}
		.aviso-numerorelacao{
			font-weight:bold;
			color:#8a6d3b;
		}
	');
	$css->mostrar();

	$js = tdClass::Criar("script");
	$js->type = "text/javascript";
	$js->add('
		$(".valorparecer").val("0,00");
		$(".valorparecer").maskMoney({symbol:"R$", thousands:".", decimal:",", symbolStay: true,showSymbol:true});	
	');
	$js->mostrar();
	
	if (isset($_GET["op"])){
		//  Carrega Panel FAREIN
		if ($_GET["op"] == "carrega_farein"){			
			switch((int)$_GET["tipo"]){
				case 16:
					$farein = "td_recuperanda";
					$descricao = "razaosocial";
				break;
				case 18:
					$farein = "td_insolvente";
					$descricao = "nome";
				break;
				case 19:
					$farein = "td_falencia";
					$descricao = "razaosocial";
				break;				
			}
			
			$sql = tdClass::Criar("sqlcriterio");
			$sql->addFiltro("processo","=",$_GET["processo"]);
			$dataset = tdClass::Criar("repositorio",array($farein))->carregar($sql);
			foreach($dataset as $linha){
				$parms = $_GET["processo"].'-'.$_GET["tipo"].'-'.$linha->id;
				$datapedidosetenca = $_GET["tipo"]==16?"Pedido " . dateToMysqlFormat($linha->datapedido,true):"Setença " . dateToMysqlFormat($linha->datasentenca,true);
				echo '
					<div class="row linha-listarelacaocredores">
						<div class="col-lg-4">
							<div class="input-group">
								<span class="input-group-addon">
									<input type="checkbox" aria-label="1ª RELAÇÃO" id="'.$parms.'-1" data-listarelacao="1" class="checkbox-numerorelacao">
								</span>
								<input type="text" class="form-control" aria-label="1ª RELAÇÃO" value="1ª RELAÇÃO" disabled readonly>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="input-group">
								<span class="input-group-addon">
									<input type="checkbox" aria-label="2ª RELAÇÃO" id="'.$parms.'-2" data-listarelacao="2" class="checkbox-numerorelacao">
								</span>
								<input type="text" class="form-control" aria-label="2ª RELAÇÃO" value="2ª RELAÇÃO" disabled readonly>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="input-group">
								<span class="input-group-addon">
									<input type="checkbox" aria-label="3ª RELAÇÃO" id="'.$parms.'-3" data-listarelacao="3" class="checkbox-numerorelacao">
								</span>
								<input type="text" class="form-control" aria-label="3ª RELAÇÃO" value="3ª RELAÇÃO" disabled readonly>
							</div>
						</div>
					</div>
					<div class="panel panel-primary">
						<div class="panel-heading">
							<span style="cursor:pointer;" class="cabecalho-farein" data-params="'.$parms.'">'.$linha->id . " - " . tdc::utf8($linha->{$descricao}).' - Data do <i>' . $datapedidosetenca . '</i></span>
							<button class="btn btn-default btn-xs icone-pequeno-cabecalho-collapse cabecalho-farein-atualizar" data-params="'.$parms.'" aria-label="Atualizar Lista de Habilitação/Impugnação">
								<span class="fas fa-arrows-rotate" aria-hidden="true"></span>
							</button>
						</div>
						<div class="panel-body" style="display:none;" id="'.$_GET["processo"].'-'.$_GET["tipo"].'-'.$linha->id.'"></div>
					</div>
				';
			}

			$script = tdc::o("script");
			$script->add('
				$(".cabecalho-farein").on("click",function(){
					var objRetorno = "#" + $(this).data("params");
					if($(objRetorno).css("display")=="none"){
						$(objRetorno).show();
						abrirLista(objRetorno);
					}else{
						$(objRetorno).hide();
					}
				});
				$(".cabecalho-farein-atualizar").on("click",function(){
					abrirLista("#" + $(this).data("params"));
				});
			');
			$script->mostrar();
			exit;
		}
		
		// Carrega Habilitação/Divergência
		if ($_GET["op"] == "carrega_divhab"){
			$table = tdClass::Criar("tabela");
			$table->class = "table table-hover";
			$thead = tdClass::Criar("thead");
			
			$tr = tdClass::Criar("tabelalinha");

			$th_credor = tdClass::Criar("tabelahead");
			$th_credor->add("Credor");
			$th_credor->width = "30%";
			
			$th_protocolo = tdClass::Criar("tabelahead");
			$th_protocolo->add("Protocolo");
			$th_protocolo->class = "text-center";
			$th_protocolo->width = "8%";
			
			$th_data = tdClass::Criar("tabelahead");
			$th_data->add("Data");
			$th_data->class = "text-center";
			$th_data->width = "8%";
			
			$th_relacao = tdClass::Criar("tabelahead");		
			$th_relacao->add("Relação");
			$th_relacao->width = "8%";
			$th_relacao->class = "text-center";

			$th_arquivos = tdClass::Criar("tabelahead");
			$th_arquivos->add("Arquivos");
			$th_arquivos->class = "text-center";
			$th_arquivos->width = "8%";
			
			$th_parecer = tdClass::Criar("tabelahead");
			$th_parecer->add("Parecer");
			$th_parecer->class = "text-center";
			$th_parecer->width = "8%";

			$th_analisado = tdClass::Criar("tabelahead");
			$th_analisado->add(tdc::utf8("Analisado"));
			$th_analisado->class = "text-center";
			$th_analisado->width = "10%";
			
			$th_decisao = tdClass::Criar("tabelahead");
			$th_decisao->add(tdc::utf8("Decisão"));
			$th_decisao->class = "text-center";
			$th_decisao->width = "10%";
			
			$th_envio = tdClass::Criar("tabelahead");
			$th_envio->add(tdc::utf8("Envio"));
			$th_envio->class = "text-center";
			$th_envio->width = "8%";
			
			$th_excluir = tdClass::Criar("tabelahead");
			$th_excluir->add(tdc::utf8("Excluir"));
			$th_excluir->class = "text-center";
			$th_excluir->width = "8%";
			
			$tr->add($th_credor,$th_protocolo,$th_data,$th_relacao,$th_arquivos,$th_parecer,$th_analisado,$th_decisao,$th_envio,$th_excluir);
			$thead->add($tr);
			$table->add($thead);

			$sql_habdiv = tdClass::Criar("sqlcriterio");
			$sql_habdiv->addFiltro("processo","=",$_GET["processo"]);
			$dataset_habdiv = tdClass::Criar("repositorio",array("td_habilitacaodivergencia"))->carregar($sql_habdiv);

			$listarelacao = isset($_GET["listarelacao"])?$_GET["listarelacao"]:0;			
			$where = " AND ( b.numerorelacao IN ($listarelacao) OR numerorelacao = 0 OR numerorelacao IS NULL )";

			$conn = Transacao::get();
			$sql2 = '
				SELECT b.origemcredor,IFNULL(b.numerorelacao,0) numerorelacao,a.*
				FROM td_habilitacaodivergencia a,td_relacaocredores b
				WHERE a.credor = b.id
				AND a.processo = '.$_GET["processo"].'
				AND b.farein = '.$_GET["farein"].'
				'.$where.'
				ORDER BY b.origemcredor,numerorelacao ASC;
			';
			$query2 = $conn->query($sql2);

			$total_div		= 0;
			$total_hab		= 0;
			$msg_origem 	= "";
			while($habdiv = $query2->fetchObject()){

				if ($habdiv->origemcredor == 2){
					$total_div++;
					$msg_origem = "Divergência";
				}else{
					$total_hab++;					
					$msg_origem = "Habilitação";
				}
				
				$credor			= tdClass::Criar("persistent",array("td_relacaocredores",$habdiv->credor));
				$tr 			= tdClass::Criar("tabelalinha");
				$tr->protocolo 	= $habdiv->id;
				
				if ($habdiv->numerorelacao == 0){
					$tr->class 						= 'bg-warning';					
					$tdAvisoNumeroRelacao 			= tdClass::Criar('tabelacelula');
					$tdAvisoNumeroRelacao->colspan 	= '10';
					$tdAvisoNumeroRelacao->class 	= 'bg-warning aviso-numerorelacao';
					$tdAvisoNumeroRelacao->add('
						<i class="fas fa-exclamation-triangle"></i>
						Este protocolo não pertence a nenhuma lista.
					');
					
					$trAvisoNumeroRelacao = tdc::o("tabelalinha");
					$trAvisoNumeroRelacao->add($tdAvisoNumeroRelacao);
					$table->add($trAvisoNumeroRelacao);
				}			
				
				$td_credor = tdClass::Criar("tabelacelula");
				$cpfj = trim($credor->contexto->cpf) . trim($credor->contexto->cnpj);
				$td_credor->add(tdc::utf8($msg_origem) . " - <b>[ " . $cpfj . " ]</b><br/>" . utf8charset($credor->contexto->nome));
				
				$td_protocolo = tdClass::Criar("tabelacelula");
				$td_protocolo->add($habdiv->numero);
				$td_protocolo->class = "text-center";
				
				$td_data = tdClass::Criar("tabelacelula");
				$dt = explode(" ",$habdiv->data);
				$td_data->add(dateToMysqlFormat($dt[0],true) . " " . $dt[1]);
				$td_data->class = "text-center";

				$td_relacao = tdClass::Criar("tabelacelula");
				$td_relacao->class = "text-center";
				$td_relacao->add('
					<div class="btn-group btn-group-xs" role="group" aria-label="...">
					  <button type="button" class="btn btn-default alterar-relacao '.($credor->contexto->numerorelacao==1?'active':'').'">1</button>
					  <button type="button" class="btn btn-default alterar-relacao '.($credor->contexto->numerorelacao==2?'active':'').'">2</button>
					  <button type="button" class="btn btn-default alterar-relacao '.($credor->contexto->numerorelacao==3?'active':'').'">3</button>
					</div>
				');

				$td_arquivos = tdClass::Criar("tabelacelula");			
				
				$idListaArquivos = "idlistaarquivos";
				$div_list_arquivos = tdClass::Criar("div");
				$div_list_arquivos->class="list-group";
				$div_list_arquivos->id = $idListaArquivos;
				
				$a = tdClass::Criar("hyperlink");
				$a->href = "#";
				$a->class = "list-group-item active";
				$a->add('Lista de Arquivos');
				$div_list_arquivos->add($a);
				
				
				$nomeBtnFileInput = "add-input-arquivo-" . $habdiv->id;
				$btnFileInput = tdClass::Criar("input");
				$btnFileInput->type = "file";
				$btnFileInput->name = $nomeBtnFileInput;
				$btnFileInput->id = $nomeBtnFileInput;
				$btnFileInput->style = "display:none;";
				
				$targetFormADDFile = "form-target-file-" . $habdiv->id;
				$iframeADDFile = tdClass::Criar("iframe");
				$iframeADDFile->name = $targetFormADDFile;
				$iframeADDFile->style = "display:none";
				
				$nomeFormADDFile = "form-add-file-" . $habdiv->id;				
				$formADDFile = tdClass::Criar("form");
				$formADDFile->action = getURLProject("index.php?controller=habilitacaodivergencia&op=addfile&credor=" . $credor->contexto->id . "&habdiv=" . $habdiv->id);
				$formADDFile->method = "POST";
				$formADDFile->target = $targetFormADDFile;
				$formADDFile->class = "list-group-item";
				$formADDFile->id = $nomeFormADDFile;
				$formADDFile->name = $nomeFormADDFile;
				$formADDFile->enctype="multipart/form-data";
				
				// Botão Adicionar Arquivo
				$nomeBtnAddFiles = "add-arquivo-" . $habdiv->id;
				$btnAddFiles = tdClass::Criar("input");
				$btnAddFiles->type = "button";
					$btnAddSpan = tdClass::Criar("span");
					$btnAddSpan->class = "fas fa-plus";
					$btnAddSpan->aria_hidden = "true";
				$btnAddFiles->class = "btn btn-info btn-block";
				$btnAddFiles->value = " Adicionar Arquivo";
				$btnAddFiles->id = $nomeBtnAddFiles;
				$btnAddFiles->name = $nomeBtnAddFiles;
				$formADDFile->add($btnFileInput,$btnAddFiles);

				$divFileNew = tdClass::Criar("span");
				$nomeSpanFileNew = "span-new-file-" . $habdiv->id;
				$divFileNew->id = $nomeSpanFileNew;
				
				$jsAddFile = tdClass::Criar("script");
				$jsAddFile->add('
					var fileuploadid = "";
					$("#'.$nomeBtnAddFiles.'").click(function(){
						$("#'.$nomeBtnFileInput.'").click();
					});
					$("#'.$nomeBtnFileInput.'").change(function(){
						$("#'.$nomeFormADDFile.'").submit();
						setTimeout(function(){
							var filearray = $("iframe[name='.$targetFormADDFile.']").contents().find("body").html();
							var fileid = filearray.split("^")[0];
							var filename = filearray.split("^")[1];
							var nomea = "arquivo-link-assembleia-"+fileid;
							console.log("FILE ID => " + fileid);
							console.log("FILENAME => " + filename);
							console.log("NOMEA => " + nomea);
							console.log("Nome Span File => " + "#'.$nomeSpanFileNew.'");
							console.log("#'.$idListaArquivos.'");
							console.log(\'<a href="http://www.innovareadministradora.com.br/site/enviodocumentos/arquivos_temp/\'+fileid+\'.pdf" target="_blank" class="list-group-item \'+nomea+\'" style="float:left;width:95%;">\'+filename+\'</a> \');
							var alink =  $(\'<a href="http://www.innovareadministradora.com.br/site/enviodocumentos/arquivos_temp/\'+fileid+\'.pdf" target="_blank" class="list-group-item \'+nomea+\'" style="width:95%;float:left;background-color:#EEE;">\'+filename+\'</a> \');
							console.log(alink);
							$("#'.$nomeSpanFileNew.'").append(alink);
							$("#'.$nomeSpanFileNew.'").append(\'<button onclick="excluirArquivo(this,\'+fileid+\')" data-id="\'+fileid+\'" data-nomea="\'+nomea+\'" class="list-group-item arquivo-link-assembleia-files arquivo-link-assembleia-excluir-\'+fileid+\'" style="width:5%;cursor:pointer;float:right;background-color:#EEE;"><span class="fas fa-trash" aria-hidden="true"></span></button>\');
						},2000);
					});
					
					function excluirArquivo(obj,fileid){
						$.ajax({
							"url":session.urlmiles,
							data:{
								controller:"habilitacaodivergencia",
								op:"excluir_arquivo_credor",
								idarquivo:$(obj).data("id")
							},
							complete:function(result){
								var resultado = parseInt(result.responseText);
								if (resultado == 1){
									$(".arquivo-link-assembleia-"+fileid).hide();
									$(".arquivo-link-assembleia-excluir-"+fileid).hide();
								}
							}
						});
					}
				');
				# $div_list_arquivos->add($formADDFile,$iframeADDFile,$jsAddFile);

				
				$sql_arquivos = tdClass::Criar("sqlcriterio");
				$sql_arquivos->addFiltro("relacaocredores","=",$credor->contexto->id);

				$dataset_arquivos = tdClass::Criar("repositorio",array("td_arquivos_credor"))->carregar($sql_arquivos);

				$formT = tdClass::Criar("form");
				$formT->action = getURLProject("index.php?controller=gerarcapa&key=k&protocolo=" . $habdiv->id . "&farein=".$_GET["farein"] . "&credor=".$credor->contexto->id . "&opt=habdiv");
				$formT->method = "POST";
				$formT->target = "_blank";

				$formZ = tdClass::Criar("form");
				$formZ->action = getURLProject("index.php?controller=gerarzip&key=k&protocolo=" . $habdiv->id . "&farein=".$_GET["farein"] . "&credor=".$credor->contexto->id . "&opt=habdiv");
				$formZ->method = "POST";
				$formZ->target = "iframez";

				$iframeZ = tdClass::Criar("iframe");
				$iframeZ->id = "iframez";
				$iframeZ->name = "iframez";
				$iframeZ->style = "display:none;";
				$formZ->add($iframeZ);

				foreach ($dataset_arquivos as $arquivo){
					if ($arquivo->descricao != '#'){
						$a 				= tdClass::Criar("hyperlink");
						$input 			= tdClass::Criar("input");
						$input->name 	= $arquivo->id;
						$input->type 	= "hidden";
						$urlfile 		= "http://www.innovareadministradora.com.br/site/enviodocumentos_/verificaarquivo.php?filename=" . $arquivo->nome;
						$a->href 		= urldecode($urlfile);
						$input->value 	= $urlfile;
						$a->target 		= "_blank";
						$a->class 		= "list-group-item";
						$a->add($arquivo->descricao);
						$divFileNew->add($a);
					}
				}

				$btnDownload = tdClass::Criar("input");
				$btnDownload->class = "btn btn-warning btn-block";
				$btnDownload->type = "submit";
				$btnDownload->value = "Criar Capa";
				$formT->add($btnDownload);

				$btnDownloadZip = tdClass::Criar("input");
				$btnDownloadZip->class = "btn btn-info btn-block";
				$btnDownloadZip->type = "submit";
				$btnDownloadZip->value = "Baixar Zip";
				$formZ->add($btnDownloadZip);
				
				$div_list_arquivos->add($divFileNew);
				$div_list_arquivos->add($formT);
				$div_list_arquivos->add($formZ);
				
				$panelReenvioArquivos = tdClass::Criar("panel");
				$panelReenvioArquivos->body('
					<label>Liberar reenvio de arquivos ?</label>
					<div class="btn-group" role="group" aria-label="Liberar envio de arquivos?">
					  <button type="button" class="btn btn-'.($habdiv->reenviararquivo==1?"success":"default").' btn-reenvioarquivos" data-opc="sim" data-divergencia="'.$habdiv->id.'">Sim</button>
					  <button type="button" class="btn btn-'.($habdiv->reenviararquivo==0?"danger":"default").' btn-reenvioarquivos" data-opc="nao" data-divergencia="'.$habdiv->id.'">Não</button>
					</div>
					<hr>
					<table class="table">
						<thead>
							<caption> Dados do Remetente </caption>
							<tr>
								<th>Nome: </th>
								<th>E-Mail: </th>
								<th>Telefone: </th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>'.$habdiv->nomeremetente.'</td>
								<td>'.$habdiv->emailremetente.'</td>
								<td>'.$habdiv->telefoneremetente.'</td>
							</tr>
						</tbody>
					</table>
				');
				$jsReenvioArquivos = tdClass::Criar("script");
				$jsReenvioArquivos->add('
					$("#tr-arquivos-'.$habdiv->id.' .btn-reenvioarquivos").click(function(){

						if ($(this).data("opc") == "sim"){
							$(this).removeClass("btn-default");
							$(this).addClass("btn-success");							
							$(".btn-reenvioarquivos[data-opc=nao]").removeClass("btn-danger");
							$(".btn-reenvioarquivos[data-opc=nao]").addClass("btn-default");
						}else{
							$(this).removeClass("btn-default");
							$(this).addClass("btn-danger");
							$(".btn-reenvioarquivos[data-opc=sim]").removeClass("btn-success");
							$(".btn-reenvioarquivos[data-opc=nao]").addClass("btn-default");
						}
						$.ajax({
							url:session.urlmiles,
							data:{
								controller:"habilitacaodivergencia",
								op:"reenvioarquivo",
								opc:$(this).data("opc"),
								divergencia:$(ttable
						});
					});
				');

				$panelReenvioArquivos->add($jsReenvioArquivos);

				$trArquivos = tdClass::Criar("tabelalinha");
				$trArquivos->style = "display:none;";
				$trArquivos->id = "tr-arquivos-" . $habdiv->id;
				$tdArquivos = tdClass::Criar("tabelacelula");
				$tdArquivos->colspan = 9;
				$tdArquivos->add($panelReenvioArquivos,$div_list_arquivos);

				$trArquivos->add($tdArquivos);
				
				$btnArquivo 				= tdClass::Criar("button");
				$btnArquivo->class 			= "btn btn-default";
				$btnArquivo->aria_label 	= "Decisão";				
				$iconArquivo 				= tdClass::Criar("span");
				$iconArquivo->class 		= "fas fa-folder-open";
				$iconArquivo->aria_hidden 	= "true";
				$btnArquivo->add($iconArquivo);
				$btnArquivo->onclick 		= "abreArquivos({$habdiv->id});";
				
				$td_arquivos->align = "center";
				$td_arquivos->add($btnArquivo);
				
				// Analisado
				$td_analisado = tdClass::Criar("tabelacelula");
				$td_analisado->align = "center";
				
				$analise_group = tdClass::Criar("div");
				$analise_group->data_toggle="buttons";
				$analise_group->class = "btn-group btn-group-analise analise_$habdiv->id";
				
				if ($habdiv->analisado == ""){
					$status_btn_sim = "default";
					$status_btn_nao = "default";
				}else{
					if ($habdiv->analisado == 1) {
						$status_btn_sim = "success";
						$status_btn_nao = "default";				
					}else{
						$status_btn_sim = "default";
						$status_btn_nao = "danger";									
					}
				}
				
				$analise_sim_label = tdClass::Criar("label");
				$analise_sim_label->class="btn btn-" . $status_btn_sim;
				$analise_sim_label->onclick = "setAnalise({$habdiv->id},1,this);";
				$analise_sim_input = tdClass::Criar("input");
				$analise_sim_input->type = "radio";
				$analise_sim_input->autocomplete="off";
				$analise_sim_label->add("Sim",$analise_sim_input);
				
				$analise_nao_label = tdClass::Criar("label");
				$analise_nao_label->class="btn btn-" . $status_btn_nao;
				$analise_nao_label->onclick = "setAnalise({$habdiv->id},0,this);";
				$analise_nao_input = tdClass::Criar("input");
				$analise_nao_input->type = "radio";			
				$analise_nao_input->autocomplete="off";
				$analise_nao_label->add(tdc::utf8("Não"),$analise_nao_input);
				
				$analise_group->add($analise_sim_label,$analise_nao_label);
				$td_analisado->add($analise_group);
				
				// Decisão
				$td_decisao = tdClass::Criar("tabelacelula");
				$td_decisao->align = "center";
				
				$decisao_group = tdClass::Criar("div");
				$decisao_group->data_toggle="buttons";
				$decisao_group->class = "btn-group btn-group-decisao decisao_$habdiv->id";
				
				if ($habdiv->decisao==""){
					$status_btn_sim = "default";
					$status_btn_nao = "default";
				}else{
					if ($habdiv->decisao == "1") {
						$status_btn_sim = "success";
						$status_btn_nao = "default";				
					}else{
						$status_btn_sim = "default";
						$status_btn_nao = "danger";									
					}
				}
				
				$decisao_sim_label = tdClass::Criar("label");
				$decisao_sim_label->class="btn btn-" . $status_btn_sim;
				$decisao_sim_label->onclick = "setDecisao({$habdiv->id},1,this);";
				$decisao_sim_input = tdClass::Criar("input");
				$decisao_sim_input->type = "radio";
				$decisao_sim_input->autocomplete="off";
				$decisao_sim_label->add("Sim",$decisao_sim_input);
				
				$decisao_nao_label = tdClass::Criar("label");
				$decisao_nao_label->class="btn btn-" . $status_btn_nao;
				$decisao_nao_label->onclick = "setDecisao({$habdiv->id},0,this);";
				$decisao_nao_input = tdClass::Criar("input");
				$decisao_nao_input->type = "radio";			
				$decisao_nao_input->autocomplete="off";
				$decisao_nao_label->add(tdc::utf8("Não"),$decisao_nao_input);
				
				$decisao_group->add($decisao_sim_label,$decisao_nao_label);
				$td_decisao->add($decisao_group);

				// Modal
				$modalName = "modal-parecer-" . $habdiv->id;
				$modal = tdClass::Criar("modal");
				$modal->nome = $modalName;
				$modal->tamanho = "modal-lg";
				$modal->addHeader("Carregar Arquivo ( Parecer )",null);
				$modal->addBody('<div id="fileuploader-'.$modalName.'" class="fileuploader">Upload</div>');
				
				// Modal
				$modalName_enviar = "modal-parecer-enviar-" . $habdiv->id;
				$modal_enviar = tdClass::Criar("modal");
				$modal_enviar->nome = $modalName_enviar;
				$modal_enviar->tamanho = "modal-lg";
				$modal_enviar->addHeader("Enviar E-Mail ( Parecer )",null);
				$modal_enviar->addBody('
					<form class="form-inline">
						<div class="form-group" style="width:80%">
							<input type="text" placeholder="Digite o E-Mail " id="emailparecer-'.$habdiv->id.'" name="emailparecer-'.$habdiv->id.'" class="form-control" style="width:100%" value="'.$credor->contexto->email.'">
						</div>
						<button class="btn btn-primary" type="button" style="float:left;margin:15px 10px;" onclick="enviarParecer('.$habdiv->id.');">Enviar</button>
						<div id="retorno-email-parecer-'.$habdiv->id.'" style="float:left;margin-top:15px;"></div>
					</form>
					<div class="form-group" style="width:95%" id="retorno-email-parecer-error-'.$habdiv->id.'">					
					</div>				
					<br /><br /><br />
				');
				if ($habdiv->parecerarquivo!=""){
					$modal_enviar->addFooter('<a href="'.Session::Get("PATH_CURRENT_FILE").$habdiv->parecerarquivo.'" target="_blank" class="btn btn-link">Click aqui para visualizar o parecer</a>');
				}else{
					$modal_enviar->addFooter('<p class="text-danger">Você precisa anexar um arquivo no parecer.</p>');
				}
				
				$btnParecer = tdClass::Criar("button");
				$btnParecer->class = "btn btn-default";
				$btnParecer->aria_label = "Decisão";				
				$iconDecisao = tdClass::Criar("span");
				$iconDecisao->class = "fas fa-file";
				$iconDecisao->aria_hidden = "true";
				$btnParecer->add($iconDecisao);
				$btnParecer->onclick = "abreModalParecer({$habdiv->id});";
							
				$td_parecer = tdClass::Criar("tabelacelula");
				$td_parecer->add($btnParecer,$modal,$modal_enviar);
				$td_parecer->align = "center";
				
				$btnEnviar = tdClass::Criar("button");
				$btnEnviar->class = "btn " . ($habdiv->parecerenviado==1?"btn-success":"btn-default");
				$btnEnviar->aria_label = " Decisão";
				$iconEnviar = tdClass::Criar("span");
				$iconEnviar->class = "fas fa-envelope";
				$iconEnviar->aria_hidden = "true";
				$btnEnviar->add($iconEnviar);
				$btnEnviar->onclick = 'abreModalParecerEnviar('.$habdiv->id.');';
				
				$td_envio = tdClass::Criar("tabelacelula");
				$td_envio->align = "center";
				$td_envio->add($btnEnviar);
				
				$btnExcluir = tdClass::Criar("button");
				$btnExcluir->class = "btn btn-danger";
				$btnExcluir->aria_label = "Decisão";
				$iconExcluir = tdClass::Criar("span");
				$iconExcluir->class = "fas fa-trash";
				$iconExcluir->aria_hidden = "true";
				$btnExcluir->add($iconExcluir);
				$btnExcluir->onclick = 'excluirDivHab('.$habdiv->id.',this);';
				
				$td_excluir = tdClass::Criar("tabelacelula");
				$td_excluir->align = "center";
				$td_excluir->add($btnExcluir);				
				
				$tr->add($td_credor,$td_protocolo,$td_data,$td_relacao,$td_arquivos,$td_parecer,$td_analisado,$td_decisao,$td_envio,$td_excluir);
				
				$trParecer 			= tdClass::Criar("tabelalinha");
				$trParecer->style 	= "display:none;";
				$trParecer->id 		= "trParecerForm-" . $habdiv->id;
				$trParecer->style 	= "border-top:0px;background-color:#d5f2cd;";
				
				$tdParecerForm 				= tdClass::Criar("tabelacelula");
				$tdParecerForm->colspan 	= 1;				
				
				$trParecerForm 				= tdClass::Criar("form");
				$trParecerForm->class 		= "form-block";
				$trParecerForm->action 		= getURLProject("index.php?controller=habilitacaodivergencia");
				$trParecerForm->method 		= "POST";
				$trParecerForm->target 		= "retorno_salvar_enviar";
				$trParecerForm->enctype		= "multipart/form-data";
				$trParecerForm->onsubmit 	= "return false";
							
				$trParecerLabelValor 		= tdClass::Criar("label");
				$trParecerLabelValor->for 	= "parecervalor";
				$trParecerLabelValor->add("Valor");
				
				$trParecerInputValor 			= tdClass::Criar("input");
				$trParecerInputValor->type 		= "text";
				$trParecerInputValor->class 	= "form-control valorparecer";
				$trParecerInputValor->id 		= "parecervalor-{$habdiv->id}";
				$trParecerInputValor->name 		= "parecervalor-{$habdiv->id}";				

				$trParecerFormGroupValor 		= tdClass::Criar("div");
				$trParecerFormGroupValor->class = "form-group formato-moeda";			
				$trParecerFormGroupValor->add($trParecerLabelValor,$trParecerInputValor);

				$trParecerLabelMoeda 		= tdClass::Criar("label");
				$trParecerLabelMoeda->for 	= "parecermoeda";
				$trParecerLabelMoeda->add('Moeda');

				$trParecerSelectMoeda 			= tdClass::Criar("selecaounica",array(13));
				$trParecerSelectMoeda->class 	= "form-control";
				$trParecerSelectMoeda->id 		= "parecermoeda-{$habdiv->id}";
				$trParecerSelectMoeda->name 	= "parecermoeda-{$habdiv->id}";

				$trParecerFormGroupMoeda 		= tdClass::Criar("div");
				$trParecerFormGroupMoeda->class = "form-group";
				$trParecerFormGroupMoeda->add($trParecerLabelMoeda,"<br />",$trParecerSelectMoeda);

				$trParecerLabelLegitimidade 		= tdClass::Criar("label");
				$trParecerLabelLegitimidade->for 	= "legitimidade";
				$trParecerLabelLegitimidade->add('Legitimidade');

				$trParecerInputLegitimidade 			= tdClass::Criar("input");
				$trParecerInputLegitimidade->type 		= "text";
				$trParecerInputLegitimidade->class 		= "form-control legitimidade";
				$trParecerInputLegitimidade->id 		= "legitimidade-{$habdiv->id}";
				$trParecerInputLegitimidade->name 		= "legitimidade-{$habdiv->id}";

				$trParecerFormGroupLegitimidade 		= tdClass::Criar("div");
				$trParecerFormGroupLegitimidade->class 	= "form-group";
				$trParecerFormGroupLegitimidade->add($trParecerLabelLegitimidade,"<br />",$trParecerInputLegitimidade);				

				$trParecerLabelClassificacao 		= tdClass::Criar("label");
				$trParecerLabelClassificacao->for 	= "parecerclassificacao";
				$trParecerLabelClassificacao->add(tdc::utf8('Classificação'));

				$trParecerSelectClassificacao 			= tdClass::Criar("selecaounica",array(6));
				$trParecerSelectClassificacao->class 	= "form-control parecerclassificacao";
				$trParecerSelectClassificacao->id 		= "parecerclassificacao-{$habdiv->id}";
				$trParecerSelectClassificacao->name 	= "parecerclassificacao-{$habdiv->id}";

				$trParecerFormGroupClassificacao 		= tdClass::Criar("div");
				$trParecerFormGroupClassificacao->class = "form-group";
				$trParecerFormGroupClassificacao->add($trParecerLabelClassificacao,"<br />",$trParecerSelectClassificacao);
				
				$trParecerLabelSalvar 		= tdClass::Criar("label");
				$trParecerLabelSalvar->for 	= "parecerclassificacao";
				$trParecerLabelSalvar->add('&nbsp;');
				
				$trParecerInputSalvar 			= tdClass::Criar("button");
				$trParecerInputSalvar->class 	= "form-control btn btn-success";
				$trParecerInputSalvar->onclick = 'salvarParecer('.$habdiv->id.');';
				$trParecerInputSalvar->add("Salvar");
							
				$trParecerFormGroupSalvar 			= tdClass::Criar("div");
				$trParecerFormGroupSalvar->class 	= "form-group";
				$trParecerFormGroupSalvar->add($trParecerLabelSalvar,"<br />",$trParecerInputSalvar);

				$trParecerFormGroupRetorno 			= tdClass::Criar("div");
				$trParecerFormGroupRetorno->class 	= "form-group";
				$trParecerFormGroupRetorno->id 		= "retorno-salvar-parecer-{$habdiv->id}";
				$trParecerFormGroupRetorno->style 	= 'float:left;margin:40px 0 0 -5px;height:30px;';

				$trParecerForm->add(
					$trParecerFormGroupValor,
					$trParecerFormGroupMoeda,
					$trParecerFormGroupLegitimidade,
					$trParecerFormGroupClassificacao,
					$trParecerFormGroupSalvar,
					$trParecerFormGroupRetorno
				);
				$tdParecerForm->add($trParecerForm);

				$tdParecerLista = tdClass::Criar("tabelacelula");				
				$tdParecerLista->colspan = 8;
				
				$tabelaParecer = tdClass::Criar("tabela");
				$tabelaParecer->class = "table table-hover";
				$tabelaParecer->id = "tabelaparecer-" . $habdiv->id;
				$tabelaParecerTHead = tdClass::Criar("thead");
				$tabelaParecerTR = tdClass::Criar("tabelalinha");
				
				
				// TH Legitimidade
				$thLegitimidade = tdCLass::Criar("tabelahead");
				$thLegitimidade->add("Legitimidade");
				$tabelaParecerTR->add($thLegitimidade);
				
				// TH Classificação
				$thClassificacao = tdCLass::Criar("tabelahead");
				$thClassificacao->add("Classificação");
				$tabelaParecerTR->add($thClassificacao);

				// TH Moeda
				$thMoeda = tdCLass::Criar("tabelahead");
				$thMoeda->add("Moeda");
				$tabelaParecerTR->add($thMoeda);

				// TH Valor
				$thValor = tdCLass::Criar("tabelahead");
				$thValor->add("Valor");
				$tabelaParecerTR->add($thValor);
				
				// TH Excluir
				$thExcluir = tdCLass::Criar("tabelahead");
				$thExcluir->add("Excluir");
				$tabelaParecerTR->add($thExcluir);
				$tabelaParecerTHead->add($tabelaParecerTR);
				
				$tabelaParecerTBody = tdClass::Criar("tbody");
				
				$tabelaParecer->add($tabelaParecerTHead,$tabelaParecerTBody);
				$tdParecerLista->add($tabelaParecer);
				
				$trParecer->add($tdParecerForm,$tdParecerLista);
				
				$jsArquivos = tdClass::Criar("script");
				$jsArquivos->add('
					function abreArquivos(habdiv){
						if ($("#tr-arquivos-" + habdiv).css("display") == "none"){
							$("#tr-arquivos-" + habdiv).show();
						}else{
							$("#tr-arquivos-"+habdiv).hide();
						}
					}
				');
				$table->add($tr,$trArquivos,$trParecer,$jsArquivos);
			}
			$jsRelacao = tdClass::Criar("script");
			$jsRelacao->add('
				$(".alterar-relacao").click(function(){
					var protocolo 	= $(this).parents("tr").first().attr("protocolo");
					var relacao 	= $(this).html();
					bootbox.confirm({
						message: "Tem certeza que deseja alterar o número da relação ?",
						buttons: {
							confirm: {
								label: "Sim",
								className: "btn-success"
							},
							cancel: {
								label: "Não",
								className: "btn-danger"
							}
						},
						callback: function (result) {
							if (result){
								$.ajax({
									url:session.urlmiles,
									data:{
										controller:"habilitacaodivergencia",
										op:"alterarrelacao",
										protocolo:protocolo,
										relacao:relacao
									},
									complete:function(ret){
										if (parseInt(ret.responseText) == 1){
											$("tr[protocolo="+protocolo+"]").hide();
										}else{
											bootbox.alert("Erro ao alterar relação.");
										}
									}
								});
							}
						}
					});
				});
			');			
			$jsRelacao->mostrar();
			$table->add(tdc::utf8("<tr><td colspan='10' align='right'>Divergências: <b>".$total_div."</b> - Habilitações: <b>".$total_hab."</b></td></tr>"));			
			$table->mostrar();
			exit;
			
		}		
		// Deletar Upload
		if ($_GET["op"] == "deletar_upload"){
			if ($conn = Transacao::get()){
				$obj = tdClass::Criar("persistent",array("td_habilitacaodivergencia",$_GET["id"]));
				if (file_exists('arquivos/'.$obj->contexto->parecerarquivo)){
					unlink('arquivos/'.$obj->contexto->parecerarquivo); #Exclui arquivo físico
				}
				$obj->contexto->parecerarquivo = "";
				$obj->contexto->armazenar();
				$conn->commit();
			}
			exit;			
		}
		// Upload do Parecer
		if ($_GET["op"] == "uploadarquivo"){
			if ($conn = Transacao::get()){
				$obj = tdClass::Criar("persistent",array("td_habilitacaodivergencia",$_GET["id"]));			
				$arquivo = str_replace("/","-",'parecer-' . $obj->contexto->numero . '.' . getExtensao($_FILES['temp_parecer']['name']));
				move_uploaded_file($_FILES['temp_parecer']['tmp_name'],'projects/2/arquivos/'.$arquivo); // Upload
				$obj->contexto->parecerarquivo = $arquivo;
				$obj->contexto->armazenar();
				$conn->commit();
			}
			exit;
		}
		// Salva Documento
		if ($_POST["op"] == "salva_documento"){
			if ($conn = Transacao::get()){
				$obj = tdClass::Criar("persistent",array("td_habilitacaodivergencia",$_POST["id"]));
				$obj->contexto->documento = $_POST["valor"];
				$obj->contexto->armazenar();
				$conn->commit();
				exit;
			}	
		}
		
	}
	
	// Bloco
	$bloco = tdClass::Criar("bloco");
	$bloco->class="col-md-12";	
	
	$titulo = tdClass::Criar("p");
	$titulo->class = "titulo-pagina";
	$titulo->add(tdc::utf8("Habilitação/Divergência de Crédito"));
	
	$sql = tdClass::Criar("sqlcriterio");
	$sql->setPropriedade("order","id DESC");
	$dataset = tdClass::Criar("repositorio",array("td_processo"))->carregar($sql);
	
	$conn = Transacao::Get();
	$processos = tdClass::Criar("div");
	foreach($dataset as $processo){
		
		$todasFAREIN = $primeiroFAREIN = "";
		$sqlFAREIN = 'SELECT id,razaosocial FROM '.($processo->tipoprocesso == 16?'td_recuperanda':'td_falencia').' WHERE processo = ' . $processo->id;
		$queryFAREIN = $conn->query($sqlFAREIN);
		$i = 1;
		while ($linhaFAREIN = $queryFAREIN->fetch()){
			$todasFAREIN .= " - " . $linhaFAREIN["razaosocial"] . "<br/><br/>";
			if ($i==1) $primeiroFAREIN = "- &nbsp;" . $linhaFAREIN["razaosocial"];
			$i++;
		}
		
		$descricaoProcesso = $processo->descricao!=''?' <i>{ '.tdc::utf8($processo->descricao).' }</i>':'';
		$a = tdClass::Criar("hyperlink");
		$a->data_processo 		= $processo->id;
		$a->data_tipoprocesso 	= $processo->tipoprocesso;
		$a->href 				= "#";
		$a->class				= "processo-accordion";
		$a->add("[ " . $processo->id . " ] [ " . $processo->numeroprocesso . " ] " . $descricaoProcesso);

		$btnFarein = '<button type="button" class="btn btn-lg btn-link btn-sm" data-toggle="popover" title="Recuperandas,Falidas e Insolventes." data-placement="left" data-content="'.$todasFAREIN.'" onmouseover=$(this).popover({html:true})><b>TODAS</b></button>';

		$btnTransferir = tdClass::Criar("button");
		$btnTransferir->class = "btn btn-default btn-xs icone-pequeno-cabecalho-collapse";
		$btnTransferir->atia_label = "Atualizar Lista de Habilitação/Impugnação";
		$btnTransferir->onclick = "abrirModalTransferencia({$processo->id});";
			$btnTransferirSpan = tdClass::Criar("span");
			$btnTransferirSpan->class = "fas fa-right-left";
			$btnTransferirSpan->aria_hidden = "true";
		$btnTransferir->add($btnTransferirSpan);

		$divproc = tdClass::Criar("div");
		$divproc->id = "td-processo-" . $processo->id;
		
		// Panel
		$panel = tdClass::Criar("panel");		
		$panel->head($a);
		$panel->head($btnFarein);
		$panel->head($btnTransferir);
		$panel->body($divproc);
		$panel->body->id = "pb-".$processo->id;
		$panel->body->style = "display:none;";
		$processos->add($panel);
	}
	
	$js = tdClass::Criar("script");
	$js->add('
		$(".processo-accordion").click(function(e){
			e.preventDefault();
			e.stopPropagation();
			
			var processo 		= $(this).data("processo");
			var tipoprocesso 	= $(this).data("tipoprocesso");
			abrirFarein(processo,tipoprocesso);
		});
		function abrirFarein(idProcesso,tipoProcesso){
			if($("#pb-" + idProcesso).css("display")=="none"){
				$("#pb-" + idProcesso).show();
				carregar("index.php?controller=habilitacaodivergencia&op=carrega_farein&processo=" + idProcesso + "&tipo=" + tipoProcesso,"#td-processo-"+idProcesso);
			}else{
				$("#pb-" + idProcesso).hide();
			}
		}
		function abrirLista(objRetorno){
			var listasrelacao 	= [];
			var processo 		= objRetorno.split("-")[0].replace("#","");
			var tipo			= objRetorno.split("-")[1];
			var farein			= objRetorno.split("-")[2];
			if ($(".checkbox-numerorelacao:checked").length > 0){
				$(".checkbox-numerorelacao:checked").each(function(){
					if ($(this).is(":checked")){
						listasrelacao.push($(this).data("listarelacao"));
					}
				});
				$.ajax({
					url:session.urlmiles,
					data:{
						controller:"habilitacaodivergencia",
						op:"carrega_divhab",
						processo:processo,
						tipo:tipo,
						farein:farein,
						listarelacao:listasrelacao.join(","),
						currentproject:session.projeto
					},
					beforeSend:function(){
						loader(objRetorno);
					},
					complete:function(ret){
						$(objRetorno).html(ret.responseText);
					}
				});
			}else{
				$(".cabecalho-farein[data-params=\""+objRetorno.replace("#","")+"\"]").click();
				bootbox.alert("Selecione uma relação para abrir as <strong>Habilitações/Divergência</strong>.");
			}			
		}
		function setDecisao(id,status,obj){
			$(".decisao_"+id+" label").removeClass("btn-success");
			$(".decisao_"+id+" label").removeClass("btn-danger");
			$(".decisao_"+id+" label").addClass("btn-default");
			if (status == 1){
				$(obj).removeClass("btn-default");
				$(obj).addClass("btn-success");
				carregarListaParecer(id);
				//$("#trParecerForm-"+id).show();
			}else if (status == 0){
				$(obj).removeClass("btn-default");
				$(obj).addClass("btn-danger");
				$("#trParecerForm-"+id).hide();		
			}
			if (status == 1){
				if ($("#trParecerForm-"+id).css("display") == "none"){
					$("#trParecerForm-"+id).show();
				}else{
					$("#trParecerForm-"+id).hide();
				}
			}

			$.ajax({
				type:"POST",
				url:session.urlmiles,
				data:{
					controller:"habilitacaodivergencia",
					op:"salva_decisao",
					id:id,
					status:status
				}
			});
		}
		function setAnalise(id,status,obj){			
			$(".analise_"+id+" label").removeClass("btn-success");
			$(".analise_"+id+" label").removeClass("btn-danger");
			$(".analise_"+id+" label").addClass("btn-default");
			if (status == 1){
				$(obj).removeClass("btn-default");
				$(obj).addClass("btn-success");
			}else if (status == 0){
				$(obj).removeClass("btn-default");
				$(obj).addClass("btn-danger");
			}
			$.ajax({
				type:"POST",
				url:session.urlmiles,
				data:{
					controller:"habilitacaodivergencia",
					op:"salva_analise",
					id:id,
					status:status
				}
			});
		}		
		function abreModalParecer(id){
			$("#modal-parecer-"+id).modal("show");
			$("#fileuploader-modal-parecer-"+id).uploadFile({
				url:getURLProject("index.php?controller=habilitacaodivergencia&op=uploadarquivo&id="+id),
				fileName:"temp_parecer",
				uploadStr:"Carregar",
				multiple:false,
				maxFileCount:1,
				showDelete:true,
				deleteCallback:function(){
					$.ajax({
						url:session.urlmiles,
						data:{
							controller:"habilitacaodivergencia",
							op:"deletar_upload",
							id:id
						}
					});
				}
			});
		}
		function salvarParecer(habdiv){
			$.ajax({
				url:session.urlmiles,
				type:"POST",
				data:{
					controller:"habilitacaodivergencia",
					op:"salvar_parecer",
					id:habdiv,
					parecervalor:$("#parecervalor-"+habdiv).val(),
					parecermoeda:$("#parecermoeda-"+habdiv).val(),
					parecerclassificacao:$("#parecerclassificacao-"+habdiv).val(),
					legitimidade:$("#legitimidade-"+habdiv).val()
				},
				beforeSend:function(){
					//$("#retorno-salvar-parecer-"+habdiv).html("<img src=\''.PATH_THEME_SYSTEM.'loading2.gif\' />");
				},
				success:function(){
					carregarListaParecer(habdiv);
					//$("#retorno-salvar-parecer-"+habdiv).html("<img src=\''.PATH_THEME_SYSTEM.'check.gif\' />");
					$("#parecervalor-"+habdiv).val("");
					$("#parecermoeda-"+habdiv).val(1);
					$("#parecerclassificacao-"+habdiv).val(1);
					$("#legitimidade-"+habdiv).val("");
				}
			});
		}
		function carregarListaParecer(habdiv){			
			$.ajax({
				url:session.urlmiles,
				type:"POST",
				data:{
					controller:"habilitacaodivergencia",
					op:"carregar_lista_parecer",
					id:habdiv
				},
				success:function(retorno){
					$("#tabelaparecer-" + habdiv + " tbody").html(retorno);
				}
			});
		}
		function excluirDivHabParecer(id,obj){
			$.ajax({
				url:session.urlmiles,
				type:"POST",
				data:{
					controller:"habilitacaodivergencia",
					op:"excluir_parecer",
					id:id
				},
				success:function(retorno){
					if (parseInt(retorno) == 1){
						$(obj).parents("tr").first().remove();
					}
				}
			});
		}
		function abreModalParecerEnviar(habdiv){
			$("#modal-parecer-enviar-"+habdiv).modal("show");
		}
		function enviarParecer(habdiv){
			if ($("#emailparecer-"+habdiv).val() == ""){
				$("#retorno-email-parecer-error-"+habdiv).html("<div class=\'alert alert-danger\' role=\'alert\'>E-Mail &eacute; obrigat&oacute;rio.</div>");
				return false;
			}
			$.ajax({
				url:session.urlmiles,
				type:"POST",
				data:{
					controller:"habilitacaodivergencia",
					op:"enviar_parecer",
					id:habdiv,
					email:$("#emailparecer-"+habdiv).val()
				},
				beforeSend:function(){
					$("#retorno-email-parecer-"+habdiv).html("<img src=\''.PATH_THEME_SYSTEM.'loading2.gif\' />");
				},
				complete:function(ret){
					if (ret.responseText == 1 || ret.responseText == "1"){
						$("#retorno-email-parecer-"+habdiv).html("<img src=\''.PATH_THEME_SYSTEM.'check.gif\' />");
					}else{
						$("#retorno-email-parecer-"+habdiv).html("<img src=\''.PATH_THEME_SYSTEM.'erro.gif\' />");
					}
				}
			});
		}
		function excluirDivHab(habdiv,obj){
			bootbox.dialog({
			  message:"Tem certeza que deseja excluir ?",
			  title:"Aviso",
			  buttons: {
				success:{
				  label:"Excluir da Base de Dados",
				  className: "btn-success",
				  callback: function(){
					$.ajax({
						url:session.urlmiles,
						type:"POST",
						data:{
							controller:"habilitacaodivergencia",
							op:"excluir",
							id:habdiv
						},
						success:function(){
							$(obj).parents("tr").remove();
						}
					});
					
				  }
				},
				danger: {
				  label: "Excluir Apenas o Crédito",
				  className: "btn-danger",
				  callback: function(){
					$.ajax({
						url:session.urlmiles,
						type:"POST",
						data:{
							controller:"habilitacaodivergencia",
							op:"excluir_credito",
							id:habdiv
						},
						success:function(){

						}
					});
				  }				  
				}
			  }
			});			
			
		}
		function abrirModalTransferencia(processo){
			$(".after-load-transferencia").hide();
			$("#modal-transferencia").modal({
				backdrop:false
			});
			$("#modal-transferencia").modal("show");
			carregar("index.php?controller=habilitacaodivergencia&op=transferencia&processo=" + processo,"#modal-transferencia .modal-body p");
		}

		var protocolotransferenciasel = 0;
		$(document).ready(function(){
			$(document).on("dblclick","#lista-divergencias,#lista-habilitacoes",function(){
				protocolotransferenciasel = $(this).val();
				carregar(session.urlmiles + "?controller=selectfarein","#div-farein",function(){
					$(".after-load-transferencia").show();
				});
			});

			$(document).on("click","#btn-transferir-farein:first",function(){
				$.ajax({
					url:session.urlmiles,
					data:{
						controller:"habilitacaodivergencia",
						op:"transferirprotocoloprocesso",
						protocolo:protocolotransferenciasel,
						farein: $("#busca_farein").val()
					},
					complete:function(ret){
						if (parseInt(ret.responseText) == 1){
							$("option[value="+protocolotransferenciasel+"]").remove();
							bootbox.alert("Alterado com Sucesso");
						}else{
							bootbox.alert("Erro ao alterar");
						}
					}
				});
			});
		});
	');

	// Carrega os arquivos para UPLOAD
	$cssUploadFile 			= tdClass::Criar("link");
	$cssUploadFile->rel 	= "stylesheet";
	$cssUploadFile->href 	= URL_LIB . 'jquery/jquery-upload-file-master/css/uploadfile.css';
	
	$jsUploadFile 			= tdClass::Criar("script");
	$jsUploadFile->type 	= "text/javascript";
	$jsUploadFile->src 		= URL_LIB . 'jquery/jquery-upload-file-master/js/jquery.uploadfile.min.js';

	// Modal para Transferencia
	$modalTransf = tdClass::Criar("modal");	
	$modalTransf->nome = "modal-transferencia";
	$modalTransf->tamanho = "modal-lg";
	$modalTransf->addHeader("Transferência",null);
	$modalTransf->addBody("");
	$modalTransf->addFooter("<small class='text-danger after-load-transferencia'>Clique duas vezes na <b>habilitação ou divergência</b> para transferir para um outro processo.</small>");	

	$bloco->add($cssUploadFile,$jsUploadFile,$titulo,$processos,$js,$modalTransf);
	$bloco->mostrar();
?>