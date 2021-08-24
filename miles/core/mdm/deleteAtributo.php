<?php
	require 'conexao.php';
	require 'prefixo.php';	
	require 'funcoes.php';
	
	$entidade = $_GET["entidade"];
	$id = $_GET["id"];
	$nome = $_GET["nome"];
	$sql = "SELECT nome FROM ".PREFIXO."entidade WHERE id = {$entidade}";
	$linha_nome = $conn->query($sql)->fetchAll();
	$sql = "ALTER TABLE {$linha_nome[0]["nome"]} DROP COLUMN {$nome};";
	
	try {
		$query = $conn->query($sql);
	}catch(Exception $e){
		
	}

	$sql = "DELETE FROM ".PREFIXO."atributo WHERE id = {$id};";
	$query = $conn->query($sql);
	header("Location: listarAtributo.php?entidade={$entidade}&" . getURLParamsProject());