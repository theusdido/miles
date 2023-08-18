function Permissoes(){
    this.usuarioSelecionado = "";
    this.usuarioPerfil = "";				
}
var permissoes  = new Permissoes();
var url_loading = session.urlloading;

$("a[href='#collapseOne']").click(function(){
    $("#collapseOne .panel-body").html(
        '<div style="width:100%;margin:10px auto">' +
            '<center>' +
                '<img width="32" align="middle" src="'+url_loading+'">' +
                '<p class="text-muted">Aguarde</p>' +
            '</center>' +	
        '</div>'
    );
    $("#collapseOne .panel-body").load(session.urlmiles + "?controller=page&page=mdm/configuracoes/permissoes/usuarios/listar");
});

$("a[href='#collapseThree']").click(function(){
    $("#collapseThree .panel-body").html(
        '<div style="width:100%;margin:10px auto">' +
            '<center>' +
                '<img width="32" align="middle" src="'+url_loading+'">' +
                '<p class="text-muted">Aguarde</p>' +
            '</center>' +	
        '</div>'
    );		
    $("#collapseThree .panel-body").load(session.urlmiles + "?controller=page&page=mdm/configuracoes/permissoes/funcoes");
});