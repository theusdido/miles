<?php
	$ent_filho 		= tdClass::Criar("persistent",array(ENTIDADE,$rel->filho));	# Entidade filho do relacionamento
	$ent_pai		= tdClass::Criar("persistent",array(ENTIDADE,$rel->pai));	# Entidade pai do relacionamento
	$descricaoRel 	= $rel->descricao==""?$ent_filho->contexto->descricao:$rel->descricao; # Label do Relacionamento

	if (!$ent_filho->contexto->hasData()){
		showMessage('Entidade filho não encontrado. [ Entidade => '.$rel->filho. ' ]');
		exit;
	}

	// Div para adicionar o form do relacionamento
	$div_rel 		= tdClass::Criar("div");
	$urlrequest 	= getURLProject(
		array('controller' 		=> 'gerarchecklist',
		'entidade' 				=> $ent_filho->contexto->id,
		'relacionamento'		=> $rel->id,
		'entidadepai' 			=> $ent_pai->contexto->id)
	);

	$conteudo 		= getUrl($urlrequest);
	if (!$conteudo){
		$div_rel->add('<div class="alert alert-danger" role="alert"><b>Ops!</b> Não foi possível criar esta página.</div>');
	}else{
		$div_rel->add($conteudo);
	}
	array_push($abaArray,array($descricaoRel,$div_rel,$gdJS,""));
