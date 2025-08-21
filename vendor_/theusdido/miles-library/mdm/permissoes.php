<?php
	require 'conexao.php';
	require 'prefixo.php';
	require_once 'funcoes.php';
	
	$url_loading = (isset($_SESSION['URL_SYSTEM_THEME'])?$_SESSION['URL_SYSTEM_THEME']:'') . 'loading.gif';
?>

<html>
	<head>
		<title>Permissões</title>
		<?php include 'head.php' ?>
		<style>
			.list-group-item .glyphicon {		
				margin-right:5px;
			}
			.list-group-item .lista-usuario{
				margin-top:10px;
			}
			.cabecalho-lista-usuario{
				cursor:pointer;
			}
			#dadosusuariopermissao{
				float:right;
				display:none;
				height:30px;
				margin:10px;
			}
			#dadosusuariopermissao > span{
				float:left;
				margin-top:5px;
			}
			.nome_usuario{
				font-size:18px;
			}
			.descricao-funcao{
				font-size:18px;
			}
			.lista_usuarios_funcoes{
				margin-top:10px;
			}
			.descricao-usuario{
				color:#999;
			}
			.filtro-pesquisa-usuario{
				float:right;
			}
			.add-usuario-funcao{
				float:right;
				margin-right:4px;
			}
			.lista-usuario{
				display:none;
			}
			#btn-restaurar-permissoes,#btn-all-permissoes{
				margin-left:10px;
			}
			#gp-btn-op{
				float:left;
				margin:10px;
			}
			#permissao-panel-funcao,#permissao-panel-menu{
				display:none;
			}

			#gp-btn-op button {
				width:100px;
			}
		</style>
		<script type="text/javascript">
			function Permissoes(){
				this.usuarioSelecionado = "";
				this.usuarioPerfil = "";				
			}
			var permissoes = new Permissoes();
			var url_loading = "<?=$url_loading?>";
		</script>
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
						<legend>Permissões</legend>
					</form>
	
						<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
						  <div class="panel panel-primary">
							<div class="panel-heading" role="tab" id="headingOne">
							  <h4 class="panel-title">
								<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
								  Por Usuário
								</a>
							  </h4>
							</div>
							<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
							  <div class="panel-body">
								<!-- Dados serão carregados aqui -->
							  </div>
							</div>
						  </div>
						  <div class="panel panel-primary">
							<div class="panel-heading" role="tab" id="headingThree">
							  <h4 class="panel-title">
								<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
								 Por Funções
								</a>
							  </h4>
							</div>
							<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
							  <div class="panel-body">
								<!-- Dados serão carregados aqui -->
							  </div>
							</div>
						  </div>
						</div>
				</div>
			</div>
		</div>
	</body>
</html>	
<script type="text/javascript">

	$("a[href='#collapseOne']").click(function(){
		$("#collapseOne .panel-body").html(
			'<div style="width:100%;margin:10px auto">' +
				'<center>' +
					'<img width="32" align="middle" src="'+url_loading+'">' +
					'<p class="text-muted">Aguarde</p>' +
				'</center>' +	
			'</div>'
		);
		$("#collapseOne .panel-body").load("listapermissoesusuarios.php<?=getURLParamsProject('?')?>");
	});
	$("a[href='#collapseThree']").click(function(){
		$("#collapseThree .panel-body").html(
			'<div style="width:100%;margin:10px auto">' +
				'<center>' +
					'<img width="32" align="middle" src="'+url_loading+'">' +
					'<p class="text-muted">Aguarde</p>' +
				'</center>' +	
			'</div>'
		);		
		$("#collapseThree .panel-body").load("listapermissoesfuncoes.php<?=getURLParamsProject('?')?>");
	});

</script>
<div class="modal fade" tabindex="-1" role="dialog" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <p>Aguarde ...</p>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->