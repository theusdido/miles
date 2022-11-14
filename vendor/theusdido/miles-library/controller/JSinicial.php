<?php	
	// JavaScript Inicial do Sistema
	$JSinicial = tdClass::Criar("script");
	$JSinicial->add('
			var td_entidadeauxiliar = [];
			function entidadesAuxiliares(){
				for(ea in td_entidade){
					if (td_entidade[ea].entidadeauxiliar == 1){
						var atributos = "id";
						for(a in td_atributo){
							if (td_atributo[a].entidade == td_entidade[ea].id){
								if (td_atributo[a].nome != "empresa" && td_atributo[a].nome != "projeto"){
									atributos += "," + td_atributo[a].nome;
								}
							}
						}
						$.ajax({
							url:config.urlrequisicoes,
							data:{
								op:"retorna_dados_entidade",
								entidade:td_entidade[ea].nomecompleto.replace("-","."),
								atributos:atributos,
								entidadeid:td_entidade[ea].id
							},
							dataType:"json",
							complete:function(ret){
								var retorno = JSON.parse(ret.responseText);								
								td_entidadeauxiliar[retorno.entidadeid] = retorno.dados;
							}
						});
					}
				}
			}
			
			// Relógio
			function relogio(){
				var data 	= config.relogio.split(" ")[0];
				var tempo 	= config.relogio.split(" ")[1];
				
				if (arguments.length <= 0){
					var dia 	= parseInt(data.split("-")[2]);
					var mes 	= parseInt(data.split("-")[1]);
					var ano 	= parseInt(data.split("-")[0]);
					var hora	= parseInt(tempo.split(":")[0]);
					var minuto	= parseInt(tempo.split(":")[1]);
					var segundo	= parseInt(tempo.split(":")[2]);
				}else{
					var dia 	= parseInt(arguments[0]);
					var mes 	= parseInt(arguments[1]);
					var ano 	= parseInt(arguments[2]);
					var hora	= parseInt(arguments[3]);
					var minuto	= parseInt(arguments[4]);
					var segundo	= parseInt(arguments[5]);
					
					if (segundo >= 59){
						segundo = 0;
						minuto++;
					}else{
						segundo++;
					}
					
					if (minuto > 59){
						minuto = 0;
						hora++;
					}
					
					if (hora > 23){
						hora = 0;
						dia++;
					}
					// Tratamento para o mes de fevereiro
					if (mes == 2){
						if (dia > 29){
							if ((ano%4)!=0){ // Ano Bissexto
								dia = 1;
							}else if(dia ==30){
								dia = 1;
							}
						}
					}else{
						if (dia >= 31){
							if (mes == 1 || mes == 3 || mes == 5 || mes == 7 || mes == 9 || mes == 11){
								dia = 1;
							}else if (dia == 32){
								dia = 1;
							}
						}
					}
					
					if (mes > 12){
						mes = 1;
						ano++;
					}
				}
				
				var data_format = completaString(dia,2) + "/" + completaString(mes,2) + "/"	+ completaString(ano,4);
				var hora_format	= completaString(hora,2) + ":" + completaString(minuto,2) + ":" + completaString(segundo,2);
				config.datahora = data_format + " " + hora_format;
				setTimeout("relogio("+dia+","+mes+","+ano+","+hora+","+minuto+","+segundo+")",1000);
				$("#temposessao-home span").html(hora_format); 
				$("#data-home span").html(data_format);
			}
			relogio();

			function goHome(){
				carregar("?controller=home","#conteudoprincipal");
			}

			// Carrega a Home
			goHome();

			// Ativa o Scroll
			$("body").css("overflow","auto");

			// Requisições
			$.ajaxSetup({
				headers: { "CustomHeader": "myValue" }				
			});

			function menuleft(){
				carregar("?controller=component&_component=menu-left","#menulateralesquerdoprincipal");
			}

			//menuleft();
	' .
	(CURRENT_THEME=='desktop'?'$("body").css("background-image","none");':'')
	);