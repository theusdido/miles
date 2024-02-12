<?php

	set_time_limit(100000000);
	if (isset($_GET["processo"])){
		if ($conn = Transacao::Get()){
			$sql = "SELECT id,processo,farein FROM td_relacaocredores WHERE processo = " . $_GET["processo"];
			$query = $conn->query($sql);
			while ($linha = $query->fetch()){
				$codigocredor = completaString($linha["processo"],5) . "." . completaString($linha["farein"],5) . "." . completaString($linha["id"],10);
				$sqlUpdate = "UPDATE td_relacaocredores SET codigocredor = '".$codigocredor."' WHERE id = " . $linha["id"];
				$queryUpdate = $conn->query($sqlUpdate);
			}
			Transacao::fechar();
		}
	}else{
		echo 'Parametro <b>processo</b> não informado.';
	}
