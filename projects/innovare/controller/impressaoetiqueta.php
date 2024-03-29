<html>
	<head>
	<title>Innovare - Impress&atilde;o de Etiquetas</title>
	<style rel="stylesheet" type="text/css">
		body{
			margin:0px;
			padding:0px;
		}		
		@media screen{
			.pagina{
				width:21cm;
				height:29cm;
				float:left;
			}
		}		
		@media print{
			.pagina{
				width:21cm;
				height:29cm;
				float:left;
			}			
		}
		.etiqueta{
			float:left;
			width:9cm;
			height:3cm;
			margin:17px 15px;
		}
		.etiqueta tr td{
			font-size:10px;
			font-family:Arial;
		}
	</style>
	<body>
		<?php
			if ($conn = Transacao::get()){
				$where_codigo = "";
				if (isset($_GET["codigo"])){
					if ($_GET["codigo"] != ""){
						$codigo = $_GET["codigo"];
						if (strpos($codigo,":") > 0){
							$c = explode(":",$codigo);
							$inicio = $c[0];
							$fim = $c[1];
							$where_codigo = "AND codigo BETWEEN $inicio and $fim ";
						}else if(strpos($codigo,"-") > 0){
							$codigos = explode("-",$codigo);
							$where_codigo = "AND codigo in (";
							$i = "";
							foreach($codigos as $c){
								if ($c != "")
									$i .= ($i=="")?$c:",".$c;
							}
							$where_codigo .= $i . ") ";
						}else{
							$where_codigo = "AND (codigo = " . $_GET["codigo"] . ")";
						}
					}
				}
				
				$farein_array = explode("^",$_GET["farein"]);
				$processo 		= $farein_array[1];
				$farein 		= $farein_array[0];
				$tipo_farein 	= $farein_array[2];
			
				$sql = "SELECT * FROM td_relacaocredores WHERE processo = {$processo} and farein = {$farein} {$where_codigo} order by classificacao,nome asc";				
				$result = $conn->query($sql);
				$cont_pagina = 0;
				foreach ($result as $key => $value){
					$credor = strtoupper($value["nome"]);				
					$classificacao	= tdClass::Criar("persistent",array("td_classificacao",$value["classificacao"]))->contexto->descricao;
					$cidade			= tdClass::Criar("persistent",array("td_cidade",$value["cidade"]))->contexto->nome;
					$estado			= tdClass::Criar("persistent",array("td_estado",$value["estado"]))->contexto->sigla;
					$cont_pagina++;
					if ($cont_pagina >= 8){
						echo "</div><div style='page-break-after: always;'>&nbsp;</div>";
						$cont_pagina = 1;
					}					
					if ($cont_pagina == 1) echo '<div class="pagina">';
					echo '							
							<table class="etiqueta">
								<tr>
									<td width="30%"><img src="tema/padrao/logo.png" width="100" style="margin:5px;"/></td>
									<td width="70%">
										<strong>&nbsp;'.completaString($value["codigo"],5).' - '.substr($credor,0,40).'</strong>
										<br />&nbsp;'.substr($value["logradouro"],0,40).','.substr($value["numero"],0,5).'
										<br />&nbsp;'.substr(trim($value["bairro"]),0,20).' - '.$cidade.'/'.$estado.'
										<br />&nbsp;Cep: '.$value["cep"].'
									</td>
								</tr>
							</table>
					';
					echo '
							<table class="etiqueta">
								<tr>
									<td width="30%"><img src="tema/padrao/logo.png" width="100" style="margin:5px;"/></td>
									<td width="70%">
										<strong>INNOVARE - Administradora em Recupera&ccedil;&atilde;o e Fal&ecirc;ncia</strong>
										<br />Travessa Germano Magrin, 100 - Ed. Parthenon
										<br />Sala 407 - Centro - Crici&uacute;ma/SC
										<br />Cep: 88.802-090
									</td>
								</tr>
							</table>
					';
				}				
			}	
		?>
	</body>
</html>