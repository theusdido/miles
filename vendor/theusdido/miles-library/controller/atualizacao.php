<?php
	// Atualização da estrutura
	if ($conn = Transacao::get()){
		$sqlEntidade = "SELECT nome FROM td_entidade";
		$queryEntidade = $conn->query($sqlEntidade);
		while ($linhaEntidade = $queryEntidade->fetch()){
			echo $linhaEntidade["nome"] . "<br/>";
		}
	}
?>