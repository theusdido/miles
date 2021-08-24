<?php

if (Session::get("usergroup") == 1 || Session::get("currentproject") == 1){

	// Retorna a lista de Projetos
	if (tdClass::Read("op") == "listarprojetos"){
		$itens = array();
		$connMILES = getMilesDataBase();
		
		$termo = isset($_GET["termo"]) ? tdClass::Read("termo") : null;
		$pesquisado = isset($_GET["termo"]) ? true : false;
		$where = "";
		if ($termo != "*"){
			if (is_numeric($termo)){
				$where = " AND a.td_projeto = " . $termo;
			}else if( is_string($termo)){
				$where = " AND b.nome LIKE '%" . $termo . "%'";
			}
		}	
		$sql = "SELECT a.td_projeto,a.td_type,a.id FROM td_connectiondatabase a,td_projeto b WHERE a.td_projeto = b.id {$where} ORDER BY a.td_projeto";
		$query = $connMILES->query($sql);

		if ($query->rowcount() > 0){
			$inputGroupPesquisa = tdClass::Criar("div");
			$inputGroupPesquisa->class = "input-group";
			$inputGroupPesquisa->id = "inputgroup-pesquisa-projeto-headersystem";
			
			$inputTextPesquisa = tdClass::Criar("input",array("text"));
			$inputTextPesquisa->class = "form-control";
			$inputTextPesquisa->placeholder = "Digite um termo pra pesquisar !";
			$inputTextPesquisa->id = "termo-pesquisa-projeto";
			
			$spanInputGroupPesquisa = tdClass::Criar("span");
			$spanInputGroupPesquisa->class = "input-group-btn";
			
			$buttonPesquisa = tdClass::Criar("button");
			$buttonPesquisa->class = "btn btn-default";
			$buttonPesquisa->id = "btn-pesquisa-projeto";
			$buttonPesquisa->add("Pesquisar");
			
			$spanInputGroupPesquisa->add($buttonPesquisa);
			$inputGroupPesquisa->add($inputTextPesquisa);
			$inputGroupPesquisa->add($spanInputGroupPesquisa);
			

			while ($p = $query->fetch()){
				$projeto = (object)getRegistro($connMILES,"td_projeto","id,nome","id=".$p["td_projeto"]);
				$tipo = (object)getRegistro($connMILES,"td_typeconnectiondatabase","id,descricao","id=".$p["td_type"]);
				$databaseid = $p["id"];

				$a = tdClass::Criar("hyperlink");
				$a->href = "#";
				$a->class = "list-group-item";
				$a->add("[ <b>{$projeto->id}</b> ] " . $projeto->nome . " - " . $tipo->descricao);
				$a->onclick = "alterProject({$projeto->id},{$tipo->id},{$databaseid});";
				array_push($itens,$a);
			}

		}
		
		$lista = tdClass::Criar("listgroup");
		$lista->addItem($itens);
		
		$divRetorno = tdClass::Criar("div");
		$divRetorno->id = "div-retorno-pesquisa-projeto";

		

		$jsPesquisar = tdClass::Criar("script");
		$jsPesquisar->add('
			$("#btn-pesquisa-projeto").click(function(){
				pesquisarTermoProjeto();
			});
			$("#termo-pesquisa-projeto").keypress(function(e){
				if (e.which == 13){
					pesquisarTermoProjeto();
				}
			});
			
			function pesquisarTermoProjeto(){
				$.ajax({
					url:session.urlsystem,
					data:{
						controller:"headersystem",
						op:"listarprojetos",
						termo:$("#termo-pesquisa-projeto").val(),
						currentproject:1,
						key:"k"
					},
					complete:function(ret){
						unloader();
						$("#div-retorno-pesquisa-projeto").html(ret.responseText);
					}
				});				
			}
		');
		if ($termo != "" || $termo == "*"){
			$lista->mostrar();
		}else if ($pesquisado){
			$alert = tdClass::Criar("alert",array("Nenhum Registro Encontrado."));
			$alert->type = "alert-warning";
			$alert->exibirfechar = false;
			$alert->alinhar = "center";
			$alert->mostrar();			
		}else if (!$pesquisado){
			$inputGroupPesquisa->mostrar();
			$divRetorno->mostrar();
			$jsPesquisar->mostrar();
		}	
		exit;
	}

	// Informações adicionais do sistema	
	$headersystem = tdClass::Criar("div");
	
	// Configuções
	$colBloco = tdClass::Criar("bloco");
	$colBloco->class = "col-md-2";
	
	$confighome = tdClass::Criar("div");
	$confighome->id = "config-home";
	
	$iconConfigHome = tdClass::Criar("span");
	$iconConfigHome->class = "fas fa-cog";
	$iconConfigHome->aria_hidden = "true";
	$confighome->add($iconConfigHome);	
	
	$aConfigHome = tdClass::Criar("hyperlink");
	$aConfigHome->href = Session::Get("URL_MDM") . "index.php?currentproject=" . Session::Get()->projeto;
	$aConfigHome->target = "_blank";
	$aConfigHome->add("Configurações");
	$confighome->add($aConfigHome);

	$colBloco->add($confighome);
	$headersystem->add($colBloco);
	
	// Alterar Projeto
	$colBloco = tdClass::Criar("bloco");
	$colBloco->class = "col-md-2";

	$iconConfigHome = tdClass::Criar("span");
	$iconConfigHome->class = "fas fa-exchange-alt";
	$iconConfigHome->aria_hidden = "true";
	$colBloco->add($iconConfigHome);
	
	$aAlterProject = tdClass::Criar("hyperlink");
	$aAlterProject->href = "#";
	$aAlterProject->target = "_blank";
	$aAlterProject->add("Alternar Projeto");
	$aAlterProject->id = "link-alter-project";
	$colBloco->add($aAlterProject);
	
	$headersystem->add($colBloco);	
	
	// Instalação
	$colBloco = tdClass::Criar("bloco");
	$colBloco->class = "col-md-2";

	$iconInstallHome = tdClass::Criar("span");
	$iconInstallHome->class = "fas fa-cogs";
	$iconInstallHome->aria_hidden = "true";
	$colBloco->add($iconInstallHome);
	
	$aInstallHome = tdClass::Criar("hyperlink");
	$aInstallHome->href = Session::Get("URL_INSTALL") . "index.php?currentproject=" . Session::Get("currentproject") . "&ambiente=" . AMBIENTE;
	$aInstallHome->target = "_blank";
	$aInstallHome->add("Instalação/Atualização");
	$aInstallHome->id = "link-install-home";
	$colBloco->add($aInstallHome);
	
	$headersystem->add($colBloco);
	
	// Aplicação
	$colBloco = tdClass::Criar("bloco");
	$colBloco->class = "col-md-2";
	
	$iconAplicacao = tdClass::Criar("span");
	$iconAplicacao->class = "fas fa-file-code";
	$iconAplicacao->aria_hidden = "true";
	$colBloco->add($iconAplicacao);
	
	$aAplicacao = tdClass::Criar("hyperlink");
	$aAplicacao->href = "#";
	$aAplicacao->target = "_blank";
	$aAplicacao->add("Aplicação");
	$aAplicacao->id = "link-aplicacao-home";
	$colBloco->add($aAplicacao);	

	$headersystem->add($colBloco);
	
	$tempoSessaoHome = tdClass::Criar("div");
	$tempoSessaoHome->id = "temposessao-home";
	
	$iTempoSessaoHome = tdClass::Criar("icon",array("fas fa-clock"));
	$tempoSessaoHome->add($iTempoSessaoHome);
	
	$spanTempoSessaoHome = tdClass::Criar("span");
	$tempoSessaoHome->add($spanTempoSessaoHome);
	
	$horaHome = tdClass::Criar("div");
	$horaHome->id = "hora-home";
	
	$iHoraHome = tdClass::Criar("icon",array("hora-home"));
	$horaHome->add($iHoraHome);
	
	$spanHoraHome = tdClass::Criar("span");
	$horaHome->add($spanHoraHome);
	
	$dataHome = tdClass::Criar("div");
	$dataHome->id = "data-home";
	
	$iDataHome = tdClass::Criar("icon",array("fas fa-calendar-alt"));
	$dataHome->add($iDataHome);
	
	$spanDataHome = tdClass::Criar("span");
	$dataHome->add($spanDataHome);
	
	$colBlocoTempoSessao = tdClass::Criar("bloco");
	$colBlocoTempoSessao->class = "col-md-2";
	$colBlocoTempoSessao->add($tempoSessaoHome);
	
	$colBlocoClock = tdClass::Criar("bloco");
	$colBlocoClock->class = "col-md-2";	
	$colBlocoClock->add($horaHome,$dataHome);

	$headersystem->add($colBlocoTempoSessao,$colBlocoClock);

	$modal = tdClass::Criar("modal");
	$modal->addHeader("Selecionar Projeto");
	$modal->nome = "modal-select-projects";
	$modal->addFooter("<small class='text-info'>Digite <b>*</b> para ver todos os projetos</small>");
	$modal->addBody("Carregando ...");
	$js = tdClass::Criar("script");
	$js->add('		
		$("#link-alter-project").click(function(e){
			e.preventDefault();
			e.stopPropagation();
			$("#modal-select-projects .modal-body p").load(getURLProject("index.php?controller=headersystem&op=listarprojetos"));
			$("#modal-select-projects").modal("show");
		});
		function alterProject(project,tipo,databaseid){
			$.ajax({
				url:config.urlrequisicoes,
				data:{
					op:"alterproject",
					project:project,
					typedatabase:tipo,
					databaseid:databaseid
				},
				beforeSend:function(){					
					statusPesquisa("disabled");
					loader("#div-retorno-pesquisa-projeto");
				},	
				complete:function(ret){
					switch(parseInt(ret.responseText)){
						case 1:
							location.href = "index.php";
						break;
						case 2:							
							$("#div-retorno-pesquisa-projeto").html("<div role=\'alert\' class=\'alert alert-danger\'>Sistema não instalado.</div>");
						break;
						default:
							$("#div-retorno-pesquisa-projeto").html("<div role=\'alert\' class=\'alert alert-danger\'>Erro ao alterar projeto.</div>");
							console.log(ret.responseText);							
					}
					statusPesquisa("enabled");
					unloader();
				},
				error:function(){
					unloader();
					$("#div-retorno-pesquisa-projeto").html("<div class=\'alert alert-danger\'>Erro ao carregar projeto!</div>");
				}				
			});
		}
		
		function statusPesquisa(status){
			if (status == "disabled"){
				$("#inputgroup-pesquisa-projeto-headersystem *").attr("disabled",true);
			}else if(status == "enabled"){
				$("#inputgroup-pesquisa-projeto-headersystem *").removeAttr("disabled");
			}	
		}
		
		$("#link-aplicacao-home").click(function(e){
			e.preventDefault();
			e.stopPropagation();
			//$("#modal-aplicacao-home .modal-body p").load("index.php?controller=headersystem&op=listarprojetos");
			$("#modal-aplicacao-home").modal("show");
		});
		
		$("#btn-criar-aplicacao").click(function(){
			$("#barra-progresso-aplicacao").show();
		});
	');


	
	
	$modalAplicacao = tdClass::Criar("modal");
	$modalAplicacao->addHeader("Aplicação");
	$modalAplicacao->nome = "modal-aplicacao-home";
	/*
	$modalAplicacao->addBody('
		<div class="progress" id="barra-progresso-aplicacao" style="visibility:hidden">
			<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
				<span class="sr-only">Criando Aplicação ...</span>
			</div>
		</div>
	');
	*/
	$modalAplicacao->addFooter('<button class="btn btn-primary" type="button" id="btn-criar-aplicacao">Criar</button>');
	$headersystem->add($modal,$modalAplicacao,$js);
}