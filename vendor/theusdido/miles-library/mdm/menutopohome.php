<?php
	require 'conexao.php';
	require 'prefixo.php';
	require 'funcoes.php';

	$currentFile = getCurrentProjectConfig("../../");
	$self = "menutopohome.php";
	$id = 
	$entidade = 
	$descricao = 
	$link = 
	$target = 
	$ordem = 
	$pai  = 
	$tipomenu =  
	$path = 
	$icon = 
	$fixo = "";
	$coluna = 0;

	$op = isset($_GET["op"])?$_GET["op"]:(isset($_POST["op"])?$_POST["op"]:'');

	if (isset($_GET["iframe"])){
		$isiframe = true;
		$parmsiframe = "&iframe=";
	}else{
		$isiframe = false;
		$parmsiframe = "";
	}	

	if ($op == "ordenacao"){
		foreach($_GET["dados"] as $d){
			$sql = "UPDATE ".PREFIXO."menu SET ordem = {$d["ordem"]} WHERE id={$d["menu"]}";
			$query = $conn->exec($sql);
		}	
		exit;
	}
	$excluir = isset($_GET["excluir"])?$_GET["excluir"]:"";
	if ($excluir != ""){
		$sql = "DELETE FROM ".PREFIXO."menu WHERE id = {$excluir};";
		$query = $conn->query($sql);
		if ($query){
			// Excluir premissões
			$sqlP = "DELETE FROM ".PREFIXO."menupermissoes WHERE menu = {$excluir};";
			$queryP = $conn->query($sqlP);
			if (!$queryP){
				if (IS_SHOW_ERROR_MESSAGE){
					echo $sqlP;
					var_dump($conn->errorInfo());
				}
			}
			echo 1;
		}else{
			if (IS_SHOW_ERROR_MESSAGE){
				var_dump($conn->errorInfo());
			}
		}
		exit;
	}
	if ($op == "carregaentidade"){
		echo '<option value="0">-- Selecione --</option>';
		$sql = "SELECT id,nome,descricao,pacote FROM ".PREFIXO."entidade ORDER BY id DESC;";
		$query = $conn->query($sql);
		While($linha = $query->fetch()){
			$descricao = executefunction("tdc::utf8",array($linha["descricao"]));
			echo "<option value='".$linha["id"]."' data-nome='".$linha["nome"]."' data-pacote='".$linha["pacote"]."' data-descricao='".$descricao."'>".$descricao." [ ".$linha["nome"]." ]</option>";
		}
		exit;
	}
	if ($op == 'carregaconsulta'){
		echo '<option value="0">-- Selecione --</option>';
		$sql = "SELECT b.id,a.nome,b.descricao,a.pacote FROM ".PREFIXO."entidade a,".PREFIXO."consulta b WHERE a.id = b.entidade ORDER BY a.id DESC;";
		$query = $conn->query($sql);
		While($linha = $query->fetch()){
			$descricao = executefunction("tdc::utf8",array($linha["descricao"]));
			echo "<option value='".$linha["id"]."' data-nome='".$linha["nome"]."' data-pacote='".$linha["pacote"]."' data-descricao='".$descricao."'>".$descricao." [ ".$linha["nome"]." ]</option>";
		}
		exit;
	}
	if ($op == 'carregarelatorio'){
		echo '<option value="0">-- Selecione --</option>';
		$sql = "SELECT b.id,a.nome,b.descricao,a.pacote FROM ".PREFIXO."entidade a,".PREFIXO."relatorio b WHERE a.id = b.entidade ORDER BY a.id DESC;";
		$query = $conn->query($sql);
		While($linha = $query->fetch()){
			$descricao = executefunction("tdc::utf8",array($linha["descricao"]));
			echo "<option value='".$linha["id"]."' data-nome='".$linha["nome"]."' data-pacote='".$linha["pacote"]."' data-descricao='".$descricao."'>".$descricao." [ ".$linha["nome"]." ]</option>";
		}
		exit;
	}
	if ($op == 'carregamovimentacao'){
		echo '<option value="0">-- Selecione --</option>';
		$sql = "SELECT b.id,a.nome,b.descricao,a.pacote FROM ".PREFIXO."entidade a,".PREFIXO."movimentacao b WHERE a.id = b.entidade ORDER BY a.id DESC;";
		$query = $conn->query($sql);
		While($linha = $query->fetch()){
			$descricao = executefunction("tdc::utf8",array($linha["descricao"]));
			echo "<option value='".$linha["id"]."' data-nome='".$linha["nome"]."' data-pacote='".$linha["pacote"]."' data-descricao='".$descricao."'>".$descricao." [ ".$linha["nome"]." ]</option>";
		}
		exit;
	}		

	if ($op == "salvar"){
		$id					= $_POST["id"];
		$entidadeRequest	= isset($_POST["entidade"])?$_POST["entidade"]:"";
		$entidade			= $entidadeRequest==""?"0":"'" .$entidadeRequest. "'";
		$descricao			= $_POST["descricao"]==""?"null":"'" .executefunction("tdc::utf8",array($_POST["descricao"])). "'";
		$link 				= $_POST["link"]==""?"'#'":"'" .$_POST["link"]. "'";
		$target				= $_POST["target"]==""?"null":"'" .$_POST["target"]. "'";		
		$pai				= isset($_POST["pai"])?$_POST["pai"]:0;
		$tp_menu			= $_POST["tipomenu"];
		$tipomenu			= "'" . $tp_menu . "'";
		$path				= "'" . $_POST["path"] . "'";
		$icon				= "'" . $_POST["icon"] . "'";
		$coluna				= isset($_POST["coluna"])?($_POST["coluna"]==''?0:$_POST["coluna"]):0;
		$fixo				= "'" . $_POST["fixo"] . "'";

		if ($_POST["ordem"] == ''){
			$ordem = getProxIdMDM("menu","ordem",array("pai","=",$pai));
		}else{
			$ordem = $_POST["ordem"];
		}

		if ($tp_menu != 'cadastro' && $tp_menu != 'raiz'){
			$sqlTpMenu 	= 'SELECT entidade FROM td_'.$tp_menu.' WHERE id = ' . $entidadeRequest;
			$query 			= $conn->query($sqlTpMenu);
			$linhaTpMenu	= $query->fetch();
			$entidade		= $linhaTpMenu['entidade'];
		}
		if ($id == ""){
			$idNew = getProxIdMDM("menu");
			$sql = "
				INSERT INTO ".PREFIXO."menu (
					id,entidade,descricao,link,target,ordem,pai,tipomenu,
					path,icon,coluna,fixo
				) VALUES (
					".$idNew.",".$entidade.",".$descricao.",".$link.",".$target.",".$ordem.",".$pai.",".$tipomenu.",
					".$path.",".$icon.",".$coluna.",".$fixo."
				);";
		}else{
			$sql = "
				UPDATE ".PREFIXO."menu 
				SET 
					entidade = ".$entidade." 
					, descricao = ".$descricao." 
					, link = ".$link." 
					, target = ".$target." 
					, ordem = ".$ordem." 
					, pai = ".$pai." 
					, tipomenu = ".$tipomenu." 
					, path = ".$path."
					, icon = ".$icon."
					, fixo = ".$fixo."
					, coluna = ".$coluna."
				WHERE id = ".$id.";
			";
		}
		$conn->beginTransaction();
		try{
			$query = $conn->query($sql);
			if ($query){
				if ($id == ""){
					try{
                        // Seta permissão para o usuário que criou o menu
                        $sqlP = "INSERT INTO ".PREFIXO."menupermissoes (id,projeto,menu,usuario,permissao) VALUES (DEFAULT,1,{$idNew},1,1);";						
                        $conn->exec($sqlP);
                    }catch(Exception $e){
					    $conn->rollBack();
						if (IS_SHOW_ERROR_MESSAGE){
							echo $sqlP;
					    	var_dump($e->getMessage());
						}
					    exit;
                    }
				}
			}
			$conn->commit();
			echo json_encode(array(
				"status" => 1,
				"msg" => "Salvo com sucesso."
			));			
		}catch(Exception $e){
			if (IS_SHOW_ERROR_MESSAGE){
				echo 'Errou';
				echo '<br/>' . $sql;
				echo '<br/>' . $e->getMessage();
				var_dump($conn->errorInfo());
				$conn->rollBack();
			}
			exit;
		}
		exit;
	}
	$id = isset($_GET["id"])?$_GET["id"]:"";

	if ($id != ""){
		$sql = "SELECT * FROM ".PREFIXO."menu WHERE id = {$id};";
		$query = $conn->query($sql);
		While ($linha = $query->fetch()){
			$entidade 	= ($linha["entidade"]==""?0:$linha["entidade"]);
			$descricao 	= executefunction("tdc::utf8",array($linha["descricao"]));
			$link 		= $linha["link"];
			$target 	= $linha["target"];
			$ordem	 	= $linha["ordem"];
			$pai 		= $linha["pai"];
			$tipomenu 	= $linha["tipomenu"]==""?"raiz":$linha["tipomenu"];
			$path		= $linha["path"];
			$icon		= $linha["icon"];
			$fixo		= $linha["fixo"];
			$coluna		= $linha["coluna"];
		}
	}

	function imprimeLinhaMenu($indice,$id,$descricao,$pai,$self,$idpai){
		global $parmsiframe;
		$retorno 	= "";
		$pai 		= $pai == 0 ? '' : $pai;
		if ($pai == ""){
			$mais = "
						<button type='button' class='btn btn-default' aria-label='Ver sub menu' onclick=versubmenu(".$id.",'".str_replace(" ","^",$descricao)."')>
						  <span class='fas fa-th-list' id='span-menuraiz-".$id."' aria-hidden='true'></span>
						</button>
			";	
		}else{
			$mais = "";
		}

		$retorno .= "	<tr data-indice='".$indice."' data-menu='".$id."'>";
		$retorno .= "		<td>".$id."</td>";
		$retorno .= "		<td>".$descricao."</td>";
		$retorno .= "		<td>".$pai."</td>";
		$retorno .= "		<td>".$mais."</td>";		
		$retorno .= "		<td align='center'>";
		$retorno .= "			<button type='button' class='btn btn-primary' onclick=location.href='".$self."?op=add&id=".$id."{$parmsiframe}&idpai={$idpai}&".getURLParamsProject()."'>";
		$retorno .= "				<span class='fas fa-pencil-alt' aria-hidden='true'></span>";
		$retorno .= "			</button>";
		$retorno .= "		</td>";		
		$retorno .= "		<td align='center'>";
		$retorno .= "			<button type='button' class='btn btn-primary' onclick=excluirMenu({$id})>";
		$retorno .= "					<span class='fas fa-trash-alt' aria-hidden='true'></span>";
		$retorno .= "				</button>";
		$retorno .= "			</td>

								<td>
									<div class='btn-group' role='group' aria-label='Ordenação de Elementos'>
										<button type='button' class='btn btn-default btn-sm btn-arrow-order' aria-label='Item Acima' onclick=reodernar(this,'up');>
										  <span class='fas fa-chevron-up' aria-hidden='true'></span>
										</button>

										<button type='button' class='btn btn-default btn-sm btn-arrow-order' aria-label='Item Abaixo' onclick=reodernar(this,'down');>
										  <span class='fas fa-chevron-down' aria-hidden='true'></span>
										</button>
									</div>
								</td>
		";
		$retorno .= "	</tr>";
		$retorno .= '
			<script type="text/javascript">
				function excluirMenu(menu){
					bootbox.confirm("Tem certeza que deseja excluir ? ",function(result){
						if (result){
							$.ajax({
								url:"'.$self.'",
								data:{
									excluir:menu,
									currentproject:'.$_SESSION["currentproject"].'
								},
								complete:function(ret){
									if (parseInt(ret.responseText) == 1){
										$("#tmenutopo tbody tr[data-menu='.$id.']").remove();
									}
								}
							});
						}
					});
				}
			</script>
		';
		return $retorno;
	}
?>
<html>
	<head>
		<title>Menu Topo ( Home )</title>
		<?php 
			include 'head.php';
		?>
		<script type="text/javascript">
			window.onload = function(){
				if ("<?=$op?>" == "add"){
					document.getElementById("id").value = "<?=$id?>";
					var tipomenu = "<?=$tipomenu?>";
					if ("<?=$id?>" != ""){
						$("#descricao").val("<?=$descricao?>");
						$("#link").val("<?=$link?>");
						$("#target").val("<?=$target?>");
						$("#ordem").val("<?=$ordem?>");
						$("#pai").val("<?=$pai?>");
						$("#tipomenu").val(tipomenu);
						if (tipomenu != "raiz" && tipomenu != "personalizado"){
							$("#entidade").load("menutopohome.php?op=carregaentidade&<?=getURLParamsProject()?>",function(){
								$("#entidade").val("<?=$entidade?>");
							});
						}
						$("#path").val("<?=$path?>");
						$("#icon").val("<?=$icon?>");
						$("#fixo").val("<?=$fixo?>");
						$("#coluna").val("<?=$coluna?>");
					}else{
						configuracaoInicial();
					}
					if ($('#tipomenu').val() == 'personalizado'){
						$("#link").attr("readonly",false);
					}else{
						$("#link").attr("readonly",true);
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
						objLink.val("files/"+$("#tipomenu").val()+"/"+$(this).val()+"/"+pacoteOBJ+objSelOpt.data("nome")+".html");
						objTarget.val("");
						
						objLink.attr("readonly",true);
						objTarget.attr("readonly",true);
					}else{

						objLink.val("");
						objTarget.val("");
						
						objDescricao.attr("readonly",false);
						objLink.attr("readonly",false);
						objTarget.attr("readonly",false);
					}
					
				});				
				$("#tipomenu").change(function(){
					carregarEntidade($(this).val());
				});				
			}
			function carregarEntidade(valor){
				$("#entidade,#pai,#coluna").attr("readonly",false);
				$("#entidade,#pai,#coluna").attr("disabled",false);
				switch(valor){
					case 'cadastro':
						$("#entidade").load("menutopohome.php?op=carregaentidade&<?=getURLParamsProject()?>",function(){
							$("#entidade").val("<?=$entidade?>");
							$("#entidade").change();
						});
					break;
					case 'consulta':
						$("#entidade").load("menutopohome.php?op=carregaconsulta&<?=getURLParamsProject()?>");
					break;
					case 'relatorio':
						$("#entidade").load("menutopohome.php?op=carregarelatorio&<?=getURLParamsProject()?>");
					break;
					case 'movimentacao':
						$("#entidade").load("menutopohome.php?op=carregamovimentacao&<?=getURLParamsProject()?>");
					break;
					case 'personalizado':
						configuracaoPersonalizado();
					break;
					case 'raiz':
						configuracaoInicial();
					break;
				}				
			}
			function configuracaoPersonalizado(){
				$("#entidade").attr("readonly",true);
				$("#entidade").attr("disabled",true);
				$("#pai,#coluna").attr("readonly",false);
				$("#pai,#coluna").attr("disabled",false);

				if ($("#id").val() == ""){
					$("#entidade,#pai").val(0);
					$("#descricao").val("");
					$("#link").val("");
					$("#target").val("");
				}

				$("#descricao").attr("readonly",false);
				$("#link").attr("readonly",false);
				$("#target").attr("readonly",false);
			}
			function configuracaoInicial(){
				$("#entidade,#pai,#coluna").val(0);
				$("#entidade,#pai,#coluna").attr("readonly",true);
				$("#entidade,#pai,#coluna").attr("disabled",true);
				$("#descricao,#link,#target,id,entidade,ordem,pai").val("");
				$("#descricao,#link").removeAttr("readonly");
				$("#entidade").load("menutopohome.php?op=carregaentidade&<?=getURLParamsProject()?>");
				$("#link").val("#");
			}
		</script>
		<style type="text/css">
			.btn-arrow-order {

			}
		</style>
	</head>
	<body>
		<?php 
			if (!$isiframe){
				include 'menu_topo.php';		
			}
		?>
		<div class="container-fluid">
			<?php include 'cabecalho.php'; ?>
			<div class="row-fluid">
				<div class="col-md-12">
					<form action="<?=$self?>" method="post">
					<?php
							if(!$isiframe){
								echo '
									<legend>
										Menu Topo Home
									</legend>
								';
							}

							if ($op == "add") {
								echo "<a href='".$self."?op=listar&id=".(isset($_GET["idpai"])?$_GET["idpai"]:"")."{$parmsiframe}&".getURLParamsProject()."' target='' class='btn btn-link' style='float:right;margin:5px;'>Voltar </a>";
							}else{
								if (!$isiframe){
									echo "<button type='button' class='btn btn-primary' style='float:right;margin:5px;width:100px;' onclick=location.href='".$self."?op=add{$parmsiframe}&".getURLParamsProject()."'><span class='fas fa-plus-circle'></span> Novo</button>";
								}
							}
							echo "<br />";

							if ($op == "add") {
						?>		
						</script>			
						<fieldset>
							<input type="hidden" id="id" name="id" />
							<div class="form-group">
								<label for="tipomenu">Tipo</label>
								<select id="tipomenu" name="tipomenu" class="form-control">
									<option value="raiz" data-tipo="raiz">Raiz</option>
									<option value="cadastro" data-tipo="page">Cadastro</option>
									<option value="consulta" data-tipo="consulta">Consulta</option>
									<option value="relatorio" data-tipo="relatorio">Relatório</option>
									<option value="movimentacao" data-tipo="movimentacao">Movimentação</option>
									<option value="personalizado" data-tipo="personalizado">Personalizado</option>
								</select>
							</div>
							<div class="form-group">
								<label for="entidade">Entidade</label>
								<select id="entidade" name="entidade" class="form-control">
								</select>
							</div>
							<div class="form-group">
								<label for="descricao">Descri&ccedil;&atilde;o</label>
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
								<label for="path">Path</label>
								<input type="text" name="path" id="path" class="form-control">
							</div>
							<div class="form-group">
								<label for="icon">Icon</label>
								<input type="text" name="icon" id="icon" class="form-control">
							</div>
							<div class="form-group">
								<label for="fixo">Fixo</label>
								<input type="text" name="fixo" id="fixo" class="form-control">
							</div>							
							<div class="form-group">
								<label for="coluna">Coluna</label>
								<select id="coluna" name="coluna" class="form-control">
									<option value="0">1ª Coluna</option>
									<option value="1">2ª Coluna</option>
								</select>
							</div>
							<div class="form-group">
								<label for="pai">Pai</label>
								<select id="pai" name="pai" class="form-control">
									<option value="0">-- Selecione --</option>
									<?php
										$sql = "SELECT id,descricao FROM ".PREFIXO."menu WHERE pai = 0 or pai is null ORDER BY ordem ASC";
										$query = $conn->query($sql);
										While($linha = $query->fetch()){
											$descricao = executefunction("tdc::utf8",array($linha["descricao"]));
											echo "<option value='".$linha["id"]."'>".$descricao."</option>";
										}
									?>
								</select>
							</div>							
							<button type="button" class="btn btn-primary" name="salvar" id="btn-salvar">Salvar</button>
							<div id="retorno" class="alert"></div>
						</fieldset>
					<?php
								}else{
					?>
					</form>	
					<div class="list-group">
					<table width="100%" class="table table-hover" id="tmenutopo" style="float:right">
						<thead>
							<tr>								
								<td width="10%">ID</td>
								<td width="40%">Descrição</td>
								<td width="27%">Pai</td>
								<td width="5%"></td>								
								<td width="5%" align="center">Editar</td>
								<td width="5%" align="center">Excluir</td>
								<td width="8%">Ordem</td>
							</tr>
						</thead>
						<tbody>
						<?php
							$indice =0;

							if ($isiframe && isset($_GET["id"])){
								$where = "WHERE a.pai = " . $_GET["id"];
							}else{
								$where = "WHERE (a.pai = 0 OR a.pai IS NULL) ";
							}
							$sql = "
								SELECT a.id,a.descricao,
								(SELECT b.descricao FROM ".PREFIXO."menu b WHERE b.id = a.pai) pai,
								a.pai
								FROM ".PREFIXO."menu a 
								{$where}
								ORDER BY a.pai ASC,a.ordem ASC,a.id ASC
							";
							$query = $conn->query($sql);
							While ($linha = $query->fetch()){
								$descricao = executefunction("tdc::utf8",array($linha["descricao"]));
								echo imprimeLinhaMenu($indice,$linha["id"],$descricao,executefunction("tdc::utf8",array($linha["pai"])),$self,$linha["pai"]);
								$indice++;
							}
						?>
						</tbody>
					</table>
					</div>
					<?php
								}
					?>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			var trs = [];

			function reodernar(obj,operador){
				var trOBJ = $(obj).parents("tr");
				var trIndice = trOBJ.data("indice");
				var dadosenviar = [];
				if (trIndice <= 0 && operador == "up") return false;
				if ((trIndice + 1) >= $("#tmenutopo tbody tr").length && operador == "down") return false;				
				$("#tmenutopo tbody tr").each(function(){
					trs.push($(this));
					$(this).remove();
				});

				if (operador == 'up'){
					var de = trs[trIndice];
					var para = trs[trIndice-1];
					trs[trIndice-1] = de;
					trs[trIndice] = para;
				}else{
					var para = trs[trIndice+1];
					var de = trs[trIndice];
					trs[trIndice+1] = de;
					trs[trIndice] = para;
				}

				for (t in trs){
					$("#tmenutopo tbody").append(trs[t]);
					trs[t].attr("data-indice",t);
					trs[t].find(".btn-arrow-order").onclick = "reodernar($(this));";
					dadosenviar.push({
						menu:trs[t].data("menu"),
						ordem:t
					});
				}
				trs.splice(0,trs.length);

				$.ajax({
					url:"menutopohome.php&<?=getURLParamsProject()?>",
					data:{
						op:"ordenacao",
						dados:dadosenviar
					}
				});
			}

			function versubmenu(id,descricao){
				var descricao = descricao.replace("^"," ");
				$("#modal-submenu .modal-body iframe").attr("src","menutopohome.php?op=listar&id=" + id + "&iframe=&<?=getURLParamsProject()?>");
				$("#modal-submenu").modal('show');
				$("#modal-title-submenu").html(descricao);
			}
			
			$("#btn-salvar").click(function(){
				$.ajax({
					type:"POST",
					url:"<?=$self?>",
					dataType:"JSON",
					data:{
						op:"salvar",
						id:$("#id").val(),
						currentproject:<?=$_SESSION["currentproject"]?>,
						tipomenu:$("#tipomenu").val(),
						entidade:$("#entidade").val(),
						descricao:$("#descricao").val(),
						link:$("#link").val(),
						target:$("#target").val(),
						ordem:$("#ordem").val(),
						pai:$("#pai").val(),
						path:$("#path").val(),
						icon:$("#icon").val(),
						fixo:$("#fixo").val(),
						coluna:$("#coluna").val()
					},
					complete:function(ret){
						var retorno = ret.responseJSON;
						if (retorno.status == 1){
							$("#retorno").addClass("alert-success");
							$("#retorno").html(retorno.msg);
						}
					}
				});
			});
		</script>

		<div class="modal fade" tabindex="-1" role="dialog" id="modal-submenu">
		  <div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 id="modal-title-submenu" class="modal-title"></h4>
		      </div>
		      <div class="modal-body">
		        <p><iframe width="100%" height="75%" border="0" frameborder="0" style="border:0px;"></iframe></p>
		      </div>
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

	</body>
</html>