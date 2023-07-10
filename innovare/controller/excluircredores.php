<?php	
	
	if (tdClass::Read("op") == "excluircredores"){
		$conn = Transacao::Get();
		$processo = tdCLass::Criar("persistent",array("td_processo",tdClass::Read("processo")))->contexto;
		$farein = tdClass::Read("farein");
		$origemcredor = tdClass::Read("origemcredor");
		
		if ($origemcredor == ""){
			$whereOrigemCredor = "";
		}else{
			$whereOrigemCredor = " AND td_origemcredor = {$origemcredor} ";
		}
		
		$idsCredores = $idsListaCredores = "";
		$sqlCredor = "SELECT * FROM td_relacaocredores WHERE td_processo = {$processo->id} AND farein = {$farein} {$whereOrigemCredor}";
		$queryCredor = $conn->query($sqlCredor);
		while ($linhaCredor = $queryCredor->fetch()){
			$idsCredores .= ($idsCredores==""?"":",") . $linhaCredor["id"];
		}
		if ($idsCredores != ""){
			$idsListaCredores = " AND reg_filho in ({$idsCredores}) ";
			$idsCredores = " AND id in ({$idsCredores}) ";
		}
		$sql = "DELETE FROM td_relacaocredores WHERE td_processo = {$processo->id} AND farein = {$farein} {$whereOrigemCredor} {$idsCredores} ";
		$query = $conn->exec($sql);
		
		$sql = "DELETE FROM td_lista WHERE entidade_pai = {$processo->tipoprocesso} AND entidade_filho = 20 AND reg_pai = {$farein} {$idsListaCredores}";
		$query = $conn->exec($sql);
		Transacao::Fechar();
		echo 1;
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
		$sql = "SELECT id,razaosocial FROM {$entidade} WHERE td_processo = {$processo->id}";
		$query = $conn->query($sql);
		while ($linha = $query->fetch()){
			echo '<option value="'.$linha["id"].'">[ '.$linha["id"].' ] - '.utf8_encode($linha["razaosocial"]).'</option>';
		}
		exit;
	}
	if (tdClass::Read("op") == "listarorigem"){
		echo '<option value="">Todos</option>';
		$conn = Transacao::Get();
		$sql = "SELECT id,descricao FROM td_origemcredor";
		$query = $conn->query($sql);
		while ($linha = $query->fetch()){
			echo '<option value="'.$linha["id"].'">[ '.$linha["id"].' ] - '.$linha["descricao"].'</option>';
		}
		exit;
	}
	$titulo = tdClass::Criar("titulo");
	$titulo->add("Exclusão de Credores");
	$titulo->mostrar();

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
	$lprocesso->mostrar();
	
	$lfarein = Campos::Lista("farein","farein","Recuperanda / Falida / Insolvente");
	$lfarein->mostrar();
	
	$lorigemcredor = Campos::Lista("origemcredor","origemcredor","Origem Credor");
	$lorigemcredor->mostrar();
	
	$btngerar = tdClass::Criar("button");
	$btngerar->add(" Excluir Credores");
	$btngerar->class = "btn btn-primary";
	$btngerar->id = "btn-excluir";
	$btngerar->mostrar();
	
	$progressbar = tdClass::Criar("progressbar");
	$progressbar->setStyle("progress-bar-primary progress-bar-striped");
	$progressbar->setPercentual(100);
	$progressbar->exbirpercentual = false;
	$progressbar->mostrar();
	
	$style = tdClass::Criar("style");
	$style->type = "text/css";
	$style->add('
		.progress{
			display:none;
			float:left;
			width:100%;
		}
		#btn-excluir{
			margin:20px 0px;
			float:right;
			width:150px;
		}
	');
	$style->mostrar();

	$script = tdClass::Criar("script");
	$script->add('
		$(document).ready(function(){
			carregarFarein($("#processo").val());
			carregarOrigemCredor();
		});	
		function carregarFarein(processo){
			$.ajax({
				url:session.urlsystem,
				data:{
					controller:"excluircredores",
					op:"listarfarein",
					processo:processo,
					origemcredor:$("#origemcredor").val(),
					currentproject:session.projeto
				},
				complete:function(ret){
					$("#farein").html(ret.responseText);
				}
			});
		}
		function carregarOrigemCredor(){
			$.ajax({
				url:session.urlsystem,
				data:{
					controller:"excluircredores",
					op:"listarorigem",
					currentproject:session.projeto
				},
				complete:function(ret){
					$("#origemcredor").html(ret.responseText);
				}
			});			
		}
		$("#processo").change(function(){
			carregarFarein($(this).val());
		});
		$("#btn-excluir").click(function(){
			$.ajax({
				url:session.urlsystem,
				data:{
					controller:"excluircredores",
					op:"excluircredores",
					processo:$("#processo").val(),
					farein:$("#farein").val(),
					origemcredor:$("#origemcredor").val(),
					currentproject:session.projeto
				},
				beforeSend:function(){
					$(".progress").show();
					$(".progress-bar").addClass("active");
					$(".progress-bar").addClass("progress-bar-primary");					
					$(".progress-bar").removeClass("progress-bar-success");
					$(".progress-bar").html("Excluíndo Credores. Aguarde.");
				},
				complete:function(ret){
					if (parseInt(ret.responseText) == 1){						
						$(".progress-bar").removeClass("active");
						$(".progress-bar").removeClass("progress-bar-primary");
						$(".progress-bar").removeClass("progress-bar-danger");
						$(".progress-bar").addClass("progress-bar-success");
						$(".progress-bar").html("Excluíndo com Sucesso.");
					}else{
						$(".progress-bar").removeClass("active");
						$(".progress-bar").removeClass("progress-bar-primary");
						$(".progress-bar").removeClass("progress-bar-success");
						$(".progress-bar").addClass("progress-bar-danger");
						$(".progress-bar").html("Erro ao efetuar a exclusão.");
					}
				}
			});
		});
		
	');
	$script->mostrar();