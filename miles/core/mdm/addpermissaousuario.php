<?php
	include 'conexao.php';
	include '../funcoes.php';	
	
	$sql = "INSERT INTO td_funcaopermissoes (id,td_projeto,td_empresa,td_funcao,td_usuario,permissao) VALUES ";
	$sql .= "(".getProxId("funcaopermissoes",$conn).",1,1,".$_GET["funcao"].",".$_GET["usuario"].",1)";	
	$query = $conn->query($sql);
	if ($query){
		echo 1;
	}
?>