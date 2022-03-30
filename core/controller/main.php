<?php

	// Carrega o layout do sistema
	$pagina = tdClass::Criar("pagina");

	// Root do Sistema
	$root = tdc::o("div");
    $root->id = "miles-root";

	if (Session::get()->autenticado){
        // Renderiza o template
		$jsTemplate = tdc::o('script');
		$jsTemplate->add('
			$(document).ready(function(){
				$.ajax({
					url:session.urlmiles,
					data:{
						controller:"template"
					},
					beforeSend:function(){
						$.loadingBlockShow({
							imgPath:getSRCLoader(),
							text:"Aguarde"
						});
					},				
					complete:function(ret){
						$("#miles-root").html(ret.responseText);
						$("#logon").remove();
						$.loadingBlockHide();
					}
				});
			});
		');
	}else{
		$jsTemplate = null;
		if (file_exists($customautentica)) include $customautentica;
		if (file_exists($systemautentica)) include $systemautentica;
    }
	$pagina->body->add($root,$jsTemplate);
	$pagina->mostrar();