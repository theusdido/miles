$(document).ready(function(){
    loadCamposContent('listar');
});

function loadCamposContent(_page){
    $('#campos-conteudo').load(session.urlmiles + '?controller=page&page=mdm/cadastro/campos/' + _page);
}

function goListarCampos(){
    loadCamposContent('listar');
}