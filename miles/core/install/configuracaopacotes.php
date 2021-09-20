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
					<form>
						<fieldset>
							<legend>Configuração dos Pacotes</legend>
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<div id="btn-salvar-pacotes" class="form-grupo-botao">
										<img id="loader-pacotes" src="<?=$_SESSION["URL_SYSTEM_THEME"]?>loading2.gif"/>
										<button type="button" class="btn btn-primary" id="btn-pacotes">
											Salvar
										</button>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<div id="retorno" class="alert" role="alert">
										<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<p class="msg"></p>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6 col-sm-6">
									<div class="panel panel-default panel-pacote">
										<div class="panel-heading">Website</div>
										<div class="panel-body">
											<div class="list-group">
												<a href="#" class="list-group-item carregar-componentes" data-pacote="website" data-componente="geral">Geral</a>
												<a href="#" class="list-group-item carregar-componentes" data-pacote="website" data-componente="institucional">Institucional</a>
												<a href="#" class="list-group-item carregar-componentes" data-pacote="website" data-componente="ecommerce">E-Commerce</a>
												<a href="#" class="list-group-item carregar-componentes" data-pacote="website" data-componente="blog">Blog</a>								  								  
												<a href="#" class="list-group-item carregar-componentes" data-pacote="website" data-componente="landpage">LandPage</a>
												<a href="#" class="list-group-item carregar-componentes" data-pacote="website" data-componente="onpage">OnePage</a>
												<a href="#" class="list-group-item carregar-componentes" data-pacote="website" data-componente="forum">Fórum</a>
												<a href="#" class="list-group-item carregar-componentes" data-pacote="website" data-componente="portal">Portal</a>
												<a href="#" class="list-group-item carregar-componentes" data-pacote="website" data-componente="redesocial">Rede Social</a>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-sm-6">							
									<div class="panel panel-default panel-pacote">
										<div class="panel-heading">Sistema</div>
										<div class="panel-body">
											<div class="list-group">
												<a href="#" class="list-group-item carregar-componentes" data-pacote="sistema" data-componente="erp">ERP - Enterprise Resource Planning</a>
												<a href="#" class="list-group-item carregar-componentes" data-pacote="sistema" data-componente="crm">CRM - Customer Relationship Management</a>
												<a href="#" class="list-group-item carregar-componentes" data-pacote="sistema" data-componente="bi">BI - Business Intelligence</a>								  								  
											</div>
										</div>
									</div>
								</div>
							</div>
						</fieldset>	  
					</form>		
				</div>
			</div>
			<!-- Modal -->
			<div class="modal fade" id="view-componente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel"></h4>
				  </div>
				  <div class="modal-body">
					<p></p>
				  </div>
				</div>
			  </div>
			</div>			
			<script type="text/javascript" language="JavaScript" src="<?=$_SESSION["URL_LIB"]?>jquery/jquery.js"></script>
			<script type="text/javascript" src="<?=$_SESSION["URL_LIB"]?>bootstrap/3.3.1/js/bootstrap.js"></script>
			<script type="text/javascript" language="JavaScript">
				var componentes = [];
				var indiceComponente = 0;
				var indiceRegistro = 0;
				var registros = [];
				$(".carregar-componentes").click(function(){
					$("#view-componente .modal-title").html($(this).html() + " <small>( Componentes )</small>");
					var pacote = $(this).data("pacote");
					var componente = $(this).data("componente");
					$("#view-componente .modal-body p").html("");
					$("#view-componente .modal-body p").load("<?=$_SESSION["URL_INSTALL"]?>package/" + pacote + "/" + componente + "/componentes.php?currentproject=<?=$_SESSION["currentproject"]?>"
					,function(){
						$(".checkbox-componente").each(function(){
						for(c in componentes){
							if (componentes[c] == $(this).attr("id")){
								$(this).attr("checked",true);
							}
						}
						});
					});
					$("#view-componente").modal({
						backdrop:false
					});
					$("#view-componente").modal("show");
				});
				$("#btn-pacotes").click(function(){
					$("#retorno").hide();
					instalarcomponentes();									
				});
				function excluirComponenteLista(compte){
					for(c in componentes){
						if (componentes[c] == compte){
							componentes.splice(c,1);
						}
					}
				}
				function excluirRegistroLista(registro){
					for(r in registros){
						if (registros[r] == registro){
							registros.splice(r,1);
						}
					}
				}
				function instalarcomponentes(){
					if (componentes.length <= 0){
						msgRetorno("<b>Error !</b>Você precisa selecionar um componente para instalar.","alert-danger");
                        return false;
                    }
					$.ajax({
						url:"<?=$_SESSION['URL_MILES']?>",
						type:"POST",
						data:{
							controller:'install/modulos',
							op:"instalarcomponente",
							componente:componentes[indiceComponente]
						},
						beforeSend:function(){
							$("#loader-pacotes").show();
						},
						complete:function(ret){
							var retorno = parseInt(ret.responseText);
							if (retorno == 1){
								instalarregistros();
								indiceComponente++;								
								if (componentes[indiceComponente] != undefined){									
									instalarcomponentes();
								}else{
									$.ajax({
										url:"<?=$_SESSION['URL_MILES']?>",
										type:"POST",
										data:{
											controller:'install/modulos',
											op:"atualizar"
										},
                                         error:function(ret){
											 msgRetorno('<b>Error !</b>'+ret.responseText,"alert-danger");
                                        }
									});
									indiceComponente 	= 0;
									indiceRegistro 		= 0;
									componentes.splice(0,componentes.length);
									$(".checkbox-componente,.checkbox-registro").prop("checked",false);
									msgRetorno('<b>Parabéns !</b>. Pacotes configurados com sucesso.');
									$("#guia-pacote").attr("src","<?=$_SESSION['URL_SYSTEM_THEME']?>check.gif");
									
								}
							}else{
								msgRetorno('<b>Error! '+componentes[indiceComponente]+' => </b> '+ret.responseText,"alert-danger");
							}
						},
                        error:function(ret){
							msgRetorno('<b>Error !</b>'+ret.responseText,"alert-danger");
                        }
					});
					
				}	
				function instalarregistros(){
					$.ajax({
						url:"<?=$_SESSION['URL_MILES']?>",
						type:"POST",
						data:{
							controller:'install/modulos',
							op:"instalarcomponente",
							registro:registros[indiceRegistro]
						},
						beforeSend:function(){
							$("#loader-pacotes").show();
						},
						error:function(ret){
							msgRetorno('<b>Error !</b>'+ret.responseText,"alert-danger");
						},
						complete:function(retorno){
							indiceRegistro++;
							if (registros[indiceRegistro] != undefined){									
								instalarregistros();
							}
						}
					});
				}
				function msgRetorno(msg,tipo = "alert-success"){
					$("#loader-pacotes").hide();
					$("#retorno").show();
					$("#retorno").removeClass("alert-success alert-danger alert-warning");
					$("#retorno").addClass(tipo);
					$("#retorno .msg").html(msg);
				}
				$(document).ready(function(){
					$("#retorno").hide();
				});
				$("#retorno .close").click(function(){
					$(this).parents(".alert").hide();
				});
			</script>
		</div>	
	</body>
</html>