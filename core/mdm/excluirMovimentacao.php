<?php
/*
	require 'conexao.php';
	require 'prefixo.php';
	require 'funcoes.php';
	
	$movimentacao = $_GET["movimentacao"];
	$linha_nome = $conn->query("SELECT nome FROM ".PREFIXO."entidade WHERE id = {$movimentacao};")->fetchAll();	
	if (sizeof($linha_nome) <= 0){
		echo 'Movimentacao n&atilde;o encontrada';
		echo '<br/><a href="listarMovimentacao.php?'.getURLParamsProject().'">Voltar</a>';
		exit;
	}
	
	$sql_atributos = "SELECT id,nome FROM " . PREFIXO . "atributo WHERE entidade = " . $movimentacao.";";
	$query_atributos = $conn->query($sql_atributos);
	foreach($query_atributos->fetchAll() as $linha_atributos){
		try{
			$sql = "DELETE FROM ".PREFIXO."atributo WHERE id = {$linha_atributos["id"]};";
			$query = $conn->query($sql);
				
			$sql = "ALTER TABLE {$linha_nome[0]["nome"]} DROP {$linha_atributos["nome"]};";
			$query = $conn->query($sql);
			if ($query){

			}
		}catch(Exception $e){
		}
	}
	
	$sql_drop = "DROP TABLE IF EXISTS {$linha_nome[0]["nome"]}";
	$conn->query($sql_drop);
	
	$sql_delete = "DELETE FROM " . PREFIXO . "entidade WHERE id = " . $movimentacao;
	$conn->query($sql_delete);

	$sql_relacionamentos = "DELETE FROM " . PREFIXO . "relacionamento WHERE pai = {$movimentacao} OR filho = {$movimentacao}";
	header("Location: listarMovimentacao.php?" . getURLParamsProject());
*/