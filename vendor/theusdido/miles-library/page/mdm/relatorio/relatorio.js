var _relatotrio = 0;
$(document).ready(function(){
    loadRelatorioContent('listar');
});

function loadRelatorioContent(_page){
    $('#relatorio-conteudo').load(session.urlmiles + '?controller=page&page=mdm/relatorio/' + _page);
}


function goListarRelatorio(){
    loadRelatorioContent('listar');
}
