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
					<?php include 'menu_movimentacao.php'; ?>
				</div>
				<div class="col-md-10">
					<table width="100%" class="table table-hover" style="float:right">
						<caption><strong>Lista de Movimentações</strong></caption>
						<tr>
							<td width="10%">Id</td>
							<td width="40%">Descrição</td>
							<td width="40%">Nome</td>
							<td width="5%" align="center">Editar</td>
							<td width="5%" align="center">Excluir</td>
						</tr>	
						<?php
							$sql = "SELECT id,descricao,td_entidade FROM ".PREFIXO."movimentacao";
							$query = $conn->query($sql);
							foreach ($query->fetchAll() as $linha){
								$descricao = $linha["descricao"];
								
								$query = $conn->query("SELECT descricao FROM td_entidade WHERE id = {$linha["td_entidade"]}")->fetch();
								$entidade = $query["descricao"];
								echo "	<tr>
											<td>{$linha["id"]}</td>
											<td>{$descricao}</td>
											<td>{$entidade}</td>
											<td align='center'>
												<button type='button' class='btn btn-primary' onclick=location.href='criarMovimentacao.php?id={$linha["id"]}&entidade={$linha["td_entidade"]}'>
													<span class='fas fa-pencil-alt' aria-hidden='true'></span>
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
					location.href='excluirEntidade.php?entidade=' + id;
				}
			}
		</script>
	</body>
</html>	
	