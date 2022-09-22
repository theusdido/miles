<?php
	include 'conexao.php';
	include '../funcoes.php';
	require 'prefixo.php';
	
	$self = "menulateralpagina.php";
	$id = $projeto = $entidade = $descricao = $link = $target = $ordem = $pai  = "";
	$op = isset($_GET["op"])?$_GET["op"]:"";
	if (isset($_GET)){
		$excluir = isset($_POST["excluir"])?$_POST["excluir"]:"";
		if ($excluir != ""){
			$sql = "DELETE FROM td_menu WHERE id = " . $excluir;
			$query = $conn->query($sql);
			if ($query){
				echo "<meta http-equiv='refresh' content='0;url=".$self."'>";
			}
			exit;
		}
	}
	
	if (isset($_POST["salvar"])){
		$id				= $_POST["id"];
		$projeto		= $_POST["projeto"];
		$entidade		= $_POST["entidade"]==""?"null":"'" .$_POST["entidade"]. "'";
		$descricao		= $_POST["descricao"]==""?"null":"'" .$_POST["descricao"]. "'";
		$link 			= $_POST["link"]==""?"null":"'" .$_POST["link"]. "'";
		$target			= $_POST["target"]==""?"null":"'" .$_POST["target"]. "'";
		$ordem			= $_POST["ordem"]==""?"null":"'" .$_POST["ordem"]. "'";
		$pai			= $_POST["pai"];
		
		if ($id == ""){
			$sql = "INSERT INTO td_menu (id,projeto,entidade,descricao,link,target,ordem,pai) VALUES (".getProxId("menu",$conn).",".$projeto.",".$entidade.",".$descricao.",".$link.",".$target.",".$ordem.",".$pai.")";
		}else{
			$sql = "UPDATE td_menu SET projeto = ".$projeto." , entidade = ".$entidade." , descricao = ".$descricao." , link = ".$link." , target = ".$target." , ordem = ".$ordem." , ".PREFIXO."pai = ".$pai." WHERE id = ".$id;
		}
		
		$query = $conn->query($sql);
				
		if (!$query){
			if (IS_SHOW_ERROR_MESSAGE){
				var_dump($conn->infoError());
			}
			exit;
		}else{
			echo "<meta http-equiv='refresh' content='0;url=".$self."'>";
		}
	}
	
	$id = isset($_GET["id"])?$_GET["id"]:"";

	if ($id != ""){
		$sql = "SELECT id,projeto,entidade,descricao,link,target,ordem,pai pai FROM td_menu WHERE id = ".$id;
		$query = $conn->query($sql);
		While ($linha = $query->fetch()){	
			$projeto =  $linha["projeto"];
			$entidade = ($linha["entidade"]==""?0:$linha["entidade"]);
			$descricao = $linha["descricao"];
			$link = $linha["link"];
			$target = $linha["target"];
			$ordem = $linha["ordem"];
			$pai = $linha["pai"];
		}
	}
?>
<html>
	<head>
		<title>Menu Lateral Página</title>
		<?php include 'head.php' ?>
		<script type="text/javascript">
			window.onload = function(){
				if ("<?=$op?>" == "add"){
					document.getElementById("id").value = "<?=$id?>";
					if ("<?=$id?>" != ""){
						$("#descricao").val("<?=$descricao?>");
						$("#projeto").val("<?=$projeto?>");
						$("#entidade").val("<?=$entidade?>");
						$("#link").val("<?=$link?>");
						$("#target").val("<?=$target?>");
						$("#ordem").val("<?=$ordem?>");
						$("#pai").val("<?=$pai?>");
					}else{
						$("#entidade,#pai").val(0);
						$("#descricao,#link,#target,id,projeto,entidade,ordem,pai").val("");
						$("#descricao,#projeto,#link").removeAttr("readonly");						
					}
					if ($("#entidade").val() != "" && $("#entidade").val() != 0){
						$("#descricao,#projeto,#link").attr("readonly",true);
					}
				}	
				$("#entidade").change(function(){
					var objDescricao  =  $("#descricao");
					var objLink = $("#link");
					var objTarget = $("#target");
					if ($(this).find("option:selected").val() != 0){
						var objSelOpt = $(this).find("option:selected");
						var pacoteOBJ = (objSelOpt.data("pacote")==""?"":objSelOpt.data("pacote")+"-");
						objDescricao.val(objSelOpt.data("descricao"));
						objLink.val("files/cadastro/"+$(this).val()+"/"+pacoteOBJ+objSelOpt.data("nome")+".html");
						objTarget.val("");
						
						objDescricao.attr("readonly",true);
						objLink.attr("readonly",true);
						objTarget.attr("readonly",true);
					}else{
						objDescricao.val("");
						objLink.val("");
						objTarget.val("");
						
						objDescricao.attr("readonly",false);
						objLink.attr("readonly",false);
						objTarget.attr("readonly",false);
					}					
				});				
			}
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
					<form action="#(self)#" method="post">
						<legend>
							Menu Lateral Página
						</legend>
							<?php
								if ($op == "add") {
									echo "<a href='".$self."' target='' class='btn btn-link' style='float:right;margin:5px;'>Voltar </a>";
								}else{
									echo "<button type='button' class='btn btn-primary' style='float:right;margin:5px;width:100px;' onclick=location.href='".$self."?op=add'><span class='fas fa-plus-circle'></span> Novo</button>";
								}
								echo "<br />";

								if ($op == "add") {
							?>		
						</script>			
						<fieldset>
							<input type="hidden" id="id" name="id">
							<input type="hidden" id="projeto" name="projeto" value="1">
							<div class="form-group">
								<label for="entidade">Entidade</label>
								<select id="entidade" name="entidade" class="form-control">
									<option value="0">-- Selecione --</option>
									<?php
										$sql = "SELECT id,nome,descricao,pacote FROM td_entidade";
										$query = $conn->query($sql);
										While($linha = $query->fetch()){
											$descricao = utf8_encode($linha["descricao"]);
											echo "<option value='".$linha["id"]."' data-nome='".$linha["nome"]."' data-pacote='".$linha["pacote"]."' data-descricao='".$descricao."'>".$descricao." [ ".$linha["nome"]." ]</option>";
										}
									?>		
								</select>
							</div>							
							<div class="form-group">
								<label for="nome">Descri&ccedil;&atilde;o</label>
								<input type="text" name="descricao" id="descricao" class="form-control">
							</div>
							<div class="form-group">
								<label for="link">Link</label>
								<input type="text" name="link" id="link" class="form-control">
							</div>
							<div class="form-group">
								<label for="target">Target</label>
								<input type="text" name="target" id="target" class="form-control">
							</div>
							<div class="form-group">
								<label for="target">Ordem</label>
								<input type="text" name="ordem" id="ordem" class="form-control">
							</div>							
							<div class="form-group">
								<label for="pai">Pai</label>
								<select id="pai" name="pai" class="form-control">
									<option value="0">-- Selecione --</option>
									<?php
										$sql = "SELECT id,descricao FROM td_menu WHERE pai <> 0 OR pai IS NOT NULL";
										$query = $conn->query($sql);
										While($linha = $query->fetch()){
											if (qtdePaiMenu($linha["id"],$conn) == 1){
												$descricao = utf8_encode ($linha["descricao"]);											
												echo "<option value='".$linha["id"]."'>".$descricao."</option>";
											}	
										}
									?>
								</select>
							</div>
							<div id="error"></div>	
							<button type="submit" class="btn btn-primary" name="salvar">Salvar</button>							  
						</fieldset>
					<?php
								}else{
					?>
					</form>	
					<div class="list-group">
					<table width="100%" class="table table-hover" style="float:right">
						<tr>
							<td width="10%">ID</td>
							<td width="50%">Descrição</td>
							<td width="30%">Pai</td>
							<td width="5%" align="center">Editar</td>
							<td width="5%" align="center">Excluir</td>
						</tr>	
						<?php
							$sql = "SELECT id,descricao,pai pai,entidade FROM td_menu ORDER BY pai ASC,ordem ASC,ID ASC";
							$query = $conn->query($sql);
							While ($linha = $query->fetch()){
								$descricao = utf8_encode($linha["descricao"]);
								$sqlMenuPai = "SELECT 1 FROM td_menu WHERE id = ".$linha["id"] . " AND pai IS NOT NULL AND pai <> 0;";
								$queryMenuPai = $conn->query($sqlMenuPai);
								if (qtdePaiMenu($linha["id"],$conn) == 2){
								
									if ($linha["pai"] != 0 && $linha["pai"] != ""){
										$sqlPai = "SELECT descricao FROM td_menu WHERE id = " . $linha["pai"] . " LIMIT 1 ";
										$queryPai = $conn->query($sqlPai);
										$linhaPai = $queryPai->fetch();
										$pai = utf8_encode($linha["descricao"]);
									}else{
										$pai = "";
									}
									echo "	<tr> ";
									echo "		<td>".$linha["id"]."</td>";
									echo "		<td>".$descricao."</td>";
									echo "		<td>".$pai."</td>";
									echo "		<td align='center'>";
									echo "			<button type='button' class='btn btn-primary' onclick=location.href='".$self."?op=add&id=".$linha["id"]."'>";
									echo "				<span class='fas fa-pencil-alt' aria-hidden='true'></span>";
									echo "			</button>";
									echo "		</td>";
									echo "		<td align='center'>";
									echo "			<button type='button' class='btn btn-primary' onclick=location.href='".$self."?excluir=".$linha["id"]."'>";
									echo "					<span class='fas fa-trash-alt' aria-hidden='true'></span>";
									echo "				</button>";
									echo "			</td>";
									echo "	</tr>";
								}	
							}
						?>
					</table>						
					</div>
					<?php
								}
					?>
				</div>
			</div>
		</div>
	</body>
</html>