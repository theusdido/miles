<?php
	include 'conexao.php';
	$sql = "DELETE FROM td_funcaopermissoes WHERE td_funcao = ".$_GET["funcao"]." AND td_usuario = ".$_GET["usuario"];
	$query = $conn->query($sql);
	if ($query){
		echo 1;
	}
?>