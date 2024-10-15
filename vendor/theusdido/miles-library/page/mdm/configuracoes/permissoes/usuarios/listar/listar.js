$(document).ready(function(){
    $('#lista-grupo-usuario').load(session.urlmiles + '?controller=mdm/configuracoes/permissoes&op=listar-grupo-usuario');
    abrirListaPermissoes('cadastro');
});

$(document).on('click',".cabecalho-lista-usuario",function(){
    var id = $(this).attr("id");
    if ($("#lista-usuario-" + id).css("display") == "none"){
        $("#lista-usuario-" + id).show();
        $(this).find(".glyphicon").addClass("glyphicon-folder-open");
        $(this).find(".glyphicon").removeClass("glyphicon-folder-close");
    }else{
        $(this).find(".glyphicon").addClass("glyphicon-folder-close");
        $(this).find(".glyphicon").removeClass("glyphicon-folder-open");
        
        $("#lista-usuario-" + id).hide();
    }
});	
$(document).on('click','.usuario-na-lista-porusuario',function(){
    $(".nome_usuario").html($(this).html());
    permissoes.usuarioSelecionado = $(this).attr("id");
    permissoes.usuarioPerfil = $(this).data("perfil");
    $("tr input[type=checkbox]").attr("checked",false);
    carregarPermissoesUsuario(permissoes.usuarioSelecionado);
});
function carregarPermissoesUsuario(usuario){			
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'mdm/configuracoes/permissoes',
            op:'listar-dados-permissoes-usuario',
            usuario:usuario
        },
        complete:function(r){
            var retorno = JSON.parse(r.responseText);
            for(e in retorno[0].entidades){
                var dados = retorno[0].entidades[e].entidadedados.split("^");
                if ($("#lista-usuarios tr[data-entidadeid="+dados[0]+"] input[type=checkbox]")[0])
                    $("#lista-usuarios tr[data-entidadeid="+dados[0]+"] input[type=checkbox]")[0].checked = (dados[1]==1?true:false);
                
                if ($("#lista-usuarios tr[data-entidadeid="+dados[0]+"] input[type=checkbox]")[1])
                    $("#lista-usuarios tr[data-entidadeid="+dados[0]+"] input[type=checkbox]")[1].checked = (dados[2]==1?true:false);
                
                if ($("#lista-usuarios tr[data-entidadeid="+dados[0]+"] input[type=checkbox]")[2])
                    $("#lista-usuarios tr[data-entidadeid="+dados[0]+"] input[type=checkbox]")[2].checked = (dados[3]==1?true:false);
                
                if ($("#lista-usuarios tr[data-entidadeid="+dados[0]+"] input[type=checkbox]")[3])
                    $("#lista-usuarios tr[data-entidadeid="+dados[0]+"] input[type=checkbox]")[3].checked = (dados[4]==1?true:false);
            }
            for(a in retorno[1].funcoes){
                var dados = retorno[1].funcoes[a].funcoesdados.split("^");
                $("#lista-funcoes tr[funcaoid="+dados[0]+"] input[type=checkbox]")[0].checked = (dados[1]==1?true:false);
            }
            for(a in retorno[2].menus){
                var dados = retorno[2].menus[a].menu.split("^");
                if ($("#lista-menu tr[menuid="+dados[0]+"] input[type=checkbox]")[0]){
                    $("#lista-menu tr[menuid="+dados[0]+"] input[type=checkbox]")[0].checked = (dados[1]==1?true:false);
                }	
            }
            $("#dadosusuariopermissao").show();
            if (permissoes.usuarioPerfil == ""){
                $("#btn-restaurar-permissoes").hide();
            }else{
                $("#btn-restaurar-permissoes").show();
            }
        }
    });
}
$(document).on('click',".abrir-atributos",function(){
    if (permissoes.usuarioSelecionado != ""){
        var idEntidade = $(this).parents("tr").data("entidadeid");
        var descricaoEntidade = $(this).parents("tr").data("entidadedescricao") + " - <small>( Atributos )</small>";
        $(".modal .modal-body p").load(session.urlmiles + '?controller=page&page=mdm/configuracoes/permissoes/usuarios/listar-atributos',
        function(){
            $(document).ready(function(){
                $.ajax({
                    url:session.urlmiles,
                    data:{
                        controller:'mdm/configuracoes/permissoes',
                        op:'listar-atributos-cadastro',
                        entidade:idEntidade,
                        usuario:permissoes.usuarioSelecionado
                    },
                    success:function(retorno){
                        $(".modal .modal-title").html(descricaoEntidade);
                        $('#modal-permissoes-atributos-cadastro').modal('show');                
                        $("#lista-atributos tbody").html(retorno);
                    }
                });
            });            
        });
    }else{
        alert("Selecione um usuário");
    }
});
function setaPermissao(obj,_item){
    if (permissoes.usuarioSelecionado != ""){			
        var idEntidade 	= $(obj).parents("tr").data("entidadeid");
        var atributoId 	= $(obj).parents("tr").attr("id");
        var funcaoid	= $(obj).parents("tr").attr("funcaoid");
        var menuid		= $(obj).parents("tr").attr("menuid");
        var acoes = "";
        $(obj).parents("tr").find("input[type=checkbox]").each(function(){
            acoes += (acoes==""?"":"^") + ($(this).prop("checked")?1:0);
        });	
        $.ajax({
            type:"POST",
            url:session.urlmiles,
            data:{
                controller:'mdm/configuracoes/permissoes',
                op:'setar-permissao',
                item:_item,
                entidade:idEntidade,
                usuario:permissoes.usuarioSelecionado,
                acoes:acoes,
                atributo:atributoId,
                funcao:funcaoid,
                menu:menuid
            },
            complete:function(){
                $.ajax({
                    url:session.urlmiles,
                    data:{
                        controller:'permissoes',
                        op:'menu'
                    }
                });
            }
        });
    }else{
        var acao = $(obj).prop("checked");
        $(obj).prop("checked",(acao?false:true));
        alert("Selecione um usuário");
    }
}
$("#btn-restaurar-permissoes").click(function(){
    bootbox.dialog({
         message: "Tem certeza que deseja restaurar as permissões de acordo com perfil do usuário?",
          title: "Permissões",
          buttons: {
            success: {
                  label: "Sim",
                  className: "btn-success",
                  callback: function() {

                      // Seta Permissão
                    var perfilusuario = permissoes.usuarioPerfil;
                    var usuario = permissoes.usuarioSelecionado;
                    if (perfilusuario != "")
                    {
                        $.ajax({
                            url:"../../../../index.php",
                            data:{
                                controller:"requisicoes",
                                op:"perfilusuario",
                                usuario:usuario,
                                perfil:perfilusuario										
                            },
                            complete:function(){
                                carregarPermissoesUsuario(usuario);
                            }
                        });
                    }
                  }
            },
            danger: {
                  label: "Não",
                  className: "btn-danger",
                  callback: function() {
                    
                  }
            }
          }
    });
});
$("#btn-all-permissoes").click(function(){
    var usuario = permissoes.usuarioSelecionado;
    bootbox.dialog({
         message: "Tem certeza que deseja setar todas as permissões ?",
          title: "Permissões",
          buttons: {
            success: {
                  label: "Adicionar Todas",
                  className: "btn-success",
                  callback: function() {
                    setarTodasPermissoes(usuario,1);
                  }
            },
            danger: {
                  label: "Retirar Todas",
                  className: "btn-danger",
                  callback: function() {
                    setarTodasPermissoes(usuario,0);
                  }
            }
          }
    });
});		
$("#permissao-btn-tela").click(function(){
    abrirListaPermissoes('cadastro');
});
$(document).on('click','#permissao-btn-consulta',function(){
    abrirListaPermissoes('consulta');
});		
$("#permissao-btn-relatorio").click(function(){
    abrirListaPermissoes('relatorio');
});
$("#permissao-btn-funcao").click(function(){
    abrirListaPermissoes('funcao');
});
$("#permissao-btn-menu").click(function(){
    abrirListaPermissoes('menu');
});
$(document).on('click','.btn-all-tela',function(){
    if (permissoes.usuarioSelecionado == ""){
        alert('Selecione um usuário');
        return false;
    }
    var opcao = $(this).data("op").split("-");
    allPermissoes(opcao[1],opcao[0],"#" + $(this).parents("table").first().attr("id"));
});
function abrirListaPermissoes(lista){
    $("#gp-btn-op button").removeClass("active");
    $("#permissao-btn-" + lista).addClass("active");			
    $(".panel.panel-lista-permissoes").hide();
    $('.panel-lista-permissoes-content').load(session.urlmiles + '?controller=page&page=mdm/configuracoes/permissoes/usuarios/' + lista);
}
function allPermissoes(op,panel,obj){
    if (permissoes.usuarioSelecionado == ""){
        alert('Selecione um usuário');
        return false;
    }
    bootbox.dialog({
         message: "Tem certeza que deseja realizar esta operação?",
          title: "Permissões",
          buttons: {
            success: {
                  label: "Selecionar Todos",
                  className: "btn-success",
                  callback: function() {
                    $(obj + " tbody input[type=checkbox][data-op="+op+"]").each(function(){
                        this.checked = true;
                        setaPermissao(this,panel);
                    });
                  }
            },
            danger: {
                  label: "Deselecionar Todos",
                  className: "btn-danger",
                  callback: function() {
                    $(obj + " tbody input[type=checkbox][data-op="+op+"]").each(function(){
                        this.checked = false;
                        setaPermissao(this,panel);
                    });        					
                  }
            }
          }
    });
}

function setarTodasPermissoes(usuario,permissao){
    if (usuario != "")
    {
        $.ajax({
            url:session.urlmiles,
            data:{
                controller:"requisicoes",
                op:"setar_todas_permissoes",
                auth:1,
                usuario:usuario,
                permissao:permissao
            },
            complete:function(){
                carregarPermissoesUsuario(usuario);
            }
        });
    }
}