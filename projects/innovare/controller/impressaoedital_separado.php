<?php
	set_time_limit(100000000);
	if (!isset($_GET["processo"])){
		echo 'Processo n&atilde;o encontrado';
		exit;
	}else{
		$processo = $_GET["processo"];
	}
	$razaosocial = "";
	$descricaoProcesso = "";
	if ($conn = Transacao::get()){		
		$sql = "SELECT comarca,juizo,cidade,logradouro,bairro,cep,telefone,estado,email,magistrado,escrivao,numeroprocesso,dipositivodecisao,resumopedidorecuperacao,tipoprocesso,complemento FROM td_processo WHERE id = $processo";
		$query = $conn->query($sql);
		if ($linha = $query->fetch()){
			$comarca 		= tdClass::Criar("persistent",array("td_comarca",$linha["comarca"]));
			$vara			= tdClass::Criar("persistent",array("td_juizo",$linha["juizo"]));
			$cidade			= tdClass::Criar("persistent",array("td_cidade",$linha["cidade"]));
			$estado			= tdClass::Criar("persistent",array("td_estado",$linha["estado"]));
			$logradouro		= $linha["logradouro"];
			$bairro			= $linha["bairro"];
			$cep			= $linha["cep"];
			$telefone		= $linha["telefone"];
			$email			= $linha["email"];
			$complemento	= $linha["complemento"];
			$magistrado		= tdClass::Criar("persistent",array("td_magistrado",$linha["magistrado"]));
			$escrivao		= $linha["escrivao"];
			$numeroprocesso			= $linha["numeroprocesso"];
			$resumopedidorecuperacao = $linha["resumopedidorecuperacao"];
			$dipositivodecisao			= $linha["dipositivodecisao"];
			if ($linha["tipoprocesso"] == 16){ // Recupera&ccedil;&atilde;o
				$descricaoProcesso = "RECUPERANDA";
				$sql = "SELECT razaosocial FROM td_recuperanda WHERE processo = $processo and id={$_GET["recuperanda"]}";
				$query = $conn->query($sql);
				if ($linha = $query->fetch()){
					$razaosocial =  $linha["razaosocial"];
					
					echo '
								<p style="text-align:justify">RECUPERA&Ccedil;&Atilde;O JUDICIAL DE '.strtoupper($razaosocial).'. EDITAL DO ART. 52, &sect; 1&ordm;, DA LEI 11.101/2005<br />
								ESTADO DE SANTA CATARINA / PODER JUDICI&Aacute;RIO <br />
								COMARCA DE '.strtoupper($comarca->contexto->descricao).' / '.strtoupper($vara->contexto->descricao).' DE '.strtoupper($cidade->contexto->nome).' <br />
								'.$logradouro.', '.($complemento!=""?$complemento.", ":" ").$bairro.' - CEP '.$cep.', Fone: '.$telefone.', '.$cidade->contexto->nome.'/'.$estado->contexto->sigla.' -&nbsp;&nbsp; E-mail: '.$email.'<br />
								JUIZ(&Iacute;ZA) DE DIREITO DA '.strtoupper($vara->contexto->descricao).' DE '.strtoupper($cidade->contexto->nome).' <br />
								JUIZ(&Iacute;ZA) DE DIREITO '.strtoupper($magistrado->contexto->nome).' <br />
								ESCRIV&Atilde;O(&Atilde;) JUDICIAL '.strtoupper($escrivao).' <br />EDITAL DE CONHECIMENTO DE TERCEIROS E INTERESSADOS <br />
								Autos n.&deg; '.$numeroprocesso.' <br />
								<strong>Requerente(s): '.strtoupper($razaosocial).'</strong><br />
								
									Conte&uacute;do e Objetivo: &quot;Em cumprimento ao disposto no &sect; 1&ordm; do artigo 52 da Lei 11.101/2005, serve o presente Edital para dar conhecimento a todos os credores e 
									demais interessados que o(a) MM. Juiz(&iacute;za) da <strong>'.$vara->contexto->descricao.' de '.$cidade->contexto->nome.'</strong> - Santa Catarina deferiu o processamento da recupera&ccedil;&atilde;o judicial 
									requerida por <strong>'.strtoupper($razaosocial).'</strong>. Ficam os credores advertidos de que, pelo disposto no &sect; 1&ordm; do artigo 7&ordm; da Lei 11.101/2005, ter&atilde;o 
									o prazo de 15 (quinze) dias a contar da publica&ccedil;&atilde;o deste Edital para apresentar diretamente &agrave; administradora judicial suas habilita&ccedil;&otilde;es ou suas 
									diverg&ecirc;ncias quanto aos cr&eacute;ditos relacionados, de modo digital, no <em>site </em><a href="http://www.innovareadministradora.com.br/documento.php" target="_blank">http://www.innovareadministradora.com.br/documento.php</a>. 
									Endere&ccedil;o atual da administradora judicial nomeada: Innovare - Administradora em Recupera&ccedil;&atilde;o e Fal&ecirc;ncia SS - ME, representada por seus s&oacute;cios MAURICIO COLLE DE FIGUEIREDO e FL&Aacute;VIO CARLOS,
									situada &agrave; Travessa Germano Magrin, n.&ordm; 100, sala 407, Edif&iacute;cio Parthenon, Centro, Crici&uacute;ma/SC, CEP: 88802-090, fones: (48) 3413-8211/99757977/99783115. 
									Os credores poder&atilde;o acessar o site <a href="http://www.innovareadministradora.com.br/" target="_blank">http://www.innovareadministradora.com.br</a> para demais 
									informa&ccedil;&otilde;es.&nbsp;'.utf8_decode("Os credores ficam advertidos, ainda, que poder&atilde;o opor obje&ccedil;&otilde;es ao plano de recupera&ccedil;&atilde;o judicial a ser apresentado pela sociedade recuperanda, nos termos dos art. 55 da Lei n. 11.101/2005. Cont&eacute;m o presente Edital o resumo do pedido, a decis&atilde;o de deferimento da recupera&ccedil;&atilde;o judicial e a rela&ccedil;&atilde;o nominal de credores, com a discrimina&ccedil;&atilde;o do valor atualizado e a classifica&ccedil;&atilde;o de cada cr&eacute;dito, bem como a advert&ecirc;ncia para apresenta&ccedil;&atilde;o de habilita&ccedil;&atilde;o, diverg&ecirc;ncia e obje&ccedil;&atilde;o ao plano, consoante determina o &sect; 1&ordm; do artigo 52 da Lei n. 11.101/2005").'. <strong><u>Resumo do pedido</u></strong>:&nbsp;'.str_replace(array("<p>","</p>"),"",$resumopedidorecuperacao).'
									<strong><u>Dispositivo da decis&atilde;o de deferimento do processamento</u></strong>: '.str_replace(array("<p>","</p>"),"",$dipositivodecisao).' <strong>Faz saber, ainda, que a(s) sociedade(s) empres&aacute;ria(s) recuperanda(s) apresentam a seguinte 
									rela&ccedil;&atilde;o de credores</strong>: RELA&Ccedil;&Atilde;O DE CREDORES DA RECUPERANDA <strong>'.strtoupper($razaosocial).'</strong>: 
					';					
				}				
			}else if ($linha["tipoprocesso"] == 19){ // Fal&ecirc;ncia
				$descricaoProcesso = "FALIDA";
				$sql = "SELECT razaosocial FROM td_falencia WHERE processo = $processo";
				$query = $conn->query($sql);
				if ($linha = $query->fetch()){
					$razaosocial =  $linha["razaosocial"];
					

					echo '
								<p style="text-align:justify">FAL&Eacute;NCIA DE '.strtoupper($razaosocial).'. EDITAL DO ART. 99, PARAGRAFO &Uacute;NICO, DA LEI N. 11.101/2005<br />
								ESTADO DE SANTA CATARINA / PODER JUDICI&Aacute;RIO <br />
								COMARCA DE '.strtoupper($comarca->contexto->descricao).' / '.strtoupper($vara->contexto->descricao).' DE '.strtoupper($cidade->contexto->nome).' <br />
								'.$logradouro.', '.($complemento!=""?$complemento.", ":" ").$bairro.' - CEP '.$cep.', Fone: '.$telefone.', '.$cidade->contexto->nome.'/'.$estado->contexto->sigla.' -&nbsp;&nbsp; E-mail: '.$email.'<br />
								JUIZ(&Iacute;ZA) DE DIREITO DA '.strtoupper($vara->contexto->descricao).' DE '.strtoupper($cidade->contexto->nome).' <br />
								JUIZ(&Iacute;ZA) DE DIREITO '.strtoupper($magistrado->contexto->nome).' <br />
								ESCRIV&Atilde;O(&Atilde;) JUDICIAL '.strtoupper($escrivao).' <br />EDITAL DE CONHECIMENTO DE TERCEIROS E INTERESSADOS <br />
								Autos n.&deg; '.$numeroprocesso.' <br />
								<strong>Requerente(s): '.strtoupper($razaosocial).'</strong><br />
								
								Conte&uacute;do e Objetivo: "Em cumprimento ao disposto no artigo 99, par&aacute;grafo &uacute;nico, da Lei N. 11.101/2005, serve o presente Edital para dar conhecimento a todos os credores e 
								demais interessados que o(a) MM. Juiz(&iacute;za) da '.$vara->contexto->descricao.' - Santa Catarina, por senten&ccedil;a datada de 26/05/2015, decretou a autofal&ecirc;ncia de '.strtoupper($razaosocial).', 
								com endere&ccedil;o &agrave; Rodovia Luiz Rosso, KM 5, n&ordm; 3.570, Morro Estev&atilde;o, Crici&uacute;ma/SC. Ficam os credores advertidos de que, pelo disposto no &sect; 1&ordm; do artigo 7&ordm; da Lei N. 11.101/2005, ter&atilde;o o prazo de 15 (quinze) dias, 
								a contar da publica&ccedil;&atilde;o deste Edital, para apresentar diretamente &agrave; administradora judicial suas habilita&ccedil;&otilde;es ou suas diverg&ecirc;ncias quanto aos cr&eacute;ditos relacionados, de modo digital, no 
								site http://www.innovareadministradora.com.br/documento.php. Endere&ccedil;o atual da administradora judicial nomeada: Innovare - Administradora em Recupera&ccedil;&atilde;o e Fal&ecirc;ncia SS - ME, 
								representada por seus s&oacute;cios MAURICIO COLLE DE FIGUEIREDO e FL&Aacute;VIO CARLOS, situada &agrave; Travessa Germano Magrin, n.&ordm; 100, sala 407, Edif&iacute;cio Parthenon, Centro, Crici&uacute;ma/SC, CEP: 88802-090, fones: (48) 3413-8211/99757977/99783115. 
								Os credores poder&atilde;o acessar o site http://www.innovareadministradora.com.br para demais informa&ccedil;&otilde;es.  Cont&eacute;m o presente Edital a &iacute;ntegra da decis&atilde;o que decreta a fal&ecirc;ncia e a rela&ccedil;&atilde;o de credores elaborada pela falida, com a discrimina&ccedil;&atilde;o do 
								valor atualizado e a classifica&ccedil;&atilde;o de cada cr&eacute;dito, bem como a advert&ecirc;ncia para apresenta&ccedil;&atilde;o de habilita&ccedil;&atilde;o e diverg&ecirc;ncia, consoante determina o &sect; 1&ordm; do artigo 7&ordm; c/c o par&aacute;grafo &uacute;nico do artigo 99, ambos da Lei n. 11.101/2005. 
								<u><b>&Iacute;ntegra da decis&atilde;o que decreta a fal&ecirc;ncia:</b></u> "'.str_replace(array("<p>","</p>"),"",$dipositivodecisao).'". Faz saber, ainda, que a(s) sociedade(s) empres&aacute;ria(s) falida(s) apresentam a seguinte rela&ccedil;&atilde;o de credores: 
								RELA&Ccedil;&Atilde;O DE CREDORES DA FALIDA '.strtoupper($razaosocial).':
					';					
				}	
			}else if ($linha["tipoprocesso"] == 18){ // Insolvencia
				
			}else{
				echo 'Nenhum tipo de processo encontrado.';
				exit;
			}
		}
		
		
	}


		$sql = "SELECT a.id,a.descricao FROM td_classificacao a WHERE exists(SELECT 1 FROM td_relacaocredores b WHERE b.classificacao = a.id AND b.processo = $processo AND (origemcredor is null OR origemcredor = 1) AND b.farein = {$_GET["recuperanda"]}) ORDER BY a.ordem ASC ";
		$query = $conn->query($sql);
		$tr = "";
		$total = 0;
		$totalReal = $totalDollar = $totalEuro = 0;
		while ($linha = $query->fetch()){
			$valorReal = $valorDollar = $valorEuro = 0;
			echo ' <strong><u>'.strtoupper($linha["descricao"]).'</u>&nbsp;('.($linha["id"]==1?"NOME - CPF - VALOR":"NOME - CPF/CNPJ - VALOR").'): </strong>';
			$sql_credores = "SELECT nome,cpf,cnpj,valor,tipo,moeda FROM td_relacaocredores WHERE processo = $processo AND classificacao = {$linha["id"]} AND farein = {$_GET["recuperanda"]} AND (origemcredor is null OR origemcredor = 1 or origemcredor is null) GROUP BY cpf,cnpj ORDER BY nome";
			$query_credores = $conn->query($sql_credores);
			$moeda_atual = 0;
			$moeda_ultima = "";
			while ($linha_credores = $query_credores->fetch()){
				$moeda = tdClass::Criar("persistent",array("td_moeda",$linha_credores["moeda"]));
				switch($linha_credores["moeda"]){
					case 1: $simboloMoeda = "R$"; break;
					case 2: $simboloMoeda = "US$"; break;
					case 3: $simboloMoeda = "EUR€"; break;
						
				}

				echo " " . strtoupper($linha_credores["nome"] . " - ". ($linha_credores["tipo"]==1?$linha_credores["cnpj"]:$linha_credores["cpf"]) . " - ".($simboloMoeda)." " . moneyToFloat($linha_credores["valor"],true) . ";");
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
				$tr .= '<tr><td width="70%" style="border:1px solid #000;font-weight:bold;">'.$linha["descricao"].' ( EUR€ )</td><td width="30%" style="text-align:right;border:1px solid #000;">EUR€'.moneyToFloat($valorEuro,true).'</td></tr>';
			
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
					<td width="70%" style="border:1px solid #000;font-weight:bold;">TOTAL ( US$ )</td><td width="30%" style="text-align:right;border:1px solid #000;">R$ '.moneyToFloat($totalDollar,true).'</td>
				</tr>':'').'
				'.($totalEuro>0?'<tr>
					<td width="70%" style="border:1px solid #000;font-weight:bold;">TOTAL ( EUR€ )</td><td width="30%" style="text-align:right;border:1px solid #000;">R$ '.moneyToFloat($totalEuro,true).'</td>
				</tr>':'').'				
			</table>
		</center>';
?>