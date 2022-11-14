<?php
	require 'conexao.php';	
	require 'prefixo.php';
	require 'funcoes.php';
	
	$entidade = $_GET["entidade"];
	if (isset($_GET["ordem"])){
		$sql = "UPDATE " . PREFIXO . "atributo SET ordem = {$_GET["ordem"]} WHERE id = {$_GET["id"]}";
		$query = $conn->query($sql);
		exit;
	}
	if (isset($_GET["transferencia"])){
		
		// Verifica se o campo já existe na entidade destino
		$verificaSQL = "SELECT 1 FROM atributo WHERE " . PREFIXO . "entidade = " . $_GET["entidade"] . " AND nome = '".$_GET["nome"]."';";
		$verificaQUERY = $conn->query($verificaSQL);
		if ($verificaQUERY->rowCount() <= 0 ){
			
			// Seleciona os dados do atributo de origem			
			$atributoAtualSQL = "SELECT (SELECT nome FROM ".PREFIXO."entidade b WHERE a.entidade = b.id ) nomeentidade,a.nome,a.descricao,a.tipo,a.tamanho,a.nulo,a.tipohtml,a.exibirgradededados,a.chaveestrangeira,a.dataretroativa,a.inicializacao FROM " . PREFIXO . "atributo a,".PREFIXO."entidade b WHERE a.entidade = b.id AND a.id=" . $_GET["atributo"];
			$atributoAtualQUERY = $conn->query($atributoAtualSQL);			
			if ($atributoAtualQUERY->rowCount() > 0){				
				$atributoAtualLINHA = $atributoAtualQUERY->fetch();
				// Verifica se o atributo existe fisicamente
				$sqlExisteFisicamente = "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$atributoAtualLINHA["nomeentidade"]}' AND  COLUMN_NAME = '{$_GET["nome"]}';";
				$queryExisteFisicamente = $conn->query($sqlExisteFisicamente);
				if ($queryExisteFisicamente->rowCount() > 0){
					// Exclui o atributo fisicamente
					$alterFisico = "ALTER TABLE {$atributoAtualLINHA["nomeentidade"]} DROP COLUMN {$_GET["nome"]};";
					$queryAlterFisico = $conn->query();
				}
			
				// Cria o atributo no destino
				executefunction("criarAtributo",array(
					$conn,
					$_GET["entidade"],
					$atributoAtualLINHA["nome"],
					$atributoAtualLINHA["descricao"],
					$atributoAtualLINHA["tipo"],
					$atributoAtualLINHA["tamanho"],
					$atributoAtualLINHA["nulo"],
					$atributoAtualLINHA["tipohtml"],
					$atributoAtualLINHA["exibirgradededados"],
					$atributoAtualLINHA["chaveestrangeira"],
					$atributoAtualLINHA["dataretroativa"],
					$atributoAtualLINHA["inicializacao"]
				));
				
				// Exclui atributo da entidade atual
				$atualizaSQL = "DELETE FROM " . PREFIXO . "atributo WHERE id = " . $_GET["atributo"];
				$atualizaQUERY = $conn->query($atualizaSQL);
				if ($atualizaQUERY){
					echo 1;
				}else{
					echo 5;
				}
			}else{
				// Atributo não existe mais na entidade de origem
				echo 3;
				exit;
			}
		}else{
			echo 2;
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
					<div class="list-group">
						<?php include 'menu_entidade.php'; ?>
						<?php include 'menu_atributo.php'; ?>
					</div>
				</div>	
				<div class="col-md-10">
		
					<table width="100%" class="table table-hover">
						<caption><strong>Lista de Atributos</strong></caption>
						<tr>
							<td width="10%">Id</td>
							<td width="30%">Descrição</td>
							<td width="20%">Nome</td>
							<td width="10%"align='center'>Ordem</td>
							<td width="10%"align='center'>Editar</td>
							<td width="10%" align='center'>Transf.</td>
							<td width="10%" align='center'>Excluir</td>							
						</tr>	
						<?php
							$sql = "SELECT id,nome,descricao,ordem FROM ".PREFIXO."atributo WHERE entidade = {$entidade} ORDER BY ordem ASC";
							$query = $conn->query($sql);
							foreach ($query->fetchAll() as $linha){
								$descricao = executefunction("tdc::utf8",array($linha["descricao"]));
								echo "	<tr>
											<td>{$linha["id"]}</td>
											<td>{$descricao}</td>
											<td>{$linha["nome"]}</td>
											<td align='center'><input type='text' class='form-control text-center' style='width:50px' onblur='salvarOrdem({$linha["id"]},this.value);' value='{$linha["ordem"]}'></td>
											<td align='center'>
												<button type='button' class='btn btn-primary' onclick=location.href='criarAtributo.php?entidade={$entidade}&id={$linha["id"]}".getURLParamsProject("&")."'>
													<span class='fas fa-pencil-alt' aria-hidden='true'></span>
												</button>
											</td>
											<td align='center'>
												<button type='button' class='btn btn-primary' onclick='abrirTransferencia({$linha["id"]},\"{$linha["nome"]}\");'>
													<span class='fas fa-exchange-alt' aria-hidden='true'></span>
												</button>
											</td>											
											<td align='center'>
												<button type='button' class='btn btn-primary' onclick=location.href='deleteAtributo.php?id={$linha["id"]}&entidade={$entidade}&nome={$linha["nome"]}".getURLParamsProject("&")."'>
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
		<div class="modal fade" tabindex="-1" role="dialog" id="transferencia">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Transferir Atributo</h4>
			  </div>
			  <div class="modal-body">
				<p>
					<select id="entidadetransferencia" name="entidadetransferencia" class="form-control">
					<?php
						$sqlT = "SELECT id,nome,descricao FROM ".PREFIXO."entidade";
						$queryT = $conn->query($sqlT);
						$linhaT = $queryT->fetchAll();
						foreach($linhaT as $dado){
							echo '<option value="'.$dado["id"].'">'. executefunction("tdc::utf8",array($dado["descricao"])) .' [ '.$dado["nome"].' ]</option>';
						}
					?>
					</select>
				</p>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="transferir()">Transfêrir</button>
			  </div>
			</div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->		
		<script type="text/javascript">
			function salvarOrdem(id,ordem){				
				if (ordem!=""){
					$.ajax({
						url:"listarAtributo.php",
						data:{
							id:id,
							ordem:ordem,
							currentproject:<?=$_SESSION["currentproject"]?>,
							entidade:<?=$entidade?>
						}
					});
				}
			}
			var atributoTransfId = "";
			var atributoTransfNome = "";
			function abrirTransferencia(atributo,nomeatributo){
				atributoTransfId = atributo;
				atributoTransfNome = nomeatributo;
				$("#transferencia").modal({
					backdrop:false
				});
				$("#transferencia").modal("show");				
			}
			function transferir(){
				$.ajax({
					url:"listarAtributo.php",
					data:{
						transferencia:" ",
						entidade:$("#entidadetransferencia").val(),
						atributo: atributoTransfId,
						nome:atributoTransfNome,
						currentproject:<?=$_SESSION["currentproject"]?>
					},
					success:function(retorno){
						if (retorno == 1 || retorno == "1"){							
							location.reload();
						}
					}
				});
			}
		</script>
	</body>
</html>