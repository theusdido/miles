<?php
	include 'conexao.php';
	$sql = "DELETE FROM td_funcaopermissoes WHERE funcao = ".$_GET["funcao"]." AND usuario = ".$_GET["usuario"];
	$query = $conn->query($sql);
	if ($query){
		echo 1;
	}
?>