var componentes 		= [];
var indiceComponente 	= 0;
var indiceRegistro 		= 0;
var registros 			= [];
var package_selecionado	= '';
var modulo_selecionado	= '';
$(".carregar-componentes").click(function(){

    // Icon abrir
    $("#view-componente .modal-title").html($(this).html() + " <small>( Componentes )</small>");
    package_selecionado	= $(this).data("pacote");
    modulo_selecionado	= $(this).data("componente");
    $("#view-componente .modal-body p").html("");
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'page',
            page:'install/component',
            package:package_selecionado,
            component:modulo_selecionado
        },
        complete:function(respota){
            $(".checkbox-componente").each(function(){
                for(c in componentes){
                    if (componentes[c] == $(this).attr("id")){
                        $(this).attr("checked",true);
                    }
                }
            });
            $("#view-componente .modal-body p").html(respota.responseText)
        }

    });
    $("#view-componente").modal({
        backdrop:false
    });
    $("#view-componente").modal("show");
});
$("#btn-pacotes").click(function(){
    $("#retorno").hide();
    instalarcomponentes();									
});
function excluirComponenteLista(compte){
    for(c in componentes){
        if (componentes[c].nome == compte){
            componentes.splice(c,1);
        }
    }
}
function excluirRegistroLista(registro){
    for(r in registros){
        if (registros[r].entidade == registro){
            registros.splice(r,1);
        }
    }
}
function instalarcomponentes(){
    if (componentes.length <= 0 && registros.length <= 0){
        msgRetorno("<b>Error !</b>Você precisa selecionar um <b>componente</b> ou <b>registro</b> para instalar.","alert-danger");
        return false;
    }
    if (typeof componentes[indiceComponente] == 'object'){
        var path 				= componentes[indiceComponente].path;
        var modulo_nome 		= componentes[indiceComponente].nome;
        var modulo_descricao 	= componentes[indiceComponente].descricao;
    }else{
        var path 				= componentes[indiceComponente];
        var modulo_nome 		= '';
        var modulo_descricao 	= '';
    }

    $.ajax({
        url:session.urlmiles,
        type:"POST",
        data:{
            controller:'install/modulos',
            op:"instalarcomponente",
            componente:path,
            modulonome:modulo_nome,
            modulodescricao:modulo_descricao
        },
        beforeSend:function(){
            $("#loader-pacotes").show();
        },
        complete:function(ret){
            var retorno = parseInt(ret.responseText);
            if (retorno == 1){
                instalarregistros();
                indiceComponente++;								
                if (componentes[indiceComponente] != undefined){									
                    instalarcomponentes();
                }else{
                    $.ajax({
               url:session.urlmiles,
                        type:"POST",
                        data:{
                            controller:'install/modulos',
                            op:"atualizar"
                        },
                         error:function(ret){
                             msgRetorno('<b>Error !</b>'+ret.responseText,"alert-danger");
                        }
                    });
                    package_selecionado = '';
                    modulo_selecionado	= '';
                    indiceComponente 	= 0;
                    indiceRegistro 		= 0;
                    componentes.splice(0,componentes.length);
                    $(".checkbox-componente,.checkbox-registro").prop("checked",false);
                    msgRetorno('<b>Parabéns !</b>. Pacotes configurados com sucesso.');
                    //$("#guia-pacote").attr("src","<?=URL_SYSTEM_THEME?>check.gif");
                    
                }
            }else{
                msgRetorno('<b>Error! '+componentes[indiceComponente]+' => </b> '+ret.responseText,"alert-danger");
            }
        },
        error:function(ret){
            msgRetorno('<b>Error !</b>'+ret.responseText,"alert-danger");
        }
    });
    
}	
function instalarregistros(){
    if (registros[indiceRegistro] == undefined) return false;
    $.ajax({
        url:session.urlmiles,
        type:"POST",
        data:{
            controller:'install/modulos',
            op:"instalarcomponente",
            registro:JSON.stringify(registros[indiceRegistro])
        },
        beforeSend:function(){
            $("#loader-pacotes").show();
        },
        error:function(ret){
            msgRetorno('<b>Error !</b>'+ret.responseText,"alert-danger");
        },
        complete:function(retorno){
            indiceRegistro++;
            if (registros[indiceRegistro] != undefined){									
                instalarregistros();
            }
        }
    });
}
function msgRetorno(msg,tipo = "alert-success"){
    $("#loader-pacotes").hide();
    $("#retorno").show();
    $("#retorno").removeClass("alert-success alert-danger alert-warning");
    $("#retorno").addClass(tipo);
    $("#retorno .msg").html(msg);
}
$(document).ready(function(){
    $('#loader-pacotes').attr('src',session.urlloading2);
    $("#retorno").hide();
});
$("#retorno .close").click(function(){
    $(this).parents(".alert").hide();
});
function addComponente(component){
    if (component != undefined && component != ''){
        componentes.push(component);
    }
}
function addRegistro(reg){
    if (reg != undefined && reg != ''){
        registros.push(reg);
    }
}

$(document).on('click','.checkbox-componente',function(){
    var componentPath		= $(this).attr("id");
    var componenteNome 		= $(this).data("module-name");
    var componenteDescricao	= $(this).data("module-description");

    if (this.checked){
        addComponente({
            path:componentPath,
            nome:componenteNome,
            descricao:componenteDescricao
        });
    }else{
        excluirComponenteLista(componenteNome);
    }
});

$(document).on('click','.checkbox-registro',function(){
    var registroName 	= $(this).data("file");
    var registroPath	= $(this).data("path");
    if (this.checked){
        registros.push({
            file:registroName,
            path:registroPath
        });
    }else{
        excluirRegistroLista(registroName);
    }
});

$(document,'checkbox-modulo-all','click').click(function(){
    var modulo_id = $(this).data("modulo");
    $(".check-modulo-" + modulo_id).click();
});