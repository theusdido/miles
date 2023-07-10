<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

$op = isset($_POST["op"])?$_POST["op"]: (isset($_GET["op"])?$_GET["op"]:'');
	if ($op != ""){
		// Enviar Parecer
		if ($op == "enviar_parecer"){
			if ($conn = Transacao::get()){
				$obj = tdClass::Criar("persistent",array("td_arquivosassembleia",$_POST["id"]));

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
				$mail->AddAddress($_POST["email"],"Innovare Administradora");
				
				$mail->WordWrap = 50; 
				//anexando arquivos no email
				
				$arquivos = $_POST["arquivos"];
				$lis = "";
				foreach ($arquivos as $arq){
					$arqOBJ = (object)$arq;
					$lis .= "<li><a href='".$arqOBJ->link."' target='_blank'>Arquivo : ".$arqOBJ->nome."</a></li>";
					if ($mail->AddAttachment($arqOBJ->link)){
						echo 'anexado';
					}else{
						echo 'n anexado';
					}
				}
				
				$mail->IsHTML(true); //enviar em HTML
				$mail->SMTPSecure = 'ssl';
				
				$mail->Subject = "Arquivos Assembleia";
				$mail->Body = "
					<img src='http://www.innovareadministradora.com.br/sistema/imagens/logo_nova.png' />
					<p>Caríssimo.</p>
					<p>Segue o link para o downloa dos arquivos.</p>
					<ul>
						".$lis."
					</ul>
					<p>Atenciosamente,</p>
					<br /><br /><br />
					<p><b>MAURICIO COLLE DE FIGUEIREDO - OAB/SC 42.506<br />
						INNOVARE - ADMINISTRADORA EM RECUPERAÇÃO E FALÊNCIA ME - SS</b><br />
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
					$obj->contexto->arquivosenviados = 1;
					$obj->contexto->armazenar();
					$conn->commit();
				}
			}
			exit;
		}		
		// Excluir
		if ($op == "excluir"){
			$arqass = tdClass::Read("id");
			
			$dv = tdClass::Criar("persistent",array("td_arquivosassembleia",$arqass));
			
			$sql = tdClass::Criar("sqlcriterio");
			$sql->addFiltro("td_relacaocredores","=",$dv->contexto->td_credor);
			$sql->addFiltro("origem","=",2);
			$arquivos = tdClass::Criar("repositorio",array("td_arquivos_credor"))->deletar($sql);
						
			$dv->contexto->deletar();				
			
			Transacao::fechar();
			exit;
			
		}
		// Recupera Documento
		if ($op == "recupera_documento"){
			if ($conn = Transacao::get()){
				$obj = tdClass::Criar("persistent",array("td_arquivosassembleia",$_POST["id"]));
				echo $obj->contexto->documento;
				exit;
			}	
		}
		// Salva Analise
		if ($op == "salva_analise"){
			if ($conn = Transacao::get()){
				$obj = tdClass::Criar("persistent",array("td_arquivosassembleia",$_POST["id"]));
				$obj->contexto->analisado = $_POST["status"];
				$obj->contexto->armazenar();
				$conn->commit();
				exit;
			}
		}
		
	}

	if (isset($_GET["op"])){
		
		// Excluir Arquivo
		if ($_GET["op"] == "excluir_arquivo"){
			$conn = Transacao::Get();
			$id = $_GET["id"];
			$sql = "DELETE FROM td_arquivos_credor WHERE id = " . $id;			
			$query = $conn->exec($sql);
			if ($query){
				$urlfile = "../site/enviodocumentos_/arquivos_temp/" .  $id . ".pdf";
				if (file_exists($urlfile)){
					unlink($urlfile);
				}
				echo 1;
			}else{
				var_dump($conn->errorInfo());
			}
			$conn->commit();
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
			$arquivofile->td_relacaocredores = $credor;
			$arquivofile->origem = 9;
			$arquivofile->armazenar();
			$conn->commit();

			move_uploaded_file($_FILES["add-input-arquivo-" . $_GET["habdiv"]]["tmp_name"],"../site/enviodocumentos_/arquivos_temp/" .  $proxID . ".pdf");
			echo $proxID . "^" . utf8_encode($filename);
			exit;
		}
		
		// Carrega Habilitação/Divergência
		if ($_GET["op"] == "carrega_arqass"){
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

			$th_arquivos = tdClass::Criar("tabelahead");
			$th_arquivos->add("Arquivos");
			$th_arquivos->class = "text-center";
			$th_arquivos->width = "8%";
			
			$th_envio = tdClass::Criar("tabelahead");
			$th_envio->add(utf8_decode("Envio"));
			$th_envio->class = "text-center";
			$th_envio->width = "8%";			

			$th_analisado = tdClass::Criar("tabelahead");
			$th_analisado->add(utf8_decode("Analisado"));
			$th_analisado->class = "text-center";
			$th_analisado->width = "10%";
						
			$th_excluir = tdClass::Criar("tabelahead");
			$th_excluir->add(utf8_decode("Excluir"));
			$th_excluir->class = "text-center";
			$th_excluir->width = "8%";
			
			$tr->add($th_credor,$th_protocolo,$th_data,$th_arquivos,$th_envio,$th_analisado,$th_excluir);
			$thead->add($tr);
			$table->add($thead);

			$conn = Transacao::get();
			$sql2 = '
				SELECT b.td_origemcredor,a.* 
				FROM td_arquivosassembleia a,td_relacaocredores b 
				WHERE a.td_credor = b.id 
				AND a.td_processo = '.$_GET["processo"].'
				AND b.farein = '.$_GET["farein"].'
				ORDER BY a.id ASC;
			';
			$query2 = $conn->query($sql2);
			$msg_origem = "";
			while($habdiv = $query2->fetchObject()){			
				
				$credor 	= tdClass::Criar("persistent",array("td_relacaocredores",$habdiv->td_credor));
				$tr = tdClass::Criar("tabelalinha");
				
				$td_credor = tdClass::Criar("tabelacelula");
				$td_credor->add("<b>".completaString($credor->contexto->id,5)."</b> - " . utf8charset($credor->contexto->nome) . " [ " .$credor->contexto->cpf . $credor->contexto->cnpj . " ] ");
				
				$td_protocolo = tdClass::Criar("tabelacelula");
				$td_protocolo->add($habdiv->numero);
				$td_protocolo->class = "text-center";

				$td_data = tdClass::Criar("tabelacelula");
				$td_data->add(dateToMysqlFormat($habdiv->data,true));
				$td_data->class = "text-center";				

				$td_arquivos = tdClass::Criar("tabelacelula");			
												
				$div_list_arquivos = tdClass::Criar("div");
				$div_list_arquivos->class="list-group";
				$divlist = "div-list-" . $habdiv->id;
				$div_list_arquivos->id =  $divlist;
				$div_list_arquivos->add(' 
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

				$formT = tdClass::Criar("form");
				$formT->action = $_SESSION["URL_SYSTEM"] . "index.php?controller=gerarcapa&key=k&protocolo=" . $habdiv->id . "&farein=". $credor->contexto->farein . "&credor=".$credor->contexto->id . "&opt=assembleia&currentproject=".$_SESSION["currentproject"];
				$formT->method = "POST";
				$formT->target = "_blank";
					
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
				$formADDFile->action = $_SESSION["URL_SYSTEM"] . "index.php?controller=arquivosassembleia&op=addfile&credor=" . $credor->contexto->id . "&habdiv=" . $habdiv->id . "&currentproject=" . $_SESSION["currentproject"];
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
					$btnAddSpan->class = "glyphicon glyphicon-plus";
					$btnAddSpan->aria_hidden = "true";
				$btnAddFiles->class = "btn btn-info btn-block";
				$btnAddFiles->value = "Adicionar Arquivo";
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
						console.log("'.$nomeBtnFileInput.'");
						$("#'.$nomeBtnFileInput.'").click();
					});
					$("#'.$nomeBtnFileInput.'").change(function(){
						$("#'.$nomeFormADDFile.'").submit();
						setTimeout(function(){
							var filearray = $("iframe[name='.$targetFormADDFile.']").contents().find("body").html()
							var fileid = filearray.split("^")[0];
							var filename = filearray.split("^")[1];
							var nomea = "arquivo-link-assembleia-"+fileid;							
							$("#'.$nomeSpanFileNew.'").append(\'<a href="http://www.innovareadministradora.com.br/site/enviodocumentos_/arquivos_temp/\'+fileid+\'.pdf" target="_blank" class="list-group-item \'+nomea+\'" style="float:left;width:95%;">\'+filename+\'</a> \');
							$("#'.$nomeSpanFileNew.'").append(\'<button onclick="excluirArquivo(this)" data-id="\'+fileid+\'" data-nomea="\'+nomea+\'" class="list-group-item arquivo-link-assembleia-files" style="float:left;width:5%;cursor:pointer;"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>\');
						},2000);
					});
					
					$(".list-group-item").click(function(e){
						e.preventDefault();
						e.stopPropagation();
					});
				');
				$div_list_arquivos->add($formADDFile,$iframeADDFile,$jsAddFile,$divFileNew);
				
				$sql_arquivos = tdClass::Criar("sqlcriterio");
				$sql_arquivos->addFiltro("td_relacaocredores","=",$credor->contexto->id);
				$sql_arquivos->addFiltro("origem","in",array(2,9));
				
				//echo $sql_arquivos->dump() . "<br/>";
				$dataset_arquivos = tdClass::Criar("repositorio",array("td_arquivos_credor"))->carregar($sql_arquivos);
				$arquivosLinks = array();
				foreach ($dataset_arquivos as $arquivo){
					$link = urldecode("http://www.innovareadministradora.com.br/site/enviodocumentos_/verificaarquivo.php?filename=" . $arquivo->nome);

					$nomeA = "arquivo-link-assembleia-" . $arquivo->id;
					$a = tdClass::Criar("hyperlink");
					$a->href = "#";
					array_push($arquivosLinks,$link);
					$a->target = "_blank";
					$a->class = "list-group-item link-open-file " . $nomeA;
					$a->add($arquivo->descricao==""?"Sem Arquivo":$arquivo->descricao);
					$a->style = "float:left;width:95%;";
					$a->data_linkfile = $link;
					$div_list_arquivos->add($a);

					// Botão Excluir
					$btnExcluirFiles = tdClass::Criar("button");
						$btnExcluirSpan = tdClass::Criar("span");
						$btnExcluirSpan->class = "glyphicon glyphicon-trash";
						$btnExcluirSpan->aria_hidden = "true";
					$nomeExcluirFiles = "arquivo-link-assembleia-files";
					$idExcluirFiles = "arquivo-file-lista-" . $arquivo->id;
					$btnExcluirFiles->id = $idExcluirFiles;
					$btnExcluirFiles->class = "list-group-item " . $nomeExcluirFiles;
					$btnExcluirFiles->data_id = $arquivo->id;
					$btnExcluirFiles->data_nomea = $nomeA;
					$btnExcluirFiles->add($btnExcluirSpan);
					$btnExcluirFiles->style = "float:left;width:5%;cursor:pointer;";
					$btnExcluirFiles->onclick = "excluirArquivo(this)";
					$div_list_arquivos->add($btnExcluirFiles);
				}

				$btnDownload = tdClass::Criar("input");
				$btnDownload->class = "btn btn-warning btn-block";
				$btnDownload->type = "submit";
				$btnDownload->value = "Criar Capa";
				$formT->add($btnDownload);
				
				$div_list_arquivos->add($formT);
				
				$trArquivos = tdClass::Criar("tabelalinha");
				$trArquivos->style = "display:none;";
				$trArquivos->id = "tr-arquivos-" . $habdiv->id;
				$tdArquivos = tdClass::Criar("tabelacelula");
				$tdArquivos->colspan = 9;
				$tdArquivos->add($div_list_arquivos);
				
				$trArquivos->add($tdArquivos);
				
				$btnArquivo = tdClass::Criar("button");
				$btnArquivo->class = "btn btn-default";
				$btnArquivo->aria_label = "Decisão";				
				$iconArquivo = tdClass::Criar("span");
				$iconArquivo->class = "glyphicon glyphicon-floppy-open";
				$iconArquivo->aria_hidden = "true";
				$btnArquivo->add($iconArquivo);
				$btnArquivo->onclick = "abreArquivos({$habdiv->id});";
				
				$td_arquivos->align = "center";
				$td_arquivos->add($btnArquivo);
				
				$btnEnviar = tdClass::Criar("button");
				$btnEnviar->class = "btn " . ($habdiv->arquivosenviados==1?"btn-success":"btn-default");
				$btnEnviar->aria_label = "Decisão";
				$iconEnviar = tdClass::Criar("span");
				$iconEnviar->class = "glyphicon glyphicon-envelope";
				$iconEnviar->aria_hidden = "true";
				$btnEnviar->add($iconEnviar);
				$btnEnviar->onclick = 'abreModalParecerEnviar('.$habdiv->id.');';
				
				// Modal
				$modalName_enviar = "modal-parecer-enviar-" . $habdiv->id;
				$modal_enviar = tdClass::Criar("modal");
				$modal_enviar->nome = $modalName_enviar;
				$modal_enviar->tamanho = "modal-lg";
				$modal_enviar->addHeader("Enviar E-Mail ( Parecer )",null);
				$modal_enviar->addBody('
					<form class="form-inline">
						<div class="form-group" style="width:80%">
							<input type="text" placeholder="Digite o E-Mail " id="emailparecer-'.$habdiv->id.'" name="emailparecer-'.$habdiv->id.'" class="form-control" style="width:100%" value="">
						</div>
						<button class="btn btn-primary" type="button" style="float:left;margin:15px 10px;" onclick="enviarParecer('.$habdiv->id.');">Enviar</button>
						<div id="retorno-email-parecer-'.$habdiv->id.'" style="float:left;margin-top:15px;"></div>
					</form>
					<div class="form-group" style="width:95%" id="retorno-email-parecer-error-'.$habdiv->id.'">
					</div>
					<br /><br /><br />
				');
				$td_envio = tdClass::Criar("tabelacelula");
				$td_envio->align = "center";
				$td_envio->add($btnEnviar,$modal_enviar);
				
				// Analisado
				$td_analisado = tdClass::Criar("tabelacelula");
				$td_analisado->align = "center";
				
				$analise_group = tdClass::Criar("div");
				$analise_group->data_toggle="buttons";
				$analise_group->class = "btn-group analise_$habdiv->id";
				
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
				$analise_nao_label->add(utf8_decode("Não"),$analise_nao_input);
				
				$analise_group->add($analise_sim_label,$analise_nao_label);
				$td_analisado->add($analise_group);
				
				$btnEnviar = tdClass::Criar("button");
				$btnEnviar->aria_label = "Decisão";
				$iconEnviar = tdClass::Criar("span");
				$iconEnviar->class = "glyphicon glyphicon-envelope";
				$iconEnviar->aria_hidden = "true";
				$btnEnviar->add($iconEnviar);
				$btnEnviar->onclick = 'abreModalParecerEnviar('.$habdiv->id.');';
				
				$btnExcluir = tdClass::Criar("button");
				$btnExcluir->class = "btn btn-danger";
				$btnExcluir->aria_label = "Decisão";
				$iconExcluir = tdClass::Criar("span");
				$iconExcluir->class = "glyphicon glyphicon-trash";
				$iconExcluir->aria_hidden = "true";
				$btnExcluir->add($iconExcluir);
				$btnExcluir->onclick = 'excluirArqAss('.$habdiv->id.',this);';
				
				$td_excluir = tdClass::Criar("tabelacelula");
				$td_excluir->align = "center";
				$td_excluir->add($btnExcluir);				
				
				$tr->add($td_credor,$td_protocolo,$td_data,$td_arquivos,$td_envio,$td_analisado,$td_excluir);
				
				$jsArquivos = tdClass::Criar("script");
				$jsArquivos->add('
					function abreArquivos(habdiv){
						
						if ($("#tr-arquivos-" + habdiv).css("display") == "none"){
							$("#tr-arquivos-" + habdiv).show();
						}else{
							$("#tr-arquivos-"+habdiv).hide();
						}
					}
					function abreModalParecerEnviar(habdiv){
						$("#modal-parecer-enviar-"+habdiv).modal("show");
					}
					function enviarParecer(habdiv){
						if ($("#emailparecer-"+habdiv).val() == ""){
							$("#retorno-email-parecer-error-"+habdiv).html("<div class=\'alert alert-danger\' role=\'alert\'>E-Mail &eacute; obrigat&oacute;rio.</div>");
							return false;
						}
						var arquivosEnviar = [];
						$(".arquivo-link-assembleia-" + habdiv).each(function(){
							arquivosEnviar.push({"link":$(this).attr("href"),"nome":$(this).html()});
						});
						$.ajax({
							url:session.urlsystem,
							type:"POST",
							data:{
								controller:"arquivosassembleia",
								op:"enviar_parecer",
								id:habdiv,
								email:$("#emailparecer-"+habdiv).val(),
								arquivos:arquivosEnviar,
								currentproject:session.projeto
							},
							beforeSend:function(){
								$("#retorno-email-parecer-"+habdiv).html("<img src=\''.PATH_THEME_SYSTEM.'loading2.gif\' />");
							},
							success:function(){
								$("#retorno-email-parecer-"+habdiv).html("<img src=\''.PATH_THEME_SYSTEM.'check.gif\' />");
							}
						});
					}
					
					$(".link-open-file").click(function(e){
						console.log("clicou");
						window.open($(this).data("linkfile"),"_blank");
						e.preventDefault();
						e.stopPropagation();
					});
				');
				
				$table->add($tr,$trArquivos,$jsArquivos);
			}
			$table->add(utf8_decode("<tr><td colspan='9' align='right'>Total: <b>".$query2->rowcount()."</b></td></tr>"));			
			$table->mostrar();
			exit;
		}		
	
		// Salva Documento
		if ($op == "salva_documento"){
			if ($conn = Transacao::get()){
				$obj = tdClass::Criar("persistent",array("td_arquivosassembleia",$_POST["id"]));
				$obj->contexto->documento = $_POST["valor"];
				$obj->contexto->armazenar();
				$conn->commit();
				exit;
			}	
		}
		
		if ($op == 'carrega_farein'){
			$processo = tdc::p("td_processo",$_GET["processo"]);
			switch((int)$processo->tipoprocesso){
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
			$sql->addFiltro("td_processo","=",$processo->id);
			$dataset = tdClass::Criar("repositorio",array($farein))->carregar($sql);
			foreach($dataset as $linha){
				$parms = $processo->id.'-'.$processo->tipoprocesso.'-'.$linha->id;
				$datapedidosetenca = $processo->tipoprocesso==16?"Pedido " . dateToMysqlFormat($linha->datapedido,true):"Setença " . dateToMysqlFormat($linha->datasentenca,true);
				echo '
					<div class="panel panel-primary">
						<div class="panel-heading">
							<span style="cursor:pointer;" class="cabecalho-farein" data-params="'.$parms.'">'.$linha->id . " - " . utf8charset($linha->{$descricao}).' - Data do <i>' . $datapedidosetenca . '</i></span>
							<button class="btn btn-default btn-xs icone-pequeno-cabecalho-collapse cabecalho-farein-atualizar" data-params="'.$parms.'" aria-label="Atualizar Lista de Habilitação/Impugnação">
								<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
							</button>
						</div>
						<div class="panel-body" style="display:none;" id="'.$processo->id.'-'.$processo->tipoprocesso.'-'.$linha->id.'"></div>
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
				
				function abrirLista(objRetorno){
					var listasrelacao 	= [];
					var processo 		= objRetorno.split("-")[0].replace("#","");
					var tipo			= objRetorno.split("-")[1];
					var farein			= objRetorno.split("-")[2];

					$.ajax({
						url:session.urlsystem,
						data:{
							controller:"arquivosassembleia",
							op:"carrega_arqass",
							processo:processo,
							tipo:tipo,
							farein:farein,
							currentproject:session.projeto
						},
						beforeSend:function(){
							loader(objRetorno);
						},
						complete:function(ret){
							$(objRetorno).html(ret.responseText);
						}
					});
				}
			');
			$script->mostrar();
			exit;
		}
	}

	// Bloco
	$bloco = tdClass::Criar("bloco");
	$bloco->class="col-md-12";	
	
	$titulo = tdClass::Criar("p");
	$titulo->class = "titulo-pagina";
	$titulo->add(utf8_decode("Arquivos para Assembleia"));
	
	$sql = tdClass::Criar("sqlcriterio");
	$sql->setPropriedade("order","id DESC");
	$dataset = tdClass::Criar("repositorio",array("td_processo"))->carregar($sql);

	$processos = tdClass::Criar("div");
	
	$conn = Transacao::Get();
	foreach($dataset as $processo){
		$todasFAREIN = $primeiroFAREIN = "";
		$sqlFAREIN = 'SELECT id,razaosocial FROM '.($processo->tipoprocesso == 16?'td_recuperanda':'td_falencia').' WHERE td_processo = ' . $processo->id;
		$queryFAREIN = $conn->query($sqlFAREIN);
		$i = 1;
		while ($linhaFAREIN = $queryFAREIN->fetch()){
			$todasFAREIN .= " - " . $linhaFAREIN["razaosocial"] . "<br/><br/>";
			if ($i==1) $primeiroFAREIN = $linhaFAREIN["razaosocial"];
			$i++;
		}

		$a = tdClass::Criar("hyperlink");
		$a->href = "#";				
		$a->class = "abrircredores";
		$a->data_processo = $processo->id;
		$a->add("[ " . $processo->id . " ] [ " . $processo->numeroprocesso . " ]");
		
		$divproc = tdClass::Criar("div");
		$divproc->id = "td-processo-" . $processo->id;
		
		$btnFarein = '<button type="button" class="btn btn-lg btn-link btn-sm" data-toggle="popover" title="Recuperandas,Falidas e Insolventes." data-placement="left" data-content="'.$todasFAREIN.'" style="float:right;margin-top:-5px;" onmouseover=$(this).popover({html:true})>'.$primeiroFAREIN.'</button>';
		
		// Panel
		$panel = tdClass::Criar("panel");		
		$panel->head($a);
		$panel->head($btnFarein);
		$panel->body($divproc);
		$panel->body->id = "pb-".$processo->id;
		$panel->body->style = "display:none;";
		$processos->add($panel);
	}
	
	$js = tdClass::Criar("script");
	$js->add('
		function abrirFarein(processo){
			if($("#pb-" + processo).css("display")=="none"){
				$("#pb-" + processo).show();
				carregar("index.php?controller=arquivosassembleia&op=carrega_farein&processo=" + processo,"#td-processo-"+processo);
			}else{
				$("#pb-" + processo).hide();
			}
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
				url:session.urlsystem,
				data:{
					controller:"arquivosassembleia",
					op:"salva_analise",
					id:id,
					status:status,
					currentproject:session.projeto
				}
			});
		}		
		function excluirArqAss(habdiv,obj){
			bootbox.dialog({
			  message:"Tem certeza que deseja excluir ?",
			  title:"Aviso",
			  buttons: {
				success:{
				  label:"Sim",
				  className: "btn-success",
				  callback: function(){
					$.ajax({
						url:session.urlsystem,
						type:"POST",
						data:{
							controller:"arquivosassembleia",
							op:"excluir",
							id:habdiv,
							currentproject:session.projeto
						},
						success:function(){
							$(obj).parents("tr").remove();
						}
					});
					
				  }
				},
				danger: {
				  label: "N&atilde;o",
				  className: "btn-danger",
				  callback: function(){
				  }
				}
			  }
			});			
			
		}
		function excluirArquivo(obj){
			$.ajax({
				url:session.urlsystem,
				data:{
					controller:"arquivosassembleia",
					op:"excluir_arquivo",
					id:$(obj).data("id"),
					currentproject:session.projeto
				},
				complete:function(ret){
					var retorno = parseInt(ret.responseText);
					if (retorno == 1){
						$(obj).remove();
						$("." + $(obj).data("nomea")).remove();
					}
				}
			});			
		}
		$(".abrircredores").click(function(e){
			e.preventDefault();
			e.stopPropagation();
			abrirFarein($(this).data("processo"));
		});
	');

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