<?php
	// Carrega o layout do sistema
	$pagina = tdClass::Criar("pagina");

	$bloco = tdClass::Criar("bloco",array("logon"));			

	$form = tdClass::Criar("tdformulario");
	$form->id = "f-autenticacao-usuario";
	$form->class = 'form-signin';
	$form->target = "retorno";

	$email = tdClass::Criar("labeledit");
	$email->label->add("Login");
	$email->label->for = "email";
	$email->input->id ="email";
	$email->input->name = "email";
	$email->input->class = "form-control";
	$email->input->placeholder="Digite seu login";

	$senha = tdClass::Criar("labeledit");
	$senha->label->add("Senha");
	$senha->label->for = "senha";
	$senha->input->id ="senha";
	$senha->input->name = "senha";
	$senha->input->type = "password";
	$senha->input->class = "form-control";
	$senha->input->placeholder="Digite sua senha";
		
	$minhasenha = tdClass::Criar("hyperlink");
	$minhasenha->href = "?controller=recuperarsenha";
	$minhasenha->add("Esqueci Minha Senha");
	$minhasenha->id = "esqueci-minhasenha-home";

	$div_minhasenha = tdClass::Criar("div");
	$div_minhasenha->class = "form-group";
	$div_minhasenha->add($minhasenha);
	
	$botao_formgroup = tdClass::Criar("div");
	$botao_formgroup->class = "form-group";
	$botao = tdClass::Criar("input");
	$botao->id = "btn-entrar";
	$botao->type = "button";
	$botao->value = "Entrar";
	$botao->class = "btn btn-block btn-primary";
	$botao_formgroup->add($botao);

	$form->fieldset->add($email,$senha,$botao_formgroup,$div_minhasenha);

	$div_logo 			= tdClass::Criar("div");
	$div_logo->class 	= "autentica-div-logo col-sm-6";
	$div_logo->add(Theme::logo());

	$jsLogon = tdc::o("script");
	$jsLogon->add('
		$("#f-autenticacao-usuario #senha").keyup(function(e){
			if (e.which == 13){
				autenticacao();
			}
		});

		$("#btn-entrar").click(function(){
			autenticacao();
		});
		function autenticacao(){
			var login = $("#f-autenticacao-usuario #email");
			var senha = $("#f-autenticacao-usuario #senha");

			if (login.val() == ""){
				statusFormControl(login,"error");
				return false;
			}
			if (senha.val() == ""){
				statusFormControl(senha,"error");
				return false;
			}

			$.ajax({
				url:session.urlmiles,
				data:{
					controller:"autentica",
					currentproject:session.projeto,
					login:login.val(),
					senha:senha.val()
				},
				beforeSend:function(){
					$.loadingBlockShow({
						imgPath:getSRCLoader(),
						text:"Aguarde"
					});
				},
				complete:function(ret){	
					var retorno = JSON.parse(ret.responseText);
					if (retorno.error_code == 0){
						$.ajax({
							url:session.urlmiles,
							data:{
								controller:"template"
							},
							complete:function(ret){
								$("#miles-root").html(ret.responseText);
								$("#logon").remove();
								$.loadingBlockHide();
							}
						});
					}else{						
						$("#retorno").html("[ " + retorno.error_code + " ] - " + retorno.error_msg);
						$("#retorno").show();
						$.loadingBlockHide();
					}
				}
			});
		}
	');

	$div_form = tdClass::Criar("div");
	$div_form->id = "div-form-logon";
	$div_form->class = "col-sm-6";
	$div_form->add($form);

	$retorno = tdClass::Criar("div");
	$retorno->class = "alert alert-danger";
	$retorno->role = "alert";
	$retorno->id = "retorno";

	$bloco->add($div_logo,$div_form,$retorno,$jsLogon);
	$pagina->body->add($bloco);
	
	$style = tdClass::Criar("style");
	$style->type = "text/css";

	// Adiciona personalização no Tema
	if (isset($mjc->themes)){
		foreach($mjc->themes as $theme){
			if ($theme->name == $mjc->theme){
				foreach($theme->screens as $screen){
					if ($screen->name == 'logon'){
						$style->add('
							#div-form-logon .tdform fieldset , #esqueci-minhasenha-home {
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