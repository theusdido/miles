// Particularidades do sistema
$(document).ready(function(){
		// Cadastro de Credor
		var contexto_credor = "#pagina-td_relacaocredores";
		if ($("#td_tipo",contexto_credor).val()=="2"){
			$("#cnpj",contexto_credor).val("null");
			$("#cnpj",contexto_credor).parent().removeClass("has-error");
			$("#cnpj",contexto_credor).parent().hide();				
			$("#cpf",contexto_credor).parent().show();
			$("#td_tipoempresa").parent().hide();
		}else{
			$("#cpf",contexto_credor).val("null");
			$("#cpf",contexto_credor).parent().removeClass("has-error");
			$("#cpf",contexto_credor).parent().hide();				
			$("#cnpj",contexto_credor).parent().show();
			$("#td_tipoempresa").parent().show();
		}
			
		$("#td_tipo",contexto_credor).change(function(){				
			if ($(this).val() == 1){
				$("#cpf").val(" ");
				$("#cpf").parent().hide();
				
				$("#cnpj").parent().show();
				$("#cnpj").val("");
				$("#td_tipoempresa").parent().show();
			}else{
				$("#cnpj").val(" ");
				$("#cnpj").parent().hide();
				
				$("#cpf").val("");
				$("#cpf").parent().show();
				$("#td_tipoempresa").parent().hide();
			}
		});
		$("#logradouro",contexto_credor).parent().css("width","79%");
		$("#numero",contexto_credor).parent().css("width","20%");
		$("#numero",contexto_credor).parent().css("margin-left","5%");
		$("#logradouro",contexto_credor).parent().parent().append($("#numero").parent());
		
		
});