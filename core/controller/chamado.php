<?php
	$bloco_chamado 		= null;
	$imgSemFotoUsuario 	= URL_SYSTEM . "system/tema/padrao/sem-foto-usuario.png";
	if ($connMILES){
		$op = isset($_GET["op"])?$_GET["op"]:"";

		// Menu do Chamado
		$menu = tdClass::Criar("listgroup");
		$menu->id = "menuchamado";
		$menu->setTitulo("Menu");
		
		$menuNovo = tdClass::Criar("hyperlink");
		$menuNovo->add("Novo");
		$menuNovo->href = "#";
		$menuNovo->class = "list-group-item";
		$menuNovo->add(tdClass::Criar("glyphicon",array("glyphicon-plus")));
				
		$menuMeusChamados = tdClass::Criar("hyperlink");
		$menuMeusChamados->add("Meus Chamados");
		$menuMeusChamados->href = "#";
		$menuMeusChamados->class = "list-group-item";
		$menuMeusChamados->add(tdClass::Criar("glyphicon",array("glyphicon-tasks")));
		
		$menu->addItem($menuNovo,$menuMeusChamados);

		
		$scriptMenu = tdClass::Criar("script");
		$scriptMenu->add('
			$("#menuchamado a:nth-child(2)").click(function(){
				carregar("index.php?controller=chamado&op=novo","#conteudoprincipal");
			});
			$("#menuchamado a:nth-child(3)").click(function(){
				carregar("index.php?controller=chamado&op=meuschamados","#conteudoprincipal");
			});
			$("#menuchamado a").click(function(e){
				e.preventDefault();
			});			
		');

		if ($op == "novo" || $op == "meuschamados" || $op == "all" || $op == "editar"){
			tdClass::Criar("titulo",array("Chamado"))->mostrar();

			$bloco = tdCLass::Criar("div");
			$bloco->class = "row";

			$colMenu = tdClass::Criar("div");
			$colMenu->class = "col-md-3";

			$colMenu->add($menu);

			$style = tdClass::Criar("style");
			$style->type = "text/css";
			$style->add('
				#menuchamado .list-group-item span{
					float:right;
				}
				form{
					margin-bottom:20px;
				}
				.loadercontexto{
					margin-left:10px;
					display:none;
				}
				#msg-retorno{
					width:100%;
					height:200px;
					display:none;
				}
				#div-chamados-abertos,#div-chamados-finalizados{
					padding:10px;
				}
				.btn-visualizar-chamado , .btn-finalizar-chamado{
					float:right;
					margin:0px 2px;
				}
				.txt-lista-chamado{
					float:left;
					width:100%;
					margin-top:-5px;
					height:50px;
				}
				#div-chamados-abertos .media-body,#div-chamados-finalizados .media-body{
					width:100%;
				}
				#div-chamados-abertos .media-body h4,#div-chamados-finalizados .media-body h4{
					margin-top:3px;
					font-size:15px;

				}
				.lista-chamado .media {
					float:left;
					width:90%;
				}
				.lista-chamado{
					border-bottom:1px solid #DDD;
					float:left;
					width:100%;
					padding:10px;
				}
				.lista-chamado:hover{
					background-color:#EEE;
				}
				.txt-lista-chamado small{
					float:left;
					clear:left;
					margin-top:-5px;
				}
				.txt-lista-chamado p{
					float:left;
					clear:left;
				}
				#cabecalhochamado #numerochamado-editar small,
				#cabecalhochamado #datacriacao-editar small 
				{
					width:75px;
					float:left;
				}
				#cabecalhochamado #numerochamado-editar small,
				#cabecalhochamado #datacriacao-editar small 
				{
					float:left;
					margin-top:3px;
				}
				#cabecalhochamado #img-usuario{
					position:absolute;
					right:20px;
					top:45px;
				}
				#descricaochamado-editar{
					margin-top:30px;
				}
				#arquivoschamado{
					margin-top:15px;
				}
				#form-interacao-chamado{
					margin-top:15px;
				}
				#numerochamado-editar{
					margin-top:-15px;
				}
				#datacriacao-editar{
					margin-top:-10px;
				}
				#numerochamado-editar strong{
					
				}
				#datacriacao-editar strong{
					font-weight:normal;
				}
				.conteudointeracao .img-usuario{
					float:right;
					margin-top:-30px;
				}
			');

			$colConteudo = tdClass::Criar("div");
			$colConteudo->class = "col-md-9";

			$script = tdClass::Criar("script");
		}

		if ($op == "salvarinteracao"){
			$proxid = getProxId("ticketinteraction",$connMILES);
			$empresa = Session::Get()->empresa;
			$projeto = CURRENT_PROJECT_ID;
			$ticket = tdClass::Read("id");
			$descricao = utf8charset(tdClass::Read("descricao"));
			$data = date("Y-m-d H:i:s");
			$usuario = Session::Get()->userid;
			$sqlsalvarinterecao = "
				INSERT INTO td_ticketinteraction 
					(id,empresa,projeto,ticket,descricao,data,usuario)
				VALUES
					({$proxid},{$empresa},{$projeto},{$ticket},'{$descricao}','$data',$usuario)
				;	
			";
			
			$query = $connMILES->exec($sqlsalvarinterecao);
			if ($query){
				gravandoArquivo($connMILES,$_FILES,$ticket,$proxid);
			}

			$script = tdClass::Criar("script");
			$script->add('
				parent.carregarInteracoes();
				parent.$(".loadercontexto").hide();
				parent.$("#descricao").val("");
			');
			$script->mostrar();
			exit;
		}

		if ($op == "listarinteracoes"){
			$ticket = $_GET["ticket"];
			$nomeusuario = "";
			$sqlinteracao = "
				SELECT id,descricao,usuario,data 
				FROM td_ticketinteraction 
				WHERE ticket = " . $ticket . "
				ORDER BY data DESC "
			;
			$queryinteracao = $connMILES->query($sqlinteracao);
			while ($linhainteracao = $queryinteracao->fetch()){
				if (is_numeric($linhainteracao["usuario"])){
					if ($linhainteracao["usuario"] > 0){
						$userobj = tdClass::Criar("persistent",array(USUARIO,$linhainteracao["usuario"]))->contexto;
						$nomeusuario = $userobj->nome;
					}
				}

				$divcabecalhoChamado = tdClass::Criar("div");
				$divcabecalhoChamado->id  = "cabecalhochamado";

				$imgUsuario = tdClass::Criar("imagem");
				$imgUsuario->src = $imgSemFotoUsuario;
				$imgUsuario->class = "img-usuario";
				
				$smallDataCriacao = tdClass::Criar("small");
				$smallDataCriacao->add("Data: ");

				$bDataCriacao = tdClass::Criar("strong");
				$bDataCriacao->add(datetimeToMysqlFormat($linhainteracao["data"],true));

				$datacriacao = tdClass::Criar("p");
				$datacriacao->id = 'datacriacao-editar';
				$datacriacao->add($smallDataCriacao,$bDataCriacao);

				$divcabecalhoChamado->add(
					$datacriacao,
					$imgUsuario
				);
				
				$descricaochamado = tdClass::Criar("div");
				$descricaochamado->id = 'descricaochamado-editar';
				$descricaochamado->add($linhainteracao["descricao"]);
				$descricaochamado->class = "text-info";

				$spanArquivos = tdClass::Criar("span");
				$sqlArquivosChamados = "SELECT id,arquivo FROM td_ticketanexo WHERE ticket = " . $ticket . " AND ticketinteraction = " . $linhainteracao["id"];
				$queryArquivosChamados = $connMILES->query($sqlArquivosChamados);
				while ($linhaArquivosChamados = $queryArquivosChamados->fetch()){
					$file = PATH_FILE . "ticket/anexo-" . $ticket . "-".$linhainteracao["id"]."-". $linhaArquivosChamados["id"] . "." . getExtensao($linhaArquivosChamados["arquivo"]);
					if (file_exists($file)){
						$a = tdClass::Criar("hyperlink");
						$a->href = $file;
						$a->target = "_blank";
						$g = tdClass::Criar("glyphicon",array("glyphicon-file"));
						$a->add($g," ",$linhaArquivosChamados["arquivo"]);
						$a->class = "btn btn-primary btn-xs";
						
						$spanArquivos->add($a);
					}	
				}
				$arquivos = tdClass::Criar("div");
				$arquivos->add($spanArquivos);
				$arquivos->id="arquivoschamado";

				$conteudointeracao = tdClass::Criar("div");
				$conteudointeracao->class = "conteudointeracao";
				$conteudointeracao->add(
					$divcabecalhoChamado,
					$descricaochamado,
					$arquivos
				);

				$interacoes = tdClass::Criar("panel");
				$interacoes->head($nomeusuario);
				$interacoes->body($conteudointeracao);
				$interacoes->mostrar();
			}
			exit;
		}

		if ($op == "editar"){
			$id = tdClass::Read("id");
			$sqlChamado = "SELECT titulo,datacriacao,descricao FROM td_ticket WHERE id = " . $id;
			$queryChamado = $connMILES->query($sqlChamado);

			if ($queryChamado->rowCount() > 0){
				$linhaChamado = $queryChamado->fetch();

				$titulo = tdClass::Criar("titulo",array($linhaChamado["titulo"]));

				$divcabecalhoChamado = tdClass::Criar("div");
				$divcabecalhoChamado->id  = "cabecalhochamado";

				$imgUsuario = tdClass::Criar("imagem");
				$imgUsuario->src = $imgSemFotoUsuario;
				$imgUsuario->id = "img-usuario";
				
				$smallNumeroChamado = tdClass::Criar("small");
				$smallNumeroChamado->add("Chamado: ");

				$bNumeroChamado = tdClass::Criar("strong");
				$bNumeroChamado->add("#".completaString($id,5));
				
				$numerochamado = tdClass::Criar("p");
				$numerochamado->id = 'numerochamado-editar';
				$numerochamado->add($smallNumeroChamado,$bNumeroChamado);

				$smallDataCriacao = tdClass::Criar("small");
				$smallDataCriacao->add("Criação : ");

				$bDataCriacao = tdClass::Criar("strong");
				$bDataCriacao->add(datetimeToMysqlFormat($linhaChamado["datacriacao"],true));

				$datacriacao = tdClass::Criar("p");
				$datacriacao->id = 'datacriacao-editar';
				$datacriacao->add($smallDataCriacao,$bDataCriacao);

				$divcabecalhoChamado->add(
					$numerochamado,
					$datacriacao,
					$imgUsuario
				);
				
				$descricaochamado = tdClass::Criar("div");
				$descricaochamado->add($linhaChamado["descricao"]);
				$descricaochamado->id = 'descricaochamado-editar';
				$descricaochamado->class = "text-info";
				
				$spanArquivos = tdClass::Criar("span");
				$sqlArquivosChamados = "SELECT id,arquivo FROM td_ticketanexo WHERE ticket = " . $id;
				$queryArquivosChamados = $connMILES->query($sqlArquivosChamados);
				while ($linhaArquivosChamados = $queryArquivosChamados->fetch()){
					$file = PATH_FILE . "ticket/anexo-" . $id . "-0-". $linhaArquivosChamados["id"] . "." . getExtensao($linhaArquivosChamados["arquivo"]);
					if (file_exists($file)){
						$a = tdClass::Criar("hyperlink");
						$a->href = $file;
						$a->target = "_blank";
						$g = tdClass::Criar("glyphicon",array("glyphicon-file"));
						$a->add($g," ",$linhaArquivosChamados["arquivo"]);
						$a->class = "btn btn-primary btn-xs";
						$spanArquivos->add($a);
					}
				}
				$arquivos = tdClass::Criar("div");
				$arquivos->add($spanArquivos);
				$arquivos->id="arquivoschamado";

				// Form Novo Chamado
				$form = tdClass::Criar("form");
				$form->id = "form-interacao-chamado";
				$form->action = "index.php?controller=chamado&op=salvarinteracao&id=" . $id . "&currentproject=" . CURRENT_PROJECT_ID;
				$form->method = "POST";
				$form->enctype = "multipart/form-data";
				$form->target = "msg-retorno";

				$fieldset = tdClass::Criar("fieldset");

				$descricao = Campos::TextArea("descricao","descricao","Descrição","");
				$arquivo = '
					<div class="form-group">
						<label>Arquivos</label>
						<br />
						<button type="button" class="btn btn-default form-control" id="addfile">
							<span class="fas fa-file" aria-hidden="true"></span>
							Clique aqui para adicionar arquivos
						</button>
						<ul class="list-group list-files">
						</ul>
					</div>
					<div class="progress" id="barradeprogresso" style="display:none;">
					  <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
							<b>Aguarde o carregamento dos arquivos</b>
					  </div>
					</div>
				';

				$msgRetorno = tdclass::Criar("iframe");
				$msgRetorno->id = "msg-retorno";
				$msgRetorno->name = "msg-retorno";
				$msgRetorno->scrooling = "no";
				$msgRetorno->frameborder = "no";
				$msgRetorno->border = "0";

				$enviar = tdClass::Criar("input");
				$enviar->type = "submit";
				$enviar->class = "btn btn-primary";
				$enviar->value = "Enviar";
				$enviar->id = "salvar-chamadointeracao";

				$loadercontexto = LOADERCONTEXTO;
				$fieldset->add($descricao,$arquivo,$enviar,$loadercontexto);
				$form->add($fieldset);

				$listarinteracoes = tdClass::Criar("div");
				$listarinteracoes->id = "listarinteracoes";

				$separador = tdClass::Criar("hr");

				$colConteudo->add(
					$titulo,
					$divcabecalhoChamado,
					$descricaochamado,
					$arquivos,					
					$form,
					$msgRetorno,
					$listarinteracoes
				);
			}else{
				$colConteudo->add('<div class="alert alert-danger">Chamado não encontrado.</div>');
			}
			
			$script->add('
				function carregarInteracoes(){
					$.ajax({
						async:false,
						url:session.urlsystem,
						data:{
							controller:"chamado",
							op:"listarinteracoes",
							ticket:'.$id.',
							currentproject:'.CURRENT_PROJECT_ID.'
						},
						complete:function(ret){
							$("#listarinteracoes").html(ret.responseText);
						}
					});
				}
				$(document).ready(function(){					
					$("#form-interacao-chamado").submit(function(){
						return validarForm();
					});
					var indice = 0;
					$("#addfile").click(function(){
						$(this).parent().append($("<input type=\'file\' id=\'arquivo"+indice+"\' name=\'arquivo"+indice+"\' style=\'display:none;\'/>"));
						$("#arquivo" + indice).click();
						$("#arquivo" + indice).change(function(){
							$(".list-files").append(\'<li class="list-group-item"><span class="fas fa-trash-alt" aria-hidden="true" style="float:right;cursor:pointer;" onclick=$(this).parent().remove();$("#\'+this.id+\'").remove()></span>\'+this.files[0].name+\'</li>\');
						});
						indice++;
					});
					carregarInteracoes();
				});
				function validarForm(){					
					var campos_obrigatorios = [];
					campos_obrigatorios["descricao"] = $("#descricao").val();

					if (!validar(campos_obrigatorios,"#form-interacao-chamado","#msg-retorno")){
						return false;
					}

					$(".loadercontexto").show();
					return true;
				}
			');
		}

		if ($op == "listarchamados"){
			$where = " WHERE 1=1 ";			
			if (Session::Get()->userid != 1){ // Condição especial para super usuário
				$where .= "
					AND a.projeto = ".CURRENT_PROJECT_ID;
			}
			$statuschamado = (int)tdClass::Read("status");
			if (tdClass::Read("status") != ""){
				$where .= " AND a.status = " . tdClass::Read("status") . " ";
			}

			$sqlChamadosAbertos = "
				SELECT 
					a.id,
					a.titulo,
					a.descricao,
					(SELECT b.nome FROM td_projeto b WHERE b.id = a.projeto) projetonome,
					a.datacriacao
				FROM td_ticket a 
				{$where}
				ORDER BY a.datacriacao desc,a.id desc;
			";
			$queryChamadoAbertos = $connMILES->query($sqlChamadosAbertos);
			if ($queryChamadoAbertos->rowCount() > 0){
				while ($linhaChamadosAbertos = $queryChamadoAbertos->fetch()){

					if (Session::Get()->usergroup == 1 && $statuschamado == 1){
						$bFinalizar 			= tdClass::Criar("button");
						$bFinalizar->class 		= "btn btn-default btn-sm btn-finalizar-chamado";
						$bFinalizar->data_id 	= $linhaChamadosAbertos["id"];
						$bFinalizar->add(tdClass::Criar("glyphicon",array("glyphicon-check")));
					}else{
						$bFinalizar = null;
					}

					if ($statuschamado == 1){
						$bVisualizar = tdClass::Criar("button");
						$bVisualizar->class = "btn btn-primary btn-sm btn-visualizar-chamado";
						$bVisualizar->add(tdClass::Criar("glyphicon",array("glyphicon-edit")));
						$bVisualizar->data_id = $linhaChamadosAbertos["id"];
					}else{
						$bVisualizar = null;
					}

					$texto = tdClass::Criar('span');
					$texto->class = "txt-lista-chamado text-info";
					$texto->add(
						"<p>".substr(strip_tags($linhaChamadosAbertos["descricao"]),0,50)."</p>".
						"<small class='text-muted'>Aberto em ".datetimeToMysqlFormat($linhaChamadosAbertos["datacriacao"],true)." - ".$linhaChamadosAbertos["projetonome"]."</small>"					
					);

					$numerochamado = completaString($linhaChamadosAbertos["id"],5);
					$media = tdClass::Criar("mediaobject");
					$media->addMediaLeft($imgSemFotoUsuario);
					$media->addMediaBody(
						"[ <b>".$numerochamado."</b> ] ".substr($linhaChamadosAbertos["titulo"],0,45),
						$texto
					);

					$listaChamado 				= tdClass::Criar("div");
					$listaChamado->class 		= "lista-chamado";
					$listaChamado->data_chamado	= $linhaChamadosAbertos["id"];
					$listaChamado->add($media,$bFinalizar,$bVisualizar);
					$listaChamado->mostrar();
				}
			}else{
				$alerta = tdClass::Criar("div");
				$alerta->class = "alert alert-warning text-center";
				$alerta->add("não há chamados nessa sessão");
				$alerta->mostrar();
			}

			if ($statuschamado == 1){
				$script = tdClass::Criar("script");
				$script->add('
					$(".btn-visualizar-chamado").click(function(){
						carregar("index.php?controller=chamado&op=editar&id=" + $(this).data("id"),"#conteudoprincipal");
					});
					$(".btn-finalizar-chamado").click(function(){
						var chamado = $(this).data("id");
						$.ajax({
							url:session.urlsystem,
							data:{
								currentproject:session.projeto,
								controller:"chamado",
								op:"finalizar",
								id:chamado
							},
							complete:function(ret){
								var retorno = JSON.parse(ret.responseText);
								if (retorno.status == "success"){
									$(".lista-chamado[data-chamado="+chamado+"]").css("background-color","#115217");
									$(".lista-chamado[data-chamado="+chamado+"]").css("color","#FFF");
									$(".lista-chamado[data-chamado="+chamado+"] .txt-lista-chamado small").css("color","#DDD");
									$(".btn-visualizar-chamado[data-id="+chamado+"]").remove();
									$(".btn-finalizar-chamado[data-id="+chamado+"]").remove();
									carregarChamadosFinalizados();
								}	
							}
						});
					});
				');
				$script->mostrar();
				}	
			exit;
		}

		if ($op == "meuschamados"){
			$divChamadosAbertos = tdClass::Criar("div");
			$divChamadosAbertos->id = "div-chamados-abertos";
			$divChamadosFinalizados = tdClass::Criar("div");
			$divChamadosFinalizados->id = "div-chamados-finalizados";

			$aba = tdClass::Criar("Aba");
			$aba->id = "aba-status-chamados";
			$aba->addItem("Abertos",$divChamadosAbertos);
			$aba->addItem("Finalizados",$divChamadosFinalizados);
			$colConteudo->add($aba);
			$script->add('
				$(document).ready(function(){
					setaPrimeiraAba("#aba-status-chamados");
					carregarChamadosAbertos();
					carregarChamadosFinalizados();
				});
				function carregarChamadosAbertos(){
					carregar("index.php?controller=chamado&op=listarchamados&status=1","#div-chamados-abertos");
					
				}
				function carregarChamadosFinalizados(){
					carregar("index.php?controller=chamado&op=listarchamados&status=4","#div-chamados-finalizados");
				}
			');
		}

		if ($op == "novo"){
			// Form Novo Chamado
			$form = tdClass::Criar("form");
			$form->id = "form-criar-chamado";
			$form->action = "index.php?controller=chamado&op=salvarchamado&currentproject=".CURRENT_PROJECT_ID;
			$form->method = "POST";
			$form->enctype = "multipart/form-data";
			$form->target = "msg-retorno";

			$fieldset = tdClass::Criar("fieldset");
			$legend = tdClass::Criar("legend");
			$legend->add("Criar um novo chamado");

			$projetoLista = Session::Get()->userid == 1?Campos::Lista("projeto","projeto","Projeto",""):null;
			$titulo = Campos::TextoLongo("titulo","titulo","Título","");
			$descricao = Campos::TextArea("descricao","descricao","Descrição","");
			$arquivo = '
				<div class="form-group">
					<label>Arquivos</label>
					<br />
					<button type="button" class="btn btn-default form-control" id="addfile">
						<span class="fas fa-file" aria-hidden="true"></span>
						Clique aqui para adicionar arquivos
					</button>
					<ul class="list-group list-files">
					</ul>
				</div>
				<div class="progress" id="barradeprogresso" style="display:none;">
				  <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
						<b>Aguarde o carregamento dos arquivos</b>
				  </div>
				</div>
			';

			$msgRetorno = tdclass::Criar("iframe");			
			$msgRetorno->id = "msg-retorno";
			$msgRetorno->name = "msg-retorno";
			$msgRetorno->scrooling = "no";
			$msgRetorno->frameborder = "no";
			$msgRetorno->border = "0";

			$enviar = tdClass::Criar("input");
			$enviar->type = "submit";
			$enviar->class = "btn btn-primary";
			$enviar->value = "Enviar";
			$enviar->id = "salvar-chamado";

			$loadercontexto = LOADERCONTEXTO;
			$fieldset->add($legend,$projetoLista,$titulo,$descricao,$arquivo,$enviar,$loadercontexto);
			$form->add($fieldset);

			$colConteudo->add($form,$msgRetorno);

			// Lista de Projetos
			$listaProjetosOptions = "";
			$sqlLCO = "SELECT id,nome FROM td_projeto";
			if (Session::Get()->userid != 1){
				$whereCO = " WHERE projeto = " . CURRENT_PROJECT_ID;
				$sqlLCO .= $whereCO;
			}
			$sqlLCO .=  " ORDER BY id DESC; ";
			//echo $sqlLCO;
			$queryLCO = $connMILES->query($sqlLCO);
			while ($linhaLCO = $queryLCO->fetch()){
				$listaProjetosOptions .= "<option value='".$linhaLCO["id"]."'>".$linhaLCO["nome"]."</option>";
			}

			$script->add('
				$(document).ready(function(){
					var indice = 0;
					$("#addfile").click(function(){
						$(this).parent().append($("<input type=\'file\' id=\'arquivo"+indice+"\' name=\'arquivo"+indice+"\' style=\'display:none;\'/>"));
						$("#arquivo" + indice).click();
						$("#arquivo" + indice).change(function(){
							$(".list-files").append(\'<li class="list-group-item"><span class="fas fa-trash-alt" aria-hidden="true" style="float:right;cursor:pointer;" onclick=$(this).parent().remove();$("#\'+this.id+\'").remove()></span>\'+this.files[0].name+\'</li>\');
						});
						indice++;
					});
					$("#fcadastrocredor").submit(function(){
						$("#barradeprogresso").show();
					});
					$("#form-criar-chamado").submit(function(){
						return validarForm();
					});
					$("#projeto").html("'.$listaProjetosOptions.'");
				});
				function validarForm(){					
					var campos_obrigatorios = [];
					campos_obrigatorios["titulo"] = $("#titulo").val();
					campos_obrigatorios["descricao"] = $("#descricao").val();

					if (!validar(campos_obrigatorios,"#form-criar-chamado","#msg-retorno")){
						return false;
					}

					$(".loadercontexto").show();
					return true;
				}
			');

		}

		if ($op == "novo" || $op == "meuschamados" || $op == "all" || $op == "editar"){
			$bloco->add($style,$colMenu,$colConteudo,$script,$scriptMenu);
			$bloco->mostrar();
			exit;			
		}	

		if ($op == "salvarchamado"){

			$numerochamado = getProxId("ticket",$connMILES);
			$titulo = utf8charset(tdClass::Read("titulo"));
			$descricao = utf8charset(tdClass::Read("descricao"));
			$sql = "
				INSERT INTO td_ticket (
					id,
					titulo,
					descricao,
					projeto,
					usuario,
					tipo,
					prioridade,
					datacriacao,
					status
				) VALUES (
					".$numerochamado.",
					'".$titulo."',
					'".$descricao."',
					".tdClass::Read("projeto").",
					".Session::Get()->userid.",
					1,
					1,
					'".date("Y-m-d H:i:s")."',
					1
			);";
			$query = $connMILES->query($sql);
			if ($query){
				gravandoArquivo($connMILES,$_FILES,$numerochamado);
			}else{
				if (IS_SHOW_ERROR_MESSAGE){
					var_dump($connMILES->errorInfo());
				}
				exit;
			}

			tdClass::Criar("bootstrap")->showCSSTag();

			$msg = "<p><strong>Chamado</strong>: <span style='font-size:18px;color:#0f63ab;'>" . str_pad($numerochamado,5, 0, STR_PAD_LEFT) . "</span></p>";
			$msg .= '<p>Obrigado, <strong>'.Session::Get()->username.'</strong>.<br /><small>Estaremos retornando o mais rápido possível sua solicitação.</small></p>';
			$msg .= "<div class='alert alert-success'>Seu chamado foi aberto com sucesso</div>";
			echo $msg;

			$jsRetorno = tdClass::Criar("script");
			$jsRetorno->add('
				parent.$(".loadercontexto").hide();
				parent.$("#form-criar-chamado").hide("300");
				parent.$("#msg-retorno").show("100");
			');
			$jsRetorno->mostrar();
			exit;
		}

		if ($op == "finalizar"){
			$ticket = tdc::p("td_ticket",tdc::r("id"));			
			$ticket->status = 4;
			if ($ticket->armazenar()){
				$status = "success";
				Transacao::Commit();
			}else{
				$status = "error";
			}
			$retorno = array("status" => $status);
			echo json_encode($retorno);
			exit;
		}
		$bloco_chamado = tdClass::Criar("bloco");
		$bloco_chamado->class = "col-md-6";

		$panel = tdClass::Criar("panel");
		
		$panel->head("Chamados" . 			
			'<button type="button" id="btn-chamado-home-meuschamados" class="btn btn-default btn-chamado-home" aria-label="Ver Meus Chamados"><span class="icone-chamado-home fas fa-tasks" aria-hidden="true"></span></button>' .
			'<button type="button" id="btn-chamado-home-novo" class="btn btn-default btn-chamado-home" aria-label="Novo Chamado"><span class="icone-chamado-home fas fa-plus-circle" aria-hidden="true"></span></button>'
		);
		$panel->tipo = "info";

		$listaChamado = tdClass::Criar("div");
		$listaChamado->id = "lista-chamado-home";
		$listaChamado->class = "list-group";
		
		$sqlChamado = '
			SELECT a.id,a.titulo,a.usuario,a.projeto
			FROM td_ticket a 
			'.(Session::Get()->userid!=1?'WHERE a.projeto = '.CURRENT_PROJECT_ID:'').'
			ORDER BY a.id DESC 
			LIMIT 3;
		';
		$queryChamado = $connMILES->query($sqlChamado);
		while($linhaChamado =  $queryChamado->fetch()) {
			
			$a = tdClass::Criar("hyperlink");
			$a->class="list-group-item";
			$a->href = "#";
			$a->data_id = $linhaChamado["id"];
			
			if ($linhaChamado["projeto"] != CURRENT_PROJECT_ID){
				$referenciaExibirChamado = DATABASECONNECTION;
			}else{
				$referenciaExibirChamado = tdClass::Criar("persistent",array(USUARIO,$linhaChamado["usuario"]))->contexto->nome;				
			}
			$SmallRefExibirChamado = '<small class="nome-usuario-abertura-chamado-home">' . substr($referenciaExibirChamado,0,25) . "</small>";
			$a->add(substr($linhaChamado["titulo"],0,60) . $SmallRefExibirChamado);
			$listaChamado->add($a);
		}
		$jsChamado = tdClass::Criar("script");
		$jsChamado->language="JavaScript";
		$jsChamado->add('
			$(function() {
				$("#btn-chamado-home-novo").click(function(){
					carregar("index.php?controller=chamado&op=novo","#conteudoprincipal");
				});
				$("#btn-chamado-home-meuschamados").click(function(){
					carregar("index.php?controller=chamado&op=meuschamados","#conteudoprincipal");
				});
				$("#lista-chamado-home .list-group-item").click(function(){
					carregar("index.php?controller=chamado&op=editar&id=" + $(this).data("id"),"#conteudoprincipal");
				});				
			});
		');
		$panel->body($listaChamado);
		$bloco_chamado->add($panel,$jsChamado);
		$bloco_chamado->mostrar();
	}

	function gravandoArquivo($connMILES,$files,$ticket,$ticketinteraction=0){
		// Gravando arquivos
		if ($files){
			foreach ($files as $file){
				$prox = getProxId("ticketanexo",$connMILES);
				$extensao = trim(substr($file["name"],strripos($file["name"],"."),5));								
				$nomearquivo = "anexo-" . $ticket . "-" . $ticketinteraction . "-" . $prox . $extensao;
				move_uploaded_file($file["tmp_name"],PATH_FILE . "ticket/" . $nomearquivo);
				$sql_insert_arquivo = "INSERT INTO td_ticketanexo (id,ticket,ticketinteraction,arquivo) VALUES({$prox},{$ticket},{$ticketinteraction},'".$file["name"]."');";
				$query = $connMILES->exec($sql_insert_arquivo);
				if (!$query){
					if (IS_SHOW_ERROR_MESSAGE){
						echo $sql_insert_arquivo . "<br/>";
						var_dump($connMILES->errorInfo());
					}
				}
			}
		}
	}