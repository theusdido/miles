<?php
	set_time_limit(7200);

	$op = tdc::r("op");
	if (isset($_GET["farein"])){

		if ($op == "downloadexcel"){
			
		}else{
			echo '
				<style type="text/css">

					.linha-registro{
						float:left;
						clear:left;
						width:400%;
						font-size:14px;
					}
					.linha-registro label,.linha-registro span{
						float:left;
					}
					.linha-registro label {
						text-align:right;
					}
					.linha-registro .label-codigo{
						width:50px;					
					}
					.linha-registro .value-codigo{
						width:50px;
					}
					
					.linha-registro .label-nome{
						width:50px;
					}
					.linha-registro .value-nome{
						width:500px;
					}
					.linha-registro .label-tipo{
						width:50px;
					}
					.linha-registro .value-tipo{
						width:75px;
					}
					.linha-registro .label-tipodoc{
						width:30px;
					}
					.linha-registro .value-tipodoc{
						width:200px;
					}
					
					.linha-registro .label-logradouro{
						width:100px;					
					}
					.linha-registro .value-logradouro{
						width:400px;
					}

					.linha-registro .label-numero{
						width:50px;
					}
					.linha-registro .value-numero{
						width:75px;
					}
					
					.linha-registro .label-complemento{
						width:100px;
					}
					.linha-registro .value-complemento{
						width:300px;
					}
					
					.linha-registro .label-bairro{
						width:100px;
					}
					.linha-registro .value-bairro{
						width:250px;
					}				
					.linha-registro .label-cidade{
						width:75px;
					}
					.linha-registro .value-cidade{
						width:150px;
					}				
					.linha-registro .label-estado{
						width:75px;
					}
					.linha-registro .value-estado{
						width:150px;
					}				
					
					.linha-registro .label-pais{
						width:100px;
					}
					.linha-registro .value-pais{
						width:150px;
					}				
					.linha-registro .label-endereco{
						width:75px;
						
					}
					.linha-registro .value-endereco{
						width:150px;
						
					}				
					.linha-registro .label-email{
						width:75px;
					}
					.linha-registro .value-email{
						width:150px;
					}	
					.linha-registro .label-cep{
						width:75px;
					}
					.linha-registro .value-cep{
						width:150px;
					}	
					.linha-registro .label-classificacao{
						width:75px;
					}
					.linha-registro .value-classificacao{
						width:750px;
					}				
					.linha-registro .label-valor{
						width:75px;
					}
					.linha-registro .value-valor{
						width:50px;
					}							
					hr{
						border:1px solid #CCC;
						width:300%;
					}
					#cabecalho{
						width:49%;
						float:left;
						font-size:12px;
					}
					#dados-ext{
						text-align:right;
						width:49%;
						float:right;
						font-size:12px;
					}
					#topo{
						width:300%;
						float:left;
						border-bottom:3px solid #000;
						margin-bottom:10px;
					}
					.icon-excel-download {
						position: fixed;
						width: 60px;
						height: 60px;
						bottom: 30px;
						right: 30px;
						background-color: #FFF;
						color: #FFF;
						text-align: center;
						font-size: 30px;
						box-shadow: 1px 1px 5px #00723f;
						z-index: 10000000;
						border: 2px solid #00723f;
						cursor:pointer;
					}
				</style>
			';
		}

		$fareinSel = $_GET["farein"];
		$parms = explode("^",$fareinSel);
		#$filenameCSV = Session::Get("PATH_CURRENT_FILE") . "relatoriocredores-" . implode("_",$parms) .".xml";
		#$fpFilenameRelatorio = fopen($filenameCSV,"w+");

		// Cabeçalho - CSV
		#$cabecalhoCSV = array(utf8_decode("Código"),"Nome","Tipo","CPF/CNPJ","Logradouro","Complemento","Bairro","Cidade","Estado","CEP",utf8_decode("País"),"Endereço Estrangeiro","E-Mail");
		#fwrite($fpFilenameRelatorio,implode(";",$cabecalhoCSV));

		$conn = Transacao::Get();
		$farein 		= $parms[0];
		$processo 		= $parms[1];
		$tipoprocesso 	= $parms[2];

		if ($tipoprocesso == "RE"){
			$sqlF = "SELECT razaosocial FROM td_recuperanda WHERE id = {$farein}";
		}else{
			$sqlF = "SELECT razaosocial FROM td_falencia WHERE id = {$farein}";
		}

		$queryF = $conn->query($sqlF);
		if ($linhaF = $queryF->fetch()){
			$nomeFAREIN = ($linhaF["razaosocial"]);
		}

		if ($op == "downloadexcel"){
			header("Content-type: text/html; charset=iso-8859-1");
			header("Content-type: application/vnd.ms-excel");
			header("Content-type: application/force-download");
			header("Content-Disposition: attachment; filename=Relatório de Credor - {$nomeFAREIN}.xls");
			header("Pragma: no-cache");

			echo '
				<table>
					<thead>
						<tr style="background-color:#376091;color:#FFF;">
							<th width="100" align="left">'.utf8_decode("Código").'</th>
							<th width="450" align="left">Nome</th>
							<th width="100" align="left">Tipo</th>
							<th width="200" align="left">CPF/CNPJ</th>
							<th width="750" align="left">'.utf8_decode("Classificação").'</th>
							<th width="150" align="left">Valor</th>
							<th width="300" align="left">Logradouro</th>
							<th width="100" align="left">Complemento</th>
							<th width="100" align="left">Bairro</th>
							<th width="100" align="left">Cidade</th>
							<th width="100" align="left">Estado</th>
							<th width="100" align="left">CEP</th>
							<th width="100" align="left">'.utf8_decode("País").'</th>
							<th width="500" align="left">'.utf8_decode("Endereço Estrangeiro").'</th>
							<th width="450" align="left">E-Mail</th>
						</tr>
					</thead>
					<tbody>	
				';
		}else{
			echo '
				<div id="topo">
					<div id="cabecalho">'.strtoupper($nomeFAREIN).' - RELAT&Oacute;RIO DE CREDORES</div>
					<div id="dados-ext">Usu&aacute;rio: '.utf8_encode(Session::Get()->username).' | Data e Hora: '.date("d/m/Y H:i:s").'</div>
				</div>	

				<img class="icon-excel-download" src="imagens/excel-donwload.png" onclick="downloadExcel()" />
			';
		}

		// Corpo CSV
		$corpoCSV 		= array();
		$sql = "SELECT * FROM td_relacaocredores WHERE farein = {$farein} AND processo = {$processo} AND origemcredor = 1;";
		$query = $conn->query($sql);

		if ($query->rowCount() > 0 ){
			while ($linha = $query->fetch()){
				
				if ($linha["tipo"] == 1){
					$tipo = "Jurídica";
					$tipodoc = "CNPJ";
				}else{
					$tipo = "Física";
					$tipodoc = "CPF";
				}
				
				$sqlCidade = "SELECT nome FROM td_cidade WHERE id = " . $linha["cidade"];
				$queryCidade = $conn->query($sqlCidade);
				$cidadenome = "";
				if ($linhaCidade = $queryCidade->fetch()){
					$cidadenome = $linhaCidade["nome"];
				}

				$sqlUF = "SELECT nome,pais FROM td_estado WHERE id = " . $linha["estado"];
				$queryUF = $conn->query($sqlUF);
				$nomeestado = "";
				if ($linhaUF = $queryUF->fetch()){
					$nomeestado = utf8_encode($linhaUF["nome"]);
				}

				$nomepais = "";
				if (is_numeric($linhaUF["pais"])){
					$sqlPais = "SELECT descricao FROM td_pais WHERE id = " . $linhaUF["pais"];
					$queryPais = $conn->query($sqlPais);
					if ($linhaPais = $queryPais->fetch()){
						$nomepais = utf8_encode($linhaPais["descricao"]);
					}
				}

				$classificacaoDescricao = "";
				if (is_numeric($linha["classificacao"])){
					$sqlClassificacao = "SELECT descricao FROM td_classificacao WHERE id = " . $linha["classificacao"];
					$queryClassificacao = $conn->query($sqlClassificacao);
					if ($linhaClassificacao = $queryClassificacao->fetch()){
						$classificacaoDescricao = substr($linhaClassificacao["descricao"],0,75);
					}
				}

				$idCredor 			= $linha["id"];
				$nomeCredor 		= $linha["nome"];
				$docCredor			= $linha[strtolower($tipodoc)];
				$logradouro			= $linha["logradouro"];
				$numero 			= $linha["numero"];
				$complemento		= $linha["complemento"];
				$bairro				= $linha["bairro"];
				$cep				= $linha["cep"];
				$enderecoexterior	= $linha["enderecoexterior"];
				$email				= $linha["email"];
				$valor				= moneyToFloat($linha["valor"],true);

				if ($op == "downloadexcel"){
					/*
					fwrite($fpFilenameRelatorio,implode(";",array(
						"\n".$idCredor,
						$nomeCredor,
						$tipo,	
						$docCredor,
						$logradouro,
						$numero,
						$complemento,
						$bairro,
						$cidadenome,
						$nomeestado,
						$cep,
						$nomepais,
						$enderecoexterior,
						$email
					)));
					*/

					echo '
						
							<tr style="border-bottom:1px solid #999;">
								<td align="left">'.$idCredor.'</td>
								<td align="left">'.utf8_decode($nomeCredor).'</td>
								<td align="left">'.utf8_decode($tipo).'</td>
								<td align="left">'.$docCredor.'</td>
								<td align="left">'.utf8_decode($classificacaoDescricao).'</td>
								<td align="left">'.$valor.'</td>
								<td align="left">'.$logradouro.'</td>
								<td align="left">'.$numero.'</td>
								<td align="left">'.$complemento.'</td>
								<td align="left">'.$bairro.'</td>
								<td align="left">'.$cidadenome.'</td>
								<td align="left">'.$nomeestado.'</td>
								<td align="left">'.$cep.'</td>
								<td align="left">'.$nomepais.'</td>
								<td align="left">'.$enderecoexterior.'</td>
								<td align="left">'.$email.'</td>
							</tr>					
					';
				}else{
					echo '
						<div class="registro">
							<div class="linha-registro">
								<label class="label-codigo">C&oacute;digo:</label><span class="value-codigo">' . $idCredor . '</span>
								<label class="label-nome">Nome:</label><span class="value-nome">' . $nomeCredor . '</span>
								<label class="label-tipo">Tipo:</label><span class="value-tipo">' . $tipo . '</span>
								<label class="label-tipodoc">'.$tipodoc.':</label><span class="value-tipodoc">' . $docCredor . '</span>

								<label class="label-classificacao">Classifica&ccedil;&atilde;o:</label><span class="value-classificacao">' . $classificacaoDescricao . '</span>
								<label class="label-valor">Valor:</label><span class="value-valor">' . $valor . '</span>
								
								<label class="label-logradouro">Logradouro:</label><span class="value-logradouro">' . $logradouro . '</span>
								<label class="label-numero">N&uacute;mero:</label><span class="value-numero">&nbsp;' . $numero . '</span>
								<label class="label-complemento">Complemento:</label><span class="value-complemento">&nbsp;' . $complemento . '</span>
								<label class="label-bairro">Bairro:</label><span class="value-bairro">&nbsp;' . $bairro . '</span>
								<label class="label-cidade">Cidade:</label><span class="value-cidade">&nbsp;' . $cidadenome . '</span>
								<label class="label-estado">Estado:</label><span class="value-estado">&nbsp;' . $nomeestado . '</span>
								
								<label class="label-cep">CEP:</label><span class="value-cep">&nbsp;' . $cep . '</span>
								<label class="label-pais">Pa&iacute;s:</label><span class="value-pais">&nbsp;'.$nomepais.'</span>
								<label class="label-endereco">End. Ext.:</label><span class="value-endereco">&nbsp;' . $enderecoexterior . '</span>
								<label class="label-email">E-Mail:</label><span class="value-email">&nbsp;' . $email . '</span>
							</div>
							<hr/>
						</div>	
					';
				}
			}

			//fclose($fpFilenameRelatorio);

			if ($op == "downloadexcel"){
					
				echo '
						</tbody>	
					</table>
				';
			}else{
				$script = tdc::o("script");
				$script->add('
					function downloadExcel(){
						window.open("'. URL_SYSTEM .'index.php?controller=relatoriocredores&farein='.$fareinSel.'&op=downloadexcel&currentproject='.Session::Get("projeto").'","iframe-excel");
					}
				');
				$script->mostrar();

				$iframeDownloadExcel = tdc::o("iframe");
				$iframeDownloadExcel->id = "iframe-excel";
				$iframeDownloadExcel->name = "iframe-excel";
				$iframeDownloadExcel->style = "display:none;";
				$iframeDownloadExcel->mostrar();

			}	
		}else{
			echo '<table width="100%"><tr style="border-bottom:1px solid #999;background-color: #fcf8e3;"><td colspan="16" style="text-align:center;">Nenhum Registro Encontrado</td></tr></table>';
		}
		exit;
	}
	$pagina = tdClass::Criar("div");

	// Bloco do formulario
	$form_bloco 				= tdClass::Criar("bloco");
	$form_bloco->class			= "col-md-12";	
	
	$form 						= tdClass::Criar("tdformulario");
	$form->onsubmit 			= "return false;";
	$form->legenda->add(utf8_decode("Relatório de Credores"));
	
	// Botão Gerar
	$btn_gerar 					= tdClass::Criar("button");
	$btn_gerar->value 			= "Gerar";
	$btn_gerar->class 			= "btn btn-primary b-gerar";
	$span_gerar	 				= tdClass::Criar("span");
	$span_gerar->class 			= "glyphicon glyphicon-file";
	$btn_gerar->id 				= "b-gerar";
	$btn_gerar->add($span_gerar,"Gerar");
	
	// Grupo de botões
	$grupo_botoes 				= tdClass::Criar("div");
	$grupo_botoes->class 		= "form-grupo-botao";
	$grupo_botoes->add($btn_gerar);
	
	$linha 						= tdClass::Criar("div");
	$linha->class 				= "row-fluid form_campos";
	
	$select_processo 			= tdClass::Criar("select");
	$select_processo->class 	= "form-control";
	$select_processo->id 		= "busca_farein";
	
	$coluna 				= tdClass::Criar("div");
	$coluna->class 			= "coluna";
	$coluna->data_ncolunas 	= 1;
	$coluna->add( Empresa::Filtro() );
	
	$lcodigo 				= tdClass::Criar("label");
	$lcodigo->for 			= "codigo";
	$lcodigo->class 		= "control-label";
	$lcodigo->add('C&oacute;digo');
	
	$ccodigo 				= tdClass::Criar("input");
	$ccodigo->type 			= "text";
	$ccodigo->class 		= "form-control";
	$ccodigo->id 			= "codigo";
	$ccodigo->name 			= "codigo";
	
	$coluna_codigo 					= tdClass::Criar("div");
	$coluna_codigo->class 			= "coluna";
	$coluna_codigo->data_ncolunas 	= 1;
	$coluna_codigo->add($lcodigo,$ccodigo);

	$coluna_pesquisa_empresa 				= tdClass::Criar("div");
	$coluna_pesquisa_empresa->class 		= "coluna";
	$coluna_pesquisa_empresa->data_ncolunas = 1;
	$coluna_pesquisa_empresa->id 			= "coluna-pesquisa-empresa";
	
	$linha->add($coluna,$coluna_pesquisa_empresa);
	
	$script = tdClass::Criar("script");
	$script->add('
		$(".alert").hide();
		$("#b-gerar").click(function(){
			gerar();
		});
		function gerar(){
			let farein = $("#retorno_empresa").val();
			if (farein == "" || farein == 0){
				$("#alert-gerar-relatorio-credor").show("200");
				setTimeout(function(){
					$("#alert-gerar-relatorio-credor").hide("200");
				},3000);
				return;
			}
			window.open(getURLProject([{key:"controller",value:"relatoriocredores"},{key:"farein", value: farein}]),"_blank");
		}
		$("#busca_contrato,#busca_nome,#busca_cpf").keypress(function(e) {
		  if ( e.which == 13 ){
			gerar();
		  }
		});
	');

	$alerta 				= tdClass::Criar("alert",array("Nenhuma Empresa Selecionada!"));
	$alerta->id				= "alert-gerar-relatorio-credor";
	$alerta->style 			= "display:none;";
	$alerta->type 			= "alert-danger";
	$alerta->alinhar 		= "left";
	$alerta->exibirfechar	= false;
	
	$grupo_botoes->add($alerta);
	$form->fieldset->add($grupo_botoes,$linha);
	$form_bloco->add($form);

	$pagina->add($form_bloco,$script);
	$pagina->mostrar();