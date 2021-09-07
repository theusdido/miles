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
					<?php include 'menu_relatorio.php'; ?>
				</div>
				<div class="col-md-10">
					<table width="100%" class="table table-hover" style="float:right">
						<caption><strong>Lista de Relatórios</strong></caption>
						<tr>
							<td width="10%">Id</td>
							<td width="40%">Descrição</td>
							<td width="40%">Nome</td>
							<td width="5%" align="center">Editar</td>
							<td width="5%" align="center">Excluir</td>
						</tr>	
						<?php
							$sql = "SELECT id,descricao,entidade FROM ".PREFIXO."relatorio";
							$query = $conn->query($sql);
							foreach ($query->fetchAll() as $linha){
								$descricao = utf8_encode($linha["descricao"]);
								
								$query = $conn->query("SELECT descricao FROM td_entidade WHERE id = {$linha["entidade"]}")->fetch();
								$entidade = utf8_encode($query["descricao"]);
								echo "	<tr>
											<td>{$linha["id"]}</td>
											<td>{$descricao}</td>
											<td>{$entidade}</td>
											<td align='center'>
												<button type='button' class='btn btn-primary' onclick=location.href='criarRelatorio.php?id={$linha["id"]}&entidade={$linha["entidade"]}&currentproject={$_SESSION["currentproject"]}'>
													<span class='fas fa-pencil-alt' aria-hidden='true'></span>
												</button>
											</td>

											<td align='center' >
												<button type='button' class='btn btn-primary' onclick='excluirRelatorio({$linha["id"]})'>
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
			function excluirRelatorio(id){				
				if (confirm("Tem certeza que deseja excluir ?")){
					location.href='excluirRelatorio.php?rel=' + id + "&currentproject=" + <?=$_SESSION["currentproject"]?>;
				}
			}
		</script>
	</body>
</html>	
	