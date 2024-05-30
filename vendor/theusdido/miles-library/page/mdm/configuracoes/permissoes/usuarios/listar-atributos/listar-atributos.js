$(".btn-all-tela-atributo").click(function(){
    var opcao = $(this).data("op").split("-");
    allPermissoes(opcao[1],opcao[0],"#" + $(this).parents("table").first().attr("id"));
});