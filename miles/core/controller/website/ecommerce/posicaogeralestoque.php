<?php	

	$produto 			= tdc::r("produto");
	$quantidade 		= tdc::r("quantidade");
	$operacao 			= (int)tdc::p("td_ecommerce_tipooperacaoestoque",tdc::r("operacao"))->tipo == 1?"-":"+";
	$variacaoproduto	= tdc::r("variacaoproduto");
	
	if ($produto == ""){
		$produto = tdc::p("td_ecommerce_tamanhoproduto",$variacaoproduto)->td_produto;
	}

	$where = " WHERE td_produto = {$produto} ";
	if ($variacaoproduto != ""){
		$where .= " AND td_variacaoproduto = {$variacaoproduto} ";
		$variacaoinsertcampo = "td_variacaoproduto";
		$variacaoinsertvalor = $variacaoproduto;
	}else{
		$variacaoinsertcampo =  $variacaoinsertvalor = "";
	}

	$conn = Transacao::Get();
	$sql = "SELECT 1 FROM td_ecommerce_posicaogeralestoque {$where} LIMIT 1";
	$query = $conn->query($sql);

	if ($query->rowCount() > 0){
		$sqlUpdate = "UPDATE td_ecommerce_posicaogeralestoque SET saldo = (saldo " . $operacao . " " . $quantidade . ") , datahora = now() {$where} ";
		$queryUpdade = $conn->query($sqlUpdate);
	}else{
		$sqlInsert = "INSERT INTO td_ecommerce_posicaogeralestoque (id,td_produto,".($variacaoproduto != ""?$variacaoinsertcampo . ",":"")."saldo,datahora) VALUES (".getProxId("ecommerce_posicaogeralestoque",$conn).",{$produto},".($variacaoproduto != ""?$variacaoinsertvalor . ",":"")."{$quantidade},now());";
		$queryInsert = $conn->query($sqlInsert);
	}

	Transacao::Fechar();