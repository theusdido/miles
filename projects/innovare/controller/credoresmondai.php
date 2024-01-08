<?php
	set_time_limit(100000000);
	$sql = tdClass::Criar("sqlcriterio");
	$sql->addFiltro("farein","=",1);
	$sql->addFiltro("processo","=",1);
	$sql->addFiltro("origemcredor","=",1);
	
	$dataset = tdClass::Criar("repositorio",array("td_relacaocredores"))->carregar($sql);	
	foreach($dataset as $linha){
		$lista = tdClass::Criar("persistent",array(LISTA))->contexto;
		$lista->entidadepai = 16;
		$lista->entidadefilho = 20;
		$lista->regpai = 1;
		$lista->regfilho = $linha->id;
		$lista->armazenar();
	}
	Transacao::fechar();
?>