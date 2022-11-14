<?php
	$linguagemprogramacao = $bancodados = "";
	$sql = "SELECT * FROM ".PREFIXO."config WHERE id = 1";
	$query = $conn->query($sql);
	foreach ($query->fetchAll() as $c){
		$linguagemprogramacao = $c["linguagemprogramacao"];
		$bancodados = $c["bancodados"];
	}
?>