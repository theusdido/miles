<?php

	// Carrega o layout do sistema
	$pagina 	= tdClass::Criar("pagina");

	$bloco 			= tdClass::Criar("bloco",array("logon"));
	$bloco->class 	= 'row';

	$form 						= tdClass::Criar("tdformulario");
	$form->id 					= "f-autenticacao-usuario";
	$form->class 				= 'form-signin';
	$form->target 				= "retorno";

	$senha 						= tdClass::Criar("labeledit");
	$senha->label->add("Senha");
	$senha->label->for 			= "senha";
	$senha->input->id 			= "senha";
	$senha->input->name 		= "senha";
	$senha->input->type 		= "password";
	$senha->input->class 		= "form-control";
	$senha->input->placeholder	= "Digite sua senha";

	$csenha 						= tdClass::Criar("labeledit");
	$csenha->label->add("Confirmar Senha");
	$csenha->label->for 			= "csenha";
	$csenha->input->id 			    = "csenha";
	$csenha->input->name 		    = "csenha";
    $csenha->input->type 		= "password";
	$csenha->input->class 		    = "form-control";
	$csenha->input->placeholder	    = "Digite sua senha novamente";

	$botao_formgroup 			= tdClass::Criar("div");
	$botao_formgroup->class 	= "form-group";
	$botao = tdClass::Criar("input");
	$botao->id 					= "btn-recuperar";
	$botao->type 				= "button";
	$botao->value 				= "Recuperar";
	$botao->class 				= "btn btn-block btn-primary";
   
	$botao_formgroup->add($botao);
	$form->fieldset->add($senha,$csenha,$botao_formgroup);

	$div_logo 			= tdClass::Criar("div");
	$div_logo->class 	= "autentica-div-logo col-sm-6 col-lg-6";
	$div_logo->add(Theme::logo());

	$jsLogon = tdc::o("script");
	$jsLogon->add('
		window.sessionStorage.setItem("is_session_active",false);
		$("#f-autenticacao-usuario #senha").keyup(function(e){
			if (e.which == 13){
				recuperar();
			}
		});

		$("#btn-recuperar").click(function(){
			recuperar();
		});
		function recuperar(){			
			var senha = $("#f-autenticacao-usuario #senha");
            var csenha = $("#f-autenticacao-usuario #csenha");

			if (senha.val() == ""){
				statusFormControl(senha,"error");
				return false;
			}
			if (csenha.val() == ""){
				statusFormControl(csenha,"error");
				return false;
			}
            if (senha != csenha){
                $("#retorno").html("A senhas não coincidem.");
                $("#retorno").show();
                $.loadingBlockHide();
            }
			$.ajax({
				url:session.urlmiles,
				data:{
					controller:"recuperacaosenha",
                    op:"alterarsenha",
					currentproject:session.projeto,
					senha:senha.val(),
                    csenha:csenha.val(),
                    hash:"'.tdc::r('hash').'"
				},
				beforeSend:function(){
					$.loadingBlockShow({
						imgPath:getSRCLoader(),
						text:"Aguarde"
					});
				},
				complete:function(ret){
					try{
						var retorno = JSON.parse(ret.responseText);
						if (retorno.status == 1){
                            location.href=session.urlmiles;
						}else{
							$("#retorno").html("[ " + retorno.error_code + " ] - " + retorno.error_msg);
							$("#retorno").show();
							$.loadingBlockHide();
						}
					}catch(e){
						$("#retorno").html("Erro interno, por favor tenta mais tarde");
						$("#retorno").show();
						$.loadingBlockHide();
					}
				},
				error:function(xhr,exception){
					console.log("Ocorreu um erro !");
				}
			});
		}

		if (typeof timeout_session !== "undefined"){
			clearTimeout(timeout_session);
		}

	');

	$div_form 				= tdClass::Criar("div");
	$div_form->id 			= "div-form-logon";
	$div_form->class 		= "col-sm-6 col-lg-6";
	$div_form->add($form);

	$retorno 				= tdClass::Criar("div");
	$retorno->class 		= "alert alert-danger";
	$retorno->role 			= "alert";
	$retorno->id 			= "retorno";

	$bloco->add($div_logo,$div_form,$retorno,$jsLogon);
	$pagina->body->add($bloco);

	$style 					= tdClass::Criar("style");
	$style->type 			= "text/css";

	// Adiciona personalização no Tema
	if (isset($mjc->themes)){
		foreach($mjc->themes as $theme){
			if ($theme->name == $mjc->theme){
				foreach($theme->screens as $screen){
					if ($screen->name == 'logon'){
						$style->add('
							#div-form-logon .tdform fieldset , #esqueci-autenticar-home {
								color:'.$screen->font->color.' !important;
							}
						');
					}
				}
			}
		}
	}

	$urlBackground 		= URL_CURRENT_PROJECT_THEME . FILE_BACKGROUND;
	$path_background	= PATH_CURRENT_PROJECT_THEME . FILE_BACKGROUND;

	if (!file_exists($path_background)){
		$urlBackground = URL_BACKGROUND;
	}

	$style->add('
		#retorno{
			display:none;
			float: left;
			width: 100%;
			text-align: center;
		}
		
		body{
			background: url('.$urlBackground.') no-repeat center top fixed;
		}
	');
	$pagina->head->add($style);
    $pagina->mostrar();