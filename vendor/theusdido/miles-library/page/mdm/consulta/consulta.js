var _consulta = 0;
$(document).ready(function(){
    loadConsultaContent('listar');
});

function loadConsultaContent(_page){
    $('#consulta-conteudo').load(session.urlmiles + '?controller=page&page=mdm/consulta/' + _page);
}


function goListarConsulta(){
    loadConsultaContent('listar');
}