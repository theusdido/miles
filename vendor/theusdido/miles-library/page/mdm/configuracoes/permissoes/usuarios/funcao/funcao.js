$(document).ready(function(){
    $('#permissao-panel-funcao tbody').load(session.urlmiles + '?controller=mdm/configuracoes/permissoes&op=listar-funcao');
});