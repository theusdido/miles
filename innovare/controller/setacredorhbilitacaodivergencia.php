<?php
	$newcredor 	= $_GET["newcredor"];
	$oldcredor 	= $_GET["oldcredor"];
	
	$conn = Transacao::Get();
	
	try}
		$conn->beginTransaction();
		
		// Atualiza o nome credor na Habilitação/Divergencia
		$sqlatualiza = "UPDATE td_habilitacaodivergencia SET td_credor = {$newcredor} WHERE td_credor = {$oldcredor};";
		$query = $conn->exec($sqlatualiza);
		
		// Atualiza os arquivos
		$sqlarquivos = "UPDATE td_arquivos_credor SET td_relacaocredores = {$newcredor} WHERE td_relacaocredores = {$oldcredor};";
		$query = $conn->exec($sqlarquivos);
		
		$conn->commit();
	}catch(Exception $e){
		echo $e->getMessage();
		$conn->rollback();
	}