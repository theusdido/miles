$(document).ready(function(){
    $('#permissao-panel-menu tbody').load(session.urlmiles + '?controller=mdm/configuracoes/permissoes&op=listar-menu');
});