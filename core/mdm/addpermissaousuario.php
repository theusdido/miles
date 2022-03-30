<?php
	include 'conexao.php';
	include '../funcoes.php';	
	
	$sql = "INSERT INTO td_funcaopermissoes (id,projeto,empresa,funcao,usuario,permissao) VALUES ";
	$sql .= "(".getProxId("funcaopermissoes",$conn).",1,1,".$_GET["funcao"].",".$_GET["usuario"].",1)";	
	$query = $conn->query($sql);
	if ($query){
		echo 1;
	}
?>