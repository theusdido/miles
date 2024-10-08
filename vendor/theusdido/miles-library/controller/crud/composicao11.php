<?php
$ent_filho 			= tdClass::Criar("persistent",array(ENTIDADE,$rel->filho));	# Entidade filho do relacionamento
$descricaoRel 		= $rel->descricao==""?$ent_filho->contexto->descricao:$rel->descricao; # Label do Relacionamento

// Div para adicionar o form do relacionamento
$div_rel 		= tdClass::Criar("div");
$div_rel->id 	= "div-composicao-relacionamento-".$ent_filho->contexto->nome;
$div_rel->class	= "div-composicao-relacionamento-11";

$urlrequest = getURLProject(
	array('controller' 		=> 'gerarcadastro',
	'entidade' 				=> $ent_filho->contexto->id,
	'relacionamento'		=> $rel->id)
);
$conteudo = getUrl($urlrequest);
if (!$conteudo){
	$div_rel->add('<div class="alert alert-danger" role="alert"><b>Ops!</b> Não foi possível criar esta página.</div>');
}else{
	$div_rel->add($conteudo);
}
array_push($abaArray,array($descricaoRel,$div_rel,"",""));