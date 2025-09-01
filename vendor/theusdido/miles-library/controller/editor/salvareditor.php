<?php
$idPagina = $_POST["id_pagina"];
$retirar_atributos = array("'no-visible ui-droppable'","'ui-droppable","'no-visible'");
if ($conn = Transacao::get()){
	try{

		if ($idPagina==""){
			$sqlProx = "SELECT IFNULL(MAX(id),0)+1 FROM td_pagina;";
			$prox = $conn->query($sqlProx)->fetch();
			$idPagina = $prox[0];
			//addLog($sqlProx);
			$sqlPagina = "INSERT INTO td_pagina (id,nome) VALUES ({$idPagina},'{$_POST["nome_pagina"]}');";
			$conn->exec($sqlPagina);
			//addLog($sqlPagina);					
		}else if(is_numeric($idPagina)){
			$deletar = "DELETE FROM td_tags WHERE pagina = {$idPagina};DELETE FROM td_tagsattributes WHERE pagina = {$idPagina};";
			$conn->exec($deletar);
			//addLog($deletar);	
		}else{
			echo 'ID da pagina invalido!';
			exit;
		}
		if (isset($_POST["tags"])){
			foreach($_POST["tags"] as $tags){
				$tagpai = isset($tags["parent"])?$tags["parent"]:"";
				//$idTag = getProxId("tags",$conn);
				$idTag = $tags["idtag"];
				
				$texto = tdc::utf8($tags["text"]);
				$sqlInsertTag = "INSERT INTO td_tags (id,pagina,nome,tagpai,texto) VALUES (".$idTag.",{$idPagina},'{$tags["name"]}','{$tagpai}','{$texto}');";
				//echo "<br>\n" . $sqlInsertTag;
				$conn->exec($sqlInsertTag);
				//addLog($sqlInsertTag);
				if (isset($tags["attributes"])){								
					$dados = explode("^",$tags["attributes"]);								
					foreach ($dados as $attr){
						if ($attr == "") continue;
						$a = explode("=",$attr);									
						if ($a[0] == "") continue;
						$sqlProx = "SELECT IFNULL(MAX(id),0)+1 FROM td_tagsattributes;";
						$prox = $conn->query($sqlProx)->fetch();
						$valorAttr = str_replace($retirar_atributos,'',$a[1]);
						$sqlInsertTag = "INSERT INTO td_tagsattributes (id,atributo,valor,tags) VALUES ({$prox[0]},'{$a[0]}','{$valorAttr}',{$idTag});";						
						$conn->exec($sqlInsertTag);
						//addLog($sqlInsertTag);
					}								
				}
			}
		}
	echo $idPagina;
	$conn->commit();
	}catch(Exception $e){		
		echo $e->getMessage();
		$conn->rollback();
	}
}