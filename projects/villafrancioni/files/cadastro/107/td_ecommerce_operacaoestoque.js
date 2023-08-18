/*
 * JS Personalizado 
 * @Data de Criacao: 18/01/2023 07:55:46 
 * @Criado por: Edilson Bitencourt, @id: 1 
 * @Página: 107 - Operação de Estoque[ td_ecommerce_operacaoestoque ] 
 */

// Invocado ao clicar no botão Novo
function beforeNew(){
	 var btnnew = arguments[0];
	 is_new_posicaoestoque = true;
}
// Executa após o carregamento padrão de uma novo registro
function afterNew(){
	 var contexto = arguments[0];
}
// Invocado ao clicar no botão Salvar
function beforeSave(){
	 var btnsave = arguments[0];
}
// Executa após o salvamento padrão de um registro
function afterSave(){
	 var fp = arguments[0];
	 var btnsave = arguments[1];
	 if (is_new_posicaoestoque){
		addEstoque();
	 }
}
// Invocado ao clicar no botão Editar 
function beforeEdit(){
	is_new_posicaoestoque = false;
	 var entidade = arguments[0];
	 var registro = arguments[1];
}
// Executa após o carregamento padrão da edição de registro
function afterEdit(){
	 var entidade = arguments[0];
	 var registro = arguments[1];
}
// Invocado ao clicar no botão Voltar
function beforeBack(){
	 var btnback = arguments[0];
}
// Executa após a ação de voltar a tela anterior
function afterBack(){
	 var btnback = arguments[0];
}
// Invocado ao clicar no botão Deletar
function beforeDelete(){
}
// Executa após a exclusão de um registro
function afterDelete(){
}
if (typeof funcionalidade === 'undefined') var funcionalidade = 'cadastro';

/* 
 ### Escreva seu código JavaScript abaixo dessa linha ou dentro das funções acima ### 
*/
var is_new_posicaoestoque = true;
function addEstoque(){
	is_new_posicaoestoque = false;
	$.ajax({
		url:session.urlmiles,
		data:{
			controller:'ecommerce/posicaogeralestoque',
			produto:$('[data-entidade=td_ecommerce_operacaoestoque]#produto').val(),
			quantidade:$('[data-entidade=td_ecommerce_operacaoestoque]#quantidade').val(),
			operacao:$('[data-entidade=td_ecommerce_operacaoestoque]#operacaoestoque').val()
		}
	});
}