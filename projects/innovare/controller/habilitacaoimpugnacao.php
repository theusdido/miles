<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

	if (isset($_POST["op"])){
		// Enviar Parecer
		if ($_POST["op"] == "enviar_parecer"){
			
			if ($conn = Transacao::get()){
				$obj = tdClass::Criar("persistent",array("td_habilitacaoimpugnacao",$_POST["id"]));
				$credor = tdClass::Criar("persistent",array("td_relacaocredores"));
				if ($credor->contexto->email==""){
					$credor->contexto->email = $_POST["email"];
					$credor->contexto->armazenar();
				} 
				
				include(PATH_LIB . "phpmailer/PHPMailerAutoload.php");
				$mail = new PHPMailer();
				$mail->SetLanguage("br","../sistema/" . PATH_LIB . "phpmailer/language/");
				$mail->IsSMTP();
				$mail->Host = "smtp.gmail.com";
				$mail->Port = 465;
				$mail->SMTPAuth = true;
				$mail->Username = "innovare@innovareadministradora.com.br";
				$mail->Password = "inn@teia#2020";
				$mail->From = "innovare@innovareadministradora.com.br";
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
				
				$mail->Subject = "Decisão Proferida";
				$mail->Body = "
					<img src='http://www.innovareadministradora.com.br/sistema/imagens/logo_nova.png' />
					<p>CarÃ­ssimo credor.</p>
					<p>Segue, anexada, a decisão proferida pela administradora judicial pertinente ao pedido de habilitação/divergência protocolado.</p>
					<p>Atenciosamente,</p>
					<br /><br /><br />
					<p><b>MAURICIO COLLE DE FIGUEIREDO - OAB/SC 42.506<br />
						INNOVARE - ADMINISTRADORA EM RECUPERAÇÃO E FALÃŠNCIA ME - SS</b><br />
						(48) 34138211 | 99757977 | 99783115<br />
						Travessa Germano Magrin, nº 100, sala 407, Edifício Parthenon, Centro, <br />
						88802-090 - Criciúma - Santa Catarina<br />
						<a href='http://www.innovareadministradora.com.br'>http://www.innovareadministradora.com.br</a><br />
					</p>	
				";
				if(!$mail->Send()){
					echo '<center><h4 style="color:#FF0000;font-weight:bold;font-size:16px;">Erro ao enviar E-Mail. Motivo: '.$mail->ErrorInfo.'</h4></center>';
					exit;
				}else{
					$obj->contexto->parecerenviado = 1;
					$obj->contexto->armazenar();
					$conn->commit();
				}
			}	
		}
		// Recupera Documento
		if ($_POST["op"] == "recupera_documento"){
			if ($conn = Transacao::get()){
				$obj = tdClass::Criar("persistent",array("td_habilitacaoimpugnacao",$_POST["id"]));
				echo $obj->contexto->documento;
				exit;
			}	
		}
		// Salva Decisao
		if ($_POST["op"] == "salva_decisao"){
			if ($conn = Transacao::get()){
				$obj = tdClass::Criar("persistent",array("td_habilitacaoimpugnacao",$_POST["id"]));
				$obj->contexto->decisao = $_POST["status"];
				$obj->contexto->armazenar();
				$conn->commit();
				exit;
			}			
		}
		// Salva Analise
		if ($_POST["op"] == "salva_analise"){						
			if ($conn = Transacao::get()){
				$obj = tdClass::Criar("persistent",array("td_habilitacaoimpugnacao",$_POST["id"]));
				$obj->contexto->analisado = $_POST["status"];
				$obj->contexto->armazenar();
				$conn->commit();
				exit;
			}
		}
		// Salvar Parecer
		if ($_POST["op"] == "salvar_parecer"){			
			if ($conn = Transacao::get()){
				
				$obj = tdClass::Criar("persistent",array("td_habilitacaoimpugnacaoparecer"));
				$obj->contexto->valor = ($_POST["parecervalor"]==null||$_POST["parecervalor"]=="")?0:moneyToFloat($_POST["parecervalor"]);
				$obj->contexto->classificacao = $_POST["parecerclassificacao"];
				$obj->contexto->moeda = $_POST["parecermoeda"];
				if ($_POST["legitimidade"]!=""){
					$obj->contexto->legitimidade = $_POST["legitimidade"];	
				}				
				$obj->contexto->projeto = 1;
				$obj->contexto->empresa = 1;
				$obj->contexto->habilitacaoimpugnacao = $_POST["id"];
				$obj->contexto->armazenar();
				$conn->commit();
			}			
			exit;
		}
		// Carregar Listar Parecer 
		if ($_POST["op"] == "carregar_lista_parecer"){
			$sql = tdClass::Criar("sqlcriterio");
			$sql->addFiltro("habilitacaoimpugnacao","=",$_POST["id"]);
			$dataset = tdClass::Criar("repositorio",array("td_habilitacaoimpugnacaoparecer"))->carregar($sql);
			foreach($dataset as $d){
				echo '	<tr>
							<td>'.$d->legitimidade.'</td>
							<td>'.utf8_encode(tdClass::Criar("persistent",array("td_classificacao",$d->classificacao))->contexto->descricao).'</td>
							<td>'.utf8_encode(tdClass::Criar("persistent",array("td_moeda",$d->moeda))->contexto->descricao).'</td>
							<td>'.moneyToFloat($d->valor,true).'</td>
							<td><button onclick="excluirDivHabParecer('.$d->id.',this);" aria-label="Excluir Parecer" class="btn btn-dafault" type="button"><span aria-hidden="true" class="glyphicon glyphicon-trash"></span></button></td>
						</tr>	
				';
			}
			exit;
		}
		// Excluir Parecer
		if ($_POST["op"] == "excluir_parecer"){			
			tdClass::Criar("persistent",array("td_habilitacaoimpugnacaoparecer",$_POST["id"]))->contexto->deletar();
			Transacao::Fechar();
			echo 1;
			exit;
		}
		
		// Salva Documento
		if ($_POST["op"] == "salva_documento"){
			if ($conn = Transacao::get()){
				$obj = tdClass::Criar("persistent",array("td_habilitacaoimpugnacao",$_POST["id"]));
				$obj->contexto->documento = $_POST["valor"];
				$obj->contexto->armazenar();
				$conn->commit();
				exit;
			}	
		}
		if ($_POST["op"] == "salvar_habilitacaoimpugnacao"){
			if ($conn = Transacao::get()){
				
				if ($_POST["credor"] == ""){
					$credor = tdClass::Criar("persistent",array("td_relacaocredores"))->contexto;
					$idCredor = $credor->getUltimo() + 1;
					$credor->id = $idCredor;
					$credor->processo = $_POST["processo"];
					$credor->farein = 	$_POST["farein"];
					$credor->tipo = $_POST["tipopessoa"];
					$credor->tipoempresa = $_POST["tipoempresa"];				
					if ($_POST["tipopessoa"] == 1){
						$credor->cnpj = $_POST["cpfj"];
					}else{
						$credor->cpf = $_POST["cpfj"];
					}				
					$credor->nome = $_POST["nome"];
					$credor->email = $_POST["email"];
					$credor->cep = $_POST["cep"];
					$credor->complemento = $_POST["complemento"];
					$credor->logradouro = $_POST["logradouro"];
					$credor->numero = $_POST["numero"];
					$credor->bairro =$_POST["bairro"];
					$credor->cidade =$_POST["cidade"];
					$credor->estado = 	$_POST["estado"];
					$credor->origemcredor = 6;
					$credor->classificacao = $_POST["classificacao"];
					$credor->moeda = $_POST["moeda"];
					$credor->natureza = $_POST["natureza"];
					$credor->valor = $_POST["valor"];
					$credor->codigo = 0;
					$credor->armazenar();
				}else{					
					$credor = tdClass::Criar("persistent",array("td_relacaocredores"))->contexto;
					$credorOLD = tdClass::Criar("persistent",array("td_relacaocredores",$_POST["credor"]))->contexto;
					$idCredor = $credor->getUltimo() + 1;
					$credor->id = $idCredor;				
					$credor->processo = $credorOLD->processo;
					$credor->farein = $credorOLD->farein;
					$credor->tipo = $credorOLD->tipo;
					$credor->tipoempresa = $credorOLD->tipoempresa;
					if ($credorOLD->tipo == 1){
						$credor->cnpj = $credorOLD->cnpj;
					}else{
						$credor->cpf = $credorOLD->cpf;
					}				
					$credor->nome = utf8_encode($credorOLD->nome);
					$credor->email = utf8_encode($credorOLD->email);
					$credor->cep = $credorOLD->cep;
					$credor->complemento = utf8_encode($credorOLD->complemento);
					$credor->logradouro = utf8_encode($credorOLD->logradouro);
					$credor->numero = $credorOLD->numero;
					$credor->bairro = utf8_encode($credorOLD->bairro);
					$credor->cidade = $credorOLD->cidade;
					$credor->estado = $credorOLD->estado;
					$credor->origemcredor = 7;
					$credor->classificacao = $credorOLD->classificacao;
					$credor->moeda = $credorOLD->moeda;
					$credor->natureza = $credorOLD->natureza;
					$credor->valor = $credorOLD->valor;
					$credor->codigo = 0;
					$credor->armazenar();
				}

				$query = $conn->query("SELECT IFNULL(MAX(id),0)+1 id FROM td_habilitacaoimpugnacao");
				$prox = $query->fetch();
				
				$habilitacaoimpugnacao = tdClass::Criar("persistent",array("td_habilitacaoimpugnacao"))->contexto;
				$habilitacaoimpugnacao->id = $prox["id"];
				$habilitacaoimpugnacao->numero = str_pad($prox["id"],5, 0, STR_PAD_LEFT) . "/" . date("Y");
				$habilitacaoimpugnacao->projeto = 1;
				$habilitacaoimpugnacao->empresa = 1;
				$habilitacaoimpugnacao->processo = $_POST["processo"];
				$habilitacaoimpugnacao->magistrado = $_POST["magistrado"];
				$habilitacaoimpugnacao->comarca = $_POST["comarca"];
				$habilitacaoimpugnacao->data = date("Y-m-d");
				$habilitacaoimpugnacao->credor = $idCredor;
				$habilitacaoimpugnacao->credorimpugnado = $_POST["credor"];
				$habilitacaoimpugnacao->parecerenviado = 0;
				$habilitacaoimpugnacao->decisao = null;
				$habilitacaoimpugnacao->analisado = "0";
				$habilitacaoimpugnacao->numeroprocesso = $_POST["numeroprocesso"];
				$habilitacaoimpugnacao->armazenar();
				
				Transacao::fechar();
				echo 1;
				exit;			
			}
		}
			
	}
	
	if (isset($_GET["op"])){
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
			$th_protocolo->width = "10%";
			
			$th_data = tdClass::Criar("tabelahead");
			$th_data->add("Data");
			$th_data->class = "text-center";
			$th_data->width = "10%";

			$th_processo = tdClass::Criar("tabelahead");
			$th_processo->add("Processo");
			$th_processo->class = "text-center";
			$th_processo->width = "13%";
			
			$th_parecer = tdClass::Criar("tabelahead");
			$th_parecer->add("AJ<br>Parecer");
			$th_parecer->class = "text-center";
			$th_parecer->width = "8%";

			$th_parecermp = tdClass::Criar("tabelahead");
			$th_parecermp->add(utf8_decode("MP<br/>Parecer"));
			$th_parecermp->class = "text-center";
			$th_parecermp->width = "8%";
			
			$th_decisao = tdClass::Criar("tabelahead");
			$th_decisao->add(utf8_decode("Decisão"));
			$th_decisao->class = "text-center";
			$th_decisao->width = "13%";

			$th_decisaodocumento = tdClass::Criar("tabelahead");
			$th_decisaodocumento->add(utf8_decode("Doc.<br/>Decisão"));
			$th_decisaodocumento->class = "text-center";
			$th_decisaodocumento->width = "8%";
			
			$tr->add($th_credor,$th_protocolo,$th_data,$th_processo,$th_parecer,$th_parecermp,$th_decisaodocumento,$th_decisao);
			$thead->add($tr);
			$table->add($thead);
			
			$sql_habdiv = tdClass::Criar("sqlcriterio");
			$sql_habdiv->addFiltro("processo","=",$_GET["processo"]);

			$dataset_habdiv = tdClass::Criar("repositorio",array("td_habilitacaoimpugnacao"))->carregar($sql_habdiv);
			foreach ($dataset_habdiv as $habdiv){
				
				$credor 	= tdClass::Criar("persistent",array("td_relacaocredores",$habdiv->credor));
				$tr = tdClass::Criar("tabelalinha");
				
				$td_credor = tdClass::Criar("tabelacelula");
				$td_credor->add("[ ".$credor->contexto->id." ] " . $credor->contexto->nome);
				
				$td_protocolo = tdClass::Criar("tabelacelula");
				$td_protocolo->add($habdiv->numero);
				$td_protocolo->class = "text-center";

				$td_data = tdClass::Criar("tabelacelula");
				$td_data->add(dateToMysqlFormat($habdiv->data,true));
				$td_data->class = "text-center";

				$td_numeroprocesso = tdClass::Criar("tabelacelula");			

				
				$td_numeroprocesso->align = "center";
				$td_numeroprocesso->add($habdiv->numeroprocesso);
							
				// Decisão
				$td_decisao = tdClass::Criar("tabelacelula");
				$td_decisao->align = "center";
				
				$decisao_group = tdClass::Criar("div");
				$decisao_group->data_toggle="buttons";
				$decisao_group->class = "btn-group decisao_$habdiv->id";
				
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
				$decisao_nao_label->add(utf8_decode("Não"),$decisao_nao_input);
				
				$decisao_group->add($decisao_sim_label,$decisao_nao_label);
				$td_decisao->add($decisao_group);

				// Modal Parecer AJ
				$modalNameAJ = "modal-pareceraj-" . $habdiv->id;
				$modalAJ = tdClass::Criar("modal");
				$modalAJ->nome = $modalNameAJ;
				$modalAJ->tamanho = "modal-lg";
				$modalAJ->addHeader("Parecer AJ",null);
				$modalAJ->addBody('<div id="fileuploader-'.$modalNameAJ.'" class="fileuploader">Upload</div>');
							
				$btnParecerAJ = tdClass::Criar("button");
				$btnParecerAJ->class = "btn btn-default";
				$btnParecerAJ->aria_label = "Parecer AJ";				
				$iconParecerAJ = tdClass::Criar("span");
				$iconParecerAJ->class = "glyphicon glyphicon-file";
				$iconParecerAJ->aria_hidden = "true";
				$btnParecerAJ->add($iconParecerAJ);
				$btnParecerAJ->onclick = "abreModalParecerAJ({$habdiv->id});";
							
				$td_parecerAJ = tdClass::Criar("tabelacelula");
				$td_parecerAJ->add($btnParecerAJ,$modalAJ);
				$td_parecerAJ->align = "center";				
				// Modal Parecer AJ
				
				// Modal Parecer MP
				$modalNameMP = "modal-parecermp-" . $habdiv->id;
				$modalMP = tdClass::Criar("modal");
				$modalMP->nome = $modalNameMP;
				$modalMP->tamanho = "modal-lg";
				$modalMP->addHeader("Parecer MP",null);
				$modalMP->addBody('<div id="fileuploader-'.$modalNameMP.'" class="fileuploader">Upload</div>');
							
				$btnParecerMP = tdClass::Criar("button");
				$btnParecerMP->class = "btn btn-default";
				$btnParecerMP->aria_label = "Parecer MP";
				$iconParecerMP = tdClass::Criar("span");
				$iconParecerMP->class = "glyphicon glyphicon-file";
				$iconParecerMP->aria_hidden = "true";
				$btnParecerMP->add($iconParecerMP);
				$btnParecerMP->onclick = "abreModalParecerMP({$habdiv->id});";

				$td_parecerMP = tdClass::Criar("tabelacelula");
				$td_parecerMP->add($btnParecerMP,$modalMP);
				$td_parecerMP->align = "center";
				// Modal Parecer MP
				
				// Modal Documento Decisão
				$modalNameDD = "modal-dd-" . $habdiv->id;
				$modalDD = tdClass::Criar("modal");
				$modalDD->nome = $modalNameDD;
				$modalDD->tamanho = "modal-lg";
				$modalDD->addHeader("Documento Decisão",null);
				$modalDD->addBody('<div id="fileuploader-'.$modalNameDD.'" class="fileuploader">Upload</div>');
							
				$btnDD = tdClass::Criar("button");
				$btnDD->class = "btn btn-default";
				$btnDD->aria_label = "Documento Decisão";
				$iconDD = tdClass::Criar("span");
				$iconDD->class = "glyphicon glyphicon-file";
				$iconDD->aria_hidden = "true";
				$btnDD->add($iconDD);
				$btnDD->onclick = "abreModalDD({$habdiv->id});";

				$td_DocumentoDecisao = tdClass::Criar("tabelacelula");
				$td_DocumentoDecisao->add($btnDD,$modalDD);
				$td_DocumentoDecisao->align = "center";
				// Modal Documento Decisão		
							
				$tr->add($td_credor,$td_protocolo,$td_data,$td_numeroprocesso,$td_parecerAJ,$td_parecerMP,$td_DocumentoDecisao,$td_decisao);
				
				$trParecer = tdClass::Criar("tabelalinha");
				$trParecer->style = "display:none;";
				$trParecer->id = "trParecerForm-" . $habdiv->id;
				$trParecer->style = "border-top:0px;background-color:#d5f2cd;";
				
				$tdParecerForm = tdClass::Criar("tabelacelula");
				$tdParecerForm->colspan = 1;
				
				
				$trParecerForm = tdClass::Criar("form");
				$trParecerForm->class = "form-block";
				$trParecerForm->action = "index.php?controller=habilitacaoimpugnacao";
				$trParecerForm->method = "POST";
				$trParecerForm->target = "retorno_salvar_enviar";
				$trParecerForm->enctype= "multipart/form-data";
							
				$trParecerLabelValor = tdClass::Criar("label");
				$trParecerLabelValor->add("Valor");
				$trParecerLabelValor->for = "parecervalor";
				
				$trParecerInputValor = tdClass::Criar("input");
				$trParecerInputValor->type = "text";
				$trParecerInputValor->class = "form-control valorparecer";
				$trParecerInputValor->id = "parecervalor-{$habdiv->id}";
				$trParecerInputValor->name = "parecervalor-{$habdiv->id}";
				//$trParecerInputValor->value = moneyToFloat(($habdiv->parecervalor=="" || $habdiv->parecervalor==null)?$credor->contexto->valor:$habdiv->parecervalor,true);
				
				$jsValorParecer = tdClass::Criar("script");
				$jsValorParecer->type = "text/javascript";
				$jsValorParecer->add('
					$(".valorparecer").val("0,00");
					$(".valorparecer").maskMoney({symbol:"R$", thousands:".", decimal:",", symbolStay: true,showSymbol:true});	
				');

				$trParecerFormGroupValor = tdClass::Criar("div");
				$trParecerFormGroupValor->class = "form-group formato-moeda";			
				$trParecerFormGroupValor->add($jsValorParecer,$trParecerLabelValor,$trParecerInputValor);
				
				$trParecerLabelMoeda = tdClass::Criar("label");
				$trParecerLabelMoeda->for = "parecermoeda";
				$trParecerLabelMoeda->add('Moeda');
				
				//$trParecerSelectMoeda = tdClass::Criar("selecaounica",array(13,($habdiv->td_parecermoeda=="" || $habdiv->td_parecermoeda==null)?$credor->contexto->td_moeda:$habdiv->td_parecermoeda));
				$trParecerSelectMoeda = tdClass::Criar("selecaounica",array(13));
				$trParecerSelectMoeda->class = "form-control";
				$trParecerSelectMoeda->id = "parecermoeda-{$habdiv->id}";
				$trParecerSelectMoeda->name = "parecermoeda-{$habdiv->id}";

				$trParecerFormGroupMoeda = tdClass::Criar("div");
				$trParecerFormGroupMoeda->class = "form-group";
				$trParecerFormGroupMoeda->add($trParecerLabelMoeda,"<br />",$trParecerSelectMoeda);
				
				$trParecerLabelLegitimidade = tdClass::Criar("label");
				$trParecerLabelLegitimidade->for = "legitimidade";
				$trParecerLabelLegitimidade->add('Legitimidade');
				
				$trParecerInputLegitimidade = tdClass::Criar("input");
				$trParecerInputLegitimidade->type = "text";
				$trParecerInputLegitimidade->class = "form-control legitimidade";
				$trParecerInputLegitimidade->id = "legitimidade-{$habdiv->id}";
				$trParecerInputLegitimidade->name = "legitimidade-{$habdiv->id}";
				//$trParecerInputLegitimidade->value = ($habdiv->legitimidade=="" || $habdiv->legitimidade==null)?$credor->contexto->nome:$habdiv->legitimidade;

				$trParecerFormGroupLegitimidade = tdClass::Criar("div");
				$trParecerFormGroupLegitimidade->class = "form-group";
				$trParecerFormGroupLegitimidade->add($trParecerLabelLegitimidade,"<br />",$trParecerInputLegitimidade);				

				$trParecerLabelClassificacao = tdClass::Criar("label");
				$trParecerLabelClassificacao->for = "parecerclassificacao";
				$trParecerLabelClassificacao->add(utf8_decode('Classificação'));
				
				//$trParecerSelectClassificacao = tdClass::Criar("selecaounica",array(6,($habdiv->td_parecerclassificacao==""||$habdiv->td_parecerclassificacao==null)?$credor->contexto->td_classificacao:$habdiv->td_parecerclassificacao));
				$trParecerSelectClassificacao = tdClass::Criar("selecaounica",array(6));
				$trParecerSelectClassificacao->class = "form-control parecerclassificacao";
				$trParecerSelectClassificacao->id = "parecerclassificacao-{$habdiv->id}";
				$trParecerSelectClassificacao->name = "parecerclassificacao-{$habdiv->id}";

				$trParecerFormGroupClassificacao = tdClass::Criar("div");
				$trParecerFormGroupClassificacao->class = "form-group";
				$trParecerFormGroupClassificacao->add($trParecerLabelClassificacao,"<br />",$trParecerSelectClassificacao);
				
				$trParecerLabelSalvar = tdClass::Criar("label");
				$trParecerLabelSalvar->for = "parecerclassificacao";
				$trParecerLabelSalvar->add('&nbsp;');
				
				$trParecerInputSalvar = tdClass::Criar("button");
				$trParecerInputSalvar->class = "form-control btn btn-success";
				$trParecerInputSalvar->add("Salvar");
				$trParecerInputSalvar->onclick = 'salvarParecer('.$habdiv->id.');';
							
				$trParecerFormGroupSalvar = tdClass::Criar("div");
				$trParecerFormGroupSalvar->class = "form-group";
				$trParecerFormGroupSalvar->add($trParecerLabelSalvar,"<br />",$trParecerInputSalvar);

				
				$trParecerFormGroupRetorno = tdClass::Criar("div");
				$trParecerFormGroupRetorno->class = "form-group";
				$trParecerFormGroupRetorno->id = "retorno-salvar-parecer-{$habdiv->id}";
				$trParecerFormGroupRetorno->style = 'float:left;margin:40px 0 0 -5px;height:30px;';
							
				$trParecerForm->add($trParecerFormGroupValor,$trParecerFormGroupMoeda,$trParecerFormGroupLegitimidade,$trParecerFormGroupClassificacao,$trParecerFormGroupSalvar,$trParecerFormGroupRetorno);
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
				
				$table->add($tr,$trParecer,$jsArquivos);
			}
			$table->mostrar();
			exit;
		}		
		// Deletar Upload
		if ($_GET["op"] == "deletar_upload"){
			if ($conn = Transacao::get()){
				$obj = tdClass::Criar("persistent",array("td_habilitacaoimpugnacao",$_GET["id"]));
				if (file_exists('arquivos/'.$obj->contexto->parecerarquivo)){
					unlink('arquivos/'.$obj->contexto->parecerarquivo); #Exclui arquivo físico
				}
				$obj->contexto->parecerarquivo = "";
				$obj->contexto->armazenar();
				$conn->commit();
			}
			exit;			
		}
		// Upload do Parecer AJ
		if ($_GET["op"] == "uploadarquivoAJ"){
			if ($conn = Transacao::get()){
				$obj = tdClass::Criar("persistent",array("td_habilitacaoimpugnacao",$_GET["id"]));			
				$arquivo = str_replace("/","-",'parecerAJ-' . $obj->contexto->numero . '.' . getExtensao($_FILES['temp_parecer']['name']));
				move_uploaded_file($_FILES['temp_parecer']['tmp_name'],'arquivos/'.$arquivo); // Upload
				$obj->contexto->parecerarquivo = $arquivo;
				$obj->contexto->armazenar();
				$conn->commit();
			}
			exit;
		}
		// Upload do Parecer MP
		if ($_GET["op"] == "uploadarquivoMP"){
			if ($conn = Transacao::get()){
				$obj = tdClass::Criar("persistent",array("td_habilitacaoimpugnacao",$_GET["id"]));			
				$arquivo = str_replace("/","-",'parecerMP-' . $obj->contexto->numero . '.' . getExtensao($_FILES['temp_parecer']['name']));
				move_uploaded_file($_FILES['temp_parecer']['tmp_name'],'arquivos/'.$arquivo); // Upload
				$obj->contexto->parecerarquivo = $arquivo;
				$obj->contexto->armazenar();
				$conn->commit();
			}
			exit;
		}
		// Upload Documento Decisão
		if ($_GET["op"] == "uploadarquivoDD"){
			if ($conn = Transacao::get()){
				$obj = tdClass::Criar("persistent",array("td_habilitacaoimpugnacao",$_GET["id"]));			
				$arquivo = str_replace("/","-",'DocumentoDecisao-' . $obj->contexto->numero . '.' . getExtensao($_FILES['temp_parecer']['name']));
				move_uploaded_file($_FILES['temp_parecer']['tmp_name'],'arquivos/'.$arquivo); // Upload
				$obj->contexto->parecerarquivo = $arquivo;
				$obj->contexto->armazenar();
				$conn->commit();
			}
			exit;
		}		
		// Carrega Farein
		if ($_GET["op"] == "carrega_farein"){
			$tipoprocesso = tdClass::Criar("persistent",array("td_processo",$_GET["processo"]))->contexto->tipoprocesso;
			$sql = tdClass::Criar("sqlcriterio");
			$sql->addFiltro("td_processo","=",$_GET["processo"]);
			$dataset = tdClass::Criar("repositorio",array(tdClass::Criar("persistent",array(ENTIDADE,$tipoprocesso))->contexto->nome))->carregar($sql);
			foreach($dataset as $d){
				echo '<option value="'.$d->id.'">' . utf8_encode($d->razaosocial) . '</option>';
			}
			exit;
		}
	}
	
echo '

<script type="text/javascript" src="system/classes/tdc/gradededados.class.js"></script>
<script type="text/javascript" src="system/lib/tails/validar.js"></script>
<script type="text/javascript" src="system/lib/jquery/jquery.mask.js"></script>
';

	
	// Bloco
	$bloco = tdClass::Criar("bloco");
	$bloco->class="col-md-12";	
	
	$titulo = tdClass::Criar("p");
	$titulo->class = "titulo-pagina";
	$titulo->add(utf8_decode("Habilitação/Impugnação de Crédito"));

	$sql = tdClass::Criar("sqlcriterio");
	$sql->setPropriedade("order","id DESC");
	$dataset = tdClass::Criar("repositorio",array("td_processo"))->carregar($sql);

	$processos = tdClass::Criar("div");
	$processos->style = "float:right;width:100%;";
	
	foreach($dataset as $processo){
		$a = tdClass::Criar("hyperlink");
		$a->href = "#";		
		$a->onclick = "abrirLista({$processo->id});";
		$a->add("[ ".$processo->id." ] [ " . $processo->numeroprocesso . " ]");
		
		$btnAtualizar = tdClass::Criar("button");
		$btnAtualizar->class = "btn btn-default btn-xs icone-pequeno-cabecalho-collapse";
		$btnAtualizar->atia_label = "Atualizar Lista de Habilitação/Impugnação";		
		$btnAtualizar->onclick = "atualizarLista({$processo->id});";
			$btnAtualizarSpan = tdClass::Criar("span");
			$btnAtualizarSpan->class = "glyphicon glyphicon-refresh";
			$btnAtualizarSpan->aria_hidden = "true";
		$btnAtualizar->add($btnAtualizarSpan);
		
		$divproc = tdClass::Criar("div");
		$divproc->id = "td-processo-" . $processo->id;
		
		$btnNovo = tdClass::Criar("button");
		$btnNovo->class = "btn btn-primary b-novo";
		$btnNovo->add("Novo");
		$btnNovo->onclick = "abreCadastroHabImp(".$processo->id.",".$processo->tipoprocesso.")";
		
		// Panel
		$panel = tdClass::Criar("panel");		
		$panel->head($a);
		$panel->head($btnAtualizar);
		$panel->body($btnNovo);
		$panel->body($divproc);
		$panel->body->id = "pb-".$processo->id;
		$panel->body->style = "display:none;";
		$processos->add($panel);
	}
	
	$js = tdClass::Criar("script");
	$js->add('
		function abrirLista(processo){
			if($("#pb-" + processo).css("display")=="none"){
				$("#pb-" + processo).show();
				carregar("index.php?controller=habilitacaoimpugnacao&op=carrega_divhab&processo=" + processo,"#td-processo-"+processo);
			}else{
				$("#pb-" + processo).hide();
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
				url:"index.php?controller=habilitacaoimpugnacao",
				data:{
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
				url:"index.php?controller=habilitacaoimpugnacao",
				data:{
					op:"salva_analise",
					id:id,
					status:status
				}
			});
		}
		$(".valorparecer").maskMoney({symbol:"R$", thousands:".", decimal:",", symbolStay: true,showSymbol:true});
		function abreModalParecerAJ(id){
			$("#modal-pareceraj-"+id).modal("show");
			$("#fileuploader-modal-pareceraj-"+id).uploadFile({
				url:"index.php?controller=habilitacaoimpugnacao&op=uploadarquivoAJ&id="+id,
				fileName:"temp_parecer",
				uploadStr:"Carregar",
				multiple:false,
				maxFileCount:1,
				showDelete:true,				
				deleteCallback:function(){
					$.ajax({
						url:"index.php?controller=habilitacaoimpugnacao",
						data:{
							op:"deletar_upload",
							id:id
						}
					});
				}
			});
		}
		function abreModalParecerMP(id){
			$("#modal-parecermp-"+id).modal("show");
			$("#fileuploader-modal-parecermp-"+id).uploadFile({
				url:"index.php?controller=habilitacaoimpugnacao&op=uploadarquivoMP&id="+id,
				fileName:"temp_parecer",
				uploadStr:"Carregar",
				multiple:false,
				maxFileCount:1,
				showDelete:true,				
				deleteCallback:function(){
					$.ajax({
						url:"index.php?controller=habilitacaoimpugnacao",
						data:{
							op:"deletar_upload",
							id:id
						}
					});
				}
			});
		}
		function abreModalDD(id){
			$("#modal-dd-"+id).modal("show");
			$("#fileuploader-modal-dd-"+id).uploadFile({
				url:"index.php?controller=habilitacaoimpugnacao&op=uploadarquivoDD&id="+id,
				fileName:"temp_parecer",
				uploadStr:"Carregar",
				multiple:false,
				maxFileCount:1,
				showDelete:true,				
				deleteCallback:function(){
					$.ajax({
						url:"index.php?controller=habilitacaoimpugnacao",
						data:{
							op:"deletar_upload",
							id:id
						}
					});
				}
			});
		}		
		function salvarParecer(habimp){
			$.ajax({
				url:"index.php?controller=habilitacaoimpugnacao",
				type:"POST",
				data:{
					op:"salvar_parecer",
					id:habimp,
					parecervalor:$("#parecervalor-"+habimp).val(),
					parecermoeda:$("#parecermoeda-"+habimp).val(),
					parecerclassificacao:$("#parecerclassificacao-"+habimp).val(),
					legitimidade:$("#legitimidade-"+habimp).val()
				},
				beforeSend:function(){
					//$("#retorno-salvar-parecer-"+habimp).html("<img src=\''.PATH_THEME_SYSTEM.'loading2.gif\' />");
				},
				success:function(){
					carregarListaParecer(habimp);
					//$("#retorno-salvar-parecer-"+habimp).html("<img src=\''.PATH_THEME_SYSTEM.'check.gif\' />");
					$("#parecervalor-"+habimp).val("");
					$("#parecermoeda-"+habimp).val(1);
					$("#parecerclassificacao-"+habimp).val(1);
					$("#legitimidade-"+habimp).val("");
				}
			});
		}
		function carregarListaParecer(habimp){		
			$.ajax({
				url:"index.php?controller=habilitacaoimpugnacao",
				type:"POST",
				data:{
					op:"carregar_lista_parecer",
					id:habimp
				},
				success:function(retorno){
					$("#tabelaparecer-" + habimp + " tbody").html(retorno);
				}
			});
		}
		function excluirDivHabParecer(id,obj){
			$.ajax({
				url:"index.php?controller=habilitacaoimpugnacao",
				type:"POST",
				data:{
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
		function abreCadastroHabImp(processo,tipoprocesso){
			$.ajax({
				url:"index.php?controller=habilitacaoimpugnacao&op=carrega_farein&processo=" + processo,
				success:function(retorno){
					$("#farein").html(retorno);
				},
				complete:function(){
					$("#aba-habimp").hide();
					$("#salvarFG").hide();					
					// Reseta
					$("#btn-impugnacao,#btn-habilitacao").show();
					$("#habilitacao-judicial #processo,#habilitacao-judicial #cpfj,#habilitacao-judicial #nome,#habilitacao-judicial #valor,#habilitacao-judicial #email,#habilitacao-judicial #cep,#habilitacao-judicial #bairro,#habilitacao-judicial #logradouro,#habilitacao-judicial #numero,#habilitacao-judicial #complemento,#processo-judicial #numeroprocesso,#processo-judicial #dataajuizamento").val("");
					$("#habilitacao-judicial #tipopessoa,#habilitacao-judicial #tipoempresa,#habilitacao-judicial #moeda,#habilitacao-judicial #classificacao,#habilitacao-judicial #natureza,#habilitacao-judicial #cidade,#habilitacao-judicial #estado,#processo-judicial #magistrado,#processo-judicial #comarca").find("option").first().attr("selected","selected");
					$("#retorno-status-salvar").html("");
					
					$("#habilitacao-judicial div,#processo-judicial div").removeClass("has-success");
					$("#habilitacao-judicial div span,#processo-judicial div span").remove();
					
					$("#modal-habimp").modal({
						backdrop:false
					});
					$("#modal-habimp").modal("show");
					$("#habilitacao-judicial #processo").val(processo);
					$("#habilitacao-judicial #tipoprocesso").val(tipoprocesso);
				}
			});
			//carregaFarein(processo,"#farein");
		}
		$("#habilitacao-judicial #tipopessoa").change(function(){
			if ($(this).val() == 2){
				$("#tipoempresa").parent().hide();
			}else{
				$("#tipoempresa").parent().show();
			}
		});
		$("#numeroprocesso,#cpfj,#nome,#valor,#email").blur(function(){
			if ($(this).val() != ""){
				$(this).parent().removeClass("has-error");
			}
		});
		var tipoprotocolo = "";
		$("#salvarFG .b-salvar").click(function(){			
			$("#retorno-status-salvar").html("");
			if ($("#numeroprocesso").val() == ""){
				$("#retorno-status-salvar").html("<div class=\'alert alert-danger alerta-resposta\' role=\'alert\'>Campo <b>Número do Processo</b> é obrigatório.</div>");
				$("#numeroprocesso").parent().addClass("has-error");
				setTimeout(function(){
					$(".alerta-resposta").hide("1000");
				},3000);
				return false;
			}
			if ($("#dataajuizamento").val() == ""){
				$("#retorno-status-salvar").html("<div class=\'alert alert-danger alerta-resposta\' role=\'alert\'>Campo <b>Data do Ajuizamento</b> é obrigatório.</div>");
				$("#dataajuizamento").parent().addClass("has-error");
				setTimeout(function(){
					$(".alerta-resposta").hide("1000");
				},3000);
				return false;
			}
			
			if (tipoprotocolo == "H"){
				var campos = new Array("cpfj","nome","valor","email");
				for(a in campos){
					if ($("#" + campos[a]).val() == ""){
						$("#retorno-status-salvar").html("<div class=\'alert alert-danger alerta-resposta\' role=\'alert\'>Campo <b>"+$("#" + campos[a]).attr("placeholder")+"</b> é obrigatório.</div>");
						$("#" + campos[a]).parent().addClass("has-error");
						setTimeout(function(){
							$(".alerta-resposta").hide("1000");
						},3000);
						return false;
					}
				}	
			}
			if (tipoprotocolo == "I"){
				if ($("#habilitacao-judicial #credor").val() == ""){
					$("#retorno-status-salvar").html("<div class=\'alert alert-danger alerta-resposta\' role=\'alert\'>Selecione um <b>credor</b> para Impugnação.</div>");
					setTimeout(function(){
						$(".alerta-resposta").hide("1000");
					},3000);
					return false;
				}
			}
	
			$("#loader-salvar-habilitacao").show();
			$.ajax({
				type:"POST",
				url:"index.php?controller=habilitacaoimpugnacao",
				data:{
					op:"salvar_habilitacaoimpugnacao",
					processo:$("#habilitacao-judicial #processo").val(),
					farein:$("#farein").val(),
					tipopessoa:$("#habilitacao-judicial #tipopessoa").val(),
					tipoempresa:$("#habilitacao-judicial #tipoempresa").val(),
					cpfj:$("#habilitacao-judicial #cpfj").val(),
					nome:$("#habilitacao-judicial #nome").val(),
					valor:$("#habilitacao-judicial #valor").val(),
					moeda:$("#habilitacao-judicial #moeda").val(),
					classificacao:$("#habilitacao-judicial #classificacao").val(),
					natureza:$("#habilitacao-judicial #natureza").val(),
					email:$("#habilitacao-judicial #email").val(),
					cep:$("#habilitacao-judicial #cep").val(),
					logradouro:$("#habilitacao-judicial #logradouro").val(),
					numero:$("#habilitacao-judicial #numero").val(),
					complemento:$("#habilitacao-judicial #complemento").val(),
					bairro:$("#habilitacao-judicial #bairro").val(),
					cidade:$("#habilitacao-judicial #cidade").val(),
					estado:$("#habilitacao-judicial #estado").val(),
					numeroprocesso:$("#processo-judicial #numeroprocesso").val(),
					magistrado:$("#processo-judicial #magistrado").val(),
					comarca:$("#processo-judicial #comarca").val(),
					credor:$("#habilitacao-judicial #credor").val()
				},
				complete:function(){
					$("#loader-salvar-habilitacao").hide();					
					$("#retorno-status-salvar").html("<div class=\'alert alert-success\' role=\'alert\'>Protocolo criado com Sucesso.</div>");
					
					setTimeout(function(){
						$("#modal-habimp").modal("hide");
					},5000);					
				}
			});
			
		});
		function carregaFarein(processo,lista){
			$(lista).load("index.php?controller=habilitacaoimpugnacao&op=carrega_farein&processo=" + processo);
		}
		var gradesdedados = [];
		function qtdeTempRegistro(){
			return 0;
		}
		var dados = [];
		$("#btn-habilitacao").click(function(){
			tipoprotocolo = "H";
			$("#btn-impugnacao").hide();
			$("#habilitacao-judicial").show();
			$("a[href=#3-modal-habimp-conteudo-aba1]").hide();
			$("a[href=#2-modal-habimp-conteudo-aba2]").show();			
			$("#aba-habimp").show();
			$("#aba-habimp .tab-content div,#aba-habimp .nav-tabs li").removeClass("active");
			$("#aba-habimp .tab-content div:first,#aba-habimp .nav-tabs li:first").addClass("active");
			$("#salvarFG").show();
			$("#1-modal-habimp-conteudo-aba0").addClass("in");
		});		
		$("#btn-impugnacao").click(function(){
			tipoprotocolo = "I";
			$("#btn-habilitacao").hide();
			$("a[href=#2-modal-habimp-conteudo-aba2]").hide();
			$("a[href=#3-modal-habimp-conteudo-aba1]").show();
			$("#aba-habimp").show();
			$("#aba-habimp .tab-content div,#aba-habimp .nav-tabs li").removeClass("active");
			$("#aba-habimp .tab-content div:first,#aba-habimp .nav-tabs li:first").addClass("active");
			$("#salvarFG").show();
			$("#1-modal-habimp-conteudo-aba0").addClass("in");
			
			var contexto = "#pesquisa-credor";
			// Carrega a grade de dados padrão
			var pesquisaCredor = new GradeDeDados(20);
			pesquisaCredor.contexto=contexto;
			pesquisaCredor.retornaFiltro = true;
			gradesdedados[contexto] = pesquisaCredor;
			pesquisaCredor.funcaoretorno = "selecionarCredor";
			pesquisaCredor.addFiltro("origemcredor","=",4);
			console.log($("#farein").val());
			pesquisaCredor.addFiltro("farein","=",$("#farein").val());
			pesquisaCredor.show();
		});
		function selecionarCredor(id){
			$("#habilitacao-judicial").show();
			$("#pesquisa-credor .gradededados tr").attr("style","background-color:#FFF;color:#000;cursor:pointer;");
			$("#pesquisa-credor .gradededados tr[idregistro="+id+"]").attr("style","background-color:#006400;color:#FFF;cursor:pointer;");
			$("#credor").val(id);
		}
		function atualizarLista(processo){
			carregar("index.php?controller=habilitacaoimpugnacao&op=carrega_divhab&processo=" + processo,"#td-processo-"+processo);
		}

	');
		
	// Botões de Escolha entre Habilitação ou Impugnação
	$habimpBtnGroup = tdClass::Criar("div");
	$habimpBtnGroup->aria_label = "Escolha entre Habilitação ou Impugnação";
	$habimpBtnGroup->role = "group";
	$habimpBtnGroup->class = "btn-group btn-group-justified";
	
	$habimpBtnGroup_hab = tdClass::Criar("hyperlink");
	$habimpBtnGroup_hab->id = "btn-habilitacao";
	$habimpBtnGroup_hab->role = "button";
	$habimpBtnGroup_hab->class = "btn btn-primary";
	$habimpBtnGroup_hab->href = "#";
	$habimpBtnGroup_hab->add("Habilitação");
	
	$habimpBtnGroup_imp = tdClass::Criar("hyperlink");
	$habimpBtnGroup_imp->id = "btn-impugnacao";
	$habimpBtnGroup_imp->role = "button";
	$habimpBtnGroup_imp->class = "btn btn-primary";
	$habimpBtnGroup_imp->href = "#";
	$habimpBtnGroup_imp->add("Impugnação");
	
	$habimpBtnGroup->add($habimpBtnGroup_hab,$habimpBtnGroup_imp);
	
	// Cadastro de Habilitação
	$fCadHab = tdClass::Criar("form");
	$fCadHab->id = "habilitacao-judicial";
	$fCadHab->style = "display:none;margin-top:20px;border:1px solid #DDD;padding:10px;";
		
	$processoOculto = tdClass::Criar("input");
	$processoOculto->id = "processo";
	$processoOculto->name = "processo";
	$processoOculto->type = "hidden";
	$fCadHab->add($processoOculto);
	
	$credorOculto = tdClass::Criar("input");
	$credorOculto->id = "credor";
	$credorOculto->name = "credor";
	$credorOculto->type = "hidden";
	$fCadHab->add($credorOculto);
	
	$TipoprocessoOculto = tdClass::Criar("input");
	$TipoprocessoOculto->id = "tipoprocesso";
	$TipoprocessoOculto->name = "tipoprocesso";
	$TipoprocessoOculto->type = "hidden";
	$fCadHab->add($TipoprocessoOculto);	
	
	
	$fareinFG = tdClass::Criar("div");
	$fareinFG->class = "form-group";
	$labelFarein = tdClass::Criar("label");
	$labelFarein->for = "farein";
	$labelFarein->add("Falida / Recuperanda / Insolvente");
	$selectFarein = tdClass::Criar("select");
	$selectFarein->id = "farein";
	$selectFarein->name = "farein";
	$selectFarein->class = "form-control";
	$fareinFG->add($labelFarein,$selectFarein);
	//$fCadHab->add($fareinFG);
	
	$tipopessoaFG = tdClass::Criar("div");
	$tipopessoaFG->class = "form-group";
	$labelTipopessoa = tdClass::Criar("label");
	$labelTipopessoa->for = "tipopessoa";
	$labelTipopessoa->add("Tipo de Pessoa");
	$selectTipopessoa = tdClass::Criar("select");
	$selectTipopessoa->id = "tipopessoa";
	$selectTipopessoa->name = "tipopessoa";
	$selectTipopessoa->class = "form-control";
		$opt = tdClass::Criar("option");
		$opt->value = 1;
		$opt->add("Jurídica");
		$selectTipopessoa->add($opt);

		$opt = tdClass::Criar("option");
		$opt->value = 2;
		$opt->add("Física");
		$selectTipopessoa->add($opt);
	$tipopessoaFG->add($labelTipopessoa,$selectTipopessoa);
	$fCadHab->add($tipopessoaFG);
	
	$tipoempresaFG = tdClass::Criar("div");
	$tipoempresaFG->class = "form-group";
	$labelTipoempresa = tdClass::Criar("label");
	$labelTipoempresa->for = "tipoempresa";
	$labelTipoempresa->add("Tipo de Empresa");
	$selectTipoempresa = tdClass::Criar("select");
	$selectTipoempresa->id = "tipoempresa";
	$selectTipoempresa->name = "tipoempresa";
	$selectTipoempresa->class = "form-control";
	$dsTipoempresa = tdClass::Criar("repositorio",array("td_tipoempresa"))->carregar();
	foreach ($dsTipoempresa as $d){
		$opt = tdClass::Criar("option");
		$opt->value = $d->id;
		$opt->add($d->descricao);
		$selectTipoempresa->add($opt);
	}
	$tipoempresaFG->add($labelTipoempresa,$selectTipoempresa);
	$fCadHab->add($tipoempresaFG);	
	
	$cpfjFG = tdClass::Criar("div");
	$cpfjFG->class = "form-group";
	$labelCpfj = tdClass::Criar("label");
	$labelCpfj->for = "cpfj";
	$labelCpfj->add("CNPJ/CPF");
	$inputCpfj = tdClass::Criar("input");
	$inputCpfj->type = "text";
	$inputCpfj->id = "cpfj";
	$inputCpfj->name = "cpfj";
	$inputCpfj->class = "form-control formato-cpfj";
	$inputCpfj->placeholder="CNPJ/CPF";
	$cpfjFG->add($labelCpfj,$inputCpfj);
	$fCadHab->add($cpfjFG);
	
	$nomeFG = tdClass::Criar("div");
	$nomeFG->class = "form-group";
	$labelNome = tdClass::Criar("label");
	$labelNome->for = "nome";
	$labelNome->add("Nome");
	$inputNome = tdClass::Criar("input");
	$inputNome->type = "text";
	$inputNome->id = "nome";
	$inputNome->name = "nome";
	$inputNome->class = "form-control";
	$inputNome->placeholder="Nome";
	$nomeFG->add($labelNome,$inputNome);
	$fCadHab->add($nomeFG);
	
	$valorFG = tdClass::Criar("div");
	$valorFG->class = "form-group";
	$labelValor = tdClass::Criar("label");
	$labelValor->for = "valor";
	$labelValor->add("Valor");
	$inputValor = tdClass::Criar("input");
	$inputValor->type = "text";
	$inputValor->id = "valor";
	$inputValor->name = "valor";
	$inputValor->class = "form-control formato-moeda";
	$inputValor->placeholder="Valor";
	$valorFG->add($labelValor,$inputValor);
	$fCadHab->add($valorFG);	
	
	$moedaFG = tdClass::Criar("div");
	$moedaFG->class = "form-group";
	$labelMoeda = tdClass::Criar("label");
	$labelMoeda->for = "moeda";
	$labelMoeda->add("Moeda");
	$selectMoeda = tdClass::Criar("select");
	$selectMoeda->id = "moeda";
	$selectMoeda->name = "moeda";
	$selectMoeda->class = "form-control";
	$dsMoeda = tdClass::Criar("repositorio",array("td_moeda"))->carregar();
	foreach ($dsMoeda as $d){
		$opt = tdClass::Criar("option");
		$opt->value = $d->id;
		$opt->add($d->descricao);
		$selectMoeda->add($opt);
	}
	$moedaFG->add($labelMoeda,$selectMoeda);
	$fCadHab->add($moedaFG);
	
	$classificacaoFG = tdClass::Criar("div");
	$classificacaoFG->class = "form-group";
	$labelClassificacao = tdClass::Criar("label");
	$labelClassificacao->for = "classificacao";
	$labelClassificacao->add("Classificação");
	$selectClassificacao = tdClass::Criar("select");
	$selectClassificacao->id = "classificacao";
	$selectClassificacao->name = "classificacao";
	$selectClassificacao->class = "form-control";
	$dsClassificacao = tdClass::Criar("repositorio",array("td_classificacao"))->carregar();
	foreach ($dsClassificacao as $d){
		$opt = tdClass::Criar("option");
		$opt->value = $d->id;
		$opt->add($d->descricao);
		$selectClassificacao->add($opt);
	}
	$classificacaoFG->add($labelClassificacao,$selectClassificacao);
	$fCadHab->add($classificacaoFG);

	$naturezaFG = tdClass::Criar("div");
	$naturezaFG->class = "form-group";
	$labelNatureza = tdClass::Criar("label");
	$labelNatureza->for = "natureza";
	$labelNatureza->add("Natureza");
	$selectNatureza = tdClass::Criar("select");
	$selectNatureza->id = "natureza";
	$selectNatureza->name = "natureza";
	$selectNatureza->class = "form-control";
	$dsNatureza = tdClass::Criar("repositorio",array("td_natureza"))->carregar();
	foreach ($dsNatureza as $d){
		$opt = tdClass::Criar("option");
		$opt->value = $d->id;
		$opt->add($d->descricao);
		$selectNatureza->add($opt);
	}
	$naturezaFG->add($labelNatureza,$selectNatureza);
	$fCadHab->add($naturezaFG);
	
	$emailFG = tdClass::Criar("div");
	$emailFG->class = "form-group";
	$labelEmail = tdClass::Criar("label");
	$labelEmail->for = "email";
	$labelEmail->add("E-Mail");
	$inputEmail = tdClass::Criar("input");
	$inputEmail->type = "text";
	$inputEmail->id = "email";
	$inputEmail->name = "email";
	$inputEmail->class = "form-control formato-email";
	$inputEmail->placeholder="E-Mail";
	$emailFG->add($labelEmail,$inputEmail);
	$fCadHab->add($emailFG);
	
	$cepFG = tdClass::Criar("div");
	$cepFG->class = "form-group";
	$labelCep = tdClass::Criar("label");
	$labelCep->for = "cep";
	$labelCep->add("CEP");
	$inputCep = tdClass::Criar("input");
	$inputCep->type = "text";
	$inputCep->id = "cep";
	$inputCep->name = "cep";
	$inputCep->class = "form-control formato-cep";
	$inputCep->placeholder="CEP";
	$cepFG->add($labelCep,$inputCep);
	$fCadHab->add($cepFG);

	$logradouroFG = tdClass::Criar("div");
	$logradouroFG->class = "form-group";
	$labelLogradouro = tdClass::Criar("label");
	$labelLogradouro->for = "logradouro";
	$labelLogradouro->add("Logradouro");
	$inputLogradouro = tdClass::Criar("input");
	$inputLogradouro->type = "text";
	$inputLogradouro->id = "logradouro";
	$inputLogradouro->name = "logradouro";
	$inputLogradouro->class = "form-control";
	$inputLogradouro->placeholder="Logradouro";
	$logradouroFG->add($labelLogradouro,$inputLogradouro);
	$fCadHab->add($logradouroFG);
	
	$numeroFG = tdClass::Criar("div");
	$numeroFG->class = "form-group";
	$labelNumero = tdClass::Criar("label");
	$labelNumero->for = "numero";
	$labelNumero->add("Número");
	$inputNumero = tdClass::Criar("input");
	$inputNumero->type = "text";
	$inputNumero->id = "numero";
	$inputNumero->name = "numero";
	$inputNumero->class = "form-control";
	$inputNumero->placeholder="Número";
	$numeroFG->add($labelNumero,$inputNumero);
	$fCadHab->add($numeroFG);
	
	$complementoFG = tdClass::Criar("div");
	$complementoFG->class = "form-group";
	$labelComplemento = tdClass::Criar("label");
	$labelComplemento->for = "complemento";
	$labelComplemento->add("Complemento");
	$inputComplemento = tdClass::Criar("input");
	$inputComplemento->type = "text";
	$inputComplemento->id = "complemento";
	$inputComplemento->name = "complemento";
	$inputComplemento->class = "form-control";
	$inputComplemento->placeholder="Complemento";
	$complementoFG->add($labelComplemento,$inputComplemento);
	$fCadHab->add($complementoFG);
	
	$bairroFG = tdClass::Criar("div");
	$bairroFG->class = "form-group";
	$labelBairro = tdClass::Criar("label");
	$labelBairro->for = "bairro";
	$labelBairro->add("Bairro");
	$inputBairro = tdClass::Criar("input");
	$inputBairro->type = "text";
	$inputBairro->id = "bairro";
	$inputBairro->name = "bairro";
	$inputBairro->class = "form-control";
	$inputBairro->placeholder="Bairro";
	$bairroFG->add($labelBairro,$inputBairro);
	$fCadHab->add($bairroFG);
	
	$cidadeFG = tdClass::Criar("div");
	$cidadeFG->class = "form-group";
	$labelCidade = tdClass::Criar("label");
	$labelCidade->for = "cidade";
	$labelCidade->add("Cidade");
	$selectCidade = tdClass::Criar("select");
	$selectCidade->id = "cidade";
	$selectCidade->name = "cidade";
	$selectCidade->class = "form-control";
	$dsCidade = tdClass::Criar("repositorio",array("td_cidade"))->carregar();
	foreach ($dsCidade as $d){
		$opt = tdClass::Criar("option");
		$opt->value = $d->id;
		$opt->add($d->nome);
		$selectCidade->add($opt);
	}
	$cidadeFG->add($labelCidade,$selectCidade);
	$fCadHab->add($cidadeFG);

	$estadoFG = tdClass::Criar("div");
	$estadoFG->class = "form-group";
	$labelEstado = tdClass::Criar("label");
	$labelEstado->for = "estado";
	$labelEstado->add("Estado");
	$selectEstado = tdClass::Criar("select");
	$selectEstado->id = "estado";
	$selectEstado->name = "estado";
	$selectEstado->class = "form-control";
	$dsEstado = tdClass::Criar("repositorio",array("td_estado"))->carregar();
	foreach ($dsEstado as $d){
		$opt = tdClass::Criar("option");
		$opt->value = $d->id;
		$opt->add($d->nome);
		$selectEstado->add($opt);
	}
	$estadoFG->add($labelEstado,$selectEstado);
	$fCadHab->add($estadoFG);
	
	// Cadastro de Processo
	$fCadProcesso = tdClass::Criar("form");
	$fCadProcesso->id = "processo-judicial";
	$fCadProcesso->style = "margin-top:20px;border:1px solid #DDD;padding:10px;";
	
	$numeroprocessoFG = tdClass::Criar("div");
	$numeroprocessoFG->class = "form-group";
	$labelNumeroProcesso = tdClass::Criar("label");
	$labelNumeroProcesso->for = "numeroprocesso";
	$labelNumeroProcesso->add("Número do Processo");
	$inputNumeroProcesso = tdClass::Criar("input");
	$inputNumeroProcesso->type = "text";
	$inputNumeroProcesso->id = "numeroprocesso";
	$inputNumeroProcesso->name = "numeroprocesso";
	$inputNumeroProcesso->class = "form-control";
	$inputNumeroProcesso->placeholder="Número do Processo";
	$numeroprocessoFG->add($labelNumeroProcesso,$inputNumeroProcesso);
	$fCadProcesso->add($numeroprocessoFG);
	
	$dataajuizamentoFG = tdClass::Criar("div");
	$dataajuizamentoFG->class = "form-group";
	$labelDataajuizamento = tdClass::Criar("label");
	$labelDataajuizamento->for = "dataajuizamento";
	$labelDataajuizamento->add("Data do Ajuizamento");
	$inputDataajuizamento = tdClass::Criar("input");
	$inputDataajuizamento->type = "text";
	$inputDataajuizamento->id = "dataajuizamento";
	$inputDataajuizamento->name = "dataajuizamento";
	$inputDataajuizamento->class = "form-control formato-data";
	$inputDataajuizamento->placeholder="Data de Ajuizamento";
	$dataajuizamentoFG->add($labelDataajuizamento,$inputDataajuizamento);
	$fCadProcesso->add($dataajuizamentoFG);	
	
	$magistradoFG = tdClass::Criar("div");
	$magistradoFG->class = "form-group";
	$labelMagistrado = tdClass::Criar("label");
	$labelMagistrado->for = "magistrado";
	$labelMagistrado->add("Magistrado");
	$selectMagistrado = tdClass::Criar("select");
	$selectMagistrado->id = "magistrado";
	$selectMagistrado->name = "magistrado";
	$selectMagistrado->class = "form-control";
	$dsMagistrado = tdClass::Criar("repositorio",array("td_magistrado"))->carregar();
	foreach ($dsMagistrado as $d){
		$opt = tdClass::Criar("option");
		$opt->value = $d->id;
		$opt->add($d->nome);
		$selectMagistrado->add($opt);
	}
	$magistradoFG->add($labelMagistrado,$selectMagistrado);
	$fCadProcesso->add($magistradoFG);
	
	$comarcaFG = tdClass::Criar("div");
	$comarcaFG->class = "form-group";
	$labelComarca = tdClass::Criar("label");
	$labelComarca->for = "comarca";
	$labelComarca->add("Comarca");
	$selectComarca = tdClass::Criar("select");
	$selectComarca->id = "comarca";
	$selectComarca->name = "comarca";
	$selectComarca->class = "form-control";
	$dsMagistrado = tdClass::Criar("repositorio",array("td_comarca"))->carregar();
	foreach ($dsMagistrado as $d){
		$opt = tdClass::Criar("option");
		$opt->value = $d->id;
		$opt->add($d->descricao);
		$selectComarca->add($opt);
	}
	$comarcaFG->add($labelComarca,$selectComarca);
	$fCadProcesso->add($comarcaFG);
	
	$PesquisaCredor = tdClass::Criar("div");
	$PesquisaCredor->id = "pesquisa-credor";
	$PesquisaCredor->style = "margin-top:20px;";
	
	$modalName = "modal-habimp";
	
	$aba_html = tdClass::Criar("aba");
	$aba_html->nome = "aba-habilitacaoimpugnacao";
	$aba_html->contexto = $modalName;	
	$aba_html->addItem("Processo",$fCadProcesso,"",1);
	$aba_html->addItem("Pesquisa",$PesquisaCredor,"",3);
	$aba_html->addItem("Credor",$fCadHab,"",2);
	
	$aba_html->id = "aba-habimp";
	$aba_html->style = "display:none;margin-top:20px;";
	
	// Loader Salvar
	$loaderSalvarFG = tdClass::Criar("imagem");
	$loaderSalvarFG->id = "loader-salvar-habilitacao";
	$loaderSalvarFG->src = PATH_THEME_SYSTEM . "loading2.gif";
	$loaderSalvarFG->style = "margin-top:-10px;display:none";
	
	// Retorno Status Salvar
	$retornoSalvar = tdClass::Criar("div");
	$retornoSalvar->id = "retorno-status-salvar";
	
	$btnsalvarFG = tdClass::Criar("div");
	$btnsalvarFG->id = "salvarFG";
	$btnsalvarFG->class = "form-group";	
	$btnsalvarFG->style = "margin-top:20px;display:none;";
	$btnSalvar = tdClass::Criar("button");
	$btnSalvar->class = "btn btn-success b-salvar";
	$btnSalvar->button = "button";
	$spanSalvar = tdClass::Criar("span");
	$spanSalvar->class = "glyphicon glyphicon-ok";
	$spanSalvar->add(" Salvar");
	$btnSalvar->add($spanSalvar);
	$btnSalvar->style = "float:none !important";
	$btnsalvarFG->add($btnSalvar,$loaderSalvarFG,$retornoSalvar);
	
	// Modal - Cadastro de Habilitação/Impugnação
	$addBody = tdClass::Criar("p");
	$modal = tdClass::Criar("modal");
	$modal->nome = $modalName;
	$modal->tamanho = "modal-lg";
	$modal->addHeader("Cadastro de Habilitação/Impugnação",null);
	$addBody->add($fareinFG,$habimpBtnGroup,$aba_html,$btnsalvarFG);
	$modal->addBody($addBody);
	$modal->mostrar();
	
	// Carrega os arquivos para UPLOAD
	$cssUploadFile = tdClass::Criar("link");
	$cssUploadFile->rel = "stylesheet";
	$cssUploadFile->href = PATH_LIB . 'jquery/jquery-upload-file-master/css/uploadfile.css';
	
	$jsUploadFile = tdClass::Criar("script");
	$jsUploadFile->type = "text/javascript";
	$jsUploadFile->src = PATH_LIB . 'jquery/jquery-upload-file-master/js/jquery.uploadfile.min.js';

	$bloco->add($cssUploadFile,$jsUploadFile,$titulo,$processos,$js);
	$bloco->mostrar();
?>