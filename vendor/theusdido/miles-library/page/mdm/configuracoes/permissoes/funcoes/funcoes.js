$(document).ready(function(){
    $('#permissoes-funcoes').load(session.urlmiles + '?controller=mdm/configuracoes/permissoes&op=listar-permissoes-funcoes');
});