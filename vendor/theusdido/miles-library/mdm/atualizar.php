<?php

	set_time_limit(7200);

	require 'conexao.php';
	require 'prefixo.php';
	require 'funcoes.php';


?>
<html>
	<head>
		<title>Atualizar</title>
		<?php include 'head.php' ?>
		<style>
			.btn-atualizar{
				float:right;
			}
			#retorno-ajax-desenvolvimentoToProducao{
				float:right;
			}
			#fDesenvolvimentoToProducao .checkbox{
				float:left;
				width:33%;
			}
			#fDesenvolvimentoToProducao hr{
				float:left;
				width:100%;
			}
		</style>
	</head>
	<body>
		<?php include 'menu_topo.php'; ?>
		<div class="container-fluid">
			<?php include 'cabecalho.php'; ?>
			<div class="row-fluid">
				<div class="col-md-2">
					<?php
						include 'menu_entidade.php'; 
					?>
				</div>
				<div class="col-md-10">
					<form>
						<legend>Atualizar</legend>
					</form>
	

					<div class="panel panel-default">
						<div class="panel-heading">Desenvolvimento > Produção</div>
						<div class="panel-body">

							<form id="fDesenvolvimentoToProducao" class="form-inline">
								<div class="checkbox">
									<label>
										<b>Estrutura</b>
										<a id="selecionarEntidadeEstrutura" href="#"> [ Selecionar Entidades ]</a>
									</label>
								</div>
								<div class="checkbox">
									<label>
										<input type="checkbox" id="cbRegistro"> <b>Registro</b>
										<a id="selecionarEntidadeRegistro" href="#"> [ Selecionar Entidades ]</a>
									</label>
								</div>								
								<div class="checkbox">
									<label>
										<input type="checkbox" id="cbArquivo"> <b>Arquivo</b>
										<a id="selecionarEntidadeArquivo" href="#"> [ Selecionar Entidades ]</a>
									</label>
								</div>
								<hr/>
								<button type="button" class="btn btn-primary btn-atualizar" onclick="desenvolvimentoToProducao()">Atualizar</button>
								<div id="retorno-ajax-desenvolvimentoToProducao"></div>
							</form>						

						</div>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			function desenvolvimentoToProducao(){
				var entidadeestrutura 	= "";
				var entidaderegistro 	= "";
				var entidadearquivo 	= "";
				
				if ($(".entidadeestrutura:checked,.entidaderegistro:checked,.entidadearquivo:checked").length <= 0){
					bootbox.alert('Selecione uma opção.');
					return false;
				}

				$(".entidadeestrutura:checked").each(function(){
					entidadeestrutura += (entidadeestrutura==""?"":",") + $(this).data("entidade");
				});
				$(".entidaderegistro:checked").each(function(){
					entidaderegistro += (entidaderegistro==""?"":",") + $(this).data("entidade");
				});
				$(".entidadearquivo:checked").each(function(){
					entidadearquivo += (entidadearquivo==""?"":",") + $(this).data("entidade");
				});

				$.ajax({
					url:"../../index.php",					
					data:{
						controller:"mdm/atualizar",
						currentproject:<?=$_SESSION["currentproject"]?>,
						op:"desenvolvimentotoproducao",
						entidadesestrutura:entidadeestrutura,
						entidadesregistro:entidaderegistro,
						entidadesarquivo:entidadearquivo
					},
					beforeSend:function(){
						$("#retorno-ajax-desenvolvimentoToProducao").html('<img src="../tema/padrao/loading2.gif" id="loading" style="float:left;margin-left:48%;" />');
					},
					complete:function(){
						$("#retorno-ajax-desenvolvimentoToProducao").html("");
					}
				});
			}
			$("#selecionarEntidadeEstrutura").click(function(e){
				e.preventDefault();
				$(".entidadeestrutura:checked").each(function(){
					$(this).attr("checked",false);
				});				
				$("#modalEntidadeEstrutura").modal({
					backdrop:false
				});
				$("#modalEntidadeEstrutura").modal('show');
			});
			$("#selecionarEntidadeRegistro").click(function(e){
				e.preventDefault();
				$(".entidaderegistro:checked").each(function(){
					$(this).attr("checked",false);
				});				
				$("#modalEntidadeRegistro").modal({
					backdrop:false
				});
				$("#modalEntidadeRegistro").modal('show');
			});
			$("#selecionarEntidadeArquivo").click(function(e){
				e.preventDefault();
				$(".entidadearquivo:checked").each(function(){
					$(this).attr("checked",false);
				});				
				$("#modalEntidadeArquivo").modal({
					backdrop:false
				});
				$("#modalEntidadeArquivo").modal('show');
			});

			$(document).ready( () => {
				$("#cbEstruturaAll").click( function() {
					selectAllEstrutura($(this).is(":checked"));
				});
				$('#modalEntidadeEstrutura').on('shown.bs.modal', function () {
					selectAllEstrutura($("#cbEstruturaAll").is(":checked"));
				});
			});

			function selectAllEstrutura(check){
				$(".entidadeestrutura").attr("checked",check);
			}
		</script>

	<div class="modal fade" tabindex="-1" role="dialog" id="modalEntidadeEstrutura">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Entidade</h4>
			</div>
			<div class="modal-body">
				<form>
					<div class="checkbox">
						<label for="cbEstruturaAll">
							<input type="checkbox" id="cbEstruturaAll"> <b>Selecionar Todas</b>
						</label>
					</div>
					<hr />
					<?php
						$sql = "SELECT descricao,id FROM td_entidade";
						$query = $conn->query($sql);
						while ($linha = $query->fetch()){
							echo '
								<div class="checkbox">
									<label>
										<input type="checkbox" class="entidadeestrutura" data-entidade="'.$linha["id"].'">'.utf8_encode($linha["descricao"]).'
									</label>
								</div>
							';
						}
					?>
				</form>
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->


	<div class="modal fade" tabindex="-1" role="dialog" id="modalEntidadeRegistro">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">Selecionar Entidades</h4>
		  </div>
		  <div class="modal-body">
				<form>
				<?php
					$sql = "SELECT descricao,id FROM td_entidade";
					$query = $conn->query($sql);
					while ($linha = $query->fetch()){
						echo '
							<div class="checkbox">
								<label>
									<input type="checkbox" class="entidaderegistro" data-entidade="'.$linha["id"].'">'.utf8_encode($linha["descricao"]).'
								</label>
							</div>
						';
					}
				?>
				</form>
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<div class="modal fade" tabindex="-1" role="dialog" id="modalEntidadeArquivo">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">Selecionar Entidades</h4>
		  </div>
		  <div class="modal-body">
				<form>
				<?php
					$sql = "SELECT descricao,id FROM td_entidade";
					$query = $conn->query($sql);
					while ($linha = $query->fetch()){
						echo '
							<div class="checkbox">
								<label>
									<input type="checkbox" class="entidadearquivo" data-entidade="'.$linha["id"].'">'.utf8_encode($linha["descricao"]).'
								</label>
							</div>
						';
					}
				?>
				</form>
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->	

	</body>
</html>
