<?php
	set_time_limit(7200);
	$op = tdClass::Read("op");
	if ($op == "criarpdf"){

		$farein = tdClass::Read("farein");
		$credor = tdClass::Read("codigo");
		$urlimpressao = Session::Get("URL_SYSTEM") . "index.php?controller=impressaocomunicacao&gerarpdf=true&codigo={$credor}&farein={$farein}&key=k";
		$html = file_get_contents($urlimpressao);
		$html = str_replace(array('<dd>','</dd>','&emsp;&emsp;'),'',$html);

        $mpdfPathInstall = PATH_LIB . 'mpdf/8.0/vendor/autoload.php';
		include_once $mpdfPathInstall;

        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-P']);
        $pdffilename = Session::Get("PATH_CURRENT_FILE") . 'comunicacao/comunicacao-' . str_replace("^","-",$farein) . '-' . $credor . '.pdf';
        if (file_exists($pdffilename)){
            unlink($pdffilename);
        }
        $mpdf->WriteHTML($html);
        $mpdf->Output( $pdffilename ,'F');
		exit;
	}
	
	if ($op == 'enviaremail'){
		
		$farein 		= tdClass::Read("farein");
		$credor 		= tdClass::Criar("persistent",array("td_relacaocredores",tdClass::Read("codigo")))->contexto;
		$email 			= $credor->email;
		$nome			= $credor->nome;
		$tipoprocesso 	= explode("^",$farein)[2] == "RE" ? "Recuperação Judicial" : "Falência Judicial";

		include(PATH_LIB . "phpmailer/PHPMailerAutoload.php");

		$mail = new PHPMailer();
		$mail->SetLanguage("br","../sistema/" . PATH_LIB . "phpmailer/language/");
		$mail->IsSMTP();
		$mail->SMTPDebug = 0;
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'ssl';
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465;

		$mail->Username = "innovare@innovareadministradora.com.br";
		$mail->Password = "inn@teia#2020";
		$mail->FromName = "Innovare Administradora Judicial";
		$mail->From = "innovare@innovareadministradora.com.br";

		if (isset($_GET["emailextra"])){
			if ($_GET["emailextra"] != ""){
				$emails = explode(";",$_GET["emailextra"]);
				foreach($emails as $e){
					#$mail->AddAddress("{$e}","{$nome}");
				}
			}
		}else{
			$emails = explode(";",$email);
			foreach($emails as $e){
				#$mail->AddAddress("{$e}","{$nome}");
			}
		}
		$mail->AddAddress("edilsonbitencourt@hotmail.com","{$nome}");
		$mail->WordWrap = 50;

		//anexando arquivos no email
		$filename = Session::Get("PATH_CURRENT_FILE") . 'comunicacao/comunicacao-' . str_replace("^","-",$farein) . '-' . $credor->id . '.pdf';
		$mail->AddAttachment($filename);

		$mail->IsHTML(true); //enviar em HTML
		$mail->Subject = utf8_decode("Comunicação de " . $tipoprocesso);
		$mail->Body = utf8_decode("
			<img src='http://www.innovareadministradora.com.br/sistema/projects/2/imagens/logo.png' />
			<p>Caríssimo(a) credor(a), <b>{$nome}</b>.</p>
			<p>Segue, anexada, uma comunicação de {$tipoprocesso}.</p>
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
			echo 1;
		}
		exit;
	}
	
	if (tdClass::Read("op") == "carregacredoresids"){
		$farein = tdClass::Read("farein");
		$whereCodigo = tdClass::Read("credor") != "" ? " AND id = " . tdClass::Read("credor") : "";
		$conn = Transacao::Get();
		$retorno = array();
		$sql = "SELECT id,nome,cnpj,cpf,farein,processo,email FROM td_relacaocredores WHERE farein = {$farein} AND origemcredor = 11  {$whereCodigo};";
		$query = $conn->query($sql);
		while ($linha = $query->fetch()){
			$processo = tdClass::Criar("persistent",array("td_processo",$linha["processo"]))->contexto;
			array_push($retorno,array(
				"id" => $linha["id"],
				"nome" => $linha["nome"],
				"cpfj" => $linha["cpf"] . $linha["cnpj"],
				"farein" => utf8_decode($linha["farein"] . "^" . $processo->id . "^" . ($processo->tipoprocesso == 16?"RE":"FA")),
				"emails" => $linha["email"]
			));
		}
		echo json_encode($retorno);
		exit;	
	}
	if (tdClass::Read("op") == "listarfarein"){		
		$conn = Transacao::Get();
		$processo = tdClass::Criar("persistent",array("td_processo",tdClass::Read("processo")))->contexto;
		if ($processo->tipoprocesso == "16"){
			$entidade = "td_recuperanda";
		}else if ($processo->tipoprocesso == "19"){
			$entidade = "td_falencia";
		}
		$sql = "SELECT id,razaosocial FROM {$entidade} WHERE processo = {$processo->id}";
		$query = $conn->query($sql);
		while ($linha = $query->fetch()){
			echo '<option value="'.$linha["id"].'">'.utf8_encode(strtoupper($linha["razaosocial"])).'</option>';
		}
		exit;
	}

	// Bloco
	$bloco = tdClass::Criar("bloco");
	$bloco->class="col-md-12";	
	
	$titulo = tdClass::Criar("p");
	$titulo->class = "titulo-pagina";
	$titulo->add(utf8_decode("Envio Comunicação E-Mail"));
	
	$processos = array();
	$sql = tdClass::Criar("sqlcriterio");
	$sql->setPropriedade("order","id DESC");
	$dataset = tdClass::Criar("repositorio",array("td_processo"))->carregar($sql);
	foreach($dataset as $p){
		$option = tdClass::Criar("option");
		$option->value = $p->id;
		$option->add("[ {$p->id} ] - {$p->numeroprocesso}");
		array_push($processos,$option);
	}

	$lprocesso = Campos::Lista("processo","processo","Processo",$processos);
	$lfarein = Campos::Lista("farein","farein","Recuperanda / Falida / Insolvente");
	$credor = Campos::TextoLongo("credor","credor","Credor");
	
	$btnpesquisar = tdClass::Criar("button");
	$btnpesquisar->class = "btn btn-primary";
	$btnpesquisar->add("Pesquisar");
	$btnpesquisar->id = "btn-pesquisar";


	$script = tdClass::Criar("script");
	$script->add('
		$(document).ready(function(){
			$("#tretorno").hide();
			carregarFarein($("#processo").val());
		});
		function carregarFarein(processo){
			$.ajax({
				url:session.urlmiles,
				data:{
					controller:"envioarquivoavulso",
					op:"listarfarein",
					processo:processo
				},
				complete:function(ret){
					$("#farein").html(ret.responseText);
				}
			});
		}

		$("#processo").change(function(){
			carregarFarein($(this).val());
		});
		
		$("#btn-pesquisar").click(function(){
			$.ajax({
				url:session.urlmiles,
				data:{
					controller:"enviocomunicacaoemail",
					op:"carregacredoresids",
					farein:$("#farein").val(),
					credor:$("#credor").val()
				},
				beforeSend:function(){
					$("#tretorno tbody").html("");
					var tr = $("<tr id=\'trloader\'>");
					var td = $("<td colspan=\'6\'>");
					tr.append(td);
					$("#tretorno tbody").append(tr);
					loader("#trloader td");
					$("#tretorno").show();
				},
				complete:function(ret){
					var retorno = JSON.parse(ret.responseText);
					if (retorno.length > 0){
						for(c in retorno){
							var tr = $("<tr>");
							var id = $("<td>"+retorno[c].id+"</td>");
							var nome = $("<td>"+retorno[c].nome+"</td>");
							var cpfj = $("<td>"+retorno[c].cpfj+"</td>");
							var emailshtml = (retorno[c].emails=="" || retorno[c].emails == null)?"":retorno[c].emails.replace(";"," ");
							var emails = $("<td><small>"+emailshtml+"</small></td>");
							var checkbox = $("<td><input type=\'checkbox\' class=\'checkemail\' data-emails=\'"+emailshtml+"\' data-credor=\'"+retorno[c].id+"\' data-farein=\'"+retorno[c].farein+"\'></td>");
							var enviaremail = $("<td><button type=\'button\' onclick=enviarEmail("+retorno[c].id+",\'"+retorno[c].farein+"\',\'"+retorno[c].emails+"\') data-credor=\'"+retorno[c].id+"\' data-farein=\'"+retorno[c].farein+"\' class=\'btn btn-default btn-sm btn-enviaremail\' aria-label=\'Enviar\'><span class=\'glyphicon glyphicon-envelope\' axria-hidden=\'true\'></span></button></td>");

							tr.append(id);
							tr.append(nome);
							tr.append(cpfj);
							tr.append(emails);
							tr.append(enviaremail);
							tr.append(checkbox);
							$("#tretorno tbody").append(tr);
						}
						$("#btn-enviarselecionado").show();
					}else{
						$("#tretorno tbody").html("<tr><td colspan=\'6\'><div class=\'alert alert-warning text-center\'><b>Nenhum Registro Encontrado</b></div></td></tr>");
						$("#btn-enviarselecionado").hide();
					}
					$("#trloader").remove();
				}
			});
		});
		$("#checkall").click(function(){			
			if ($(".checkemail").prop("checked")){
				$(".checkemail").prop("checked",false);
			}else{
				$(".checkemail").prop("checked",true);
			}
		});

		$("#btn-enviarselecionado").click(function(){
			$(".checkemail:checked").each(function(){
				var farein = $(this).data("farein");
				var credor = $(this).data("credor");
				var emails = $(this).data("emails");
				criarPDF(credor,farein,emails);
			});
		});

		function enviarEmail(credor,farein,emails){			
			bootbox.prompt({
				title: "Adicionar E-Mail Extra",
				value: emails,
				callback: function (result) {
					criarPDF(credor,farein,result);
				}
			});
		}
		function enviarEmailAJAX(credor,farein,emails){
			$.ajax({
				url:session.urlmiles,
				data:{
					controller:"enviocomunicacaoemail",
					op:"enviaremail",
					farein:farein,
					codigo:credor,
					emailextra:emails
				},
				complete:function(ret){
					var retorno = parseInt(ret.responseText);
					if (retorno == 1){
						$(".btn-enviaremail[data-credor="+credor+"]").removeClass("btn-default");
						$(".btn-enviaremail[data-credor="+credor+"]").addClass("btn-success");
					}
				}
			});
		}
		function criarPDF(credor,farein,emails){
			$.ajax({
				url:session.urlmiles,
				data:{
					controller:"enviocomunicacaoemail",
					op:"criarpdf",
					farein:farein,
					codigo:credor
				},
				complete:function(ret){
					enviarEmailAJAX(credor,farein,emails);
				}
			});			
		}
	');

	$style = tdClass::Criar("style");
	$style->add('
		#btn-pesquisar{
			margin: 10px auto;
			float:right;
		}
		#btn-enviarselecionado{
			float:right;
			margin-bottom:20px;
			display:none;
		}
	');
	
	$table 	= tdClass::Criar("tabela");
	$table->class = "table table-hover";
	$table->id = "tretorno";
	$thead 	= tdClass::Criar("thead");
	$tr 	= tdClass::Criar("tabelalinha");

	$thID 	= tdClass::Criar("tabelahead");
	$thID->add("ID");
	$thID->width = "5%";

	$thNome	= tdClass::Criar("tabelahead");
	$thNome->add("Nome");
	$thNome->width = "50%";

	$thCPFJ	= tdClass::Criar("tabelahead");
	$thCPFJ->add("CPF/CNPJ");	
	$thCPFJ->width = "20%";
	
	$thEmails	= tdClass::Criar("tabelahead");
	$thEmails->add("E-Mails");	
	$thEmails->width = "15%";
	
	$thEnviar	= tdClass::Criar("tabelahead");
	$thEnviar->add("");	
	$thEnviar->width = "5%";
	
	$checkall = tdClass::Criar("input");
	$checkall->type = "checkbox";
	$checkall->id = "checkall";
	
	$thCheckAll	= tdClass::Criar("tabelahead");
	$thCheckAll->add($checkall);
	$thCheckAll->width = "5%";	

	$tr->add($thID , $thNome , $thCPFJ , $thEmails , $thEnviar , $thCheckAll);
	$thead->add($tr);
	
	$tbody = tdClass::Criar("tbody");
	$table->add($thead,$tbody);

	$btnenviarselecionados = tdClass::Criar("button");
	$btnenviarselecionados->class = "btn btn-info";
	$btnenviarselecionados->add("Enviar Selecionados");
	$btnenviarselecionados->id = "btn-enviarselecionado";
	
	$bloco->add($titulo,$lprocesso,$lfarein,$credor,$btnpesquisar,$table,$btnenviarselecionados,$script,$style);
	$bloco->mostrar();