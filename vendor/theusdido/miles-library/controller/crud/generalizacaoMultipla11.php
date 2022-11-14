<?php
// Flag para generalização
if ($panelM == ""){
	$panelM = tdClass::Criar("div");
	$panelM->class = "panel panel-link panel-flag-generalizacao";
	$panelM_head = tdClass::Criar("div");
	$panelM_head->class = "panel-heading";
	$panelM_head->add("Especificação de " . $entidade->contexto->descricao);
	$panelM_body = tdClass::Criar("div");
	$panelM_body->class = "panel-body";
	
	// Cria um SELECT para opção de escolha
	$selectM = tdClass::Criar("select");
	$selectM->class = "form-control input-sm select-flag-generalizacao";
	$selectM->multiple = "multiple";
	$selectM->id = "select-generalizacao-multipla";
	$selectM->data_indexM = $contEntGenM;

}
	
$ent_filho = tdClass::Criar("persistent",array(ENTIDADE,$rel->filho));	# Entidade filho do relacionamento
$descricaoRel = $rel->descricao==""?$ent_filho->contexto->descricao:$rel->descricao; # Label do Relacionamento

// Div para adicionar o form do relacionamento
$div_rel = tdClass::Criar("div");
$div_rel->class = "div-relacionamento-generalizacao-multipla";
$div_rel->id = "drvm-".$ent_filho->contexto->nome;

$urlrequest = URL_MILES . '?controller=gerarcadastro&entidade='.$ent_filho->contexto->id . "&relacionamento=" . $rel->id . "&currentproject=" . $_GET["currentproject"];
$conteudo = getUrl($urlrequest);
if (!$conteudo){
	$div_rel->add('<div class="alert alert-danger" role="alert"><b>Ops!</b> Não foi possível criar esta página.</div>');
}else{
	$div_rel->add($conteudo);
}

array_push($abaArray,array($descricaoRel,$div_rel,'generalizacaoABA-M ' . $ent_filho->contexto->nome , ""));
$contEntGen++;
$contEntGenM++;