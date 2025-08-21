$(document).ready(function(){
    $('#permissao-panel-relatorio tbody').load(session.urlmiles + '?controller=mdm/configuracoes/permissoes&op=listar-relatorio');
});