<?php	
	if (isset($_GET["op"])){

		$usuario 	= $_GET["usuario"];
		$senha 		= $_GET["senha"];
		$base 		= $_GET["base"];
		$host		= $_GET["host"];
		$tipo		= $_GET["tipo"];
		$porta		= $_GET["porta"];

		$conexao = testarconexao($host,$base,$porta,$usuario,$tipo,$senha);
		
		if ($_GET["op"] == "testarconexao"){
			if (isset($_GET["apenasstatus"])){				
				echo $conexao;
			}else{
				if ($conexao == 1){				
					echo '<div class="alert alert-success" role="alert"><b>Parabéns !</b>. Conectado com Sucesso.</div>';
				}else{
					echo '<div class="alert alert-danger" role="alert">'.$conexao.'</div>';
				}
			}
		}
		if ($_GET["op"] == "statusconexao"){
			echo $conexao;
			exit;
		}
		if ($_GET["op"] == "criarbanco"){
				if ($conexao == 1){
					try{
						$conn = new PDO("$tipo:host=$host;port=$porta;dbname=$base",$usuario,$senha);
						$conn->exec("CREATE TABLE td_instalacao (id int not null primary key, bancodedadoscriado tinyint, sistemainstalado tinyint, pacoteconfigurado tinyint);");
						$conn->exec("INSERT INTO td_instalacao (id,bancodedadoscriado,sistemainstalado,pacoteconfigurado) VALUES (1,1,0,0);");
						$conn->exec("CREATE TABLE td_conexoes (id int not null primary key, host varchar(60), base varchar(60), porta varchar(15) , usuario varchar(60) , senha varchar(200) , tipo varchar(15));");
						$conn->exec("INSERT INTO td_conexoes (id,usuario,senha,base,host,tipo,porta) VALUES (1,'$usuario','$senha','$base','$host','$tipo','$porta');");
						echo 1;
					}catch(Exception $e){
						echo '<div class="alert alert-danger" role="alert">Erro ao conexão base de dados. Motivo: ';
						foreach ($conn->errorInfo() as $erro){
							echo $erro . "</br>";
						}
						echo '</div>';
					}
				}else{
					echo '<div class="alert alert-danger" role="alert">Erro ao criar base de dados. Motivo: ' . $conexao;
				}
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
			$erro[2] = "<b>Porta</b> não pode ser vazio.";
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
				
					<img id="loader-criarbanco" src="<?=$_SESSION["URL_SYSTEM_THEME"]?>loading2.gif"/>
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
	
	$databaseConfig = $_SESSION["PATH_CURRENT_PROJECT"] . "config/".$_SESSION["currenttypedatabase"]."_mysql.ini";
	$host = $base = $port = $user = $password = $type = "";
	if ($_SESSION["CRIARBASE"] == 1){
		$dm 		= parse_ini_file($databaseConfig);
		$host 		= $dm["host"];
		$base 		= $dm["base"];
		$port 		= $dm["porta"];
		$user 		= $dm["usuario"];
		$password 	= $dm["senha"];
		$type 		= $dm["tipo"];
	}
?>
<script type="text/javascript" language="JavaScript" src="<?=$_SESSION["URL_LIB"]?>jquery/jquery.js"></script>
<script type="text/javascript" language="JavaScript">
	$(document).ready(function(){
		$("#host").val("<?=$host?>");
		$("#base").val("<?=$base?>");
		$("#porta").val("<?=$port?>");
		$("#usuario").val("<?=$user?>");
		$("#senha").val("<?=$password?>");
		$("#tipo").val("<?=$type?>");
	});
	$("#btn-testarconexao").click(function(){
		$.ajax({
			url:"criarbase.php",
			data:{
				op:"testarconexao",
				host:$("#host").val(),
				base:$("#base").val(),
				porta:$("#porta").val(),
				usuario:$("#usuario").val(),
				senha:$("#senha").val(),
				tipo:$("#tipo").val()
			},
			success:function(retorno){
				$("#retorno").show("5000");
				$("#retorno").html(retorno);
				setTimeout(function(){
					$("#retorno").hide("5000");
				},3000);				
			}
		});
	});
	$("#btn-criarbanco").click(function(){
		$("#loader-criarbanco").show();
		$.ajax({
			url:"criarbase.php",
			data:{
				op:"criarbanco",
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
					$("#guia-base").attr("src","<?=$_SESSION['URL_SYSTEM_THEME']?>check.gif");
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