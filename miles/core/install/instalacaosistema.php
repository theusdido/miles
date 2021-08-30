<?php

	include 'conexao.php';
	include '../system/funcoes.php';

	if (!empty($_POST)){

		define("PREFIXO",$config["PREFIXO"] . "_");
		if ($_POST["op"] == "installed"){
			$sql = "SELECT sistemainstalado FROM  td_instalacao WHERE id = 1;";
			$query = $conn->query($sql);
			$linha = $query->fetch();
			echo $linha["sistemainstalado"];
			exit;
		}
		
		if ($_POST["op"] == "criararquivosconfiguracao"){
			require_once '../system/classes/install/install.class.php';
			tdInstall::$projeto = $_POST["projetoid"];
			tdInstall::criarArquivosConfiguracao(array(
				"PROJECT_DESC" => $_POST["projetodesc"],
				"DATABASE_PADRAO" => $_POST["databasepadrao"],
				"CURRENT_DATABASE" => $_POST["databasepadrao"]
			));			
			exit;
		}
		if ($_POST["op"] == "inserirregistros" && (int)$_POST["novainstalacao"] == 1){
			
			// Usuários
			inserirRegistro($conn,PREFIXO . "usuario",1, array("nome","email","senha","permitirexclusao","permitirtrocarempresa",PREFIXO . "grupousuario","perfilusuario",PREFIXO . "perfil"), array("'Root'","'root'","'63a9f0ea7bb98050796b649e85481845'",1,1,1,0,null));

			// Menu
			inserirRegistro($conn,PREFIXO . "menu",1, array("descricao","link","target","td_pai","ordem","fixo","td_entidade","tipomenu"), array("'Administração'","'#'","''",0,1,"'adm'",0,1));
			$usuarioID = getEntidadeId("usuario",$conn);
			inserirRegistro($conn,PREFIXO . "menu",2, array("descricao","link","target","td_pai","ordem","fixo","td_entidade","tipomenu"), array("'Usuário'","'files/cadastro/".$usuarioID."/td_usuario.html'","''",1,1,"''",$usuarioID,1));
			$menuID = getEntidadeId("menu",$conn);
			inserirRegistro($conn,PREFIXO . "menu",3, array("descricao","link","target","td_pai","ordem","fixo","td_entidade","tipomenu"), array("'Menu'","'files/cadastro/".$menuID."/td_menu.html'","''",1,2,"''",$menuID,1));
			$projetoID = getEntidadeId("projeto",$conn);
			inserirRegistro($conn,PREFIXO . "menu",4, array("descricao","link","target","td_pai","ordem","fixo","td_entidade","tipomenu"), array("'Projeto'","'files/cadastro/".$projetoID."/td_projeto.html'","''",1,3,"''",$projetoID,1));
			$empresaID = getEntidadeId("empresa",$conn);
			inserirRegistro($conn,PREFIXO . "menu",5, array("descricao","link","target","td_pai","ordem","fixo","td_entidade","tipomenu"), array("'Empresa'","'files/cadastro/".$empresaID."/td_empresa.html'","''",1,4,"''",$empresaID,1));
			$avisoID = getEntidadeId("aviso",$conn);
			inserirRegistro($conn,PREFIXO . "menu",6, array("descricao","link","target","td_pai","ordem","fixo","td_entidade","tipomenu"), array("'Aviso'","'files/cadastro/".$avisoID."/td_aviso.html'","''",1,5,"''",$avisoID,1));
			$grupousuarioID = getEntidadeId("grupousuario",$conn);
			inserirRegistro($conn,PREFIXO . "menu",7, array("descricao","link","target","td_pai","ordem","fixo","td_entidade","tipomenu"), array("'Grupo de Usuário'","'files/cadastro/".$grupousuarioID."/td_grupousuario.html'","''",1,6,"''",$grupousuarioID,1));
			
			inserirRegistro($conn,PREFIXO . "menu",8, array("descricao","link","target","td_pai","ordem","fixo","td_entidade","tipomenu"), array("'Ticket'","'#'","''",0,2,"'ticket'",0,1));
			$ticketstatusID = getEntidadeId("ticketstatus",$conn);
			inserirRegistro($conn,PREFIXO . "menu",9, array("descricao","link","target","td_pai","ordem","fixo","td_entidade","tipomenu"), array("'Status'","'files/cadastro/".$ticketstatusID."/td_ticketstatus.html'","''",8,1,"''",$ticketstatusID,1));
			$ticketprioridadeID = getEntidadeId("ticketprioridade",$conn);
			inserirRegistro($conn,PREFIXO . "menu",10, array("descricao","link","target","td_pai","ordem","fixo","td_entidade","tipomenu"), array("'Prioridade'","'files/cadastro/".$ticketprioridadeID."/td_ticketprioridade.html'","''",8,2,"''",$ticketprioridadeID,1));
			$tickettipoID = getEntidadeId("tickettipo",$conn);
			inserirRegistro($conn,PREFIXO . "menu",11, array("descricao","link","target","td_pai","ordem","fixo","td_entidade","tipomenu"), array("'Tipo'","'files/cadastro/".$tickettipoID."/td_tickettipo.html'","''",8,3,"''",$tickettipoID,1));
			$ticketID = getEntidadeId("ticket",$conn);
			inserirRegistro($conn,PREFIXO . "menu",12, array("descricao","link","target","td_pai","ordem","fixo","td_entidade","tipomenu"), array("'Ticket'","'files/cadastro/".$ticketID."/td_ticket.html'","''",8,4,"''",$ticketID,1));
			$ticketinteractionID = getEntidadeId("ticketinteraction",$conn);
			inserirRegistro($conn,PREFIXO . "menu",13, array("descricao","link","target","td_pai","ordem","fixo","td_entidade","tipomenu"), array("'Ticket Interação'","'files/cadastro/".$ticketinteractionID."/td_ticketinteraction.html'","''",8,5,"''",$ticketinteractionID,1));
			$ticketseguidoresID = getEntidadeId("ticketseguidores",$conn);
			inserirRegistro($conn,PREFIXO . "menu",14, array("descricao","link","target","td_pai","ordem","fixo","td_entidade","tipomenu"), array("'Seguidores'","'files/cadastro/".$ticketseguidoresID."/td_ticketseguidores.html'","''",8,6,"''",$ticketseguidoresID,1));

			// Menu Compilar
			inserirRegistro($conn,PREFIXO . "menu",15, array("descricao","link","target","td_pai","ordem","fixo","td_entidade","tipomenu"), array("'Compilar'","'index.php?controller=compilar'","''",1,7,"''",0,"'personalizado'"));

			// Grupo Usuário
			inserirRegistro($conn,PREFIXO . "grupousuario",1, array("descricao"), array("'Desenvolvimento'"));
			inserirRegistro($conn,PREFIXO . "grupousuario",2, array("descricao"), array("'Administrador'"));

			// Config
			inserirRegistro($conn,PREFIXO . "config",1, array(
				"urlupload",
				"urlrequisicoes",
				"urlsaveform",
				"urlloadform",
				"urluploadform",
				"urlpesquisafiltro",
				"urlenderecofiltro",
				"urlexcluirregistros",
				"urlinicializacao",
				"urlloading",
				"urlloadgradededados",
				"urlrelatorio",
				"urlmenu",
				"bancodados",
				"linguagemprogramacao",
				"pathfileupload",
				"pathfileuploadtemp",
				"testecharset",
				"tipogradedados"
			), array(
				"'index.php?controller=upload'",
				"'index.php?controller=requisicoes'",
				"'index.php?controller=salvarform'",
				"'index.php?controller=loadform'",
				"'index.php?controller=upload'",
				"'index.php'",
				"'index.php'",
				"'index.php?controller=excluirregistros'",
				"'index.php?controller=inicializacao'",
				"'index.php?controller=loading'",
				"'index.php?controller=gradededados'",
				"'index.php?controller=relatorio'",
				"'index.php?controller=menu'",
				"'mysql'",
				"'php'",
				"'projects/".$config["CURRENT_PROJECT"]."/arquivos'",
				"'projects/".$config["CURRENT_PROJECT"]."/arquivos/temp'",
				"'á'",
				"'table'"
			));

			// Status
			inserirRegistro($conn,PREFIXO . "status",1, array("descricao","classe"), array("'Ativo'","'td-status-ativo'"));
			inserirRegistro($conn,PREFIXO . "status",2, array("descricao","classe"), array("'Sucesso'","'td-status-sucesso'"));
			inserirRegistro($conn,PREFIXO . "status",3, array("descricao","classe"), array("'Alerta'","'td-status-alerta'"));
			inserirRegistro($conn,PREFIXO . "status",4, array("descricao","classe"), array("'Erro'","'td-status-erro'"));
			inserirRegistro($conn,PREFIXO . "status",5, array("descricao","classe"), array("'Informativo'","'td-status-info'"));			

			// Tipo Aviso
			inserirRegistro($conn,PREFIXO . "tipoaviso",1, array("descricao"), array("'Sucesso'"));
			inserirRegistro($conn,PREFIXO . "tipoaviso",2, array("descricao"), array("'Alerta'"));
			inserirRegistro($conn,PREFIXO . "tipoaviso",3, array("descricao"), array("'Erro'"));
			inserirRegistro($conn,PREFIXO . "tipoaviso",4, array("descricao"), array("'Informativo'"));

			// Tipo de Conexão com Banco de Dados
			inserirRegistro($conn,PREFIXO . "typeconnectiondatabase",1, array("nome,descricao"), array("'desenv'","'Desenvolvimento'"));
			inserirRegistro($conn,PREFIXO . "typeconnectiondatabase",2, array("nome,descricao"), array("'teste'","'Testes'"));
			inserirRegistro($conn,PREFIXO . "typeconnectiondatabase",3, array("nome,descricao"), array("'homolog'","'Homologação'"));
			inserirRegistro($conn,PREFIXO . "typeconnectiondatabase",4, array("nome,descricao"), array("'producao'","'Produção'"));

			// Banco de Dados
			inserirRegistro($conn,PREFIXO . "database",1, array("nome","descricao"), array("mysql","'MySQL'"));
			inserirRegistro($conn,PREFIXO . "database",2, array("nome","descricao"), array("cache","'CACHÉ'"));
			
			// Aba - Projeto
			inserirRegistro($conn,PREFIXO . "abas",2, array("td_entidade","descricao","atributos"), array(getEntidadeId("td_abas",$conn),"'Aba'",getAtributoId(getEntidadeId("td_abas",$conn),"nome",$conn)));

			// Local para CharSet
			inserirRegistro($conn,PREFIXO . "charset",1, array("local","charset"), array("'Página principal (index)'","'D'"));
			inserirRegistro($conn,PREFIXO . "charset",2, array("local","charset"), array("'Grade de Dados (load)'","'E'"));
			inserirRegistro($conn,PREFIXO . "charset",3, array("local","charset"), array("'Formulário (load)'","'N'"));
			inserirRegistro($conn,PREFIXO . "charset",4, array("local","charset"), array("'Classe Campos'","'N'"));
			inserirRegistro($conn,PREFIXO . "charset",5, array("local","charset"), array("'MDM Embituido PHP'","'E'"));
			inserirRegistro($conn,PREFIXO . "charset",6, array("local","charset"), array("'MDM Salvar Form com Submit'","'E'"));
			inserirRegistro($conn,PREFIXO . "charset",7, array("local","charset"), array("'Gerar HTML no CRUD'","'D'"));
			inserirRegistro($conn,PREFIXO . "charset",8, array("local","charset"), array("'Javascript Embutido no PHP'","'D'"));
			
		}

		if (file_exists($bdPathFile)){
			
			if ($_POST["op"] == "instalar"){
				
				$query = $conn->query("UPDATE td_instalacao SET sistemainstalado = 1 WHERE id = 1;");
				if (!$query){
					foreach ($conn->errorInfo() as $erro){
						echo $erro . "</br>";
					}
				}
				echo 1;
				exit;
				
			}
			if ($_POST["op"] == "instrucao"){
				include $_POST["instrucao"];
				echo 1;
				exit;
			}
			
			if ($_POST["op"] == "config_projeto"){
				$conn->query("INSERT INTO td_projeto (id,nome) VALUES (1,'".utf8_decode($_POST["nome"])."');");
				exit;
			}
		}	
	}
	$projetodesc = $projetodiretorio = $projetoprefixo = "";
	$displaybutton = "Instalar";
	$novaInstalacao = 1;
	if (isset($_SESSION["INSTALACAOSISTEMA"])){
		if ($_SESSION["INSTALACAOSISTEMA"] == 1){
			$projetodesc 		= $config["PROJETO_DESC"];
			$projetodiretorio 	= $config["PROJETO_FOLDER"];
			$projetoprefixo 	= $config["PREFIXO"];
			$displaybutton 		= "Atualizar";
			$novaInstalacao 	= 0;
		}
	}
?>
<html>
	<head>
		<?php 
			include 'head.php'; 
		?>			
	</head>
	<body>
		<div class="container-fluid">
			<?php 
				include 'topo.php';
			?>
			<div class="row-fluid">
				<div class="col-md-3">
					<?php include 'guia.php'; ?>
				</div>
				<div class="col-md-9">
					<div class="row-fluid">
						<div id="form-grupo-botao-instalacao">
							<img id="loader-instalar" src="<?=$_SESSION["URL_SYSTEM_THEME"]?>loading2.gif"/>
							<button type="button" class="btn btn-primary btn-instalacaosistema" id="btn-instalar">
								<?=$displaybutton?>				
							</button>
							<button type="button" class="btn btn-info btn-instalacaosistema" id="btn-personalizar">
								Personalizada
							</button>
							<div class="progress" id="barradeprogresso-instalacao">
							  <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
								20% Complete
							  </div>
							</div>
							<div id="retorno"></div>
						</div>
					</div>
					<div class="row-fluid" id="linha-formulario">
						<form id="form-instalacao-sistema">
							<fieldset>
								<legend>Configurações</legend>	
								<div class="form-group">
									<label for="projectname">Projeto</label>
									<input type="text" class="form-control" id="projectname" name="projectname" placeholder="Empresa/Organização" value="<?=$projetodesc?>">
								</div>
								<div class="form-group">
									<label for="projectfolder">Diretório</label>
									<input type="text" class="form-control" id="projectfolder" name="projectfolder" placeholder="Diretório Raiz" value="<?=$projetodiretorio?>">
								</div>
								<div class="form-group">
									<label for="prefixo">Prefixo</label>
									<input type="text" class="form-control" id="prefixo" name="prefixo" placeholder="Prefixo" value="<?=$projetoprefixo?>">
								</div>
							</fieldset>	
						</form>
					</div>
					<div class="row-fluid" id="linha-personalizacao">
						<div class="panel panel-info" id="panel-personalizacao">
						  <div class="panel-heading">
							<h3 class="panel-title">Personalizar Instalação</h3>
						  </div>
						  <div class="panel-body">
							<form id="formulario-personalizado"></form>
						  </div>
						  <div class="panel-footer">
							<button type="button" class="btn btn-warning btn-instalacaosistema" id="btn-atualizar-personalizado">
								Atualizar
							</button>
							<img id="loading-atualizar-personalizado" src="<?=$_SESSION["URL_SYSTEM_THEME"]?>loading2.gif" />
						  </div>
						</div>
					</div>					
				</div>
			</div>
			<script type="text/javascript" src="<?=$_SESSION["URL_LIB"]?>jquery/jquery.js"></script>
			<script type="text/javascript" src="<?=$_SESSION["URL_LIB"]?>tdlib/js/tdlib.js"></script>
			<script type="text/javascript">
				<?php
					if (isset($_GET["installsystem"])){
						echo 'setTimeout(function(){$("#btn-instalar").click();},100);';
						echo "\n";
					}
				?>
				var showpersonalizar = false;
				var linhas = [];
				
				var componentes = [];
				// System
				componentes['system.entidade'] 				= 'system/entidade.php';
				componentes['system.atributo'] 				= 'system/atributo.php';				
				componentes['system.menu'] 					= 'system/menu.php';
				componentes['system.grupousuario'] 			= 'system/grupousuario.php';
				componentes['system.usuario'] 				= 'system/usuario.php';				
				componentes['system.relacionamento']		= 'system/relacionamento.php';
				componentes['system.abas'] 					= 'system/abas.php';		
				componentes['system.lista'] 				= 'system/lista.php';
				componentes['system.pagina'] 				= 'system/pagina.php';
				componentes['system.tagsattributes'] 		= 'system/tagsattributes.php';
				componentes['system.tags'] 					= 'system/tags.php';
				componentes['system.tipoaviso'] 			= 'system/tipoaviso.php';
				componentes['system.aviso'] 				= 'system/aviso.php';
				componentes['system.config'] 				= 'system/config.php';
				componentes['system.entidadePermissoes']	= 'system/entidadePermissoes.php';
				componentes['system.atributoPermissoes'] 	= 'system/atributoPermissoes.php';
				componentes['system.funcao'] 				= 'system/funcao.php';
				componentes['system.funcaoPermissoes']		= 'system/funcaoPermissoes.php';
				componentes['system.menuPermissoes'] 		= 'system/menuPermissoes.php';
				componentes['system.atributoFiltro'] 		= 'system/atributoFiltro.php';
				componentes['system.status'] 				= 'system/status.php';
				componentes['system.consulta'] 				= 'system/consulta.php';
				componentes['system.relatorio'] 			= 'system/relatorio.php';
				componentes['system.movimentacao'] 			= 'system/movimentacao.php';
				componentes['system.log'] 					= 'system/log.php';
				componentes['system.menucrud'] 				= 'system/menucrud.php';
				componentes['system.typeconnectiondatabase']= 'system/typeconnectiondatabase.php';
				componentes['system.database'] 				= 'system/database.php';
				componentes['system.connectiondatabase'] 	= 'system/connectiondatabase.php';
				componentes['system.connectionftp'] 		= 'system/connectionftp.php';
				componentes['system.charset'] 				= 'system/charset.php';
				componentes['system.projeto'] 				= 'system/projeto.php';
				componentes['system.endereco'] 				= 'system/endereco.php';
				componentes['system.empresa'] 				= 'system/empresa.php';
				componentes['system.historicoatividade']	= 'system/historicoatividade.php';
				componentes['system.comunicado']			= 'system/comunicado.php';
				componentes['system.email']					= 'system/email.php';
				// Helpdesk
				componentes['helpdesk.status'] 				= 'helpdesk/status.php';
				componentes['helpdesk.prioridade'] 			= 'helpdesk/prioridade.php';
				componentes['helpdesk.tipo']				= 'helpdesk/tipo.php';
				componentes['helpdesk.seguidores'] 			= 'helpdesk/seguidores.php';
				componentes['helpdesk.ticket'] 				= 'helpdesk/ticket.php';
				componentes['helpdesk.anexos'] 				= 'helpdesk/anexos.php';
				// Aplicativo
				componentes['aplicativo.dispositivo']		= 'aplicativo/dispositivo.php';
				componentes['aplicativo.usuario'] 			= 'aplicativo/usuario.php';
				// Geral
				componentes['geral.mes']					= 'geral/mes.php';
				componentes['geral.diasemana']				= 'geral/diasemana.php';
				componentes['geral.feriado'] 				= 'geral/feriado.php';

				var totalinstrucao = 0;
				var progressaoatual = 0;
				$("#btn-instalar").click(function(){
					for (c in componentes){
						linhas.push(componentes[c]);
					}
					totalinstrucao = linhas.length;
					$.ajax({
						type:"POST",
						url:"instalacaosistema.php",
						data:{
							<?=getBDParams()?>
							op:"instalar",
							projectfolder:$("#projectfolder").val(),
							projectname:$("#projectname").val(),
							prefixo:$("#prefixo").val(),
							currentproject:<?=$_SESSION["currentproject"]?>
						},
						success:function(retorno){
							if (retorno == 1 || retorno == "1"){
								$("#barradeprogresso-instalacao .progress-bar").css("width","0%");
								$("#barradeprogresso-instalacao .progress-bar").html("0%");
								$("#barradeprogresso-instalacao").show();
								executa(linhas[progressaoatual]);
							}else{
								$("#retorno").html('<div class="alert alert-danger" role="alert">Erro ao instalar o sistema. Motivo: ' +retorno+ '</div>');
								$("#retorno").show();								
							}
						}
					});
				});
				$("#btn-personalizar").click(function(){
					if (showpersonalizar){						
						$("#linha-formulario").show();
						$("#linha-personalizacao").hide();
						showpersonalizar = false;
					}else{						
						$("#linha-formulario").hide();
						$("#linha-personalizacao").show();
						showpersonalizar = true;
						addComponentesPersonalizados();
					}
				});
				$("#btn-atualizar-personalizado").click(function(){
					// Pega os itens personalizados
					$("#formulario-personalizado .form-group input[type=checkbox]:checked").each(function(){
						instrucao = componentes[$(this).data("componente")];
						$.ajax({
							type:"POST",
							url:"instalacaosistema.php",
							beforeSend:function(){
								$("#loading-atualizar-personalizado").show();
							},
							data:{
								<?=getBDParams()?>
								op:"instrucao",
								instrucao:instrucao,
								currentproject:<?=$_SESSION["currentproject"]?>
							},
							complete:function(retorno){
								$("#loading-atualizar-personalizado").hide();
								if (parseInt(retorno.responseText) == 1){
									td.CallBackMenssage('Atualizado com Sucessos','success');
								}else{
									td.CallBackMenssage('Erro ao atualizar','danger');
								}
							}
						});	
					});
					
				});

				
				function executa(instrucao){
					$.ajax({
						type:"POST",
						url:"instalacaosistema.php",
						data:{
							<?=getBDParams()?>
							op:"instrucao",
							instrucao:instrucao,
							currentproject:<?=$_SESSION["currentproject"]?>
						},
						success:function(retorno){
							if (retorno == 1 || retorno == "1"){
								progressaoatual++;
								var percentual = parseInt((progressaoatual*100)/totalinstrucao);
								$("#barradeprogresso-instalacao .progress-bar").css("width",percentual + "%");
								$("#barradeprogresso-instalacao .progress-bar").html(percentual + "%");
								
								if (progressaoatual < totalinstrucao){
									executa(linhas[progressaoatual]);
								}else{
									$("#barradeprogresso-instalacao .progress-bar").removeClass("progress-bar-info");
									$("#barradeprogresso-instalacao .progress-bar").addClass("progress-bar-success");
									$("#barradeprogresso-instalacao .progress-bar").html("Sistema Instalado com Sucesso!");
									$("#guia-instalacao").attr("src","<?=$_SESSION['URL_SYSTEM_THEME']?>check.gif");
									setTimeout(function(){
										$("#barradeprogresso-instalacao").hide("5000");
									},5000);
									$.ajax({
										type:"POST",
										url:"instalacaosistema.php",
										data:{
											<?=getBDParams()?>
											op:"inserirregistros",
											novainstalacao:"<?=$novaInstalacao?>",
											currentproject:<?=$_SESSION["currentproject"]?>
										},
										complete:function(){
											// Setando Permissões
											$.ajax({
												url:"../index.php?",
												data:{
													<?=getBDParams()?>
													controller:"requisicoes",
													op:"setar_todas_permissoes",
													auth:1,
													permissao:1
												}
											});
										}
									});
									<?php
										if (isset($_GET["installsystem"])){
											echo 'parent.finalizacaoDisplayInstalacao();';
											$newdir = "../projects/" . $_GET["projetoid"] . "/";
											copiardiretorio("../projects/1/",$newdir,true,"config");
										}
									?>
									$.ajax({
										type:"POST",
										url:"instalacaosistema.php",
										data:{
											<?=getBDParams()?>
											op:"criar_arquivos_configuracao_projeto",
											nome:$("#projectname").val(),
											currentproject:<?=$_SESSION["currentproject"]?>
										},
										success:function(){

										}
									});
								}
								$.ajax({
									type:"POST",
									url:"instalacaosistema.php",
									data:{
										<?=getBDParams()?>
										op:"config_projeto",
										nome:$("#projectname").val(),
										currentproject:<?=$_SESSION["currentproject"]?>
									},
									success:function(){

									}
								});
							}else{
								$("#retorno").html('<div class="alert alert-danger" role="alert">Erro ao instalar o sistema. Motivo: ' +retorno+ '</div>');
								$("#retorno").show();
							}
						}
					});					
				}
				$(document).ready(function(){
					
				});

				function addComponentesPersonalizados(){
					var divRow = $('<div class="row">');
					for(c in componentes){
						var divCol		= $('<div class="col-md-4 col-sm-6">');
						var formgroup 	= $('<div class="form-group">');
						var label 		= $('<label for="'+c+'">'+c+'</label>');
						var checkbox	= $('<input id="'+c+'" type="checkbox" data-componente="'+c+'">');

						formgroup.append(checkbox,label);
						divCol.append(formgroup);
						divRow.append(divCol);
					}
					$("#formulario-personalizado").append(divRow);
				}
			</script>
		</div>	
	</body>
</html>
<?php
	function getBDParams(){
		if (isset($_GET["installsystem"])){
			echo '
				installsystem:1,
				usuario:"'.$_GET["usuario"].'",
				senha:"'.$_GET["senha"].'",
				base:"'.$_GET["base"].'",
				host:"'.$_GET["host"].'",
				tipo:"'.$_GET["tipo"].'",
				porta:"'.$_GET["porta"].'",
			';
		}
	}
?>