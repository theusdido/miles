var _movimentacao = 0;
$(document).ready(function(){
    loadMovimentacaoContent('listar');
});

function loadMovimentacaoContent(_page){
    $('#movimentacao-conteudo').load(session.urlmiles + '?controller=page&page=mdm/movimentacao/' + _page);
}


function goListarMovimentacao(){
    loadMovimentacaoContent('listar');
}