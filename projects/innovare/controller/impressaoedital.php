<html>
	<head>
		<title>Impress&atilde;o de Edital do Processo</title>
		<style>
			body,table{
				font-family:Calibri;
				font-size:11;
			}
			.texto-edital{
				text-align:justify;
			}
		</style>
	</head>
	<body>
		<?php
			set_time_limit(100000000);
			if (!isset($_GET["farein"])){
				echo 'Não foram encontrados dados com esses parametros';
				exit;
			}else{
				$farein_array 	= explode("^",$_GET["farein"]);
				$farein 		= $farein_array[0];
				$processo 		= $farein_array[1];
				$tipo_farein 	= $farein_array[2];
			}

			$razaosocial 		= "";
			$descricaoProcesso 	= "";
			if ($conn = Transacao::get()){		
				$sql = "SELECT comarca,juizo,cidade,logradouro,bairro,cep,telefone,estado,email,magistrado,escrivao,numeroprocesso,dipositivodecisao,resumopedidorecuperacao,tipoprocesso,complemento FROM td_processo WHERE id = $processo;";
				$query = $conn->query($sql);
				if ($linha = $query->fetch()){
					$is_error		= false;
					$object_error	= '';

					$comarca 		= tdc::p('td_comarca',$linha["comarca"]);
					$vara			= tdc::p('td_juizo',$linha["juizo"]);
					$cidade			= tdc::p('td_cidade',$linha["cidade"]);
					$estado			= tdc::p('td_estado',$linha["estado"]);
					$magistrado		= tdc::p('td_magistrado',$linha["magistrado"]);

					if (!$comarca->hasData()){
						$is_error = true;
						showMessage('Processo sem Comarca cadastrada.');
					}
					if (!$vara->hasData()){
						$is_error = true;
						showMessage('Processo sem Vara cadastrada.');
					}					
					if (!$cidade->hasData()){
						$is_error = true;
						showMessage('Processo sem Cidade cadastrada.');
					}
					if (!$estado->hasData()){
						$is_error = true;
						showMessage('Processo sem Estado cadastrada.');
					}
					if (!$magistrado->hasData()){
						$is_error = true;
						showMessage('Processo sem Magistrado cadastrada.');
					}

					if ($is_error){						
						exit;
					}

					
					$logradouro		= $linha["logradouro"];
					$bairro			= $linha["bairro"];
					$cep			= $linha["cep"];
					$telefone		= $linha["telefone"];
					$email			= $linha["email"];
					$complemento	= $linha["complemento"];
					
					$escrivao		= $linha["escrivao"];
					$numeroprocesso			= $linha["numeroprocesso"];
					$resumopedidorecuperacao = $linha["resumopedidorecuperacao"];
					$dipositivodecisao			= $linha["dipositivodecisao"];
					if ($tipo_farein == "RE"){ // Recupera&ccedil;&atilde;o
						$descricaoProcesso = "RECUPERANDA";
						$fareinID = 16;

						// Carrega Lista
						$sql = "SELECT regfilho FROM ".LISTA." WHERE entidadepai = 15 and entidadefilho = 16 and regpai = {$processo} and regfilho = {$farein};";
						$query = $conn->query($sql);
						$reg_fiho_recuperanda = $query->fetch();
						if ($query->rowCount() <= 0){
							echo utf8_decode('<div class="alert alert-danger">Não foi possível encontrar credores. Rever o processo de importação!</div>');
							exit;
						}
						
						$sql = "SELECT razaosocial FROM td_recuperanda WHERE id = " . $reg_fiho_recuperanda["regfilho"];
						$query = $conn->query($sql);

						if ($linha = $query->fetch()){
							$razaosocial =  $linha["razaosocial"];

							echo '
								<center><b><p style="text-align:center">RECUPERA&Ccedil;&Atilde;O JUDICIAL DE '.strtoupper($razaosocial).' <br/>
								EDITAL DO ART. 7&ordm;, &sect; 1&ordm;, C/C ART. 52, &sect; 1&ordm;, AMBOS DA LEI N&ordm; 11.101/2005<br />
								ESTADO DE SANTA CATARINA / PODER JUDICI&Aacute;RIO <br />
								COMARCA DE '.strtoupper($comarca->contexto->descricao).' / '.strtoupper($vara->contexto->descricao).' DE '.strtoupper($cidade->contexto->nome).' <br />
								'.$logradouro.', '.($complemento!=""?$complemento.", ":" ").$bairro.' - CEP '.$cep.', Fone: '.$telefone.', '.$cidade->contexto->nome.'/'.$estado->contexto->sigla.' -&nbsp;&nbsp; E-mail: '.$email.'<br />
								JUIZ(&Iacute;ZA) DE DIREITO DA '.strtoupper($vara->contexto->descricao).' DE '.strtoupper($cidade->contexto->nome).' <br />
								JUIZ(&Iacute;ZA) DE DIREITO '.strtoupper($magistrado->contexto->nome).' <br />
								ESCRIV&Atilde;O(&Atilde;) JUDICIAL '.strtoupper($escrivao).' <br />EDITAL DE CONHECIMENTO DE TERCEIROS E INTERESSADOS <br />
								AUTOS N.&deg; '.$numeroprocesso.' </center>
								
								<p class="texto-edital">
								Conte&uacute;do e Objetivo: &quot;Em cumprimento ao disposto no &sect; 1&ordm; do artigo 52 da Lei 11.101/2005, serve o presente Edital para dar conhecimento a todos os credores e 
								demais interessados que o(a) MM. Juiz(&iacute;za) da <strong>'.$vara->contexto->descricao.'</strong> da comarca de <strong>'.$cidade->contexto->nome.'</strong> - Santa Catarina deferiu o processamento da recupera&ccedil;&atilde;o judicial 
								requerida por <strong>'.strtoupper($razaosocial).'</strong>. Ficam os credores advertidos de que, pelo disposto no &sect; 1&ordm; do artigo 7&ordm; da Lei 11.101/2005, ter&atilde;o 
								o prazo de 15 (quinze) dias a contar da publica&ccedil;&atilde;o deste Edital para apresentar diretamente &agrave; administradora judicial suas habilita&ccedil;&otilde;es ou suas 
								diverg&ecirc;ncias quanto aos cr&eacute;ditos relacionados, de modo digital, no <em>site </em><a href="http://www.innovareadministradora.com.br/enviodocumentos" target="_blank">http://www.innovareadministradora.com.br/enviodocumentos</a>. 
								Endere&ccedil;o atual da administradora judicial nomeada: Innovare - Administradora em Recupera&ccedil;&atilde;o e Fal&ecirc;ncia SS - ME, representada por seus s&oacute;cios MAURICIO COLLE DE FIGUEIREDO e FL&Aacute;VIO CARLOS,
								situada &agrave; Travessa Germano Magrin, n.&ordm; 100, sala 407, Edif&iacute;cio Parthenon, Centro, Crici&uacute;ma/SC, CEP: 88802-090, fones: (48) 3413-8211/99757977/99783115. 
								Os credores poder&atilde;o acessar o site <a href="http://www.innovareadministradora.com.br/" target="_blank">http://www.innovareadministradora.com.br</a> para demais 
								informa&ccedil;&otilde;es.&nbsp;'.utf8_decode("Os credores ficam advertidos, ainda, que poder&atilde;o opor obje&ccedil;&otilde;es ao plano de recupera&ccedil;&atilde;o judicial a ser apresentado pela sociedade recuperanda, nos termos dos art. 55 da Lei n. 11.101/2005. Cont&eacute;m o presente Edital o resumo do pedido, a decis&atilde;o de deferimento da recupera&ccedil;&atilde;o judicial e a rela&ccedil;&atilde;o nominal de credores, com a discrimina&ccedil;&atilde;o do valor atualizado e a classifica&ccedil;&atilde;o de cada cr&eacute;dito, bem como a advert&ecirc;ncia para apresenta&ccedil;&atilde;o de habilita&ccedil;&atilde;o, diverg&ecirc;ncia e obje&ccedil;&atilde;o ao plano, consoante determina o &sect; 1&ordm; do artigo 52 da Lei n. 11.101/2005").'. <strong><u>Resumo do pedido</u></strong>:&nbsp;'.str_replace(array("<p>","</p>"),"",$resumopedidorecuperacao).'
								<strong><u>Dispositivo da decis&atilde;o de deferimento do processamento</u></strong>: '.str_replace(array("<p>","</p>"),"",$dipositivodecisao).' <strong>Faz saber, ainda, que a(s) sociedade(s) empres&aacute;ria(s) recuperanda(s) apresentam a seguinte 
								rela&ccedil;&atilde;o de credores</strong>: RELA&Ccedil;&Atilde;O DE CREDORES DA RECUPERANDA <strong>'.strtoupper($razaosocial).'</strong>: 
							';					
						}				
					}else if ($tipo_farein == "FA"){ // Fal&ecirc;ncia
						$descricaoProcesso = "FALIDA";
						$fareinID = 19;
						// Carrega Lista
						$sql = "SELECT regfilho FROM ".LISTA." WHERE entidadepai = 15 and entidadefilho = 19 and regpai = {$processo} and regfilho = {$farein}";
						$query = $conn->query($sql);
						$reg_fiho_falida = $query->fetch();
						
						$sql = "SELECT razaosocial FROM td_falencia WHERE id = " . $reg_fiho_falida["regfilho"];
						$query = $conn->query($sql);
						if ($linha = $query->fetch()){
							$razaosocial =  $linha["razaosocial"];

							echo '
								<center><b><p style="text-align:center">FAL&Eacute;NCIA DE '.strtoupper($razaosocial).'. EDITAL DO ART. 99, PARAGRAFO &Uacute;NICO, DA LEI N. 11.101/2005<br />
								ESTADO DE SANTA CATARINA / PODER JUDICI&Aacute;RIO <br />
								COMARCA DE '.strtoupper($comarca->contexto->descricao).' / '.strtoupper($vara->contexto->descricao).' DE '.strtoupper($cidade->contexto->nome).' <br />
								'.$logradouro.', '.($complemento!=""?$complemento.", ":" ").$bairro.' - CEP '.$cep.', Fone: '.$telefone.', '.$cidade->contexto->nome.'/'.$estado->contexto->sigla.' -&nbsp;&nbsp; E-mail: '.$email.'<br />
								JUIZ(&Iacute;ZA) DE DIREITO DA '.strtoupper($vara->contexto->descricao).' DE '.strtoupper($cidade->contexto->nome).' <br />
								JUIZ(&Iacute;ZA) DE DIREITO '.strtoupper($magistrado->contexto->nome).' <br />
								ESCRIV&Atilde;O(&Atilde;) JUDICIAL '.strtoupper($escrivao).' <br />EDITAL DE CONHECIMENTO DE TERCEIROS E INTERESSADOS <br />
								Autos n.&deg; '.$numeroprocesso.' </center><br />
								
								<p class="texto-edital">
								Conte&uacute;do e Objetivo: "Em cumprimento ao disposto no artigo 99, par&aacute;grafo &uacute;nico, da Lei N. 11.101/2005, serve o presente Edital para dar conhecimento a todos os credores e 
								demais interessados que o(a) MM. Juiz(&iacute;za) da '.$vara->contexto->descricao.' - Santa Catarina, por senten&ccedil;a datada de 26/05/2015, decretou a autofal&ecirc;ncia de '.strtoupper($razaosocial).', 
								com endere&ccedil;o &agrave; Rodovia Luiz Rosso, KM 5, n&ordm; 3.570, Morro Estev&atilde;o, Crici&uacute;ma/SC. Ficam os credores advertidos de que, pelo disposto no &sect; 1&ordm; do artigo 7&ordm; da Lei N. 11.101/2005, ter&atilde;o o prazo de 15 (quinze) dias, 
								a contar da publica&ccedil;&atilde;o deste Edital, para apresentar diretamente &agrave; administradora judicial suas habilita&ccedil;&otilde;es ou suas diverg&ecirc;ncias quanto aos cr&eacute;ditos relacionados, de modo digital, no 
								site http://www.innovareadministradora.com.br/enviodocumentos. Endere&ccedil;o atual da administradora judicial nomeada: Innovare - Administradora em Recupera&ccedil;&atilde;o e Fal&ecirc;ncia SS - ME, 
								representada por seus s&oacute;cios MAURICIO COLLE DE FIGUEIREDO e FL&Aacute;VIO CARLOS, situada &agrave; Travessa Germano Magrin, n.&ordm; 100, sala 407, Edif&iacute;cio Parthenon, Centro, Crici&uacute;ma/SC, CEP: 88802-090, fones: (48) 3413-8211/99757977/99783115. 
								Os credores poder&atilde;o acessar o site http://www.innovareadministradora.com.br para demais informa&ccedil;&otilde;es.  Cont&eacute;m o presente Edital a &iacute;ntegra da decis&atilde;o que decreta a fal&ecirc;ncia e a rela&ccedil;&atilde;o de credores elaborada pela falida, com a discrimina&ccedil;&atilde;o do 
								valor atualizado e a classifica&ccedil;&atilde;o de cada cr&eacute;dito, bem como a advert&ecirc;ncia para apresenta&ccedil;&atilde;o de habilita&ccedil;&atilde;o e diverg&ecirc;ncia, consoante determina o &sect; 1&ordm; do artigo 7&ordm; c/c o par&aacute;grafo &uacute;nico do artigo 99, ambos da Lei n. 11.101/2005. 
								<u><b>&Iacute;ntegra da decis&atilde;o que decreta a fal&ecirc;ncia:</b></u> "'.str_replace(array("<p>","</p>"),"",$dipositivodecisao).'". Faz saber, ainda, que a(s) sociedade(s) empres&aacute;ria(s) falida(s) apresentam a seguinte rela&ccedil;&atilde;o de credores: 
								RELA&Ccedil;&Atilde;O DE CREDORES DA FALIDA '.strtoupper($razaosocial).':
							';					
						}	
					}else if ($tipo_farein == "IN"){ // Insolvencia
						$fareinID = 18;
					}else{
						echo 'Nenhum tipo de processo encontrado.';
						exit;
					}
				}else{
					echo 'Credores não encontrado.';
					exit;					
				}
				
				
			}
				$sqlListaCredores = "SELECT regfilho FROM td_lista c WHERE c.entidadepai = {$fareinID} and c.entidadefilho = 20 and c.regpai = {$farein};";				
				$queryListaCredores = $conn->query($sqlListaCredores);
				$idsListaCredores = "";
				while ($linhaListaCredores = $queryListaCredores->fetch()){
					$idsListaCredores .= ($idsListaCredores==""?"":",") . $linhaListaCredores["regfilho"];
				}
				#echo ">>>>" . $idsListaCredores;
				if ($idsListaCredores == ""){
					$bootstrap = tdClass::Criar("link");
					$bootstrap->href = PATH_LIB . 'bootstrap/3.3.1/css/bootstrap.css';
					$bootstrap->rel = 'stylesheet';
					$bootstrap->mostrar();
					
					echo utf8_decode('<div class="alert alert-warning" role="alert"><center><b>AVISO!</b> Não há <i>Relação de Credores</i> vinculada.</center> </div>');
					exit;
				} 
				$sql = "SELECT a.id,a.descricao FROM td_classificacao a WHERE exists(SELECT 1 FROM td_relacaocredores b WHERE b.id in ({$idsListaCredores}) AND  b.classificacao = a.id AND b.farein = {$farein} AND b.processo = {$processo} AND (b.origemcredor is null OR b.origemcredor = 1)) ORDER BY a.ordem ASC ";;
				//echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>$sql<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<";
				$query = $conn->query($sql);
				$tr = "";
				$total = 0;
				$totalReal = $totalDollar = $totalEuro = 0;
				while ($linha = $query->fetch()){
					$valorReal = $valorDollar = $valorEuro = 0;
					echo ' <strong><u>'.strtoupper($linha["descricao"]).'</u>&nbsp;('.($linha["id"]==1?"NOME - CPF - VALOR":"NOME - CPF/CNPJ - VALOR").'): </strong>';
					$sql_credores = "SELECT nome,cpf,cnpj,valor,tipo,moeda FROM td_relacaocredores WHERE farein = {$farein} AND processo = {$processo} AND classificacao = {$linha["id"]} AND (origemcredor is null OR origemcredor = 1) ORDER BY nome";

					$query_credores = $conn->query($sql_credores);
					$moeda_atual = $valor = 0;
					$moeda_ultima = "";
					while ($linha_credores = $query_credores->fetch()){
						$moeda = tdClass::Criar("persistent",array("td_moeda",$linha_credores["moeda"]));
						$simboloMoeda = "";
						switch($linha_credores["moeda"]){
							case 1: $simboloMoeda = "R$"; break;
							case 2: $simboloMoeda = "US$"; break;
							case 3: $simboloMoeda = "EUR€"; break;
								
						}
						if ($linha_credores["tipo"] == '' || $linha_credores["tipo"] == 0){
							$tipocredor = '';
						}else{
							$tipocredor = ($linha_credores["tipo"]==1?$linha_credores["cnpj"]:$linha_credores["cpf"]) . " - ";
						}
						echo " " . strtoupper($linha_credores["nome"] . " - " . $tipocredor . ($simboloMoeda).moneyToFloat($linha_credores["valor"],true) . ";");
						if ($linha_credores["moeda"] == 1){
							$valorReal += $linha_credores["valor"];
						}
						if ($linha_credores["moeda"] == 2){
							$valorDollar += $linha_credores["valor"];
						}
						if ($linha_credores["moeda"] == 3){
							$valorEuro += $linha_credores["valor"];
						}				
						#$moeda_atual = $linha_credores["moeda"];
						#if ($moeda_ultima != $moeda_atual){					
					#		if ($moeda_ultima != "")
					#		$valor = 0;
					#	}	
						$valor += $linha_credores["valor"];
					#	$moeda_ultima = $moeda_atual;
					}
					if 	($valorReal > 0)		
						$tr .= '<tr><td width="70%" style="border:1px solid #000;font-weight:bold;">'.$linha["descricao"].' ( R$ )</td><td width="30%" style="text-align:right;border:1px solid #000;">R$'.moneyToFloat($valorReal,true).'</td></tr>';
					if 	($valorDollar > 0)		
						$tr .= '<tr><td width="70%" style="border:1px solid #000;font-weight:bold;">'.$linha["descricao"].' ( US$ )</td><td width="30%" style="text-align:right;border:1px solid #000;">US$'.moneyToFloat($valorDollar,true).'</td></tr>';
					if 	($valorEuro > 0)		
						$tr .= '<tr><td width="70%" style="border:1px solid #000;font-weight:bold;">'.$linha["descricao"].' ( EUR&#8364; )</td><td width="30%" style="text-align:right;border:1px solid #000;">EUR&#8364;'.moneyToFloat($valorEuro,true).'</td></tr>';
					
					$totalReal += $valorReal;
					$totalDollar += $valorDollar;
					$totalEuro += $valorEuro;
				}
		echo '
					</p>
		';
		echo '	<center>
					<h3><u>QUADRO RESUMO DO VALOR TOTAL DEVIDO PELA '.$descricaoProcesso.' POR CLASSE DE CREDORES</u></h3>
					<table  cellspacing = "0" width="80%">
						'.$tr.'
						'.($totalReal>0?'<tr>
							<td width="70%" style="border:1px solid #000;font-weight:bold;">TOTAL ( R$ )</td><td width="30%" style="text-align:right;border:1px solid #000;">R$ '.moneyToFloat($totalReal,true).'</td>
						</tr>':'').'
						'.($totalDollar>0?'<tr>
							<td width="70%" style="border:1px solid #000;font-weight:bold;">TOTAL ( US$ )</td><td width="30%" style="text-align:right;border:1px solid #000;">US$ '.moneyToFloat($totalDollar,true).'</td>
						</tr>':'').'
						'.($totalEuro>0?'<tr>
							<td width="70%" style="border:1px solid #000;font-weight:bold;">TOTAL ( EUR&#8364; )</td><td width="30%" style="text-align:right;border:1px solid #000;">R&#8364; '.moneyToFloat($totalEuro,true).'</td>
						</tr>':'').'				
					</table>
				</center>';
		?>
	</body>
</html>	