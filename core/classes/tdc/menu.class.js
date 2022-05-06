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

	let caret  = " <span class='caret'>";
	let nivel = [];
	let ultpai = "";
	
	// Nível 0
	let menu 	= $("<ul class='nav navbar-nav' >");
	let submenu = null;
	for (d in dados){
		let pai = parseInt(dados[d].pai);
		if (pai == 0){
			let li = $("<li>");
			let link = dados[d].link == "#"?dados[d].link:session.currentprojectregisterpath+dados[d].link;
			let a =  $("<a class='dropdown-toggle' role='button' aria-haspopup='true' data-toggle='dropdown' href='"+link+"' aria-expanded='false'>"+dados[d].descricao+"</span></a>");
			li.append(a);
			menu.append(li);
			nivel[dados[d].id] = li;
		}else{
			if (ultpai != pai){
				submenu = $('<ul class="dropdown-menu" role="menu">');
				ultpai= pai;				
				if (pai != "" && pai != undefined && pai != 0){					
					try {
 					  	nivel[pai].find("a").append(caret);	
					}catch(err) {
						continue;
					}
				}	
			}	
			let li = $("<li>");
			let linkpath = session.folderprojectfiles + dados[d].link;
			if (dados[d].entidade == "" || dados[d].entidade == 0){
				
			}else{
				//var linkpath = dados[d].link == "#"?dados[d].link:session.folderfiles+dados[d].link;
			}
			
			
			let a =  $("<a target='"+(dados[d].target == ""?"_self":dados[d].target)+"' data-path='"+linkpath+"' data-id='"+dados[d].id+"' data-target='#conteudoprincipal' href='"+(dados[d].target == "" || dados[d].target == ""?"#":dados[d].link)+"' data-tipomenu='"+dados[d].tipomenu+"'>"+dados[d].descricao+"</span></a>");
			let instancia = this;
			if (dados[d].target != "_blank"){
				a.click(dados[d],function(handler){
					let tipomenu = $(this).data("tipomenu");
					if (!isNumeric(tipomenu)){
						funcionalidade = tipomenu;
					}
					menuprincipalselecionado = $(this).data("id");
					instancia.menuselecionado = menuprincipalselecionado;
					instancia.carregarpagina($(this).data("path"),$(this).data("target"),handler.data);
					addLog("","","", getEntidadeId("administracao-menu"),menuprincipalselecionado, 5, $(this).data("path"));
				});
			}
			li.append(a);
			submenu.append(li);
			
			try {
				if (typeof nivel[pai] === "object"){
					nivel[pai].append(submenu);
				}
			}catch(err) {
				console.log('Falhou ao abrir esse menu => ' + submenu + " = " + pai);
				continue;
			}

		}
	}
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