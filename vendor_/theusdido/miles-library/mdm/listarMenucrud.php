<?php
	require 'conexao.php';
	require 'prefixo.php';	
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
					<?php include 'menu_menucrud.php'; ?>
				</div>
				<div class="col-md-10">
					<table width="100%" class="table table-hover" style="float:right">
						<caption><strong>Lista de Menu ( CRUD )</strong></caption>
						<tr>
							<td width="10%">Id</td>
							<td width="70%">Descrição</td>
							<td width="10%" align="center">Editar</td>
							<td width="10%" align="center">Excluir</td>
						</tr>	
						<?php
							$sql = "SELECT id,descricao FROM ".PREFIXO."menucrud";
							$query = $conn->query($sql);
							foreach ($query->fetchAll() as $linha){
								$descricao = utf8_encode($linha["descricao"]);
								
								echo "	<tr>
											<td>{$linha["id"]}</td>
											<td>{$descricao}</td>
											<td align='center'>
												<button type='button' class='btn btn-primary' onclick=location.href='criarMenucrud.php?id={$linha["id"]}'>
													<span class='fas fa-pencil-alt' aria-hidden='true'></span>
												</button>
											</td>

											<td align='center' >
												<button type='button' class='btn btn-primary' onclick='excluirMenucrud({$linha["id"]})'>
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
			function excluirMenucrud(id){				
				if (confirm("Tem certeza que deseja excluir ?")){
					location.href='excluirMenucrud.php?menu=' + id;
				}
			}
		</script>
	</body>
</html>	
	