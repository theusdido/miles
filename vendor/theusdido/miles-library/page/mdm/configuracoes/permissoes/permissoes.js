function Permissoes(){
    this.usuarioSelecionado = "";
    this.usuarioPerfil = "";				
}
var permissoes  = new Permissoes();
var url_loading = session.urlloading;

let por_usuario_btn = '#btn-porusuario';
let por_funcoes_btn = '#btn-porfuncoes';

$(por_usuario_btn).click(function(){
    let por_usuario_body = '#porUsuario .accordion-body';
    $(por_usuario_body).html(
        '<div style="width:100%;margin:10px auto">' +
            '<center>' +
                '<img width="32" align="middle" src="'+url_loading+'">' +
                '<p class="text-muted">Aguarde</p>' +
            '</center>' +	
        '</div>'
    );
    $(por_usuario_body).load(session.urlmiles + "?controller=page&page=mdm/configuracoes/permissoes/usuarios/listar");
});

$(por_funcoes_btn).click(function(){
    let por_funcoes_body = '#porFuncoes .accordion-body';    
    $(por_funcoes_body).html(
        '<div style="width:100%;margin:10px auto">' +
            '<center>' +
                '<img width="32" align="middle" src="'+url_loading+'">' +
                '<p class="text-muted">Aguarde</p>' +
            '</center>' +	
        '</div>'
    );		
    $(por_funcoes_body).load(session.urlmiles + "?controller=page&page=mdm/configuracoes/permissoes/funcoes");
});