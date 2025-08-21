$(document).ready(function(){
    loadCadastroContent('listar');
});

function loadCadastroContent(_page){
    $('#cadastro-conteudo').load(session.urlmiles + '?controller=page&page=mdm/cadastro/' + _page);
}

function goListarCadastro(e){
    e.preventDefault();
    e.stopPropagation();
    loadCadastroContent('listar');
}