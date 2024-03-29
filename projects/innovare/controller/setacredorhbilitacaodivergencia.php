<?php
	$newcredor 	= $_GET["newcredor"];
	$oldcredor 	= $_GET["oldcredor"];
	
	$conn = Transacao::Get();
	
	try{
		$conn->beginTransaction();
		
		// Atualiza o nome credor na Habilitação/Divergencia
		$sqlatualiza = "UPDATE td_habilitacaodivergencia SET credor = {$newcredor} WHERE credor = {$oldcredor};";
		$query = $conn->exec($sqlatualiza);
		
		// Atualiza os arquivos
		$sqlarquivos = "UPDATE td_arquivos_credor SET relacaocredores = {$newcredor} WHERE relacaocredores = {$oldcredor};";
		$query = $conn->exec($sqlarquivos);
		
		$conn->commit();
	}catch(Exception $e){
		echo $e->getMessage();
		$conn->rollback();
	}