function Menu (){
	this.navbar;
	this.contexto;
	this.header;
	this.collapse;
	this.menuselecionado;
	this.exibirbrand = false;
	this.dados;
}
Menu.prototype.criar = function(){
	this.navbar = $("<nav class='navbar navbar-default'>");	
}
Menu.prototype.mostrar = function(){
	this.criar();
	this.header();
	this.collapse();
	this.load();
	$(this.contexto).append(this.navbar);
}
Menu.prototype.header = function(){
	this.header = $("<div class='navbar-header'>");
	if (this.exibirbrand){
		let brand = $('<button id="btn-home-menuprincipal" onclick="goHome();" type="button" class="btn btn-default" aria-label="Home"><i class="fas fa-home"></i></button>');
		this.header.append(brand);
	}
	let navbartoggle = $(
		'<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menuprincipal" aria-expanded="false" aria-controls="navbar">' +
			'<span class="sr-only">Toggle navigation</span>' +
            '<span class="icon-bar"></span>' +
            '<span class="icon-bar"></span>' +
            '<span class="icon-bar"></span>' +
        '</button>'
	);
	this.header.append(navbartoggle);
	this.navbar.append(this.header);
}
Menu.prototype.collapse = function(){
	this.collapse = $("<div id='menuprincipal' class='collapse navbar-collapse' aria-expanded='false'>");
	this.navbar.append(this.collapse);
}
Menu.prototype.menuprincipal = function(dados){

	let caret  	= " <span class='caret'>";
	let menu 	= $("<ul class='nav navbar-nav' >");
	let instancia 	= this;	
	dados.forEach(function(menu_item)
	{
		let pai 	= parseInt(menu_item.pai);
		let li 		= $("<li>");
		let link 	= menu_item.link == "#"?menu_item.link:session.currentprojectregisterpath+menu_item.link;
		let a 		= $("<a class='dropdown-toggle' role='button' aria-haspopup='true' data-toggle='dropdown' href='"+link+"' aria-expanded='false'>"+menu_item.descricao+"</span></a>");
		li.append(a);
		menu.append(li);

		if (menu_item.filhos.length > 0){
			li.find("a").append(caret);
			let	submenu = $('<ul class="dropdown-menu" role="menu">');
			menu_item.filhos.forEach(function(subitem)
			{
				let li_submenu 	= $("<li>");
				let linkpath 	= session.folderprojectfiles + subitem.link;
				let a_submenu 	=  $("<a target='"+(subitem.target == ""?"_self":subitem.target)+"' data-path='"+linkpath+"' data-id='"+subitem.id+"' data-target='#conteudoprincipal' href='"+(subitem.target == "" || subitem.target == ""?"#":subitem.link)+"' data-tipomenu='"+subitem.tipomenu+"'>"+subitem.descricao+"</span></a>");				

				if (subitem.target != "_blank"){
					a_submenu.click(subitem,function(handler){
						let tipomenu = $(this).data("tipomenu");
						if (!isNumeric(tipomenu)){
							funcionalidade = tipomenu;
						}
						menuprincipalselecionado 	= $(this).data("id");
						instancia.menuselecionado 	= menuprincipalselecionado;
						
						instancia.carregarpagina($(this).data("path"),$(this).data("target"),handler.data);
						addLog("","","", getEntidadeId("administracao-menu"),menuprincipalselecionado, 5, $(this).data("path"));
					});
				}
				li_submenu.append(a_submenu);
				submenu.append(li_submenu);
			});
			li.append(submenu);
		}
	});
	this.collapse.append(menu); // Adiciona no corpo do menu
}
Menu.prototype.load = function(){

	if (config.urlmenu == "" || config.urlmenu == undefined || config.urlmenu == null){
		console.log("URL MENU não configurada.");
		return false;
	}

	let instancia = this;
	$.ajax({
		url:config.urlmenu,
		data:{
			op:"retorna_dados"
		},
		complete:function(r){
			var retorno = JSON.parse(r.responseText);
			instancia.menuprincipal(retorno)
			instancia.dados = retorno;
		},
		error:function(ret){
			console.log("ERRO ao carregar o menu => " + ret.responseText);
		}
	});	
}
Menu.prototype.carregarpagina = function(path,target,dados_menu){
	carregar(path,target,function(){
		carregarScriptCRUD(dados_menu.tipomenu,dados_menu.entidade);
	});
	let instancia = this;
	// Log de acesso ao menu
	$.ajax({
		url:config.urlrequisicoes,
		data:{
			op:"logmenu",
			menu:instancia.menuselecionado
		}
	});	
}