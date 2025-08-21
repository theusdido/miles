<?php
	require 'conexao.php';
	require 'prefixo.php';
	require 'funcoes.php';
?>
<html>
	<head>
		<title>CharSet</title>
		<?php include 'head.php' ?>
		<script type="text/javascript">
		function setCharset(id,obj){
			$.ajax({
				url:"<?=URL_MILES?>",
				data:{
					controller:'mdm/charset',
					op:"setar",
					id:id,
					charset:obj.value
				}
			});
		}
		
		function carregarAtributo(entidade){
			$.ajax({
				url:"<?=URL_MILES?>",
				data:{
					controller:'mdm/charset',
					op:"listaratributo",
					entidade:entidade
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
					url:"<?=URL_MILES?>",
					data:{
						controller:'mdm/charset',
						op:"corrigir",
						entidade:$("#entidadelista").val(),
						atributo:$("#atributolista").val()
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
								<th width="45%">Local</th>
								<th width="10%" class="text-center">Arquivos</th>
								<th width="10%" class="text-center">Nulo</th>
								<th width="15%" class="text-center">UTF-8 Decode</th>
								<th width="15%" class="text-center">UTT-8 Encode</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$sql = "SELECT * FROM td_charset ORDER BY id ASC";
								$query = $conn->query($sql);
								while ($linha = $query->fetch()){
									$id 		= $linha["id"];
									$local 		= executefunction('tdc::utf8',array($linha["local"]));
									$id_modal 	= 'myModal' . $linha["id"];
									echo '
											<tr>
												<td>'.$linha["id"].'
											</td>
											<td>'.$local.'</td>
											<td>
									';

									include 'charset_modal.php';

									echo '
											</td>
												
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
									echo '<option value="'.$dado["id"].'">'. executefunction('tdc::utf8',array($dado["descricao"])) .' [ '.$dado["nome"].' ]</option>';
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