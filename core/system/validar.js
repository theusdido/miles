// Validações e Formatação padrão para formulário
function validar (campos,contexto,msgRetornoContexto){
	for (campo in campos){
		if ($("#" + campo).hasClass("td-file-hidden")){
			if ($("#" + campo).val().search("E~") >= 0){
				abrirAlerta("Campo &nbsp;" + $("#" + campo).attr("id") + " &eacute; obrigat&oacute;rio.","alert-danger",msgRetornoContexto);
				return false;
			}
		}
		if ($("#" + campo).hasClass("formato-ckeditor")){
			if ($("#" + campo).val() != ""){
				$("#" + campo).parents().removeClass("has-error");
			}
		}
		if ($("#" + campo).hasClass("td-file-hidden")){
			if ($("#" + campo).val() != ""){
				$("#" + campo).parents().removeClass("has-error");
			}
		}
	}

	for (campo in campos){
		var campo =	$("#"+campo,contexto);
		if ((campo.val() == "" || campo.val() == null || campo.val() == undefined) && (campo.attr("name")!=undefined)){
			if (campo.attr("type") == "hidden" || campo.css("display") == "none" || campo.parent().css("display") == "none"){
				abrirAlerta("Atributo<small>( Oculto )</small> &nbsp;" + campo.attr("name"),"alert-danger",msgRetornoContexto);
			}
			if (campo.attr("data-entidade") == undefined){
				var dataentidade = "";
			}else{
				var dataentidade = '[data-entidade='+campo.attr("data-entidade")+']';
			}

			var campoMesmo = $(".form-control"+dataentidade+"[id="+campo.attr("id")+"]");

			$('a[href="#' + campoMesmo.parents(".tab-pane").attr("id") + '"]',contexto).first().tab('show');
			campoMesmo.blur(function(){
				if (this.value != "") $(this).parent().removeClass("has-error");
			});
			campoMesmo.parent().addClass("has-error");
			setTimeout(function(){
				campoMesmo.focus();
			},500);
			console.log("Campo inválido => " + campo.attr("id") + "[" + campo.attr("data-entidade") + "]");
			return false;
		}else if(validarFormatacao(campos)){ // Verifica se um campo já está vázio
			interromper = true;
			return false;
		}else{
			interromper = true;
			$(this).parents().removeClass("has-error");
		}
	}
	return true;
}

// Validação de E-Mail
function validarEmail(email){			
	er = /^[a-zA-Z0-9][a-zA-Z0-9\._-]+@([a-zA-Z0-9\._-]+\.)[a-zA-Z-0-9]{2}/;
	if(!er.exec(email) ){	
		return false;
	}else{
		return true;
	}
}
// Validar Telefone
function validarTelefone(telefone){
	valor = replaceAll(telefone,"()- ","");//.replace("(","").replace(")","").replace("-","").replace(" ","");
	if (valor.length == 10 || valor.length == 11){
		return true;
	}else{
		return false;
	}
}
// Verifica se tem algum campo com formataÃ§Ã£o invÃ¡lida
function validarFormatacao(campos){	
	var retorno = false;
	for (campo in campos){
		$("#" + campo + " .form-group").each(function(){
			if ($(this).hasClass("has-error")){				
				retorno = true;
			}
		});
	}		
	return retorno;
}
// Validar CPF
function validarCPF(strCPF){
	var strCPF = replaceAll(strCPF,".-","");
    var Soma;
    var Resto;
    Soma = 0;
	if (strCPF == "00000000000") return false;
	if (strCPF == "11111111111") return false;
	if (strCPF == "22222222222") return false;
	if (strCPF == "33333333333") return false;
	if (strCPF == "44444444444") return false;
	if (strCPF == "55555555555") return false;
	if (strCPF == "66666666666") return false;
	if (strCPF == "77777777777") return false;
	if (strCPF == "88888888888") return false;
	if (strCPF == "99999999999") return false;
    
	for (i=1; i<=9; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
	Resto = (Soma * 10) % 11;
	
    if ((Resto == 10) || (Resto == 11))  Resto = 0;
    if (Resto != parseInt(strCPF.substring(9, 10)) ) return false;
	
	Soma = 0;
    for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
    Resto = (Soma * 10) % 11;
	
    if ((Resto == 10) || (Resto == 11))  Resto = 0;
    if (Resto != parseInt(strCPF.substring(10, 11) ) ) return false;
    return true;
}
// Validar Data
function validarData(str){	
	valor = replaceAll(str,"/","");		
	if (valor.length == 8){		
		return true;
	}else{
		return false;
	}	
}
// Validar CEP
function validarCEP(str){
	var valor 	= "";
	valor 		= replaceAll(str,"-","");
	valor 		= replaceAll(valor,".","");		
	
	var n 		= valor.search(/[a-z]/ig);	
	if (n == false) return false;
	
	if (valor.length == 8){
		return true;
	}else{
		return false;
	}
}
// Valida CNPJ
function validarCNPJ(cnpj) {
 
    cnpj = cnpj.replace(/[^\d]+/g,'');
 
    if(cnpj == '') return false;
     
    if (cnpj.length != 14)
        return false;
 
    // Elimina CNPJs invalidos conhecidos
    if (cnpj == "00000000000000" ||
        cnpj == "11111111111111" ||
        cnpj == "22222222222222" ||
        cnpj == "33333333333333" ||
        cnpj == "44444444444444" ||
        cnpj == "55555555555555" ||
        cnpj == "66666666666666" ||
        cnpj == "77777777777777" ||
        cnpj == "88888888888888" ||
        cnpj == "99999999999999")
        return false;
         
    // Valida DVs
    tamanho = cnpj.length - 2
    numeros = cnpj.substring(0,tamanho);
    digitos = cnpj.substring(tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0))
        return false;
         
    tamanho = tamanho + 1;
    numeros = cnpj.substring(0,tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1))
          return false;
           
    return true;    
}
// *******************************************
// Estrutura de validação de cada campo
// *******************************************

if (typeof $().mask == "function"){
	// Cep
	$('.formato-cep').mask('99999-999');
	$('.formato-cep').blur(function(){	
		$('.status-cep').remove();
		if (this.value!=''){			
			if (validarCEP(this.value)){	
				statusFormControl(this,'success');
			}else{
				statusFormControl(this,'error');
			}
		}else{
			statusFormControl(this,'default');
		}
	});
	// CNPJ
	$('.formato-cnpj').mask('99.999.999/9999-99');
	$('.formato-cnpj').blur(function(){
		$('.status-cnpj').remove();
		if (this.value!=''){
			if (validarCNPJ(this.value)){
				statusFormControl(this,'success');
			}else{
				statusFormControl(this,'error');
			}
		}else{
			statusFormControl(this,'default');
		}
	});

	// CPF
	$('.formato-cpf').mask('999.999.999-99');
	$('.formato-cpf').blur(function(){	
		$('.status-cpf').remove();
		if (this.value!=''){			
			if (validarCPF(this.value)){
				statusFormControl(this,'success');
			}else{
				statusFormControl(this,'error');
			}
		}else{
			statusFormControl(this,'default');
		}
	});

	//CPFJ - CNPJ e CNPJ
	$('.formato-cpfj').mask('999.999.999-99');
	$('.formato-cpfj').blur(function(){
		$('.status-cpfj').remove();	
		if (this.value!=''){
			if (this.value.length > 14){			
				if (validarCNPJ(this.value)){
					statusFormControl(this,'success');
				}else{
					statusFormControl(this,'error');
				}
			}else{			
				if (validarCPF(this.value)){
					statusFormControl(this,'success');
				}else{
					statusFormControl(this,'error');
				}
			}
		}else{
			statusFormControl(this,'default');
		}
	});
	$(".formato-cpfj").keypress(function(){
		var valor = replaceAll(this.value,".","");
		valor = replaceAll(valor,"-","");
		valor = replaceAll(valor,"/","");
		
		if (this.value.length >= 14){
			$(this).mask("99.999.999/9999-99");
			$(this).val(valor);
		}else{
			$(this).mask("999.999.999-99");
			$(this).val(valor);
		}
	});

	// Data
	$(".formato-data").mask("99/99/9999");
	$(".formato-data").blur(function(){
		if (this.value!=''){
			// Valida a formatação
			if (validarData(this.value)){
				statusFormControl(this,"success");
			}else{
				statusFormControl(this,"error");
				return false;
			}
			// Valida se a data realmente existe
			$.ajax({
				context:this,
				url:typeof config === "undefined"?"index.php?controller=requisicoes":config.urlrequisicoes,
				data:{
					op:"valida-data",
					data:this.value
				},
				complete:function(ret){
					var retorno = ret.responseText;
					if (retorno==1){
						statusFormControl(this,"success");
					}else{
						statusFormControl(this,"error");
					}
				},
				error:function(ret){
					console.log("ERRO ao tentar validar a data => " + ret.responseText);
				}
			});
			if ($(this).hasClass("data-retroativa")){
				$.ajax({
					context:this,
					url:config.urlrequisicoes,
					data:{
						op:"data-retroativa",
						data:this.value
					},
					complete:function(ret){
						var retorno = responseText;
						if (retorno==0){
							statusFormControl(this,"error");
						}
					},
					error:function(ret){
						console.log("ERRO ao tentar validar a data retroativa => " + ret.responseText);
					}
				});
			}
		}else{
			statusFormControl(this,"default");
		}
	});

	//Data e Hora
	$('.formato-datahora').mask('99/99/9999 99:99:99');

	// E-Mail
	$('.formato-email').blur(function(e){	
		$('.status-email').remove();
		if (this.value!=''){
			if (validarEmail(this.value)){
				statusFormControl(this,'success');
			}else{								
				statusFormControl(this,'error');
			}							
		}else{
			statusFormControl(this,'default');
		}
	});

	let casasdecimais = 2;
	try{
		casadecimais = parseInt(config.casasdecimais);
	}catch(e){
		console.warn('Configuração de casas decimais não encontrada.');
	}
	
	if (typeof $.fn.maskMoney === "function"){

		// Moeda
		$(".formato-moeda").maskMoney({
			symbol:"R$", 
			thousands:".", 
			decimal:",",
			symbolStay: true,
			showSymbol:true
		});

		// Número Decimal
		$(".formato-numerodecimal").maskMoney({
			symbol:"", 
			thousands:"", 
			decimal:",", 
			symbolStay: false,
			showSymbol:false, 
			precision:casasdecimais
		});
	}

	// Número Processo Judicial
	$('.formato-numeroprocessojudicial').mask('9999999-99.9999.9.99.9999');

	// Telefone
	$('.formato-telefone').mask('(99) 99999-9999');
	$('.formato-telefone').blur(function(e){
		$('.status-telefone').remove();
		if (this.value!=''){
			if (validarTelefone(this.value)){
				statusFormControl(this,'success');
			}else{
				statusFormControl(this,'error');
			}							
		}else{
			statusFormControl(this,'default');
		}
	});
	$(".formato-telefone").keyup(function(e){
		var numerotelefone = this.value.replace("(","").replace(" ","").replace(")","").replace("-","");
		var codigoarea = numerotelefone.substr(0,2);
		var qtde = numerotelefone.length;
		if (qtde <= 10){
			var PREFIXO = numerotelefone.substr(2,4);
			var numero = numerotelefone.substr(6,4);
		}else{
			var PREFIXO = numerotelefone.substr(2,5);
			var numero = numerotelefone.substr(7,4);
		}
	});

	// Número Inteiro
	$(".formato-numerointeiro").keypress(function(e){
			var tecla = e.which;
			if ((tecla > 47 && tecla < 58)) return true;
			else {
				if (tecla != 8) return false;
				else return true;
			}
	});

	// Calendário
	var clicado = false;
	$(".formato-calendario").parents(".calendar-picker-group").find(".input-group-btn button").click(function(){
		
		$(this).parents(".calendar-picker-group").find(".formato-calendario").datepicker({
			dateFormat: "dd/mm/yy",
			dayNames: ["Domingo","Segunda","Terça","Quarta","Quinta","Sexta","Sábado"],
			dayNamesMin: ["D","S","T","Q","Q","S","S","D"],
			dayNamesShort: ["Dom","Seg","Ter","Qua","Qui","Sex","Sáb","Dom"],
			monthNames: ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"],
			monthNamesShort: ["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"],
			nextText: "Próximo",
			prevText: "Anterior"
		});
		$(this).parents(".calendar-picker-group").find(".formato-calendario").datepicker("show");
		
		if (clicado == false){
			$(this).parents(".calendar-picker-group").find(".formato-calendario").focus();
			clicado = true;
		}else{
			clicado = false;
		}
	});

	// AddList
	$('.add_list').popover({
	 html : true
	});
	$('.add_list').click(function(){
		$(".popover").css("max-width","500px");
		carregar("index.php?controller=crud&op=add&t='.$coluna->chaveestrangeira.'&popover=true","#content-popover-'.$coluna->id.'");
	});
	
	//Mês-Ano
	$(".formato-mesano").mask("99/9999");
	
	// Hora
	$(".formato-hora").mask("99:99:99");
}