// Classe Página
function Pagina(){	
	this.objSelected;
	this.idtag = 0;
	this.id = "";
	this.objectMove;
	this.editorBody = ".editor-body";
	this.indiceaddcontainerclass = 0;
}
Pagina.prototype.salvar = function(){	
	var nome_pagina = "";	
	if (pagina.id == ""){
		bootbox.prompt("Nome da página", function(result){
			if (result != null) {
				nome_pagina = result;
				salvar();
				this.modal('hide');
			}
		});
	}else{
		salvar();
		var msg = bootbox.alert("Salvo com Sucesso");
		setTimeout(function(){
			msg.modal('hide');
		},1000);
	}
	function salvar(){
		var array_tags = [];
		var idtag = "";

		$(".editor-body *").each(function(){

			var element = $(this);
			var atributos = "";
			var tag_parent = "";
			var texto = retornaTextoTag(element);

			
			if (element.context == undefined){
				var atributoselemento = element[0].attributes;
			}else{
				var atributoselemento = element.context.attributes;
			}

			// Recuperando os valores de atributos e propriedades
			for (a in atributoselemento){
				var attribute = atributoselemento[a];
				if( typeof attribute != 'function'){
					var attrValue = attribute.nodeValue;

					if (attribute.nodeName != undefined){						
						if (attribute.nodeName.toLowerCase() == "idtag"){
							idtag = attribute.nodeValue;							
							continue;
						}
						if (attribute.nodeName.toLowerCase() == "class"){
							attrValue = attrValue.replace("ui-droppable","").replace("ui-draggable-handle","").replace("ui-draggable","").trim();
						}					
						
						var styleTag = "";			
						if (attribute.nodeName.toLowerCase() == "style"){
						/*
						*	
						*	Tinha um tratamento para a propriedade "style"
						*	
						*	
						*	if (element.attr("idtag") == 11){
						*		var astyletag = attrValue.split(";");
						*		for (e in astyletag){
						*			console.log(astyletag[e].trim());
						*		}
						*	}
						*	for (e in elementosEditor[idtag]){
						*		styleTag += elementosEditor[idtag][e].atributo + ":" + elementosEditor[idtag][e].valor + ";";
						*	}
						*	attrValue = styleTag;
						*/
						}
						// Transforma cor em Hexadecimal
						if (attrValue.split("rgb(").length > 1){
							attrValue = corHexadecimalClass(attrValue);
						}
						atributos += '^' + attribute.nodeName + '=' + attrValue ;
					}
				}
			}
			if (element.parents().hasClass("btn-link")){
				//console.log("QTDADE FILHO "+$(this).prop("tagName")+" ("+$(this).attr("id")+") => " + $(this).parent().children().length);
				//console.log($(this).parent().children());
				//console.log($(this).parent().html());
			}
			
			if (element.attr("idtag") == 2){
				console.log("Elemento pai => " + element.parent().attr("idtag"));
			}
			array_tags.push({
				name:element.prop("tagName"),
				attributes : atributos,
				parent:element.parent().attr("idtag"),
				idtag:idtag,
				text:texto
			});
		});

		$.ajax({
			type:"POST",
			url:"index.php?controller=editor/salvareditor",
			data:{
				op:"salvar_pagina_editor",
				tags:array_tags,
				nome_pagina:nome_pagina,
				id_pagina:pagina.id,
				idtag:idtag
			},
			success:function(retorno){
				pagina.id = retorno;
			}
		});
	}
}
Pagina.prototype.addElement = function(){
	
	pagina.idtag = pagina.idtag+1;
	
	if (pagina.objSelected == undefined){
		console.log("Nenhuma Elemento Selecionado");
		return false;
	} 
	if (arguments.length <= 0 ){

		pagina.objSelected.attr("idtag", (pagina.idtag));
		
		if (typeof pagina.objectMove === "undefined"){
			pagina.objectMove = $(pagina.editorBody);
		}
			
		// Adiciona o elemento dentro do outro
		pagina.objectMove.append(pagina.objSelected);
		
		addEventoEditor(pagina.objSelected);
	}else{
		var objFilho = arguments[0];
		var objPai = arguments[1];
		
		objFilho.attr("idtag", (pagina.idtag));
		
		// Adiciona o elemento dentro do outro
		objPai.append(objFilho);
		
		addEventoEditor(objFilho);
		
	}
}
Pagina.prototype.createElement = function(tagName,fields){	
	var objTag = $("<"+tagName+">",fields)
	if (arguments.length > 2){
		objTag.html(arguments[2]);
	}
	// Retorna o elemento HTML
	return objTag;
}
var pagina = new Pagina();
// Arrasta 
$( "#layoutBar" ).draggable({
	cancel: "#layoutBar .panel-body"
});
  $(function(){
		$(".editor-element").draggable();
  });
// Botões do Editor
$(".editor-element").draggable({
	
	helper:"clone",
	drag:function(){ /* **** Ação quando o elemento do editor está sendo arrastado **** */
		
		var objecttag = $(this).data("tag");
		var classattribute = "";
		
		if (objecttag == "div" || objecttag == "main" || objecttag == "header" || objecttag == "article" || objecttag ==  "footer") classattribute = "no-visible";
		
		// Criando Elemento
		var fields = {
			class:classattribute
		}
		clearBarProperties();
		pagina.objSelected = pagina.createElement(objecttag,fields);
	},
	stop:function(){ /* **** Ação quando o elemento do editor for solto **** */

		// Adicionando Elemento
		pagina.addElement();
		
		if ($(this).data("componente") != undefined){
			eval("if (typeof " + $(this).data("componente") + " == 'function') " + $(this).data("componente") + "();");
		}
		
		var idtag = $(pagina.objSelected).attr("idtag");
		
		// Soltamos o objeto
		$(pagina.objSelected).draggable({
			stop:function(){
				selectedElement($(pagina.objSelected));
			}
		});		
		
		// Criar um novo Atributo ao clicar na tabela
		$(".table-dom-attribute,.table-css-attribute").dblclick(function(){
			if ($(this).hasClass("table-dom-attribute")){
				var tipo = "dom";
			}else{
				var tipo = "css";
			}
			
			var tr = 	$('<tr>' +
						'<td width="5%"><img src="system/images/icon/editor/delete.png" class="delete-attribute" /></td>' +
						'<td width="35%"><input type="text" class="name-attribute  ui-autocomplete-input" /></td>' +
						'<td width="60%"><input type="text" class="value-attribute" /></td>' +
					'</tr>'
					);
			$(this).append(tr);
			$( ".table-css-attribute .name-attribute" ).autocomplete({
			  source: [ "background",
						"background-attachment",
						"background-image",
						"background-color",
						"background-position",
						"background-repeat",
						"border",
						"border-bottom",
						"border-bottom-color",
						"border-bottom-style",
						"border-bottom-width",
						"border-color",
						"border-left",
						"border-left-color",
						"border-left-style",
						"border-left-width",
						"border-right",
						"border-right-color",
						"border-right-style",
						"border-right-width",
						"border-style",
						"border-top",
						"border-top-color",
						"border-top-style",
						"border-top-width",
						"border-width",
						"clear",
						"clip",
						"color",
						"cursor",
						"display",
						"filter",
						"float",
						"font",
						"font-face",
						"font-family",
						"font-size",
						"font-style",
						"fonte-variant",
						"font-weight",
						"height",
						"import",
						"left",
						"letter-spacing",
						"line-height",
						"list-style",
						"list-style-image",
						"list-style-position",
						"list-style-type",
						"margin",
						"margin-left",
						"margin-right",
						"margin-bottom",
						"margin-top",
						"overflow",
						"padding",
						"padding-bottom",
						"padding-left",
						"padding-right",
						"padding-top",
						"page-break-after",
						"page-break-before",
						"position",
						"text-align",
						"text-decoration",
						"text-indent",
						"text-transform",
						"top",
						"vertical-align",
						"visibility",
						"width",
						"z-index" ]
			});

			$( ".table-dom-attribute .name-attribute" ).autocomplete({
			  source: [ "accept",
						"accept-charse",
						"action",
						"alt",
						"async",
						"autobuffer",
						"autocomplete",
						"autofocus",
						"autoplay",
						"challenge",
						"charset",
						"charset",
						"checked",
						"cite",
						"cols",
						"colspan",
						"content",
						"controls",
						"coords",
						"data",
						"datetime",
						"datetime",
						"defer",
						"disabled",
						"enctype",
						"for",
						"form",
						"formaction",
						"formenctype",
						"formmethod",
						"formnovalidate",
						"formtarget",
						"headers",
						"height",
						"high",
						"href",
						"hreflang",
						"http-equiv",
						"icon",
						"id",
						"ismap",
						"keytype",
						"label",
						"list",
						"loop",
						"low",
						"manifest",
						"max",
						"maxlength",
						"media",
						"method",
						"min",
						"multiple",
						"name",
						"novalidate",
						"onafterprint",
						"onbeforeprint",
						"onbeforeunload",
						"onblur",
						"onerror",
						"onfocus",
						"onhashchange",
						"onload",
						"onmessage",
						"onoffline",
						"ononline",
						"onpopstate",
						"onredo",
						"onresize",
						"onstorage",
						"onundo",
						"onunload",
						"open",
						"optimum",
						"pattern",
						"ping",
						"placeholder",
						"poster",
						"pubdate",
						"radiogroup",
						"readonly",
						"rel",
						"required",
						"reversed",
						"rows",
						"rowspan",
						"sandbox",
						"scope",
						"scoped",
						"seamless",
						"selected",
						"shape",
						"size",
						"sizes",
						"span",
						"src",
						"start",
						"step",
						"summary",
						"target",
						"target",
						"type",
						"usemap",
						"value",
						"width",
						"wrap"]
			});
			$(".name-attribute").blur(function(){
				addNameAttribute($(this),this.value,idtag);
			});			
			$(".value-attribute").keypress(function(event){
				if ( event.which == 13 ) {
					addValueAttribute(this,1);
				}
			});
			$(".value-attribute").blur(function(){
				addValueAttribute(this,1);
			});
			deleteAttribute($(this));
		});
		
		addContainerClass(pagina.objSelected);
		addAlcasRedimensionamneto(pagina.objSelected);
	},
});
$("#btn-salvar-editor").click(function(){
	pagina.salvar();
});
// Função para limpar a barra de propriedades
function clearBarProperties(){
	pagina.objSelected = "";
	$(".table-dom-attribute,.table-css-attribute").find("tr").remove();
}
// Funçao para adicionar atributos na barra de propriedades
function addBarAttributes(obj){
	
	$(".table-geral-attribute tr").remove();
	$(".table-geral-attribute").find("tr").hide();
	var idtag = obj.attr("idtag").trim();	
	if ($(".table-geral-attribute").find("tr[id=TR_idtag_"+idtag+"]").length <= 0){
		var idNameTexto ="name_texto_"+idtag;
		var idValueTexto = "value_texto_"+idtag;
		var trTexto = $('<tr id="TR_idtag_'+idtag+'">' +
			'<td width="35%"><input id="'+idNameTexto+'" type="text" class="name-attribute  ui-autocomplete-input" value="Texto" readonly-="true" /></td>' +
			'<td width="60%"><input id="'+idValueTexto+'" type="text" class="value-attribute" value="'+retornaTextoTag($(obj))+'"/></td>' +
			'</tr>'
		);
		$(".table-geral-attribute").append(trTexto);
		registraAcaoBotaoValueBarraPropriedades();		
	}else{
		$(".table-geral-attribute").find("tr").show()
		$("#value_texto_"+idtag).val(retornaTextoTag($(obj)));
	}

	if (obj.context == undefined){
		var objatributtes = obj[0].attributes;
	}else{
		var objatributtes = obj.context.attributes;
	}

	// Recuperando os valores de atributos e propriedades
	for (a in objatributtes){

		var attribute = objatributtes[a];		
		if( typeof attribute != 'function'){
			
			if (attribute.nodeName == undefined) continue;
			
			var nameAttr		= attribute.nodeName.trim();
			var valueAttr		= attribute.nodeValue.trim();			

			if (nameAttr != undefined){				
				if (nameAttr.toLowerCase() == "idtag") continue;
				// Tratamento para o atributo "class"
				if (nameAttr.toLowerCase() == "class"){
					valueAttr = valueAttr.replace("no-visible","").replace("ui-droppable","").replace("ui-draggable-handle","").replace("ui-draggable","").replace("ui-draggable-dragging","");
					if (valueAttr.trim()=="" || valueAttr.undefined) continue;
				}
				// Tratamento para a classe "style"
				if (nameAttr.toLowerCase() == "style"){					
					var atrrCSS = valueAttr.split(";");
					for (b in atrrCSS){
						valueAttr 	= atrrCSS[b].split(":")[1];
						nameAttr 	= atrrCSS[b].split(":")[0];
						
						if (nameAttr == undefined || valueAttr == undefined) continue;
						
						if (!existeAtributoCSS(idtag,nameAttr)){
							if (nameAttr.trim() == "position"){
								obj.css(nameAttr.trim(),"relative");
							}else{								
								// Tratando atributos de relacionamentos
								if (nameAttr.trim() != "height"){
									obj.css(nameAttr.trim(),"");
								}
							}
							continue;
						}else{
							valueAttr = retornaAtributoCSS(idtag,nameAttr);
							console.log(nameAttr.trim() + " => " + valueAttr);
							obj.css(nameAttr.trim(),valueAttr);
						}
						var imgAttribute =  $('<img data-attribute="name_'+nameAttr.trim()+'_'+idtag+'" src="system/images/icon/editor/delete.png" class="delete-attribute" />')
						var nameAttribute = $('<input id="name_'+nameAttr.trim()+'_'+idtag+'" type="text" class="name-attribute  ui-autocomplete-input" value="'+nameAttr.trim()+'"/>');
						var valueAttribute = $('<input id="value_'+nameAttr.trim()+'_'+idtag+'" type="text" class="value-attribute" value="'+valueAttr.trim()+'"/>');
						
						nameAttribute.blur(function(){
							addNameAttribute($(this),this.value,idtag);
						});			
						valueAttribute.keypress(function(event){
							if ( event.which == 13 ) {
								addValueAttribute(this,2);
							}
						});
						valueAttribute.blur(function(){
							addValueAttribute(this,2);
						});
						var tdImg = $('<td width="5%"></td>');
						tdImg.append(imgAttribute);
						var tdName = $('<td width="35%"></td>');
						tdName.append(nameAttribute);
						var tdValue = $('<td width="60%"></td>');
						tdValue.append(valueAttribute);
						var tr = 	$('<tr />');
						tr.append(tdImg);
						tr.append(tdName);
						tr.append(tdValue);
						$(".table-css-attribute").append(tr);
					}
				}else{
					var tr = 	$('	<tr>' +
										'<td width="5%"><img data-attribute="name_'+nameAttr+'_'+idtag+'" src="system/images/icon/editor/delete.png" class="delete-attribute" /></td>' +
										'<td width="35%"><input type="text" id="name_'+nameAttr.trim()+'_'+idtag+'" class="name-attribute  ui-autocomplete-input" value="'+nameAttr+'"/></td>' +
										'<td width="60%"><input type="text" id="value_'+nameAttr.trim()+'_'+idtag+'" class="value-attribute" value="'+valueAttr+'"/></td>' +
									'</tr>'
								);
					$(".table-dom-attribute").append(tr);
					deleteAttribute($(".table-dom-attribute"));
				}
			}
		}
	}
}
// Mostrar o Icone para excluir um atributo
function deleteAttribute(table){
	table.find("tr").hover(function(){
		//$(".delete-attribute").hide();
		//$(this).find(".delete-attribute").show();
		$(this).find(".delete-attribute").click(function(){
			if (table.hasClass("table-dom-attribute")){
				pagina.objSelected.removeAttr($("#" + $(this).data("attribute")).val());
			}else{
				if ($("#" + $(this).data("attribute")).val() != undefined){
					pagina.objSelected.css($("#" + $(this).data("attribute")).val(),"");
				}	
			}
			$(this).parents("tr").remove();
		});
	});	
}
$(document).ready(function(){
	if (GET("id") != ""){
		pagina.id = GET("id");
		carrgarPagina(GET("id"));
	}
});
function selectedElement(element){	
	clearBarProperties();
	pagina.objSelected = element;
	addBarAttributes(pagina.objSelected);
}
function RGB2HTML(red, green, blue)
{
    var decColor = parseInt(red) + 256 * parseInt(green) + 65536 * parseInt(blue);
	var zeros = "";	
	for (i=1;i<=(6-String(decColor.toString(16)).length);i++){
		zeros += "0";
	}
    return "#" + decColor.toString(16) + zeros;
}
function addNameAttribute(obj,valueName,idtag){
	obj.attr("id","name_" + valueName + "_" + idtag);
	obj.parents("tr").find(".value-attribute").attr("id","value_" + valueName + "_" + idtag);
	obj.parents("tr").find(".delete-attribute").attr("data-attribute","name_" + valueName + "_" + idtag);	
}
// Parametro "origem": 1 - Adicionado pelo usuário , 2 - Adicionado pelo arrasto do objeto 
function addValueAttribute(obj,origem){
	if ($(obj).prop("id") == "" || $(obj).prop("id") == undefined) return false;
	if (pagina.objSelected == "") return false;
	
	var idName = $("#name_"+$(obj).attr("id").split("_")[1]+"_"+$(obj).attr("id").split("_")[2]).val();
	if ($(obj).parents("table").hasClass("table-dom-attribute")){
		var tipo = "dom";
	}else if($(obj).parents("table").hasClass("table-css-attribute")){
		var tipo = "css";
	}else if($(obj).parents("table").hasClass("table-geral-attribute")){
		var tipo = "geral";
	}
	if (tipo == "dom"){
		if (idName.toLowerCase() == "idtag") return false;
		if (idName == "") return false;
		if ($(pagina.objSelected).attr(idName) == undefined){
			$(pagina.objSelected).attr(idName,$(obj).val());
		}else{
			if ($(obj).val() == ""){
				if (idName.toLowerCase() == "class"){
					$(pagina.objSelected).attr(idName,"no-visible ui-droppable");
				}else{
					$(pagina.objSelected).attr(idName,"");
				}
			}else{
				//if ($(pagina.objSelected).attr(idName).search(" ") >= 0){
				//	$(pagina.objSelected).attr(idName, pagina.objSelected.attr(idName) + " " + $(obj).val());
				//}else{
					//if ($(pagina.objSelected).attr(idName).search($(obj).val()) < 0){
						$(pagina.objSelected).attr(idName,$(obj).val());
					//}
				//}
			}
		}		
	}else if(tipo == "css"){
		if ($(obj).val()!=""){
			pagina.objSelected.css(idName,$(obj).val());
		}else if(idName != ""){
			pagina.objSelected.css(idName,"");
		}
	}else if(tipo == "geral"){
		setaTextoTag(pagina.objSelected,$(obj).val());
		//pagina.objSelected.html($(obj).val());
	}
	if (origem == 1){
		if (typeof elementosEditor[$(pagina.objSelected).attr("idtag")] === "undefined"){
			elementosEditor[$(pagina.objSelected).attr("idtag")] = new Array({atributo:idName,valor:$(obj).val()});
		}else{
			for (s in elementosEditor[$(pagina.objSelected).attr("idtag")]){
				if (elementosEditor[$(pagina.objSelected).attr("idtag")][s].atributo == idName){
					elementosEditor[$(pagina.objSelected).attr("idtag")].splice(s,1);
				}
			}
			elementosEditor[$(pagina.objSelected).attr("idtag")].push({atributo:idName,valor:$(obj).val()});
		}
	}	
}
function GET(variavel){
	var variavelRetorno = "";
	if (location.href.search(variavel) > 0){
		var parms = location.href.split("?")[1].split("&");
		for (p in parms){
			var par = parms[p].split("=");
			if (par[0] == variavel){
				variavelRetorno = par[1];
			}
		}
		return variavelRetorno;
	}else{
		return variavelRetorno;
	}
}
function carrgarPagina(idPagina){
	$.ajax({
		url:"index.php?controller=editor/loadeditor",
		data:{
			op:"load_pagina_editor",
			pagina:idPagina
		},
		dataType:"json",
		complete:function(retorno){
			var retorno = JSON.parse(retorno.responseText);
			for (t in retorno){
				var obj = pagina.createElement(retorno[t].tag,retorno[t].atributos,retorno[t].texto);
				$(".editor-body").append(obj);
				addEventoEditor(obj);
				if (retorno[t].paitag != ""){
					$("[idtag="+retorno[t].paitag+"]").append(obj);
				}
				pagina.idtag++;
			}
		}
	});
}
function addEventoEditor(objeto){

	// Seleciona elemento ao clicar
	objeto.click(function(e){
		e.stopPropagation();
		e.preventDefault();

		// Evita o re-carregamento da barra de propriedades caso o elemento já esteja selecionado
		if ($(this).attr("idtag") !== $(pagina.objSelected).attr("idtag")){
			clearBarProperties();
			pagina.objSelected = $(this);
			addBarAttributes($(this));
		}
		
		// Clica para editar um texto
		if ($(this).hasClass("edicao-texto")){
			//$(this).click(function(){
				criarCaixaEdicaoTexto($(this));
			//});
		}
		
	});

	// Soltamos o objeto
	$(objeto).droppable({
		greedy: true,
		drop:function( event, ui){ // Ocorre quando deixamos um objeto em cima do elemento
			$(this).append(pagina.objSelected);
			pagina.objectMove = $(this);			
		}
	});

	// Arrasta o objeto
	$(objeto).draggable({
		drag:function(){ // Ocorre quando arrastamos um objeto
			pagina.objSelected = $(this);			
		}
	});
}

$( function() { // Bloco de Tratamento especial para o corpo do editor
	$(pagina.editorBody).droppable({
		greedy: true,
		drop:function( event, ui){ // Ocorre quando deixamos um objeto em cima do editor
			$(this).append(pagina.objSelected);
			pagina.objectMove = $(this);
		}
	});
	$(pagina.editorBody).click(function(e){
		e.stopPropagation();
		pagina.objSelected = "";
	});
});	
function corHexadecimalClass(classes){
	var corhexadecimal = "";
	var classes_array = classes.split(";");
	var retornoClasse = "";
	for(c in classes_array){
		var style = classes_array[c].trim();
		if (style != ""){
			if (style.split("rgb(").length > 1){
				var separandacores = style.split("rgb(")[1].replace(" ","").replace(" ","").replace(");","").replace(")","");
				var desformatarcor = separandacores.split(",");

				if (isNumeric(desformatarcor[0].trim()) && isNumeric(desformatarcor[1].trim()) && isNumeric(desformatarcor[2].trim())){
					corhexadecimal = style.split("rgb(")[0] + RGB2HTML(desformatarcor[0],desformatarcor[1],desformatarcor[2]);
				}
				retornoClasse += corhexadecimal.split(":")[0].trim() + ":" + corhexadecimal.split(":")[1].trim() + ";";
			}else{
				retornoClasse += style.split(":")[0].trim() + ":" + style.split(":")[1].trim() + ";";
			}
		}
	}
	return retornoClasse;
}
var elementosEditor = [];
function existeAtributoCSS(objIdTag,atributo){
	var existe = false;
	for (s in elementosEditor[objIdTag]){
		if (elementosEditor[objIdTag][s].atributo.trim() == atributo.trim()){
			existe = true;
		}
	}
	return existe;
}
function retornaAtributoCSS(objIdTag,atributo){
	var valor = false;
	for (s in elementosEditor[objIdTag]){
		if (elementosEditor[objIdTag][s].atributo.trim() == atributo.trim()){
			valor = elementosEditor[objIdTag][s].valor.trim();
		}
	}
	return valor;
}
$(document).keyup(function(event){
	if ( event.which == 46 ) {
		if (pagina.objSelected){
			pagina.objSelected.remove();
			clearBarProperties();
		}
	}
});

var ferramentasShow = true;
shortcut.add("F6",function() 
{
	if (ferramentasShow){
		$(".leftbar-editor,#btn-salvar-editor").hide();
		$(".addbuttoncontainer").hide();
		ferramentasShow = false;
	}else{		
		$(".leftbar-editor,#btn-salvar-editor").show();
		$(".addbuttoncontainer").show();
		ferramentasShow = true;
	}
	
});
function retornaTextoTag(obj){
	var texto = "";
	var res = $.parseHTML(obj.html());
	for(ph in res){
		if (typeof res[ph].data === "string"){
			texto = res[ph].data;
		}
	}
	return texto;
}
function setaTextoTag(obj,texto){
	var res = $.parseHTML(obj.html());
	obj.html("");
	for(ph in res){
		if (typeof res[ph].data === "string"){
			//texto = res[ph].data;
			obj.append(texto);
		}else{
			obj.append(res[ph]);
		}
	}
}

function registraAcaoBotaoValueBarraPropriedades(){
	$(".value-attribute").keypress(function(event){
		if ( event.which == 13 ) {
			addValueAttribute(this,1);
		}
	});
	$(".value-attribute").blur(function(){
		addValueAttribute(this,1);
	});
}

function criarCaixaEdicaoTexto(obj){
	
	var pai = obj.parent();
	var texto = obj.html();
	var novo = $("<input type=\'text\' value=\'"+texto+"\'>");

	novo.css("border","0px");
	obj.hide();
	pai.append(novo);
	novo.focus();
	
	novo.blur(function(){
		retornarEdicaoTexto();
	});
	
	novo.keyup(function(e){
		if (e.which == 13){
			retornarEdicaoTexto();
		}
	});
	
	function retornarEdicaoTexto(){
		novo.hide();
		obj.html(novo.val());
		obj.show();
		selectedElement(obj);
		novo.remove();
	}
}
function addContainerClass(elemento){
	var tagname = elemento.prop("tagName").toLowerCase();
	var addbuttoncontainer = $('<button type="button" class="btn btn-default addbuttoncontainer" aria-label="Left Align"><span class="fas fa-plus-circle" aria-hidden="true"></span>');

	$(addbuttoncontainer).css("position","absolute");
	$(addbuttoncontainer).css("left",(pagina.indiceaddcontainerclass * 40));
	$(addbuttoncontainer).css("bottom","-35px");
	$(addbuttoncontainer).css("height","35px");

	if (tagname != "img"){
		$(elemento).addClass("element-container");
		$(elemento).append(addbuttoncontainer);
		$(elemento).hover(function(e){
			e.preventDefault();
			e.stopPropagation();
			$(this).css("border","1px solid #000");
			addbuttoncontainer.css("border","1px solid #000");
		});
		$(elemento).mouseout(function(e){
			e.preventDefault();
			e.stopPropagation();			
			$(this).css("border","1px dashed #AAA");
			addbuttoncontainer.css("border","1px solid #F0F0F0");
		});
		pagina.indiceaddcontainerclass += 1;
		
	}
}
function addAlcasRedimensionamneto(elemento){
	var centrocima = $("<div id='centrocima' class='alcasredimensionamento'></div>");
	var centrobaixo = $("<div id='centrobaixo' class='alcasredimensionamento'></div>");
	var linhahorizontalreferencia = $("<hr class='linhahorizontalreferencia'>");
	var tamanho = 5;
	var metade = tamanho / 2;
	var ajuste = 4;
	
	var largura = parseInt(elemento.css("width").replace("px",""));
	var altura = parseInt(elemento.css("height").replace("px",""));	
	
	centrobaixo.css("left",(largura / 2) - ajuste);
	centrobaixo.css("top",(altura - ajuste));
	
	centrocima.css("left",(largura / 2) - ajuste);
	centrocima.css("top",(0 - ajuste));
	
	elemento.append(centrocima);
	elemento.append(centrobaixo);
	elemento.append(linhahorizontalreferencia);

	// Arrasta o objeto
	$(centrobaixo).draggable({
		drag:function(){ // Ocorre quando arrastamos um objeto
			elemento.css("position","absolute");
			var topabsoluto = parseFloat(elemento.css("top").replace("px",""));
			elemento.css("position","relative");
			var novaaltura = (parseFloat(mouseposition.y) - topabsoluto);
			var posicaocentrobaixo = parseFloat(novaaltura) - parseInt(metade);
			var topolimite = parseFloat(topabsoluto + 5);
			if (novaaltura >= topolimite){
				elemento.css("height",novaaltura);
				$(centrobaixo).css("top", posicaocentrobaixo);
			}
			if (posicaocentrobaixo <= topolimite){
				console.log("Passou do Limite => " + topolimite + "=>" + posicaocentrobaixo);
				$(centrobaixo).css("top", (parseFloat(posicaocentrobaixo) - parseInt(metade)));
				return false;
			}
		}
	});	
	$(centrocima).draggable({
		drag:function(){ // Ocorre quando arrastamos um objeto
			elemento.css("position","absolute");
			var topabsoluto = parseFloat(elemento.css("top").replace("px",""));
			elemento.css("position","relative");
			var alturaelemento = parseFloat(elemento.css("height").replace("px",""));
			var deltamovimentacao = parseFloat(mouseposition.y) + parseFloat(topabsoluto);
			var novaaltura = parseFloat(alturaelemento) - parseFloat(deltamovimentacao);
			linhahorizontalreferencia.css("top",parseFloat(mouseposition.y) - 21.7);
			//elemento.css("height",novaaltura);
			//elemento.css("top",parseFloat(mouseposition.y));
			
		},
		stop: function( event, ui ) {
			console.log();
			elemento.css("position","absolute");
			var topabsoluto = parseFloat(elemento.css("top").replace("px",""));
			elemento.css("position","relative");			
			var linhaauxiliartop = parseFloat(linhahorizontalreferencia.css("top"));
			var alturaelemento = parseFloat(elemento.css("height").replace("px",""));
			console.log(linhaauxiliartop + "^" + topabsoluto + "^" + alturaelemento);
			elemento.css("height",alturaelemento - (linhaauxiliartop - topabsoluto));
			console.log("Soltou e parou de arrastar");
		}

	});
	
	function reajustacentrocima(top){
		centrocima.css("left",(largura / 2) - ajuste);
		//centrocima.css("top",(top));
		$("#centrocima").css("top",top);
	}
}
var mouseposition = {};
$( document ).on( "mousemove", function( event ) {
  //$( "#log" ).text( "pageX: " + event.pageX + ", pageY: " + event.pageY );
  mouseposition = {
	  x: event.pageX,
	  y: event.pageY
  };
});