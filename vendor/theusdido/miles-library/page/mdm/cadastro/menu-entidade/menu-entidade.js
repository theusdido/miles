$('#menu-entidade-cadastro a').click(function(){
    goMenu($(this).data('href'));
});

function goMenu(menu_cadastro){
    loadConteudo('mdm/cadastro/' + menu_cadastro);
}