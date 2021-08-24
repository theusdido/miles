<?php

	$logon = tdClass::Criar("bloco",array("userinfo"));	
	$logon->class = "col-md-4 col-sm-4";
	
	$fotoperfil = isset(tdClass::Criar("persistent",array(USUARIO,Session::Get()->userid))->contexto->fotoperfil)?tdClass::Criar("persistent",array(USUARIO,Session::Get()->userid))->contexto->fotoperfil:"";
	$userFotoPerfil = Session::Get("PATH_CURRENT_FILE") . "fotoperfil-".getEntidadeId("usuario",Transacao::Get())."-".Session::Get()->userid.".".getExtensao($fotoperfil);
	
	$img_user = tdClass::Criar('imagem');
	$img_user->class = "img-thumbnail perfilusuariohome";
	$img_user->alt = "Imagem Usu&aacute;rio";
	if (file_exists($userFotoPerfil)){
		$img_user->src = $userFotoPerfil;
	}else if (file_exists(Session::Get('PATH_SYSTEM_THEME') . "sem-foto-usuario.png")){
		$img_user->src = Session::Get('URL_SYSTEM_THEME') . "sem-foto-usuario.png";	
	}else{
		$img_user = null;
	}
	
	$link_logout = tdClass::Criar("hyperlink");
	$link_logout->href = getURLProject("index.php?controller=logout");
	$link_logout->add("[ Desconectar ]");
	
	if (Session::get()->usergroup == 1){
		$link_miles = tdClass::Criar("hyperlink");
		$link_miles->href = "/miles";
		$link_miles->add("[ Miles ]");
	}else{
		$link_miles = null;
	}

	$br = tdClass::Criar("br");
	$nome_user = tdClass::Criar("h",array(4));
	$nome_user->add(Session::get()->username,$br,$link_miles,$link_logout);
	
	
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
	$logon->add($img_user,$nome_user,$jsLogon,$modalEditUserHome);