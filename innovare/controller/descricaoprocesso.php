<?php
	$conn = Transacao::Get();

	$processo = tdClass::Criar("persistent",array("td_processo",$_GET["processo"]))->contexto;
	$entidade_farein = $processo->tipoprocesso == 16 ? 'td_recuperanda' : 'td_falencia';
	
	$sql = "SELECT razaosocial FROM {$entidade_farein} WHERE td_processo = " . $processo->id . " LIMIT 1 ";
	$query = $conn->query($sql);
	if ($query){
		if ($linha = $query->fetch()){
			//$processo->descricao = utf8_decode($linha["razaosocial"]);
			$nome = $linha["razaosocial"];
			$processo->descricao = 'Teste';
			$conn->exec("UPDATE td_processo SET descricao = '{$nome}' WHERE id = " . $processo->id);
			//$processo->armazenar();
			Transacao::Fechar();
		}
	}else{
		var_dump($query->errorInfo());
	}