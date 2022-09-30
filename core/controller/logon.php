<?php
	$logon 			= tdClass::Criar("bloco",array("userinfo"));	
	$logon->class 	= "col-md-3 col-sm-4";
	
	$fotoperfil 		= isset(tdClass::Criar("persistent",array(USUARIO,Session::Get()->userid))->contexto->fotoperfil)?tdClass::Criar("persistent",array(USUARIO,Session::Get()->userid))->contexto->fotoperfil:"";
	$userFotoPerfil 	= PATH_CURRENT_FILE . "fotoperfil-".getEntidadeId("usuario",Transacao::Get())."-".Session::Get()->userid.".".getExtensao($fotoperfil);
	
	$img_user 			= tdClass::Criar('imagem');
	$img_user->class 	= "img-thumbnail perfilusuariohome";
	$img_user->alt 		= "Imagem Usu&aacute;rio";

	if (file_exists($userFotoPerfil)){
		$img_user->src 	= $userFotoPerfil;
	}else if (file_exists(PATH_SYSTEM_THEME . "sem-foto-usuario.png")){
		$img_user->src 	= URL_SYSTEM_THEME . "sem-foto-usuario.png";	
	}else{
		$img_user 		= null;
	}
	
	$link_logout = tdClass::Criar("hyperlink");
	$link_logout->href = getURLProject("index.php?controller=logout");
	$link_logout->add("[ Desconectar ]");
	
	$info_box 		= tdc::o('div');
	$info_box->id 	= "info-box-logon";
	$nome_user 		= tdClass::Criar("div");
	$nome_user->id	= "1info-box-username";
	$nome_user->add(Session::get()->username);
	$info_box->add($nome_user,$link_logout);
	
	$modalEditUserHome = tdClass::Criar("modal");
	$modalEditUserHome->nome = "modaledituserhome";
	$modalEditUserHome->addHeader("Usuário");
	$modalEditUserHome->addBody("Editando usuário");
	$modalEditUserHome->addFooter("Salvar alterações");
	
	
	$jsLogon = tdClass::Criar("script");
	$jsLogon->add('
		$(".perfilusuariohome").click(function(){
			$("#modaledituserhome").modal("show");
		});
	');
	$logon->add($img_user,$info_box,$jsLogon,$modalEditUserHome);
