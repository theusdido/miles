<?php
	


	// Retorna a lista de Projetos
	if (tdClass::Read("op") == "listarprojetos"){
		$itens 		= array();
		
		$termo = isset($_GET["termo"]) ? tdClass::Read("termo") : null;
		$pesquisado = isset($_GET["termo"]) ? true : false;
		$where = "";
		if ($termo != "*"){
			if (is_numeric($termo)){
				$where = " AND a.projeto = " . $termo;
			}else if( is_string($termo)){
				$where = " AND b.nome LIKE '%" . $termo . "%'";
			}
		}	
		$sql = "SELECT a.projeto,a.type,a.id FROM td_connectiondatabase a,projeto b WHERE a.projeto = b.id {$where} ORDER BY a.projeto";
		$query = $conn->query($sql);

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
				$projeto = (object)getRegistro($conn,"td_projeto","id,nome","id=".$p["projeto"]);
				$tipo = (object)getRegistro($conn,"td_typeconnectiondatabase","id,descricao","id=".$p["type"]);
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
	$headersystem = array();
	$headersystem_col = 'headersystem-col';
	
	// Gerenciamento das Configurações
	$colBlocoGerenciamento 			= tdClass::Criar("bloco");
	$colBlocoGerenciamento->class 	= 'col-md-4 ' . $headersystem_col;
	
	$confighome 		= tdClass::Criar("div");
	$confighome->id 	= "config-home";
	
	$iconConfigHome 				= tdClass::Criar("span");
	$iconConfigHome->class 			= "fas fa-cog";
	$iconConfigHome->aria_hidden 	= "true";
	$confighome->add($iconConfigHome);	

	$aConfigHome 				= tdClass::Criar("hyperlink");
	$aConfigHome->href 			= URL_MDM . "index.php?currentproject=" . CURRENT_PROJECT_ID;
	$aConfigHome->target 		= "_blank";
	$aConfigHome->add("Gerenciamento");
	$confighome->add($aConfigHome);

	$colBlocoGerenciamento->add($confighome);	
	
	// Instalação
	$colBlocoIntalacao 			= tdClass::Criar("bloco");
	$colBlocoIntalacao->class 	= 'col-md-4 ' . $headersystem_col;

	$iconInstallHome = tdClass::Criar("span");
	$iconInstallHome->class = "fas fa-cogs";
	$iconInstallHome->aria_hidden = "true";
	$colBlocoIntalacao->add($iconInstallHome);
	
	$aInstallHome 			= tdClass::Criar("hyperlink");
	$aInstallHome->href 	= URL_MILES . "index.php?controller=install/modulos";
	$aInstallHome->target 	= "_blank";
	$aInstallHome->add("Pacotes");
	$aInstallHome->id 		= "link-install-home";
	$colBlocoIntalacao->add($aInstallHome);
	
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
	
	$colBlocoTempoSessao 			= tdClass::Criar("bloco");
	$colBlocoTempoSessao->class 	= 'col-md-2 ' . $headersystem_col;
	$colBlocoTempoSessao->add($tempoSessaoHome);
	
	$colBlocoClock 			= tdClass::Criar("bloco");
	$colBlocoClock->class 	= 'col-md-2 ' . $headersystem_col;	
	$colBlocoClock->add($horaHome,$dataHome);

	if (Session::get("usergroup") == 1 || PROJECT_ID == 1){	
		array_push($headersystem, $colBlocoGerenciamento);
		array_push($headersystem, $colBlocoIntalacao);
	}else{
		$colBlocoOffset = tdClass::Criar("bloco");
		$colBlocoOffset->class = 'col-md-8 headersystem-col';
		array_push($headersystem, $colBlocoOffset);
	}

	array_push($headersystem, $colBlocoTempoSessao);
	array_push($headersystem, $colBlocoClock);	
