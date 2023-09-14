$(document).ready(function(){
    $('#entidade').load(session.urlmiles + '?controller=mdm/menu/topo&op=option-entidade');
    $('#pai').load(session.urlmiles + '?controller=mdm/menu/topo&op=listar-entidade');

    if (_menutopo != 0){
        load();
    }else{
        configuracaoInicial();
    }
});

$("#btn-salvar").click(function(){
    $.ajax({
        type:"POST",
        url:session.urlmiles,
        dataType:"JSON",
        data:{
            controller:'mdm/menu/topo',
            op:"salvar",
            id:$("#id").val(),
            tipomenu:$("#tipomenu").val(),
            entidade:$("#entidade").val(),
            descricao:$("#descricao").val(),
            link:$("#link").val(),
            target:$("#target").val(),
            ordem:$("#ordem").val(),
            pai:$("#pai").val(),
            path:$("#path").val(),
            icon:$("#icon").val(),
            fixo:$("#fixo").val(),
            coluna:$("#coluna").val()
        },
        complete:function(ret){
            var retorno = ret.responseJSON;
            if (retorno.status == 1){
                if ($("#tipomenu").val() == 'conceito'){
                    gerarConceito($("#entidade").val());
                }
                unLoaderSalvar();
                $("#retorno").addClass("alert-success");
                $("#retorno").html(retorno.msg);
            }
        },
        error:function(res){
            console.log(res.responseText);
            $("#retorno").addClass("alert-danger");
            $("#retorno").html('Erro Interno.');
        },
        beforeSend:function(){
            addLoaderSalvar();
        }        
    });
});

function load(){
    $.ajax({
        url:session.urlmiles,
        dataType:'json',
        data:{
            controller:'mdm/menu/topo',
            id:_menutopo,
            op:'load'
        },
        complete:function(_res){
            let _data = _res.responseJSON;

            $("#id").val(_data.id);
            $("#tipomenu").val(_data.tipomenu);
            $("#entidade").val(_data.entidade);
            $("#descricao").val(_data.descricao);
            $("#link").val(_data.link);
            $("#target").val(_data.target);
            $("#ordem").val(_data.ordem);
            $("#pai").val(_data.pai);
            $("#path").val(_data.path);
            $("#icon").val(_data.icon);
            $("#fixo").val(_data.fixo);
            $("#coluna").val(_data.coluna);
        }
    });
}

$("#entidade").change(function(){
    var objDescricao  =  $("#descricao");
    var objLink = $("#link");
    var objTarget = $("#target");
    if ($(this).find("option:selected").val() != 0){
        var objSelOpt = $(this).find("option:selected");
        var pacoteOBJ = (objSelOpt.data("pacote")==""?"":objSelOpt.data("pacote")+"-");
        objDescricao.val(objSelOpt.data("descricao"));
        
        if ($("#tipomenu").val() == 'conceito'){
            objLink.val("files/"+$("#tipomenu").val()+"/"+pacoteOBJ+objSelOpt.data("nome")+".html");
        }else{
            objLink.val("files/"+$("#tipomenu").val()+"/"+$(this).val()+"/"+pacoteOBJ+objSelOpt.data("nome")+".html");
        }

        objTarget.val("");
        
        objLink.attr("readonly",true);
        objTarget.attr("readonly",true);
    }else{

        objLink.val("");
        objTarget.val("");
        
        objDescricao.attr("readonly",false);
        objLink.attr("readonly",false);
        objTarget.attr("readonly",false);
    }
    
});				
$("#tipomenu").change(function(){
    carregarEntidade($(this).val());
});	

function carregarEntidade(valor){
    $("#entidade,#pai,#coluna").attr("readonly",false);
    $("#entidade,#pai,#coluna").attr("disabled",false);
    switch(valor){
        case 'cadastro':
            $("#entidade").load(session.urlmiles + "?controller=mdm/menu/topo&op=option-entidade",function(){
                $("#entidade").change();
            });
        break;
        case 'consulta':
            $("#entidade").load(session.urlmiles + "?controller=mdm/menu/topo&op=carregaconsulta");
        break;
        case 'relatorio':
            $("#entidade").load(session.urlmiles + "?controller=mdm/menu/topo&op=carregarelatorio");
        break;
        case 'movimentacao':
            $("#entidade").load(session.urlmiles + "?controller=mdm/menu/topo&op=carregarelatorio");
        break;
        case 'personalizado':
            configuracaoPersonalizado();
        break;
        case 'raiz':
            configuracaoInicial();
        break;
    }				
}
function configuracaoPersonalizado(){
    $("#entidade").attr("readonly",true);
    $("#entidade").attr("disabled",true);
    $("#pai,#coluna").attr("readonly",false);
    $("#pai,#coluna").attr("disabled",false);

    if ($("#id").val() == ""){
        $("#entidade,#pai").val(0);
        $("#descricao").val("");
        $("#link").val("");
        $("#target").val("");
    }

    $("#descricao").attr("readonly",false);
    $("#link").attr("readonly",false);
    $("#target").attr("readonly",false);
}
function configuracaoInicial(){
    $("#entidade,#pai,#coluna").val(0);
    $("#entidade,#pai,#coluna").attr("readonly",true);
    $("#entidade,#pai,#coluna").attr("disabled",true);
    $("#descricao,#link,#target,id,entidade,ordem,pai").val("");
    $("#descricao,#link").removeAttr("readonly");
    $('#entidade').load(session.urlmiles + '?controller=mdm/menu/topo&op=option-entidade');
    $("#link").val("#");
}