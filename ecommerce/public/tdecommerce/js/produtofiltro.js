// JS - Produto Filtro
var filtrosCategorias = [];
var filtrosTipos = [];
var filtrosUvas = [];
var filtrosPreco;

function pesquisarFiltro(){	
	filtrosPreco = {
		minvalue:$("#filtro-produto-home #minamount").val(),
		maxvalue:$("#filtro-produto-home #maxamount").val()
	};
	$.ajax({
		url:session.path_tdecommerce,
		data:{
			controller:"produtofiltro",
			op:"pesquisa",
			preco:filtrosPreco,
			categoria:$("#filtro-categoria").val(),
			subcategoria:$("#filtro-subcategoria").val()
		},
		beforeSend:function(){
			$(".resultado-filtro").html('<img src="'+session.path_system_theme+'loading2.gif" class="loading2" />');
		},
		complete:function(ret){
			$(".resultado-filtro").html(ret.responseText);
		}
	});
}

/*
$(".btn-filtrar").click(function(e){
	e.stopPropagation();
	e.preventDefault();
	pesquisarFiltro();
});
*/