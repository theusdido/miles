$(document).ready(function(){
    $('#permissao-panel-consulta tbody').load(session.urlmiles + '?controller=mdm/configuracoes/permissoes&op=listar-consultas');
});