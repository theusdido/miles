<?php
	set_time_limit(100000000);
	$sql = tdClass::Criar("sqlcriterio");
	$sql->addFiltro("farein","=",1);
	$sql->addFiltro("td_processo","=",1);
	$sql->addFiltro("td_origemcredor","=",1);
	
	$dataset = tdClass::Criar("repositorio",array("td_relacaocredores"))->carregar($sql);	
	foreach($dataset as $linha){
		$lista = tdClass::Criar("persistent",array(LISTA))->contexto;
		$lista->entidade_pai = 16;
		$lista->entidade_filho = 20;
		$lista->reg_pai = 1;
		$lista->reg_filho = $linha->id;
		$lista->armazenar();
	}
	Transacao::fechar();
?>