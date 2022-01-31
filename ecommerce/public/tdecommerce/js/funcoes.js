/****************************************************************************

* Flash Tag Write Object v1.8 - by Lucas Fererira - www.lucasferreira.com   *

* Info and Usage: www.lucasferreira.com/flashtag                            *

* bugs/reports: contato@lucasferreira.com                                   *

****************************************************************************/



if(Browser == undefined)

{

	var Browser = {

		isIE: function(){ return (window.ActiveXObject && document.all && navigator.userAgent.toLowerCase().indexOf("msie") > -1  && navigator.userAgent.toLowerCase().indexOf("opera") == -1) ? true : false; }

	};

}



var Flash = function(movie, id, width, height, initParams)

{

	this.html = "";

	

	this.variables = new Array();

	

	this.flashversion = (typeof initParams != "undefined" && typeof initParams.flashversion != "undefined") ? initParams.flashversion : "7,0,0,0";

	

	this.attributes = {

		"classid": "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000",

		"codebase": "http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab#version=" + this.flashversion,

		"type": "application/x-shockwave-flash"

	};

	

	this.params = { "pluginurl": "http://www.macromedia.com/go/getflashplayer" };

	

	if(movie)

	{

		this.addAttribute("data", movie);

		this.addParameter("movie", movie);

	}

	

	if(id && id != null && (this.id = id)) 

	{

		this.addAttribute("id", this.id);

		this.addAttribute("name", this.id);

	}

	else

	{

		this.id = null;

	}

	

	if(width) this.addAttribute("width", width);

	if(height) this.addAttribute("height", height);

	

	if(initParams != undefined)

	{

		for(var i in initParams) this.addParameter(i.toString(), initParams[i]);

	}

};

Flash.version = "v1.8";



Flash.prototype.getObject = function()

{

	if(this.id == null) return null;

	try

	{

		if(window.document[this.id])

		{

			return window.document[this.id];

		}

		else

		{

			return document.getElementById(window.document[this.id]);

		}

	}

	catch(e) { return null; }

};



Flash.getObjectByExceptions = function(obj, excep)

{

	var tempObj = {};

	for(var i in obj)

	{

		var EOF = false;

		for(var j=0; j<excep.length; j++) if(excep[j] == i.toString()) { EOF = true; break; };

		if(!EOF) tempObj[i] = obj[i];

	}

	return tempObj;

};



Flash.prototype.addAttribute = function(prop, val){ this.attributes[prop] = val; };

Flash.prototype.addParameter = function(prop, val){ this.params[prop] = val; };

Flash.prototype.addVariable = function(prop, val){ this.variables.push([prop, val]); };



Flash.prototype.getFlashVars = function()

{

	for(var i=0, tempString = new Array(); i<this.variables.length; i++) tempString.push(this.variables[i].join("="));

	return tempString.join("&");

};

Flash.prototype.toString = function()

{

	this.params.flashVars = this.getFlashVars();

	if(Browser.isIE())

	{

		//IE

		this.html = "<ob" + "ject";

		var attr = Flash.getObjectByExceptions(this.attributes, ["type", "data"]);

		for(var i in attr) if(i.toString() != "extend") this.html += " " + i.toString() + " = \"" + attr[i] + "\"";

		this.html += "> ";

		var params = Flash.getObjectByExceptions(this.params, ["pluginurl", "extend"]);

		for(var i in params) if(i.toString() != "extend") this.html += "<param name=\"" + i.toString() + "\" value=\"" + params[i] + "\" /> ";

		this.html += " </obj" + "ect>";

	}

	else

	{

		//non-IE

		this.html = "<!--[if !IE]> <--> <obj" + "ect";

		var attr = Flash.getObjectByExceptions(this.attributes, ["classid", "codebase"]);

		for(var i in attr) if(i.toString() != "extend") this.html += " " + i.toString() + " = \"" + attr[i] + "\"";

		this.html += "> ";

		var params = Flash.getObjectByExceptions(this.params, ["extend"]);

		for(var i in params) if(i.toString() != "extend") this.html += "<param name=\"" + i.toString() + "\" value=\"" + params[i] + "\" /> ";

		this.html += " </obj" + "ect> <!--> <![endif]-->";

	}

	return this.html;

};

Flash.prototype.write = Flash.prototype.writeIn = function(w)

{

	if(typeof w == "string" && (w = document.getElementById(w)));

	if( w != null ) { w.innerHTML = this.toString(); }

	else if( w == undefined ) { document.write( this.toString() ); }

    	else { return false; }

};



//automatization functions...

Flash.correctAll = function()

{

	if(!/msie/.test(navigator.userAgent.toLowerCase()) || !document.getElementsByTagName) return false;

	for (var i = 0, objects = document.getElementsByTagName("OBJECT"); i < objects.length;

		(objects[i].outerHTML ? (objects[i].outerHTML = objects[i].outerHTML, objects[i].style.visibility = "visible") : null), i++);

};

Flash.automatic = function(r)

{

	if(r && window.attachEvent)

	{	

		for (var i = 0, objects = document.getElementsByTagName("OBJECT"); i < objects.length; (objects[i].style.visibility = "hidden"), i++);

		window.attachEvent("onload", Flash.correctAll);

		window.attachEvent("onunload", function(){ window.detachEvent("onload", Flash.correctAll); });

	}

	else

	{

		Flash.correctAll();

	}

};







function objeto(nome,largura,altura){



document.write('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"'+

' codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"'+

' width="'+largura+'" height="'+altura+'" id="'+nome+'" align="middle">');



document.write('<param name="allowScriptAccess" value="sameDomain" />');



document.write('<param name="movie" value="'+nome+'.swf" />');



document.write('<param name="quality" value="high" />');

            

document.write('<param name="menu" value="false" />');



document.write('<param name="wmode" value="transparent" />');



document.write('<embed src="'+nome+'.swf" quality="high" menu="false" wmode="transparent" '+

' width="'+largura+'" height="'+altura+'" name="'+nome+'" align="middle"'+

' allowScriptAccess="sameDomain" type="application/x-shockwave-flash"'+

' pluginspage="http://www.macromedia.com/go/getflashplayer" />');



document.write('</object>');



}



function validaform(){
      //   d = document.dados;
         //validar nome
         if (document.dados.nomecon.value == ""){
                   alert("O campo Nome deve ser preenchido!");
                   document.dados.nomecon.focus();
                   return false;
         }
         //validar email
         if (document.dados.emailcon.value == ""){
                   alert("O campo E-mail deve ser preenchido!");
                   document.dados.emailcon.focus();
                   return false;
         }
         //validar email(verificao de endereco eletrônico)
         parte1 =  document.dados.emailcon.value.indexOf("@");
         parte2 =  document.dados.emailcon.value.indexOf(".");
         parte3 =  document.dados.emailcon.value.length;
         if (!(parte1 >= 3 && parte2 >= 6 && parte3 >= 9)) {
                   alert ("O campo E-mail deve ser conter um endereco eletrônico!");
                   document.dados.emailcon.focus();
                   return false;
         }
         //validar telefone
        // if ( document.dados.fonecon.value == ""){
        //           alert ("O campo Telefone deve ser preenchido!");
         //          document.dados.fonecon.focus();
         //          return false;
         //}
		  //validar comentario
         if (document.dados.comentario.value == ""){
                   alert("O campo Mensagem deve ser preenchido!");
                   document.dados.comentario.focus();
                   return false;
         }
         //validar telefone(verificacao se contem apenas numeros)
		// t = document.dados.fonecon.value;
		// t = t.replace(/(\.|\(|\)|\/|\-| )+/g,'');
       //  if (isNaN(t)){
        //           alert ("O campo Telefone deve conter apenas numeros!");
        //           document.dados.fonecon.focus();
        //           return false;
      //   }
       
        //validar telefone(verificacao se contem menos de 10 dígitos)
		// t = document.dados.fonecon.value;
		// t = t.replace(/(\.|\(|\)|\/|\-| )+/g,'');
		// if (t.length < 10){
        //           alert ("O campo Telefone deve conter no mínimo 10 números!");
        //           document.dados.fonecon.focus();
        //           return false;
       //  }
       
         return true;
 }
 function Mascara(tipo, campo, teclaPress) {
	if (window.event)
	{
		var tecla = teclaPress.keyCode;
	} else {
		tecla = teclaPress.which;
	}
 
	var s = new String(campo.value);
	// Remove todos os caracteres à seguir: ( ) / - . e espaço, para tratar a string denovo.
	s = s.replace(/(\.|\(|\)|\/|\-| )+/g,'');
 
	tam = s.length + 1;
 
	if ( tecla != 9 && tecla != 8 ) {
		switch (tipo)
		{
		case 'CPF' :
			if (tam > 3 && tam < 7)
				campo.value = s.substr(0,3) + '.' + s.substr(3, tam);
			if (tam >= 7 && tam < 10)
				campo.value = s.substr(0,3) + '.' + s.substr(3,3) + '.' + s.substr(6,tam-6);
			if (tam >= 10 && tam < 12)
				campo.value = s.substr(0,3) + '.' + s.substr(3,3) + '.' + s.substr(6,3) + '-' + s.substr(9,tam-9);
		break;
 
		case 'CNPJ' :
 
			if (tam > 2 && tam < 6)
				campo.value = s.substr(0,2) + '.' + s.substr(2, tam);
			if (tam >= 6 && tam < 9)
				campo.value = s.substr(0,2) + '.' + s.substr(2,3) + '.' + s.substr(5,tam-5);
			if (tam >= 9 && tam < 13)
				campo.value = s.substr(0,2) + '.' + s.substr(2,3) + '.' + s.substr(5,3) + '/' + s.substr(8,tam-8);
			if (tam >= 13 && tam < 15)
				campo.value = s.substr(0,2) + '.' + s.substr(2,3) + '.' + s.substr(5,3) + '/' + s.substr(8,4)+ '-' + s.substr(12,tam-12);
		break;
 
		case 'TEL' :
			if (tam > 2 && tam < 4)
				campo.value = '(' + s.substr(0,2) + ') ' + s.substr(2,tam);
			if (tam >= 7 && tam < 11)
				campo.value = '(' + s.substr(0,2) + ') ' + s.substr(2,4) + '-' + s.substr(6,tam-6);
		break;
 
		case 'DATA' :
			if (tam > 2 && tam < 4)
				campo.value = s.substr(0,2) + '/' + s.substr(2, tam);
			if (tam > 4 && tam < 11)
				campo.value = s.substr(0,2) + '/' + s.substr(2,2) + '/' + s.substr(4,tam-4);
		break;
		}
	}
}

