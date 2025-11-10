<?php
	// Carrega o layout do sistema
	$pagina 	= tdClass::Criar("pagina");

	$bloco 			= tdClass::Criar("bloco",array("logon"));
	$bloco->class 	= 'row';

	$form 						= tdClass::Criar("tdformulario");
	$form->id 					= "f-autenticacao-usuario";
	$form->class 				= 'form-signin';
	$form->target 				= "retorno";

	
	$email 						= tdClass::Criar("labeledit");
	$email->label->add("Login");
	$email->label->for 			= "email";
	$email->input->id 			= "email";
	$email->input->name 		= "email";
	$email->input->class 		= "form-control";
	$email->input->placeholder	= "Digite seu login";

	$senha 						= tdClass::Criar("labeledit");
	$senha->label->add("Senha");
	$senha->label->for 			= "senha";
	$senha->input->id 			= "senha";
	$senha->input->name 		= "senha";
	$senha->input->type 		= "password";
	$senha->input->class 		= "form-control";
	$senha->input->placeholder	= "Digite sua senha";

	$minhasenha 				= tdClass::Criar("hyperlink");
	$minhasenha->href 			= "#";
	$minhasenha->add("Esqueci Minha Senha");
	$minhasenha->id 			= "esqueci-minhasenha-home";
	$minhasenha->onclick		= "rotateDiv()";

	$div_minhasenha 			= tdClass::Criar("div");
	$div_minhasenha->class 		= "form-group";
	$div_minhasenha->add($minhasenha);

	$botao_formgroup 			= tdClass::Criar("div");
	$botao_formgroup->class 	= "form-group";
	$botao = tdClass::Criar("input");
	$botao->id 					= "btn-entrar";
	$botao->type 				= "button";
	$botao->value 				= "Entrar";
	$botao->class 				= "btn btn-block btn-primary";
	$botao_formgroup->add($botao);

	$esqueciminhasenha_botao_formgroup 			= tdClass::Criar("div");
	$esqueciminhasenha_botao_formgroup->class 	= "form-group";
	$esqueciminhasenha_botao 					= tdClass::Criar("input");
	$esqueciminhasenha_botao->id 				= "btn-enviar-recuperacao-senha";
	$esqueciminhasenha_botao->type 				= "button";
	$esqueciminhasenha_botao->value 			= "Enviar";
	$esqueciminhasenha_botao->class 			= "btn btn-block btn-primary";
	$esqueciminhasenha_botao_formgroup->add($esqueciminhasenha_botao);

	$esqueciminhasenha_email 						= tdClass::Criar("labeledit");
	$esqueciminhasenha_email->label->add("E-Mail");
	$esqueciminhasenha_email->label->for 			= "email-recuperacao";
	$esqueciminhasenha_email->input->id 			= "email-recuperacao";
	$esqueciminhasenha_email->input->name 			= "email-recuperacao";
	$esqueciminhasenha_email->input->class 			= "form-control";
	$esqueciminhasenha_email->input->placeholder	= "Digite seu e-mail";	

	$minhasenha_voltar 				= tdClass::Criar("hyperlink");
	$minhasenha_voltar->href 		= "#";
	$minhasenha_voltar->add("Voltar");
	$minhasenha_voltar->id 			= "voltar-logon";
	$minhasenha_voltar->onclick		= "rotateDiv()";

	$div_minhasenha_voltar 			= tdClass::Criar("div");
	$div_minhasenha_voltar->class 	= "form-group";
	$div_minhasenha_voltar->add($minhasenha_voltar);	

	// ** Rotação de Logon para Esqueci Minha Senha
	$rotating_container 			= tdc::html('div');
	$rotating_container->class 		= 'rotating-container';

	$rotating_div 					= tdc::html('div');
	$rotating_div->class 			= 'rotating-div';
	$rotating_div->id 				= 'myDiv';
	
	$front_face 					= tdc::html('div');	
	$front_face->class 				= 'front-face';

	$back_face 						= tdc::html('div');
	$back_face->class 				= 'back-face';

	$back_face->add($esqueciminhasenha_email, $esqueciminhasenha_botao_formgroup, $div_minhasenha_voltar);
	$front_face->add($email,$senha,$botao_formgroup,$div_minhasenha);

	$rotating_div->add($front_face,$back_face);
	$rotating_container->add($rotating_div);

	$form->fieldset->add($rotating_container);
	#$form->fieldset->add($email,$senha,$botao_formgroup,$div_minhasenha);

	$div_logo 			= tdClass::Criar("div");
	$div_logo->class 	= "autentica-div-logo col-sm-6 col-lg-6";
	$div_logo->add(Theme::logo());

	$jsLogon = tdc::o("script");
	$jsLogon->add('
		window.sessionStorage.setItem("is_session_active",false);
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
					try{
						var retorno = JSON.parse(ret.responseText);
						if (retorno.error_code == 0){
							$.ajax({
								url:session.urlmiles,
								data:{
									controller:"template"
								},
								complete:function(ret){
									window.sessionStorage.setItem("is_session_active",true);
									inactivityTime();
									$("#miles-root").html(ret.responseText);
									$("#logon").remove();
									$.loadingBlockHide();
								}
							});
						}else{
							showRetorno("[ " + retorno.error_code + " ] - " + retorno.error_msg);
							$.loadingBlockHide();
						}
					}catch(e){
						showRetorno("Erro interno, por favor tenta mais tarde");
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

		let rotation = 0;

		function rotateDiv() {
			hideRetorno();

			rotation += 180; // Gira 180 graus
			const myDiv = document.getElementById("myDiv");
			
			// Aplicar a rotação no eixo Y
			myDiv.style.transform = `rotateY(${rotation}deg)`;
		}
		
		function enviarLinkRecuperacaoSenha()
		{
			$.ajax({
				url:session.urlmiles,
				dataType:"JSON",
				data:{
					controller:"recuperacaosenha",
					op:"enviarlink",
					email:$("#email-recuperacao").val()
				},
				complete:function(ret){
					let res = ret.responseJSON;
					let msg_retorno = "";
					let tipo_retorno = "danger";
					switch(res.status){
						case 1:
							msg_retorno = "Link enviado com sucesso!";
							tipo_retorno = "success";
						break;
						case 2:
							msg_retorno = "Erro ao enviar e-mail de recuperação.";
						break;
						case 3:
							msg_retorno = "Não existe nenhum usuário com este e-mail.";
						break;
					}
					showRetorno(msg_retorno,tipo_retorno);
					$.loadingBlockHide();
				},
				beforeSend:function(){
					$.loadingBlockShow({
						imgPath:getSRCLoader(),
						text:"Aguarde"
					});
				},					
			});
		}

		$("#btn-enviar-recuperacao-senha").click(function(){
			enviarLinkRecuperacaoSenha();
		});

		function hideRetorno(){
			$("#retorno").html("");
			$("#retorno").hide("50");
		}

		function showRetorno(msg_retorno = "",tipo = "danger"){
		const retorno_elemento = $("#retorno");
			retorno_elemento.removeClass("alert-danger alert-success");
			retorno_elemento.addClass("alert-" + tipo);
			retorno_elemento.html(msg_retorno);
			retorno_elemento.show("50");
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

        .rotating-container {
            perspective: 1000px; /* Perspectiva 3D */
        }

        .rotating-div {
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.6s ease;
        }

        .front-face, .back-face {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden; /* Oculta o lado traseiro quando rotacionado */
        }

        .back-face {
            transform: rotateY(180deg); /* Rotaciona o verso */
			padding-top:40px;
        }
		
		#esqueci-minhasenha-home,
		#voltar-logon
		{
			margin-top:-40px;
			font-size:14px;
		}
		
		#retorno
		{
			border:none;
			border-radius:0;			
		}
		
		#retorno.alert-danger
		{
			border-bottom:3px solid #FF0000;
		}
		#retorno.alert-success
		{
			border-bottom:3px solid #00AA00;
		}
	');
	$pagina->head->add($style);