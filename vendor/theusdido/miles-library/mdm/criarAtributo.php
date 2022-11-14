<?php
	require 'conexao.php';
	require 'prefixo.php';	
	require 'funcoes.php';
	
	$nome = $descricao = $tipo = $tamanho = $linha = $nulo = $tipohtml = $exibirgradededados = 
	$chaveestrangeira = $dataretroativa = $inicializacao = $readonly = $indice = $tipoinicializacao = 
	$atributodependencia = $labelzerocheckbox = $labelumcheckbox = $legenda = $desabilitar = 
	$criarsomatoriogradededados = $naoexibircampo = "";

	$id = isset($_GET["id"])?$_GET["id"]:"";
	$entidade	= isset($_GET["entidade"])?$_GET['entidade']:$_POST['entidade'];
		
	if (isset($_POST["salvar"])){
		#$conn->beginTransaction();
		$nome = $_POST["nome"];
		$descricao = executefunction("tdc::utf8",array($_POST["descricao"],6));
		$tipo = $_POST["tipo"];		
		$tamanho = isset($_POST["tamanho"])?$_POST["tamanho"]:0;
		if ($tipo == "char" || $tipo == "varchar"){
			if ((int)$tamanho <= 0){
				$tamanho = "(200)";
			}else{
				$tamanho = "({$_POST["tamanho"]})";
			}
		}else{
			$tamanho = '';
		}
		$tamanhoSQL = (is_numeric($_POST["tamanho"])?$_POST["tamanho"]:0);
		$nulo = isset($_POST["nulo"])?'NULL':'NOT NULL';
		$tipohtml = $_POST["tipohtml"];
		$exibirgradededados = isset($_POST["exibirgradededados"])?1:0;
		$dataretroativa = isset($_POST["dataretroativa"])?1:0;
		$readonly = isset($_POST["readonly"])?1:0;
		if (isset($_POST["chaveestrangeira"])){
			$chaveestrangeira = ($_POST["chaveestrangeira"]=="")?0:($_POST["chaveestrangeira"]);
		}else{
			$chaveestrangeira = 0;
		}
		$indice = $_POST["indice"];
		$tipoinicializacao = $_POST["tipoinicializacao"];
		if (isset($_POST["atributodependencia"])){
			$atributodependencia = ($_POST["atributodependencia"]==""?0:$_POST["atributodependencia"]);
		}else{
			$atributodependencia = 0;
		}
		
		$labelzerocheckbox 			= $_POST["labelzerocheckbox"];
		$labelumcheckbox 			= $_POST["labelumcheckbox"];
		$legenda 					= $_POST["legenda"];
		$desabilitar 				= isset($_POST["desabilitar"])?1:0;
		$criarsomatoriogradededados = isset($_POST["criarsomatoriogradededados"])?1:0;
		$naoexibircampo				= isset($_POST["naoexibircampo"])?1:0;
		
		$inicializacao = str_replace("'","\'",$_POST["inicializacao"]);
		$query = $conn->query("SELECT nome FROM ".PREFIXO."entidade WHERE id = {$_POST["entidade"]}");
		$linha = $query->fetchAll();
		if ($_POST["id"] == ""){
			$sql = "ALTER TABLE {$linha[0]["nome"]} ADD COLUMN {$nome} {$tipo}{$tamanho} {$nulo};";
			$criar = $conn->query($sql);
			if ($criar){
				// ID Último atributo
				$query_ultimo = $conn->query("SELECT IFNULL(MAX(id),0)+1 id FROM ".PREFIXO."atributo");
				$linha_ultimo = $query_ultimo->fetchAll();
				$idRetorno = $linha_ultimo[0]["id"];				
				
				$sql = "INSERT INTO ".PREFIXO."atributo (id,entidade,nome,descricao,tipo,tamanho,nulo,tipohtml,exibirgradededados,chaveestrangeira,dataretroativa,inicializacao,readonly,indice,tipoinicializacao,atributodependencia,labelzerocheckbox,labelumcheckbox,legenda,desabilitar,criarsomatoriogradededados,naoexibircampo) VALUES ({$idRetorno},'{$_POST["entidade"]}','{$nome}','{$descricao}','{$tipo}',".$tamanhoSQL.",".((isset($_POST["nulo"]))?1:0).",'{$tipohtml}',{$exibirgradededados},{$chaveestrangeira},{$dataretroativa},'{$inicializacao}',{$readonly},'{$indice}',{$tipoinicializacao},{$atributodependencia},'{$labelzerocheckbox}','{$labelumcheckbox}','{$legenda}','{$desabilitar}',{$criarsomatoriogradededados},{$naoexibircampo});";
				$query = $conn->query($sql);
			}
		}else{
			$idRetorno = $_POST['id'];
			$sql_old = "SELECT nome FROM ".PREFIXO."atributo WHERE id = {$_POST['id']}";
			$query_old = $conn->query($sql_old);
			$linha_old = $query_old->fetchAll();
			$sql = "ALTER TABLE {$linha[0]["nome"]} CHANGE {$linha_old[0]['nome']} {$nome} {$tipo}{$tamanho} {$nulo};";			
			$atualizar = $conn->query($sql);
			if ($atualizar){
				$sql = ("UPDATE ".PREFIXO."atributo SET entidade='{$_POST["entidade"]}',nome='{$nome}',descricao='{$descricao}',tipo='{$tipo}',tamanho={$tamanhoSQL},nulo=".((isset($_POST["nulo"]))?1:0).", tipohtml = '{$tipohtml}', exibirgradededados = {$exibirgradededados} , chaveestrangeira = {$chaveestrangeira} , dataretroativa = {$dataretroativa}, inicializacao = '{$inicializacao}', readonly = {$readonly}, indice = '{$indice}', tipoinicializacao = {$tipoinicializacao}, atributodependencia = {$atributodependencia}, labelzerocheckbox = '{$labelzerocheckbox}' , labelumcheckbox = '{$labelumcheckbox}' , legenda = '{$legenda}' , desabilitar = '{$desabilitar}' , criarsomatoriogradededados = '{$criarsomatoriogradededados}' , naoexibircampo = {$naoexibircampo} WHERE id = {$_POST["id"]};");
				$query = $conn->query($sql);
			}
		}
		
		$error = $conn->errorInfo();
		if ($error[0] != "00000"){
			if (IS_SHOW_ERROR_MESSAGE){
				var_dump($error);
			}
			#$conn->rollback();
		}else{
			#$conn->commit();
			header('Location: criarAtributo.php?entidade=' . $_POST["entidade"] . "&id=" . $idRetorno . getURLParamsProject("&"));
		}
		exit;
	}
	if ($id!=""){

		$sql = "SELECT entidade,nome,descricao,tipo,tamanho,nulo,tipohtml,exibirgradededados,chaveestrangeira,dataretroativa,inicializacao,readonly,indice,tipoinicializacao,atributodependencia,labelzerocheckbox,labelumcheckbox,legenda,desabilitar,criarsomatoriogradededados,naoexibircampo FROM ".PREFIXO."atributo WHERE id = {$id}";
		$query = $conn->query($sql);
		foreach($query->fetchAll() as $linha){
			$entidade					= executefunction("tdc::utf8",array($linha["entidade"]));
			$nome						= $linha["nome"];
			$descricao					= executefunction("tdc::utf8",array($linha["descricao"]));
			$tipo						= $linha["tipo"];
			$tamanho					= $linha["tamanho"];
			$nulo 						= $linha["nulo"];
			$tipohtml					= $linha["tipohtml"];
			$exibirgradededados 		= $linha["exibirgradededados"];
			$chaveestrangeira			= $linha["chaveestrangeira"];
			$dataretroativa				= $linha["dataretroativa"];
			$inicializacao				= $linha["inicializacao"];
			$readonly					= $linha["readonly"];
			$indice						= $linha["indice"];
			$tipoinicializacao			= $linha["tipoinicializacao"];
			$atributodependencia		= $linha["atributodependencia"];
			$labelzerocheckbox 			= $linha["labelzerocheckbox"];
			$labelumcheckbox			= $linha["labelumcheckbox"];
			$legenda					= $linha["legenda"];
			$desabilitar				= $linha["desabilitar"];
			$criarsomatoriogradededados	= $linha["criarsomatoriogradededados"];
			$naoexibircampo				= $linha["naoexibircampo"];
		}
	}
?>
<html>
	<head>
		<title>Criar Coluna</title>
		<?php include 'head.php'; ?>
		<script type="text/javascript">
			window.onload = function(){
				document.getElementById("nome").value = "<?=$nome?>";
				document.getElementById("descricao").value = "<?=$descricao?>";
				document.getElementById("tipo").value = "<?=$tipo?>";
				document.getElementById("tamanho").value = "<?=$tamanho?>";
				document.getElementById("nulo").checked = (<?=(int)$nulo?>==0)?false:true;
				document.getElementById("tipohtml").value = "<?=$tipohtml?>";
				document.getElementById("exibirgradededados").checked = <?=($exibirgradededados==0?'false':'true')?>;
				document.getElementById("chaveestrangeira").value = "<?=$chaveestrangeira?>";
				document.getElementById("dataretroativa").checked = (<?=(int)$dataretroativa?>==0)?false:true;
				document.getElementById("inicializacao").value = "<?=$inicializacao?>";
				document.getElementById("readonly").checked = (<?=(int)$readonly?>==0)?false:true;
				document.getElementById("indice").value = "<?=$indice?>";
				document.getElementById("tipoinicializacao").value = ("<?=$tipoinicializacao?>"==""?1:"<?=$tipoinicializacao?>");
				document.getElementById("atributodependencia").value = "<?=$atributodependencia?>";
				document.getElementById("labelzerocheckbox").value = "<?=$labelzerocheckbox?>";
				document.getElementById("labelumcheckbox").value = "<?=$labelumcheckbox?>";
				document.getElementById("legenda").value = "<?=$legenda?>";
				document.getElementById("desabilitar").checked = (<?=(int)$readonly?>==0)?false:true;
				document.getElementById("criarsomatoriogradededados").checked = (<?=(int)$criarsomatoriogradededados?>==0)?false:true;
				document.getElementById("naoexibircampo").checked = (<?=(int)$naoexibircampo?>==0)?false:true;
				
				// Quando mudar o tipo de elemento HTML
				document.getElementById("tipohtml").onchange = function(){
					if (document.getElementById("nome").value == ""){
						switch(this.value){
							case "1":
								document.getElementById("tipo").value = "varchar";
								document.getElementById("tamanho").value = "35";
							break;
							case "2":
								document.getElementById("tipo").value = "varchar";
								document.getElementById("tamanho").value = "120";
							break;						
							case "3":
								document.getElementById("tipo").value = "varchar";
								document.getElementById("tamanho").value = "200";
							break;
							case "4":
								document.getElementById("tipo").value = "smallint";
								document.getElementById("tamanho").value = "";
							break;						
							case "5":
								document.getElementById("tipo").value = "varchar";
								document.getElementById("tamanho").value = "200";
							break;
							case "6":
								document.getElementById("tipo").value = "varchar";
								document.getElementById("tamanho").value = "64";
								document.getElementById("nome").value = "senha";
								document.getElementById("descricao").value = "Senha";
							break;
							case "7":
								document.getElementById("tipo").value = "boolean";
								document.getElementById("tamanho").value = "";								
							break;
							case "8":
								document.getElementById("tipo").value = "varchar";
								document.getElementById("tamanho").value = "25";
								document.getElementById("nome").value = "telefone";
								document.getElementById("descricao").value = "Telefone";							
							break;
							case "9":
								document.getElementById("tipo").value = "varchar";
								document.getElementById("tamanho").value = "9";
								document.getElementById("nome").value = "cep";
								document.getElementById("descricao").value = "CEP";							
							break;
							case "10":
								document.getElementById("tipo").value = "varchar";
								document.getElementById("tamanho").value = "14";
								document.getElementById("nome").value = "cpf";
								document.getElementById("descricao").value = "CPF";							
							break;
							case "11":
								document.getElementById("tipo").value = "date";
								document.getElementById("tamanho").value = "";
								document.getElementById("nome").value = "data";
								document.getElementById("descricao").value = "Data";							
							break;
							case "12":
								document.getElementById("tipo").value = "varchar";
								document.getElementById("tamanho").value = "200";
								document.getElementById("nome").value = "email";
								document.getElementById("descricao").value = "E-Mail";
							break;
							case "13":
								document.getElementById("tipo").value = "float";
								document.getElementById("tamanho").value = "";
								document.getElementById("nome").value = "valor";
								document.getElementById("descricao").value = "Valor";
							break;
							case "14":
								document.getElementById("tipo").value = "text";
								document.getElementById("tamanho").value = "";
							break;
							case "15":
								document.getElementById("tipo").value 		= "varchar";
								document.getElementById("tamanho").value 	= "19";
								document.getElementById("nome").value 		= "cnpj";
								document.getElementById("descricao").value 	= "CNPJ";
							break;
							case "16":
								document.getElementById("tipo").value = "int";
								document.getElementById("tamanho").value = "";
							break;
							case "17":
								document.getElementById("tipo").value = "varchar";
								document.getElementById("tamanho").value = "60";
								document.getElementById("nome").value = "cpfj";
								document.getElementById("descricao").value = "CPF / CNPJ";
							break;
							case "19":
								document.getElementById("tipo").value = "text";
								document.getElementById("tamanho").value = "";
								document.getElementById("nome").value = "arquivo";
								document.getElementById("descricao").value = "Arquivo";
							break;					
							case "21":
								document.getElementById("tipo").value = "text";
								document.getElementById("tamanho").value = "";
								document.getElementById("nome").value = "texto";
								document.getElementById("descricao").value = "Texto";
							break;
							case "22":
								document.getElementById("tipo").value = "int";
								document.getElementById("tamanho").value = "";
							break;
							case "23":
								document.getElementById("tipo").value = "datetime";
								document.getElementById("tamanho").value = "";
								document.getElementById("nome").value = "datahora";
								document.getElementById("descricao").value = "Data/Hora";								
							break;
							case "24":
								document.getElementById("tipo").value = "int";
								document.getElementById("tamanho").value = "";
							break;							
							case "25":
								document.getElementById("tipo").value = "int";
								document.getElementById("tamanho").value = "";
							break;
							case "29":
								document.getElementById("tipo").value = "varchar";
								document.getElementById("tamanho").value = "7";
								document.getElementById("nome").value = "mesano";
								document.getElementById("descricao").value = "Mês/Ano";
							break;
							case "31":
								document.getElementById("tipo").value 		= "float";
								document.getElementById("tamanho").value 	= 0;
							break;							
						}
					}
					habilitaCheckbox();
				}
				$("#tipo").change(function(){
					if (this.value == "int" || this.value == "tinyint" || this.value == "smallint" || this.value == "mediumint" || this.value == "bigint"){
						$("#tamanho").val("");
					}
				});
				document.getElementById("collection").value = "utf8_general_ci";
				habilitaCheckbox();
			}
			function habilitaCheckbox(){
				if ($("#tipohtml").val() == "7"){
					$("#labelzerocheckbox").parents(".form-group").first().show();
					$("#labelumcheckbox").parents(".form-group").first().show();

					if ($("#labelzerocheckbox").val() == ""){
						$("#labelzerocheckbox").val("Não");
					}
					if ($("#labelumcheckbox").val() == ""){
						$("#labelumcheckbox").val("Sim");
					}
				}else{
					$("#labelzerocheckbox").parents(".form-group").first().hide();
					$("#labelumcheckbox").parents(".form-group").first().hide();
					$("#labelzerocheckbox,#labelumcheckbox").val("");
				}
			}
		</script>
	</head>
	<body>
		<?php include 'menu_topo.php'; ?>	
		<div class="container-fluid">
			<?php include 'cabecalho.php'; ?>
			<div class="row-fluid">
				
				<div class="col-md-2">
					<?php include 'menu_entidade.php'; ?>
					<?php include 'menu_atributo.php'; ?>				
				</div>
				<div class="col-md-10">
					<form action="criarAtributo.php" method="post">
						<legend>
							Atributos
							<span id="label-id-atributo"><small>ID: </small><?=$id?></span>
						</legend>
						<fieldset>
							<input type="hidden" id="id" name="id" value="<?=$id?>" />
							<input type="hidden" name="entidade" value="<?php echo $entidade; ?>">
							<input type="hidden" name="currentproject" value="<?php echo $_SESSION["currentproject"]; ?>">
							<div class="form-group">
								<label for="tipohtml">Tipo (HTML)</label>
								<select id="tipohtml" name="tipohtml" class="form-control">
									<option value="">-- Selecione --</option>
									<option value="1">01 - Texto ( Curto )</option>
									<option value="2">02 - Texto ( M&eacute;dio )</option>
									<option value="3">03 - Texto ( Longo )</option>
									<option value="4">04 - Lista de Sele&ccedil;&atilde;o &Uacute;nica</option>
									<option value="5">05 - Lista de Sele&ccedil;&atilde;o M&uacute;ltipla</option>
									<option value="6">06 - Senha</option>
									<option value="7">07 - Checkbox</option>
									<option value="8">08 - Telefone (xx) xxxx-xxxxx</option>
									<option value="9">09 - CEP (xxxxx-xxx)</option>
									<option value="10">10 - CPF (xxx.xxx.xxx-xx)</option>
									<option value="11">11 - Data (dd/mm/aaaa)</option>
									<option value="12">12 - E-Mail</option>
									<option value="13">13 - Monetário R$</option>
									<option value="14">14 - Área de Texto</option>
									<option value="15">15 - CNPJ (xx.xxx.xxx/xxxx-xx)</option>
									<option value="16">16 - Oculto</option>
									<option value="17">17 - CPFJ ( Cpf e Cnpj )</option>
									<option value="18">18 - Número de Processo Judicial</option>
									<option value="19">19 - Arquivo ( Caminho )</option>
									<option value="20">20 - Arquivo ( Físico )</option>
									<option value="21">21 - Editor de Texto e HTML ( CK Editor )</option>
									<option value="22">22 - Filtro ( Pesquisa )</option>
									<option value="23">23 - Data e Hora ( dd/mm/aaaa hh:mm:ss )</option>
									<option value="24">24 - Filtro ( Endereço )</option>
									<option value="25">25 - Número ( Inteiro )</option>
									<option value="26">26 - Número ( Decimal )</option>
									<option value="27">27 - Multi Linha</option>
									<option value="28">28 - Hora</option>
									<option value="29">29 - Mês/Ano</option>
									<option value="30">30 - Is Null e Is Empty</option>
									<option value="30">31 - Percentual</option>
								</select>
							</div>							
							<div class="form-group">
								<label for="nome">Nome</label>
								<input type="text" id="nome" name="nome" class="form-control">
							</div>
							<div class="form-group">
								<label for="descricao">Descrição</label>
								<input type="text" id="descricao" name="descricao" class="form-control">
							</div>	
							<div class="form-group" style="display:none;">
									<label for="labelzerocheckbox">Checkbox Label 0</label>
									<input type="text" id="labelzerocheckbox" name="labelzerocheckbox" class="form-control">
							</div>
							<div class="form-group" style="display:none;">
									<label for="labelumcheckbox">Checkbox Label 1</label>
									<input type="text" id="labelumcheckbox" name="labelumcheckbox" class="form-control">
							</div>
							<div class="form-group">
								<label for="descricao">Tipo</label>
								<select id="tipo" name="tipo" class="form-control">
									<option value="int">int</option>
									<option value="varchar">varchar</option>
									<option value="text">text</option>
									<option value="date">date</option>
									<option value="tinyint">tinyint</option>
									<option value="smallint">smallint</option>
									<option value="mediumint">mediumint</option>
									<option value=""></option>
									<option value="bigint">bigint</option>
									<option value="decimal">decimal</option>
									<option value="float">float</option>
									<option value="double">double</option>
									<option value="real">real</option>
									<option value="bit">bit</option>
									<option value="boolean">boolean</option>
									<option value="serial">serial</option>
									<option value="date">date</option>
									<option value="datetime">datetime</option>
									<option value="timestamp"></option>
									<option value="time">time</option>
									<option value="year">year</option>
									<option value="char">char</option>
									<option value="tinytext">tinytext</option>
									<option value="mediumtext">mediumtext</option>
									<option value="longtext">longtext</option>
									<option value="binary">binary</option>
									<option value="varbinary">varbinary</option>
									<option value="tinyblob">tinyblob</option>
									<option value="mediumblob">mediumblob</option>
									<option value="blob">blob</option>
									<option value="longblob">longblob</option>
									<option value="enum">enum</option>
									<option value="set">set</option>
									<option value="geometry">geometry</option>
									<option value="point">point</option>
									<option value="linestring">linestring</option>
									<option value="polygon">polygon</option>
									<option value="multipoint">multipoint</option>
									<option value="multilinestring">multilinestring</option>
									<option value="multipolygon">multipolygon</option>
									<option value="geometrycollection">geometrycollection</option>				
								</select>
							</div>
							<div class="form-group">	
								<label for="tamanho">Tamanho</label>
								<input type="text" id="tamanho" name="tamanho" class="form-control">
							</div>
							<div class="form-group">
								<label for="omissao">Omissão</label>
								<select name="omissao" class="form-control">
									<option value="NONE">NONE</option>			
									<option value="USER_DEFINED">USER_DEFINED</option>			
									<option value="NULL">NULL</option>			
									<option value="CURRENT_TIMESTAMP">CURRENT_TIMESTAMP</option>							
								</select>									
							</div>
							<div class="form-group">
								<label for="collection">Coleção</label>	
								<select name="collection" class="form-control" id="collection">
									<option value=""></option>
									<optgroup title="ARMSCII-8 Armenian" label="armscii8">
									<option title="Arménio, Binário" value="armscii8_bin">armscii8_bin</option>
									<option title="Arménio, Sensível a maiúsculas/minúculas" value="armscii8_general_ci">armscii8_general_ci</option>
									</optgroup>
									<optgroup title="US ASCII" label="ascii">
									<option title="Europeu de Oeste (multilingua), Binário" value="ascii_bin">ascii_bin</option>
									<option title="Europeu de Oeste (multilingua), Sensível a maiúsculas/minúculas" value="ascii_general_ci">ascii_general_ci</option>
									</optgroup>
									<optgroup title="Big5 Traditional Chinese" label="big5">
									<option title="Chinês Tradicional, Binário" value="big5_bin">big5_bin</option>
									<option title="Chinês Tradicional, Sensível a maiúsculas/minúculas" value="big5_chinese_ci">big5_chinese_ci</option>
									</optgroup>
									<optgroup title="Binary pseudo charset" label="binary">
									<option title="Binário" value="binary">binary</option>
									</optgroup>
									<optgroup title="Windows Central European" label="cp1250">
									<option title="Europeu Central (multilingua), Binário" value="cp1250_bin">cp1250_bin</option>
									<option title="Croata, Sensível a maiúsculas/minúculas" value="cp1250_croatian_ci">cp1250_croatian_ci</option>
									<option title="Checo, Não-sensível a a maiúsculas/minúculas" value="cp1250_czech_cs">cp1250_czech_cs</option>
									<option title="Europeu Central (multilingua), Sensível a maiúsculas/minúculas" value="cp1250_general_ci">cp1250_general_ci</option>
									<option title="Polish, Sensível a maiúsculas/minúculas" value="cp1250_polish_ci">cp1250_polish_ci</option>
									</optgroup>
									<optgroup title="Windows Cyrillic" label="cp1251">
									<option title="Cyrillic (multilingua), Binário" value="cp1251_bin">cp1251_bin</option>
									<option title="Búlgaro, Sensível a maiúsculas/minúculas" value="cp1251_bulgarian_ci">cp1251_bulgarian_ci</option>
									<option title="Cyrillic (multilingua), Sensível a maiúsculas/minúculas" value="cp1251_general_ci">cp1251_general_ci</option>
									<option title="Cyrillic (multilingua), Não-sensível a a maiúsculas/minúculas" value="cp1251_general_cs">cp1251_general_cs</option>
									<option title="Ucraniano, Sensível a maiúsculas/minúculas" value="cp1251_ukrainian_ci">cp1251_ukrainian_ci</option>
									</optgroup>
									<optgroup title="Windows Arabic" label="cp1256">
									<option title="Árabe, Binário" value="cp1256_bin">cp1256_bin</option>
									<option title="Árabe, Sensível a maiúsculas/minúculas" value="cp1256_general_ci">cp1256_general_ci</option>
									</optgroup>
									<optgroup title="Windows Baltic" label="cp1257">
									<option title="Báltico (multilingua), Binário" value="cp1257_bin">cp1257_bin</option>
									<option title="Báltico (multilingua), Sensível a maiúsculas/minúculas" value="cp1257_general_ci">cp1257_general_ci</option>
									<option title="Lituano, Sensível a maiúsculas/minúculas" value="cp1257_lithuanian_ci">cp1257_lithuanian_ci</option>
									</optgroup>
									<optgroup title="DOS West European" label="cp850">
									<option title="Europeu de Oeste (multilingua), Binário" value="cp850_bin">cp850_bin</option>
									<option title="Europeu de Oeste (multilingua), Sensível a maiúsculas/minúculas" value="cp850_general_ci">cp850_general_ci</option>
									</optgroup>
									<optgroup title="DOS Central European" label="cp852">
									<option title="Europeu Central (multilingua), Binário" value="cp852_bin">cp852_bin</option>
									<option title="Europeu Central (multilingua), Sensível a maiúsculas/minúculas" value="cp852_general_ci">cp852_general_ci</option>
									</optgroup>
									<optgroup title="DOS Russian" label="cp866">
									<option title="Russo, Binário" value="cp866_bin">cp866_bin</option>
									<option title="Russo, Sensível a maiúsculas/minúculas" value="cp866_general_ci">cp866_general_ci</option>
									</optgroup>
									<optgroup title="SJIS for Windows Japanese" label="cp932">
									<option title="Japonês, Binário" value="cp932_bin">cp932_bin</option>
									<option title="Japonês, Sensível a maiúsculas/minúculas" value="cp932_japanese_ci">cp932_japanese_ci</option>
									</optgroup>
									<optgroup title="DEC West European" label="dec8">
									<option title="Europeu de Oeste (multilingua), Binário" value="dec8_bin">dec8_bin</option>
									<option title="Sueco, Sensível a maiúsculas/minúculas" value="dec8_swedish_ci">dec8_swedish_ci</option>
									</optgroup>
									<optgroup title="UJIS for Windows Japanese" label="eucjpms">
									<option title="Japonês, Binário" value="eucjpms_bin">eucjpms_bin</option>
									<option title="Japonês, Sensível a maiúsculas/minúculas" value="eucjpms_japanese_ci">eucjpms_japanese_ci</option>
									</optgroup>
									<optgroup title="EUC-KR Korean" label="euckr">
									<option title="Coreano, Binário" value="euckr_bin">euckr_bin</option>
									<option title="Coreano, Sensível a maiúsculas/minúculas" value="euckr_korean_ci">euckr_korean_ci</option>
									</optgroup>
									<optgroup title="GB2312 Simplified Chinese" label="gb2312">
									<option title="Chinês Simplificado, Binário" value="gb2312_bin">gb2312_bin</option>
									<option title="Chinês Simplificado, Sensível a maiúsculas/minúculas" value="gb2312_chinese_ci">gb2312_chinese_ci</option>
									</optgroup>
									<optgroup title="GBK Simplified Chinese" label="gbk">
									<option title="Chinês Simplificado, Binário" value="gbk_bin">gbk_bin</option>
									<option title="Chinês Simplificado, Sensível a maiúsculas/minúculas" value="gbk_chinese_ci">gbk_chinese_ci</option>
									</optgroup>
									<optgroup title="GEOSTD8 Georgian" label="geostd8">
									<option title="Georgiano, Binário" value="geostd8_bin">geostd8_bin</option>
									<option title="Georgiano, Sensível a maiúsculas/minúculas" value="geostd8_general_ci">geostd8_general_ci</option>
									</optgroup>
									<optgroup title="ISO 8859-7 Greek" label="greek">
									<option title="Grego, Binário" value="greek_bin">greek_bin</option>
									<option title="Grego, Sensível a maiúsculas/minúculas" value="greek_general_ci">greek_general_ci</option>
									</optgroup>
									<optgroup title="ISO 8859-8 Hebrew" label="hebrew">
									<option title="Hebráico, Binário" value="hebrew_bin">hebrew_bin</option>
									<option title="Hebráico, Sensível a maiúsculas/minúculas" value="hebrew_general_ci">hebrew_general_ci</option>
									</optgroup>
									<optgroup title="HP West European" label="hp8">
									<option title="Europeu de Oeste (multilingua), Binário" value="hp8_bin">hp8_bin</option>
									<option title="Inglês, Sensível a maiúsculas/minúculas" value="hp8_english_ci">hp8_english_ci</option>
									</optgroup>
									<optgroup title="DOS Kamenicky Czech-Slovak" label="keybcs2">
									<option title="Checo-Eslovaco, Binário" value="keybcs2_bin">keybcs2_bin</option>
									<option title="Checo-Eslovaco, Sensível a maiúsculas/minúculas" value="keybcs2_general_ci">keybcs2_general_ci</option>
									</optgroup>
									<optgroup title="KOI8-R Relcom Russian" label="koi8r">
									<option title="Russo, Binário" value="koi8r_bin">koi8r_bin</option>
									<option title="Russo, Sensível a maiúsculas/minúculas" value="koi8r_general_ci">koi8r_general_ci</option>
									</optgroup>
									<optgroup title="KOI8-U Ukrainian" label="koi8u">
									<option title="Ucraniano, Binário" value="koi8u_bin">koi8u_bin</option>
									<option title="Ucraniano, Sensível a maiúsculas/minúculas" value="koi8u_general_ci">koi8u_general_ci</option>
									</optgroup>
									<optgroup title="cp1252 West European" label="latin1">
									<option title="Europeu de Oeste (multilingua), Binário" value="latin1_bin">latin1_bin</option>
									<option title="Dinamarquês, Sensível a maiúsculas/minúculas" value="latin1_danish_ci">latin1_danish_ci</option>
									<option title="Europeu de Oeste (multilingua), Sensível a maiúsculas/minúculas" value="latin1_general_ci">latin1_general_ci</option>
									<option title="Europeu de Oeste (multilingua), Não-sensível a a maiúsculas/minúculas" value="latin1_general_cs">latin1_general_cs</option>
									<option title="Alemão (dicionário), Sensível a maiúsculas/minúculas" value="latin1_german1_ci">latin1_german1_ci</option>
									<option title="Alemão (lista telefónica), Sensível a maiúsculas/minúculas" value="latin1_german2_ci">latin1_german2_ci</option>
									<option title="Spanish, Sensível a maiúsculas/minúculas" value="latin1_spanish_ci">latin1_spanish_ci</option>
									<option title="Sueco, Sensível a maiúsculas/minúculas" value="latin1_swedish_ci">latin1_swedish_ci</option>
									</optgroup>
									<optgroup title="ISO 8859-2 Central European" label="latin2">
									<option title="Europeu Central (multilingua), Binário" value="latin2_bin">latin2_bin</option>
									<option title="Croata, Sensível a maiúsculas/minúculas" value="latin2_croatian_ci">latin2_croatian_ci</option>
									<option title="Checo, Não-sensível a a maiúsculas/minúculas" value="latin2_czech_cs">latin2_czech_cs</option>
									<option title="Europeu Central (multilingua), Sensível a maiúsculas/minúculas" value="latin2_general_ci">latin2_general_ci</option>
									<option title="Húngaro, Sensível a maiúsculas/minúculas" value="latin2_hungarian_ci">latin2_hungarian_ci</option>
									</optgroup>
									<optgroup title="ISO 8859-9 Turkish" label="latin5">
									<option title="Turco, Binário" value="latin5_bin">latin5_bin</option>
									<option title="Turco, Sensível a maiúsculas/minúculas" value="latin5_turkish_ci">latin5_turkish_ci</option>
									</optgroup>
									<optgroup title="ISO 8859-13 Baltic" label="latin7">
									<option title="Báltico (multilingua), Binário" value="latin7_bin">latin7_bin</option>
									<option title="Estoniano, Não-sensível a a maiúsculas/minúculas" value="latin7_estonian_cs">latin7_estonian_cs</option>
									<option title="Báltico (multilingua), Sensível a maiúsculas/minúculas" value="latin7_general_ci">latin7_general_ci</option>
									<option title="Báltico (multilingua), Não-sensível a a maiúsculas/minúculas" value="latin7_general_cs">latin7_general_cs</option>
									</optgroup>
									<optgroup title="Mac Central European" label="macce">
									<option title="Europeu Central (multilingua), Binário" value="macce_bin">macce_bin</option>
									<option title="Europeu Central (multilingua), Sensível a maiúsculas/minúculas" value="macce_general_ci">macce_general_ci</option>
									</optgroup>
									<optgroup title="Mac West European" label="macroman">
									<option title="Europeu de Oeste (multilingua), Binário" value="macroman_bin">macroman_bin</option>
									<option title="Europeu de Oeste (multilingua), Sensível a maiúsculas/minúculas" value="macroman_general_ci">macroman_general_ci</option>
									</optgroup>
									<optgroup title="Shift-JIS Japanese" label="sjis">
									<option title="Japonês, Binário" value="sjis_bin">sjis_bin</option>
									<option title="Japonês, Sensível a maiúsculas/minúculas" value="sjis_japanese_ci">sjis_japanese_ci</option>
									</optgroup>
									<optgroup title="7bit Swedish" label="swe7">
									<option title="Sueco, Binário" value="swe7_bin">swe7_bin</option>
									<option title="Sueco, Sensível a maiúsculas/minúculas" value="swe7_swedish_ci">swe7_swedish_ci</option>
									</optgroup>
									<optgroup title="TIS620 Thai" label="tis620">
									<option title="Tailandês, Binário" value="tis620_bin">tis620_bin</option>
									<option title="Tailandês, Sensível a maiúsculas/minúculas" value="tis620_thai_ci">tis620_thai_ci</option>
									</optgroup>
									<optgroup title="UCS-2 Unicode" label="ucs2">
									<option title="Unicode (multilingua), Binário" value="ucs2_bin">ucs2_bin</option>
									<option title="Croata, Sensível a maiúsculas/minúculas" value="ucs2_croatian_ci">ucs2_croatian_ci</option>
									<option title="Checo, Sensível a maiúsculas/minúculas" value="ucs2_czech_ci">ucs2_czech_ci</option>
									<option title="Dinamarquês, Sensível a maiúsculas/minúculas" value="ucs2_danish_ci">ucs2_danish_ci</option>
									<option title="Esperanto, Sensível a maiúsculas/minúculas" value="ucs2_esperanto_ci">ucs2_esperanto_ci</option>
									<option title="Estoniano, Sensível a maiúsculas/minúculas" value="ucs2_estonian_ci">ucs2_estonian_ci</option>
									<option title="Unicode (multilingua), Sensível a maiúsculas/minúculas" value="ucs2_general_ci">ucs2_general_ci</option>
									<option title="Unicode (multilingua)" value="ucs2_general_mysql500_ci">ucs2_general_mysql500_ci</option>
									<option title="Alemão (lista telefónica), Sensível a maiúsculas/minúculas" value="ucs2_german2_ci">ucs2_german2_ci</option>
									<option title="Húngaro, Sensível a maiúsculas/minúculas" value="ucs2_hungarian_ci">ucs2_hungarian_ci</option>
									<option title="Icelandic, Sensível a maiúsculas/minúculas" value="ucs2_icelandic_ci">ucs2_icelandic_ci</option>
									<option title="Latvian, Sensível a maiúsculas/minúculas" value="ucs2_latvian_ci">ucs2_latvian_ci</option>
									<option title="Lituano, Sensível a maiúsculas/minúculas" value="ucs2_lithuanian_ci">ucs2_lithuanian_ci</option>
									<option title="Persian, Sensível a maiúsculas/minúculas" value="ucs2_persian_ci">ucs2_persian_ci</option>
									<option title="Polish, Sensível a maiúsculas/minúculas" value="ucs2_polish_ci">ucs2_polish_ci</option>
									<option title="Europeu de Oeste, Sensível a maiúsculas/minúculas" value="ucs2_roman_ci">ucs2_roman_ci</option>
									<option title="Romanian, Sensível a maiúsculas/minúculas" value="ucs2_romanian_ci">ucs2_romanian_ci</option>
									<option title="desconhecido, Sensível a maiúsculas/minúculas" value="ucs2_sinhala_ci">ucs2_sinhala_ci</option>
									<option title="Slovak, Sensível a maiúsculas/minúculas" value="ucs2_slovak_ci">ucs2_slovak_ci</option>
									<option title="Slovenian, Sensível a maiúsculas/minúculas" value="ucs2_slovenian_ci">ucs2_slovenian_ci</option>
									<option title="Traditional Spanish, Sensível a maiúsculas/minúculas" value="ucs2_spanish2_ci">ucs2_spanish2_ci</option>
									<option title="Spanish, Sensível a maiúsculas/minúculas" value="ucs2_spanish_ci">ucs2_spanish_ci</option>
									<option title="Sueco, Sensível a maiúsculas/minúculas" value="ucs2_swedish_ci">ucs2_swedish_ci</option>
									<option title="Turco, Sensível a maiúsculas/minúculas" value="ucs2_turkish_ci">ucs2_turkish_ci</option>
									<option title="Unicode (multilingua)" value="ucs2_unicode_520_ci">ucs2_unicode_520_ci</option>
									<option title="Unicode (multilingua), Sensível a maiúsculas/minúculas" value="ucs2_unicode_ci">ucs2_unicode_ci</option>
									<option title="desconhecido, Sensível a maiúsculas/minúculas" value="ucs2_vietnamese_ci">ucs2_vietnamese_ci</option>
									</optgroup>
									<optgroup title="EUC-JP Japanese" label="ujis">
									<option title="Japonês, Binário" value="ujis_bin">ujis_bin</option>
									<option title="Japonês, Sensível a maiúsculas/minúculas" value="ujis_japanese_ci">ujis_japanese_ci</option>
									</optgroup>
									<optgroup title="UTF-16 Unicode" label="utf16">
									<option title="desconhecido, Binário" value="utf16_bin">utf16_bin</option>
									<option title="Croata, Sensível a maiúsculas/minúculas" value="utf16_croatian_ci">utf16_croatian_ci</option>
									<option title="Checo, Sensível a maiúsculas/minúculas" value="utf16_czech_ci">utf16_czech_ci</option>
									<option title="Dinamarquês, Sensível a maiúsculas/minúculas" value="utf16_danish_ci">utf16_danish_ci</option>
									<option title="Esperanto, Sensível a maiúsculas/minúculas" value="utf16_esperanto_ci">utf16_esperanto_ci</option>
									<option title="Estoniano, Sensível a maiúsculas/minúculas" value="utf16_estonian_ci">utf16_estonian_ci</option>
									<option title="desconhecido, Sensível a maiúsculas/minúculas" value="utf16_general_ci">utf16_general_ci</option>
									<option title="Alemão (lista telefónica), Sensível a maiúsculas/minúculas" value="utf16_german2_ci">utf16_german2_ci</option>
									<option title="Húngaro, Sensível a maiúsculas/minúculas" value="utf16_hungarian_ci">utf16_hungarian_ci</option>
									<option title="Icelandic, Sensível a maiúsculas/minúculas" value="utf16_icelandic_ci">utf16_icelandic_ci</option>
									<option title="Latvian, Sensível a maiúsculas/minúculas" value="utf16_latvian_ci">utf16_latvian_ci</option>
									<option title="Lituano, Sensível a maiúsculas/minúculas" value="utf16_lithuanian_ci">utf16_lithuanian_ci</option>
									<option title="Persian, Sensível a maiúsculas/minúculas" value="utf16_persian_ci">utf16_persian_ci</option>
									<option title="Polish, Sensível a maiúsculas/minúculas" value="utf16_polish_ci">utf16_polish_ci</option>
									<option title="Europeu de Oeste, Sensível a maiúsculas/minúculas" value="utf16_roman_ci">utf16_roman_ci</option>
									<option title="Romanian, Sensível a maiúsculas/minúculas" value="utf16_romanian_ci">utf16_romanian_ci</option>
									<option title="desconhecido, Sensível a maiúsculas/minúculas" value="utf16_sinhala_ci">utf16_sinhala_ci</option>
									<option title="Slovak, Sensível a maiúsculas/minúculas" value="utf16_slovak_ci">utf16_slovak_ci</option>
									<option title="Slovenian, Sensível a maiúsculas/minúculas" value="utf16_slovenian_ci">utf16_slovenian_ci</option>
									<option title="Traditional Spanish, Sensível a maiúsculas/minúculas" value="utf16_spanish2_ci">utf16_spanish2_ci</option>
									<option title="Spanish, Sensível a maiúsculas/minúculas" value="utf16_spanish_ci">utf16_spanish_ci</option>
									<option title="Sueco, Sensível a maiúsculas/minúculas" value="utf16_swedish_ci">utf16_swedish_ci</option>
									<option title="Turco, Sensível a maiúsculas/minúculas" value="utf16_turkish_ci">utf16_turkish_ci</option>
									<option title="Unicode (multilingua)" value="utf16_unicode_520_ci">utf16_unicode_520_ci</option>
									<option title="Unicode (multilingua), Sensível a maiúsculas/minúculas" value="utf16_unicode_ci">utf16_unicode_ci</option>
									<option title="desconhecido, Sensível a maiúsculas/minúculas" value="utf16_vietnamese_ci">utf16_vietnamese_ci</option>
									</optgroup>
									<optgroup title="UTF-16LE Unicode" label="utf16le">
									<option title="desconhecido, Binário" value="utf16le_bin">utf16le_bin</option>
									<option title="desconhecido, Sensível a maiúsculas/minúculas" value="utf16le_general_ci">utf16le_general_ci</option>
									</optgroup>
									<optgroup title="UTF-32 Unicode" label="utf32">
									<option title="desconhecido, Binário" value="utf32_bin">utf32_bin</option>
									<option title="Croata, Sensível a maiúsculas/minúculas" value="utf32_croatian_ci">utf32_croatian_ci</option>
									<option title="Checo, Sensível a maiúsculas/minúculas" value="utf32_czech_ci">utf32_czech_ci</option>
									<option title="Dinamarquês, Sensível a maiúsculas/minúculas" value="utf32_danish_ci">utf32_danish_ci</option>
									<option title="Esperanto, Sensível a maiúsculas/minúculas" value="utf32_esperanto_ci">utf32_esperanto_ci</option>
									<option title="Estoniano, Sensível a maiúsculas/minúculas" value="utf32_estonian_ci">utf32_estonian_ci</option>
									<option title="desconhecido, Sensível a maiúsculas/minúculas" value="utf32_general_ci">utf32_general_ci</option>
									<option title="Alemão (lista telefónica), Sensível a maiúsculas/minúculas" value="utf32_german2_ci">utf32_german2_ci</option>
									<option title="Húngaro, Sensível a maiúsculas/minúculas" value="utf32_hungarian_ci">utf32_hungarian_ci</option>
									<option title="Icelandic, Sensível a maiúsculas/minúculas" value="utf32_icelandic_ci">utf32_icelandic_ci</option>
									<option title="Latvian, Sensível a maiúsculas/minúculas" value="utf32_latvian_ci">utf32_latvian_ci</option>
									<option title="Lituano, Sensível a maiúsculas/minúculas" value="utf32_lithuanian_ci">utf32_lithuanian_ci</option>
									<option title="Persian, Sensível a maiúsculas/minúculas" value="utf32_persian_ci">utf32_persian_ci</option>
									<option title="Polish, Sensível a maiúsculas/minúculas" value="utf32_polish_ci">utf32_polish_ci</option>
									<option title="Europeu de Oeste, Sensível a maiúsculas/minúculas" value="utf32_roman_ci">utf32_roman_ci</option>
									<option title="Romanian, Sensível a maiúsculas/minúculas" value="utf32_romanian_ci">utf32_romanian_ci</option>
									<option title="desconhecido, Sensível a maiúsculas/minúculas" value="utf32_sinhala_ci">utf32_sinhala_ci</option>
									<option title="Slovak, Sensível a maiúsculas/minúculas" value="utf32_slovak_ci">utf32_slovak_ci</option>
									<option title="Slovenian, Sensível a maiúsculas/minúculas" value="utf32_slovenian_ci">utf32_slovenian_ci</option>
									<option title="Traditional Spanish, Sensível a maiúsculas/minúculas" value="utf32_spanish2_ci">utf32_spanish2_ci</option>
									<option title="Spanish, Sensível a maiúsculas/minúculas" value="utf32_spanish_ci">utf32_spanish_ci</option>
									<option title="Sueco, Sensível a maiúsculas/minúculas" value="utf32_swedish_ci">utf32_swedish_ci</option>
									<option title="Turco, Sensível a maiúsculas/minúculas" value="utf32_turkish_ci">utf32_turkish_ci</option>
									<option title="Unicode (multilingua)" value="utf32_unicode_520_ci">utf32_unicode_520_ci</option>
									<option title="Unicode (multilingua), Sensível a maiúsculas/minúculas" value="utf32_unicode_ci">utf32_unicode_ci</option>
									<option title="desconhecido, Sensível a maiúsculas/minúculas" value="utf32_vietnamese_ci">utf32_vietnamese_ci</option>
									</optgroup>
									<optgroup title="UTF-8 Unicode" label="utf8">
									<option title="Unicode (multilingua), Binário" value="utf8_bin">utf8_bin</option>
									<option title="Croata, Sensível a maiúsculas/minúculas" value="utf8_croatian_ci">utf8_croatian_ci</option>
									<option title="Checo, Sensível a maiúsculas/minúculas" value="utf8_czech_ci">utf8_czech_ci</option>
									<option title="Dinamarquês, Sensível a maiúsculas/minúculas" value="utf8_danish_ci">utf8_danish_ci</option>
									<option title="Esperanto, Sensível a maiúsculas/minúculas" value="utf8_esperanto_ci">utf8_esperanto_ci</option>
									<option title="Estoniano, Sensível a maiúsculas/minúculas" value="utf8_estonian_ci">utf8_estonian_ci</option>
									<option title="Unicode (multilingua), Sensível a maiúsculas/minúculas" value="utf8_general_ci">utf8_general_ci</option>
									<option title="Unicode (multilingua)" value="utf8_general_mysql500_ci">utf8_general_mysql500_ci</option>
									<option title="Alemão (lista telefónica), Sensível a maiúsculas/minúculas" value="utf8_german2_ci">utf8_german2_ci</option>
									<option title="Húngaro, Sensível a maiúsculas/minúculas" value="utf8_hungarian_ci">utf8_hungarian_ci</option>
									<option title="Icelandic, Sensível a maiúsculas/minúculas" value="utf8_icelandic_ci">utf8_icelandic_ci</option>
									<option title="Latvian, Sensível a maiúsculas/minúculas" value="utf8_latvian_ci">utf8_latvian_ci</option>
									<option title="Lituano, Sensível a maiúsculas/minúculas" value="utf8_lithuanian_ci">utf8_lithuanian_ci</option>
									<option title="Persian, Sensível a maiúsculas/minúculas" value="utf8_persian_ci">utf8_persian_ci</option>
									<option title="Polish, Sensível a maiúsculas/minúculas" value="utf8_polish_ci">utf8_polish_ci</option>
									<option title="Europeu de Oeste, Sensível a maiúsculas/minúculas" value="utf8_roman_ci">utf8_roman_ci</option>
									<option title="Romanian, Sensível a maiúsculas/minúculas" value="utf8_romanian_ci">utf8_romanian_ci</option>
									<option title="desconhecido, Sensível a maiúsculas/minúculas" value="utf8_sinhala_ci">utf8_sinhala_ci</option>
									<option title="Slovak, Sensível a maiúsculas/minúculas" value="utf8_slovak_ci">utf8_slovak_ci</option>
									<option title="Slovenian, Sensível a maiúsculas/minúculas" value="utf8_slovenian_ci">utf8_slovenian_ci</option>
									<option title="Traditional Spanish, Sensível a maiúsculas/minúculas" value="utf8_spanish2_ci">utf8_spanish2_ci</option>
									<option title="Spanish, Sensível a maiúsculas/minúculas" value="utf8_spanish_ci">utf8_spanish_ci</option>
									<option title="Sueco, Sensível a maiúsculas/minúculas" value="utf8_swedish_ci">utf8_swedish_ci</option>
									<option title="Turco, Sensível a maiúsculas/minúculas" value="utf8_turkish_ci">utf8_turkish_ci</option>
									<option title="Unicode (multilingua)" value="utf8_unicode_520_ci">utf8_unicode_520_ci</option>
									<option title="Unicode (multilingua), Sensível a maiúsculas/minúculas" value="utf8_unicode_ci">utf8_unicode_ci</option>
									<option title="desconhecido, Sensível a maiúsculas/minúculas" value="utf8_vietnamese_ci">utf8_vietnamese_ci</option>
									</optgroup>
									<optgroup title="UTF-8 Unicode" label="utf8mb4">
									<option title="desconhecido, Binário" value="utf8mb4_bin">utf8mb4_bin</option>
									<option title="Croata, Sensível a maiúsculas/minúculas" value="utf8mb4_croatian_ci">utf8mb4_croatian_ci</option>
									<option title="Checo, Sensível a maiúsculas/minúculas" value="utf8mb4_czech_ci">utf8mb4_czech_ci</option>
									<option title="Dinamarquês, Sensível a maiúsculas/minúculas" value="utf8mb4_danish_ci">utf8mb4_danish_ci</option>
									<option title="Esperanto, Sensível a maiúsculas/minúculas" value="utf8mb4_esperanto_ci">utf8mb4_esperanto_ci</option>
									<option title="Estoniano, Sensível a maiúsculas/minúculas" value="utf8mb4_estonian_ci">utf8mb4_estonian_ci</option>
									<option title="desconhecido, Sensível a maiúsculas/minúculas" value="utf8mb4_general_ci">utf8mb4_general_ci</option>
									<option title="Alemão (lista telefónica), Sensível a maiúsculas/minúculas" value="utf8mb4_german2_ci">utf8mb4_german2_ci</option>
									<option title="Húngaro, Sensível a maiúsculas/minúculas" value="utf8mb4_hungarian_ci">utf8mb4_hungarian_ci</option>
									<option title="Icelandic, Sensível a maiúsculas/minúculas" value="utf8mb4_icelandic_ci">utf8mb4_icelandic_ci</option>
									<option title="Latvian, Sensível a maiúsculas/minúculas" value="utf8mb4_latvian_ci">utf8mb4_latvian_ci</option>
									<option title="Lituano, Sensível a maiúsculas/minúculas" value="utf8mb4_lithuanian_ci">utf8mb4_lithuanian_ci</option>
									<option title="Persian, Sensível a maiúsculas/minúculas" value="utf8mb4_persian_ci">utf8mb4_persian_ci</option>
									<option title="Polish, Sensível a maiúsculas/minúculas" value="utf8mb4_polish_ci">utf8mb4_polish_ci</option>
									<option title="Europeu de Oeste, Sensível a maiúsculas/minúculas" value="utf8mb4_roman_ci">utf8mb4_roman_ci</option>
									<option title="Romanian, Sensível a maiúsculas/minúculas" value="utf8mb4_romanian_ci">utf8mb4_romanian_ci</option>
									<option title="desconhecido, Sensível a maiúsculas/minúculas" value="utf8mb4_sinhala_ci">utf8mb4_sinhala_ci</option>
									<option title="Slovak, Sensível a maiúsculas/minúculas" value="utf8mb4_slovak_ci">utf8mb4_slovak_ci</option>
									<option title="Slovenian, Sensível a maiúsculas/minúculas" value="utf8mb4_slovenian_ci">utf8mb4_slovenian_ci</option>
									<option title="Traditional Spanish, Sensível a maiúsculas/minúculas" value="utf8mb4_spanish2_ci">utf8mb4_spanish2_ci</option>
									<option title="Spanish, Sensível a maiúsculas/minúculas" value="utf8mb4_spanish_ci">utf8mb4_spanish_ci</option>
									<option title="Sueco, Sensível a maiúsculas/minúculas" value="utf8mb4_swedish_ci">utf8mb4_swedish_ci</option>
									<option title="Turco, Sensível a maiúsculas/minúculas" value="utf8mb4_turkish_ci">utf8mb4_turkish_ci</option>
									<option title="Unicode (multilingua)" value="utf8mb4_unicode_520_ci">utf8mb4_unicode_520_ci</option>
									<option title="Unicode (multilingua), Sensível a maiúsculas/minúculas" value="utf8mb4_unicode_ci">utf8mb4_unicode_ci</option>
									<option title="desconhecido, Sensível a maiúsculas/minúculas" value="utf8mb4_vietnamese_ci">utf8mb4_vietnamese_ci</option>
									</optgroup>
								</select>								
							</div>	
							<div class="form-group">
								<label for="atributos">Atributos</label>
								<select name="atributos" class="form-control">
									<option selected="selected" value=""></option>
									<option value="BINARY">BINARY</option>
									<option value="UNSIGNED">UNSIGNED</option>
									<option value="UNSIGNED ZEROFILL">UNSIGNED ZEROFILL</option>
									<option value="on update CURRENT_TIMESTAMP">on update CURRENT_TIMESTAMP</option>
								</select>			
							</div>
							<div class="form-group">
								<label for="chaveestrangeira">Chave Estrangeira</label>
								<select id="chaveestrangeira" name="chaveestrangeira" class="form-control">
									<option value="null" selected></option>
									<?php 
										$sql = "SELECT id,descricao,nome FROM ".PREFIXO."entidade ORDER BY id DESC";
										$query = $conn->query($sql);
										foreach($query->fetchAll() as $linha){
											echo '<option value="'.$linha["id"].'">'.executefunction("tdc::utf8",array($linha["descricao"])) . " ( ".$linha["nome"]." ) [ " . ($linha["id"]<10?"0":"") . $linha["id"] ." ]" . '</option>';
											
										}
									?>
								</select>
							</div>	
							<div class="form-group">
								<label for="indice">Indice</label>
								<select name="indice" class="form-control" id="indice">
									<option value=""></option>
									<option title="Primária" value="PK">PRIMARY</option>
									<option title="Único" value="UK">UNIQUE</option>
									<option title="Índice" value="IK">INDEX</option>
									<option title="Texto Completo" value="FK">FULLTEXT</option>
									<option title="Chave Estrangeira" value="FK">FOREIGN</option>
								</select>
							</div>
							<div class="checkbox">
								<label for="nulo">
								<input type="checkbox" id="nulo" name="nulo">Nulo
								</label>
							</div>

							<div class="checkbox">
								<label for="autoincrement">
									<input type="checkbox" name="autoincrement"> Auto Incremento
								</label>								
							</div>
							<div class="checkbox">
								<label for="exibirgradededados">
									<input type="checkbox" name="exibirgradededados" id="exibirgradededados" />Exibir coluna na grade de dados
								</label>
							</div>	
							<div class="checkbox">	
								<label for="dataretroativa">
								<input type="checkbox" name="dataretroativa" id="dataretroativa" />	N&atilde;o permitir data retroativa
								</label>
							</div>
							<div class="checkbox">
								<label for="readonly">
									<input type="checkbox" name="readonly" id="readonly" />Apenas para leitura
								</label>
							</div>
							<div class="checkbox">
								<label for="desabilitar">
									<input type="checkbox" name="desabilitar" id="desabilitar" />Desabilitar
								</label>
							</div>
							<div class="checkbox">
								<label for="criarsomatoriogradededados">
									<input type="checkbox" name="criarsomatoriogradededados" id="criarsomatoriogradededados" />Criar Somatório na Grade de Dados
								</label>
							</div>
							<div class="checkbox">
								<label for="naoexibircampo">
									<input type="checkbox" name="naoexibircampo" id="naoexibircampo" />Não exibir campo
								</label>
							</div>
							<div class="form-group">
								<label for="comentario">Comentário</label>
								<textarea name="comentario" class="form-control"></textarea>
							</div>
							<div class="form-group">	
								<label for="inicializacao">Inicializa&ccedil;&atilde;o</label>
								<small>( Valor inicial do atributo )</small>
							
								<div class="input-group">
								  <input type="text" class="form-control" aria-label="..." id="inicializacao" name="inicializacao">
								  <div class="input-group-btn">
									<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span id="spantipoinicializacao">Variável JavaScript</span> <span class="caret"></span></button>
									<ul class="dropdown-menu dropdown-menu-right">
									  <li><a href="#" target="iframe_tipo_ini" onclick="mudartipoinicializacao(1,this);">Variável JavaScript</a></li>
									  <li><a href="#" target="iframe_tipo_ini" onclick="mudartipoinicializacao(2,this);">Variável CACHÉ</a></li>
									  <li><a href="#" target="iframe_tipo_ini" onclick="mudartipoinicializacao(3,this);">Variável PHP</a></li>									  
									  <li><a href="#" target="iframe_tipo_ini" onclick="mudartipoinicializacao(4,this);">Código JavaScript</a></li>
									  <li><a href="#" target="iframe_tipo_ini" onclick="mudartipoinicializacao(5,this);">Código PHP</a></li>
									  <li><a href="#" target="iframe_tipo_ini" onclick="mudartipoinicializacao(6,this);">Código CACHÉ</a></li>
									</ul>
								  </div><!-- /btn-group -->
								</div><!-- /input-group -->
								<iframe id="iframe_tipo_ini" name="iframe_tipo_ini" style="display:none;"></iframe>
								<input type="hidden" id="tipoinicializacao" name="tipoinicializacao" class="form-control" value="1">
							</div>
							<div class="form-group" id="fdependencia">
								<label for="dependencia">Atributo de Dependência</label>
								<select id="atributodependencia" name="atributodependencia" class="form-control">
									<option value="" selected>Escolha a Atributo</option>
									<?php
										$sql = "SELECT id,descricao FROM ".PREFIXO."atributo WHERE entidade = " . $entidade  . (isset($_GET["id"])?" and id <> " . $id:"");
										$query = $conn->query($sql);
										foreach($query->fetchAll() as $linha){
											echo '<option value="'.$linha["id"].'">'.($linha["id"]<10?"0":"") . $linha["id"] ." - ".executefunction("tdc::utf8",array($linha["descricao"])).'</option>';
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="legenda">Legenda</label>
								<input type="text" id="legenda" name="legenda" class="form-control">
							</div>
							<input type="submit" value="Salvar" name="salvar" class="btn btn-primary">
						</fieldset>
					</form>	
				</div>
			</div>
		</div>						
	</body>
</html>
<script type="text/javascript">
	function mudartipoinicializacao(tipo,obj){
		$("#spantipoinicializacao").html($(obj).html());
		$("#tipoinicializacao").val(tipo);
	}
</script>