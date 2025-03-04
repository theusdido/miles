$(document).ready(function(){
    $('#lista-usuarios tbody').load(session.urlmiles + '?controller=mdm/configuracoes/permissoes&op=listar-entidade');
});