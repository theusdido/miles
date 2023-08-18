<?php

$conn = Transacao::Get();
$sqlHD  = "SELECT credor FROM td_habilitacaodivergencia";
$queryHD = $conn->query($sqlHD);
$c = 1;
while ($linhaHD = $queryHD->fetch()){
	$sql = "SELECT 1 FROM td_relacaocredores WHERE id = " . $linhaHD["credor"];
	$query = $conn->query($sql);
	
	if ($query->rowCount() <= 0) {
		echo 'n existe => ' . $linhaHD["credor"] . "<br/>";
		$c++;
	}	
}

echo 'TOTAL => ' . $c;