<?php
	require 'conexao.php';
	require 'prefixo.php';
	require 'funcoes.php';

	if (isset($_GET["op"])){
		if ($_GET["op"] == "setar"){
			$id = $_GET["id"];
			$charset = $_GET["charset"];
			$sql  = "UPDATE td_charset SET charset = '{$charset}' WHERE id = {$id};";
			$query = $conn->exec($sql);			
		}
		
		if ($_GET["op"] == "listaratributo"){
			
			$entidade = $_GET["entidade"];
			
			if (is_numeric($entidade)){
				$sqlT = "SELECT id,nome,descricao FROM ".PREFIXO."atributo WHERE ".PREFIXO."entidade = {$entidade} AND tipohtml in (1,2,3,14,16,21,27) AND tipo IN ('varchar','char','text');";
				$queryT = $conn->query($sqlT);
				$linhaT = $queryT->fetchAll();
				foreach($linhaT as $dado){
					echo '<option value="'.$dado["id"].'">'. executefunction('utf8charset',array($dado["descricao"],5)) .' [ '.$dado["nome"].' ]</option>';
				}
			}else{
				echo '<option value="descricao"> Descrição [ descricao ]</option>';
			}
		}
		
		if ($_GET["op"] == "corrigir"){
			//return ord($linhaVCharset["testecharset"]) == 195 ? false : true;
			
			$entidade = $_GET["entidade"];
			$atributo = $_GET["atributo"];
			
			if (is_numeric($entidade)){
				$sqle = "SELECT nome FROM td_entidade WHERE id = {$entidade};";
				$querye = $conn->query($sqle);
				$linhae = $querye->fetch();
				$entidade = $linhae["nome"];
			}
			
			if (is_numeric($atributo)){
				$sqla = "SELECT nome FROM td_atributo WHERE id = {$atributo};";
				$querya = $conn->query($sqla);
				$linhaa = $querya->fetch();
				$atributo = $linhaa["nome"];				
			}
			
			$sqlv = "SELECT id,{$atributo} valor FROM {$entidade};";
			$queryv = $conn->query($sqlv);
			while ($linhav = $queryv->fetch()){
				if (executefunction("isutf8",array($linhav["valor"]))){
					try {
						$valor = utf8_decode($linhav["valor"]); //Só funcionou com o comando nativo
						$sql = 'UPDATE '.$entidade.' SET '.$atributo.' = "'.$valor.'" WHERE id = ' . $linhav["id"]. ';';
						$query = $conn->query($sql);
					}catch(Throwable $t){
						echo $t->getMessage();
					}
				}
			}
		}
		exit;
	}
	
?>
<html>
	<head>
		<title>CharSet</title>
		<?php include 'head.php' ?>
		<script type="text/javascript">
		function setCharset(id,obj){
			$.ajax({
				url:"charset.php",
				data:{
					op:"setar",
					id:id,
					charset:obj.value,
					currentproject:<?=$_SESSION["currentproject"]?>
				}
			});
		}
		
		function carregarAtributo(entidade){
			$.ajax({
				url:"charset.php",
				data:{
					op:"listaratributo",
					entidade:entidade,
					currentproject:<?=$_SESSION["currentproject"]?>
				},
				beforeSend:function(){
					$("#atributolista").html("<option>Carregando ...</option>");
					$("#atributolista").attr("disabled",true);
				},
				complete:function(ret){
					$("#atributolista").html(ret.responseText);
					$("#atributolista").removeAttr("disabled");
				}
			});
		}
		
		$(document).ready(function(){
			carregarAtributo($("#entidadelista").val());
			
			$("#entidadelista").change(function(){
				carregarAtributo($(this).val());
			});
			
			$("#btn-corrigir-charset").click(function(){
				$.ajax({
					url:"charset.php",
					data:{
						op:"corrigir",
						entidade:$("#entidadelista").val(),
						atributo:$("#atributolista").val(),
						currentproject:<?=$_SESSION["currentproject"]?>
					},
					beforeSend:function(){
						$("#loading-corrigir-charset").show();
					},
					complete:function(ret){
						$("#loading-corrigir-charset").hide();
					}
				});
			});
		});
		</script>
	</head>
	<body>
		<?php include 'menu_topo.php'; ?>
		<div class="container-fluid">
			<?php include 'cabecalho.php'; ?>
			<div class="row-fluid">
				<div class="col-md-2">
					<?php						
						//include 'menu_entidade.php';
					?>
				</div>
				<div class="col-md-10">
					<form>
						<legend>CharSet</legend>
					</form>
					<table class="table table-striped">
						<thead>
							<tr>
								<th width="5%">ID</th>
								<th width="50%">Local</th>
								<th width="15%" class="text-center">Nulo</th>
								<th width="15%" class="text-center">UTF-8 Decode</th>
								<th width="15%" class="text-center">UTT-8 Encode</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$sql = "SELECT * FROM td_charset ORDER BY id ASC";
								$query = $conn->query($sql);
								while ($linha = $query->fetch()){
									$id = $linha["id"];
									echo '
											<tr>
												<td>'.$linha["id"].'</td>
												<td>'.executefunction('utf8charset',array($linha["local"],5)).'</td>
												<td class="text-center">
													<input type="radio" name="charsetoption-'.$id.'" id="charsetoption-'.$id.'-N" value="N" '.($linha["charset"]=='N'?'checked':'').' onclick="setCharset('.$id.',this)">
												</td>
												<td class="text-center">
													<input type="radio" name="charsetoption-'.$id.'" id="charsetoption-'.$id.'-D" value="D" '.($linha["charset"]=='D'?'checked':'').' onclick="setCharset('.$id.',this)">
												</td>
												<td class="text-center">
													<input type="radio" name="charsetoption-'.$id.'" id="charsetoption-'.$id.'-E" value="E" '.($linha["charset"]=='E'?'checked':'').' onclick="setCharset('.$id.',this)">
												</td>
											</tr>
									';
								}	
							?>
						</tbody>
					</table>
					<form class="form-inline">
						<legend>Corrigir <small class="subtitulo-legenda text-warning">Corrigi palavras desformatadas no banco de dados</small></legend>
						
						  <div class="form-group">
							<select id="entidadelista" name="entidadelista" class="form-control">
							<option value="td_entidade">Entidade [ td_entidade ]</option>
							<option value="td_atributo">Atributo [ td_atributo ]</option>
							<?php
								$sqlT = "SELECT id,nome,descricao FROM ".PREFIXO."entidade";
								$queryT = $conn->query($sqlT);
								$linhaT = $queryT->fetchAll();
								foreach($linhaT as $dado){
									echo '<option value="'.$dado["id"].'">'. executefunction('utf8charset',array($dado["descricao"],5)) .' [ '.$dado["nome"].' ]</option>';
								}
							?>
							</select>
						  </div>	
						  <div class="form-group">
							<select id="atributolista" name="atributolista" class="form-control">
							</select>
						  </div>
						  <button type="button" class="btn btn-primary" id="btn-corrigir-charset">Corrigir</button>
							<img src="../tema/padrao/loading2.gif" id="loading-corrigir-charset" style="display:none"/>
					</form>					
				</div>
			</div>
		</div>		
	</body>
</html>