
$(document).ready( () => {
    $('#listar-charset').load(session.urlmiles + '?controller=mdm/charset&op=listar');
    $('#entidadelista').load(session.urlmiles + '?controller=mdm/charset&op=option-corrigir-entidade',function(){
        carregarAtributo($("#entidadelista").val());
    });
    $('#atributolista').load(session.urlmiles + '?controller=mdm/charset&op=option-corrigir-atributo');

    $("#entidadelista").change(function(){
        carregarAtributo($(this).val());
    });
    
    $("#btn-corrigir-charset").click(function(){
        $.ajax({
            url:session.urlmiles,
            data:{
                controller:'mdm/charset',
                op:"corrigir",
                entidade:$("#entidadelista").val(),
                atributo:$("#atributolista").val()
            },
            beforeSend:function(){
                $("#loading-corrigir-charset").show();
            },
            complete:function(ret){
                $("#loading-corrigir-charset").hide();
            }
        });
    });

    $('#loading-corrigir-charset').attr('src',session.urlloading2);
});

function setCharset(id,obj){
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'mdm/charset',
            op:"setar",
            id:id,
            charset:obj.value
        }
    });
}

function carregarAtributo(entidade){
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'mdm/charset',
            op:"listaratributo",
            entidade:entidade
        },
        beforeSend:function(){
            $("#atributolista").html("<option>Carregando ...</option>");
            $("#atributolista").attr("disabled",true);
        },
        complete:function(ret){
            $("#atributolista").html(ret.responseText);
            $("#atributolista").removeAttr("disabled");
        }
    });
}