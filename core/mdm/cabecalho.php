<?php
	$idEntidadePrincipal = $descricaoEntidadePrincipal = $nomeEntidadePrincipal = "";
	if (isset($_GET["entidade"])){		
			$sql = "SELECT id,nome,descricao FROM ".PREFIXO."entidade WHERE id = {$_GET['entidade']}";
			$linha = $conn->query($sql)->fetchAll();
			$idEntidadePrincipal = $linha[0]["id"];
			$descricaoEntidadePrincipal = executefunction("utf8charset",array($linha[0]['descricao'],5));
			$nomeEntidadePrincipal = $linha[0]['nome'];
			echo '<blockquote class="blockquote-reverse">'. $idEntidadePrincipal .' - ' . $descricaoEntidadePrincipal . '   <small>' . $nomeEntidadePrincipal . '</small></blockquote>';			
	}