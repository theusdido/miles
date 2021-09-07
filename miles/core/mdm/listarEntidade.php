<?php
	require 'conexao.php';
	require 'prefixo.php';
	require 'funcoes.php';
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
					<table width="100%" class="table table-hover" style="float:right">
						<caption><strong>Lista de Entidades</strong></caption>
						<tr class="active">
							<td width="10%">Id</td>
							<td width="40%">Descrição</td>
							<td width="40%">Nome</td>
							<td width="5%" align="center">Editar</td>
							<td width="5%" align="center">Atributo</td>
							<td width="5%" align="center">Aba</td>
							<td width="5%" align="center">Excluir</td>
						</tr>	
						<?php
							$sql = "SELECT id,nome,descricao FROM ".PREFIXO."entidade ORDER BY id DESC";
							$query = $conn->query($sql);
							foreach ($query->fetchAll() as $linha){
								$descricao = executefunction("utf8charset",array($linha["descricao"],5));
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
												<button type='button' class='btn btn-primary' onclick=location.href='criarAba.php?entidade={$linha["id"]}&".getURLParamsProject()."'>
													<span class='fas fa-credit-card' aria-hidden='true'></span>
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
						?>
					</table>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			function excluirEntidade(id){				
				if (confirm("Tem certeza que deseja excluir ?")){
					location.href='excluirEntidade.php?entidade=' + id + "&<?=getURLParamsProject()?>";
				}
			}
		</script>
	</body>
</html>	
	