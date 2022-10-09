<?php

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
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div id="form-grupo-botao-instalacao">
								<img id="loader-instalar" src="<?=URL_LOADING2?>"/>
								<button type="button" class="btn btn-primary btn-instalacaosistema" id="btn-instalar">
									<?=$displaybutton?>				
								</button>
								<button type="button" class="btn btn-info btn-instalacaosistema" id="btn-personalizar">
									Personalizada
								</button>
								<div id="retorno"></div>
							</div>
						</div>
					</div>
					<div class="row-fluid">
						<div class="col-md-12 col-sm-12">					
							<div class="progress" id="barradeprogresso-instalacao">
							  <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
								0% Complete
							  </div>
							</div>							
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
								<div class="btn-group-atualizar-personalizado">
						  			<button type="button" class="btn btn-warning btn-instalacaosistema" id="btn-atualizar-personalizado">
										Atualizar
									</button>
									<img id="loading-atualizar-personalizado" src="<?=URL_LOADING2?>" />
								</div>
								<form id="formulario-personalizado"></form>
						  </div>
						</div>					
					</div>
				</div>
			</divs>
			<script type="text/javascript" src="<?=URL_LIB?>jquery/jquery.js"></script>
			<script type="text/javascript" src="<?=URL_LIB?>tdlib/js/tdlib.js"></script>
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
				componentes['system.entidadepermissoes']	= 'system/entidadepermissoes.php';
				componentes['system.atributopermissoes'] 	= 'system/atributopermissoes.php';
				componentes['system.funcao'] 				= 'system/funcao.php';
				componentes['system.funcaopermissoes']		= 'system/funcaopermissoes.php';
				componentes['system.menupermissoes'] 		= 'system/menupermissoes.php';
				componentes['system.atributofiltro'] 		= 'system/atributofiltro.php';
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
				componentes['geral.mes']						= 'geral/datas/mes.php';
				componentes['geral.diasemana']					= 'geral/datas/diasemana.php';
				componentes['geral.feriado'] 					= 'geral/datas/feriado.php';
				componentes['geral.email.emailconfiguracao']	= 'geral/email/emailconfiguracao.php';

				var totalinstrucao = 0;
				var progressaoatual = 0;
				$("#btn-instalar").click(function(){
					for (c in componentes){
						linhas.push(componentes[c]);
					}
					totalinstrucao = linhas.length;

					$.ajax({
						type:"POST",
						url:"<?=URL_MILES?>",
						data:{
							controller:"install/instalar",
							op:"instalar",
							projectfolder:$("#projectfolder").val(),
							projectname:$("#projectname").val(),
							prefixo:$("#prefixo").val()
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
							url:"<?=URL_MILES?>",
							beforeSend:function(){
								$("#loading-atualizar-personalizado").show();
							},
							data:{
								controller:"install/instalar",
								op:"instrucao",
								instrucao:instrucao
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
						url:"<?=URL_MILES?>",
						data:{
							controller:"install/instalar",
							op:"instrucao",
							instrucao:instrucao
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
									$("#guia-instalacao").attr("src","<?=URL_SYSTEM_THEME?>check.gif");
									setTimeout(function(){
										$("#barradeprogresso-instalacao").hide("5000");
									},5000);
									$.ajax({
										type:"POST",
										url:"<?=URL_MILES?>",
										data:{
											controller:"install/instalar",
											op:"projeto",
											nome:$("#projectname").val()
										}
									});
									$.ajax({
										type:"POST",
										url:"<?=URL_MILES?>",
										data:{
											controller:"install/instalar",
											op:"inserirregistros",
											novainstalacao:"<?=$novaInstalacao?>"
										},
										complete:function(){
											// Setando Permissões
											$.ajax({
												url:"<?=URL_MILES?>",
												data:{
													controller:"requisicoes",
													op:"setar_todas_permissoes",
													auth:1,
													permissao:1
												}
											});
										}
									});
									$.ajax({
										type:"POST",
										url:"<?=URL_MILES?>",
										data:{
											controller:'install/instalar',
											op:"arquivos",
											nome:$("#projectname").val()
										}
									});
									$.ajax({
										type:"POST",
										url:"<?=URL_MILES?>",
										data:{
											controller:'install/instalar',
											op:"versao"
										}
									});									
								}
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
					for(c in componentes){
						var formgroup 	= $('<div class="form-group">');
						var label 		= $('<label for="'+c+'">'+c+'</label>');
						var checkbox	= $('<input id="'+c+'" type="checkbox" data-componente="'+c+'">');

						formgroup.append(checkbox,label);
						$("#formulario-personalizado").append(formgroup);
					}
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