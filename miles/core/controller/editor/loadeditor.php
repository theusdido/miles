<?php
if (isset($_GET["pagina"])){
	$sql = tdClass::Criar("sqlcriterio");
	$sql->addFiltro("pagina","=",$_GET["pagina"]);
	$sql->setPropriedade("order","tagpai");
	$dataset = tdClass::Criar("repositorio",array("td_tags"))->carregar($sql);
	$tagsHTML = "";
	$itag = 1;
	$ttag = sizeof($dataset);
	echo "[";
	foreach($dataset as $tags){				
		echo '{"tag":"'.strtolower($tags->nome).'","paitag":"'. $tags->tagpai . '","texto":"'. utf8_encode($tags->texto) . '","atributos":{';
		$sql_attr = tdClass::Criar("sqlcriterio");
		$sql_attr->addFiltro("tags",'=',$tags->id);
		$dataset_attr = tdClass::Criar("repositorio",array("td_tagsattributes"))->carregar($sql_attr);
		foreach ($dataset_attr as $attr){
			echo '"' . $attr->atributo .'":"'. $attr->valor . '",';
		}
		echo '"idtag":"'. $tags->id . '"';
		echo '}}';
		if ($itag < $ttag) echo ",";
		$itag++;
	}
	echo "]";
}