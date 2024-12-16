<?php	
	if (isset($_GET["op"])){

		$usuario 	= $_GET["usuario"];
		$senha 		= $_GET["senha"];
		$base 		= $_GET["base"];
		$host		= $_GET["host"];
		$tipo		= $_GET["tipo"];
		$porta		= $_GET["porta"];

		$conexao = testarconexao($host,$base,$porta,$usuario,$tipo,$senha);
		if ($_GET["op"] == "statusconexao"){
			echo $conexao;
			exit;
		}
		exit;
	}

	function testarconexao($host,$base,$porta,$usuario,$tipo,$senha){
		$retorno = 0;
		$erro = array();
		if ($host == ""){
			$erro[0] = "<b>Host</b> não pode ser vazio.";
		}
		if ($base == ""){
			$erro[1] = "<b>Base</b> não pode ser vazio.";
		}
		if ($porta == ""){
		}
		if ($usuario == ""){
			$erro[3] = "<b>Porta</b> não pode ser vazio.";
		}			
		if (sizeof($erro) > 0){
			echo '<div class="alert alert-danger" role="alert">';
			foreach ($erro as $e){
				echo $e.'<br />';
			}
			echo '</div>';
			exit;
		}
		switch($tipo){
			case 'mysql':
				try{
					$conn = @new PDO("$tipo:host=$host;port=$porta;dbname=$base",$usuario,$senha);
					$retorno = 1;
				}catch(Exception $e){
					$retorno = $e->getMessage();
				}					
			break;
		}
		return $retorno;
	}
?>
<div class="row-fluid">
	<div class="col-md-3">
		<?php include 'guia.php'; ?>
	</div>
	<div class="col-md-9">
		<form>
			<fieldset>
				<legend>Criar Banco de Dados</legend>
				<div class="form-grupo-botao">
				
					<img id="loader-criarbanco" src="<?=URL_LOADING2?>"/>
					<button type="button" class="btn btn-primary" id="btn-criarbanco">
						Criar Banco																
					</button>	
					<button type="button" class="btn btn-default" id="btn-testarconexao">Testar Conexão</button>
					<div id="retorno"></div>
				</div>				
				<div class="form-group">
					<label for="host">Host</label>
					<input type="text" class="form-control" id="host" name="host" placeholder="Host ou Ip">
				</div>
				<div class="form-group">
					<label for="base">Base</label>
					<input type="text" class="form-control" id="base" name="base" placeholder="Banco de dados">
				</div>
				<div class="form-group">
					<label for="porta">Porta</label>
					<input type="text" class="form-control" id="porta" name="porta" placeholder="Porta">
				</div>
				<div class="form-group">
					<label for="usuario">Usuário</label>
					<input type="text" class="form-control" id="usuario" name="usuario" placeholder="Host">
				</div>
				<div class="form-group">
					<label for="senha">Senha</label>
					<input type="password" class="form-control" id="senha"  name="senha" placeholder="Senha">
				</div>
				<div class="form-group">
					<label for="tipo">Tipo</label>
					<select id="tipo" name="tipo" class="form-control">
						<option value="mysql">MySQL</option>
						<option value="cache">Caché</option>
					</select>
				</div>
			</fieldset>
		</form>
	</div>
</div>
<?php
	$host 		= 'localhost';
	$base 		= '';
	$port 		= '3306';
	$user 		= 'root';	 
	$password 	= "";
	$type 		= 'mysql';
?>
<script type="text/javascript" src="<?=URL_LIB?>jquery/jquery.js"></script>
<script type="text/javascript" src="<?=URL_SYSTEM?>funcoes.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#host").val("<?=$host?>");
		$("#base").val("<?=$base?>");
		$("#porta").val("<?=$port?>");
		$("#usuario").val("<?=$user?>");
		$("#senha").val("<?=$password?>");
		$("#tipo").val("<?=$type?>");

		$("#base").blur( function(){
			if ($(this).val() != ''){
				statusFormControl('#base','default');
			}
		});
	});
	$("#btn-testarconexao").click(function(){
		$.ajax({
			url:"<?=URL_MILES?>",
			data:{
				controller:"install/database",
				op:"testar",
				host:$("#host").val(),
				base:$("#base").val(),
				porta:$("#porta").val(),
				usuario:$("#usuario").val(),
				senha:$("#senha").val(),
				tipo:$("#tipo").val()
			},
			success:function(retorno){
				var retorno = JSON.parse(retorno);
				if (retorno.status == 1){
					$("#retorno").html(
						'<div class="alert alert-success" role="alert"><b>Parabéns!</b>. Teste de conexão realizado com sucesso.</div>'
					);
				}else{
					$("#retorno").html(
						'<div class="alert alert-danger" role="alert"><b>Error: </b> Teste de conexão falhou.</div>'
					);
				}
				$("#retorno").show("5000");
				setTimeout(function(){
					$("#retorno").hide("5000");
				},3000);
			}
		});
	});
	$("#btn-criarbanco").click(function(){
		if ($("#base").val() == ''){
			statusFormControl('#base','error');
			$("#base").focus();
			return false;
		}		
		$("#loader-criarbanco").show();
		$.ajax({
			url:"<?=URL_MILES?>",
			data:{
				controller:"install/database",
				op:"criar",
				host:$("#host").val(),
				base:$("#base").val(),
				porta:$("#porta").val(),
				usuario:$("#usuario").val(),
				senha:$("#senha").val(),
				tipo:$("#tipo").val()
			},
			success:function(retorno){
				$("#retorno").show("5000");
				$("#loader-criarbanco").hide();				
				if (retorno == 1){
					$("#retorno").html('<div class="alert alert-success" role="alert"><b>Parabéns !</b>. Base de dados criado com sucesso.</div>');
					$("#guia-base").attr("src","<?=$_SESSION["URL_SYSTEM_THEME"]?>check.gif");
				}else{
					$("#retorno").html(retorno);
				}
				setTimeout(function(){
					$("#retorno").hide("5000");
				},3000);
			}
		});
	});
</script>