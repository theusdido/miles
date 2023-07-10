<?php
	require 'conexao.php';
	require 'prefixo.php';
	require 'funcoes.php';


	$op = isset($_GET['op']) ? $_GET['op'] : '';

	if ($op == 'listar-entidades'){
		$_where = '';
		$termo 	= isset($_GET['termo']) ? $_GET['termo'] : '';

		if ($termo != ''){
			if (is_numeric($termo)){
				$_where = "WHERE id = $termo";
			}else{
				$_where = "WHERE nome LIKE '%$termo%' OR descricao LIKE '%$termo%' ";
			}
		}

		$sql = "SELECT id,nome,descricao FROM ".PREFIXO."entidade $_where ORDER BY id DESC";
		$query = $conn->query($sql);
		foreach ($query->fetchAll() as $linha){
			$descricao = $linha["descricao"];
			echo "	<tr>
						<td>{$linha["id"]}</td>
						<td>{$descricao}</td>
						<td>{$linha["nome"]}</td>
						<td align='center'>
							<button type='button' class='btn btn-primary' onclick=location.href='criarEntidade.php?entidade={$linha["id"]}&".getURLParamsProject()."'>
								<span class='fas fa-pencil-alt' aria-hidden='true'></span>
							</button>	
						</td>
						<td align='center'>
							<button type='button' class='btn btn-primary' onclick=location.href='criarAtributo.php?entidade={$linha["id"]}&".getURLParamsProject()."'>
								<span class='fas fa-list-alt' aria-hidden='true'></span>
							</button>																							
						</td>
						
						<td align='center'>
							<button type='button' class='btn btn-primary' onclick='gerarCadastro({$linha["id"]});'>
								<span class='fas fa-code' aria-hidden='true'></span>
							</button>
						</td>
						
						<td align='center' >
							<button type='button' class='btn btn-primary' onclick='excluirEntidade({$linha["id"]})'>
								<span class='fas fa-trash-alt' aria-hidden='true'></span>
							</button>
						</td>											
					</tr>
			";		
		}
		exit;
	}
?>
<html>
	<head>
		<title>Listar Coluna</title>
		<?php include 'head.php' ?>
	</head>
	<body>
		<?php include 'menu_topo.php'; ?>
		<div class="container-fluid">
			<?php include 'cabecalho.php'; ?>
			<div class="row-fluid">
				<div class="col-md-2">
					<?php include 'menu_entidade.php'; ?>
				</div>
				<div class="col-md-10">
					<table id="table-lista-entidade" width="100%" class="table table-hover" style="float:right">
						<caption><strong>Lista de Entidades</strong></caption>
						<thead>
							<tr>
								<td colspan="7" style="padding:5px 0">
									<div class="input-group">
      									<input type="text" id="entidade-termo" class="form-control" placeholder="Pesquisar ...">
      									<span class="input-group-btn">
        									<button class="btn btn-primary" type="button" style="height: 34px" onclick="listarEntidades()">
												<i class='fas fa-search' aria-hidden='true'></i>
											</button>
      									</span>
    								</div>
								</td>
							</tr>
							<tr class="active">
								<td width="10%">Id</td>
								<td width="40%">Descrição</td>
								<td width="40%">Nome</td>
								<td width="5%" align="center">Editar</td>
								<td width="5%" align="center">Atributo</td>
								<td width="5%" align="center">Gerar</td>
								<td width="5%" align="center">Excluir</td>
							</tr>	
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){
				listarEntidades();
				$('#entidade-termo').focus();
			});
			function excluirEntidade(id){				
				if (confirm("Tem certeza que deseja excluir ?")){
					location.href='excluirEntidade.php?entidade=' + id + "&<?=getURLParamsProject()?>";
				}
			}

			function gerarCadastro(entidade_id){
				$.ajax({
					url:"<?=URL_API?>",
					data:{
						controller:"gerarcadastro",
						entidade:entidade_id,
						principal:true,
						acao:'compilar'
					},
					beforeSend:function(){
					},					
					complete:function(retorno){
						bootbox.alert('Arquivos Gerados !');
					},
					error:function(){
						bootbox.alert('Erro ao gerar os arquivos !');
					}
				});
			}

			function listarEntidades(){
				$.ajax({
					url:"listarEntidade.php",
					data:{
						op:'listar-entidades',
						termo:$('#entidade-termo').val(),
						currentproject:<?=$_SESSION["currentproject"]?>
					},
					complete:function(res){
						$('#table-lista-entidade tbody').html(res.responseText);
					}
				});
			}

			$('#entidade-termo').keyup(function(e){
				listarEntidades();
			});

		</script>
	</body>
</html>
	