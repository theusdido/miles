$(document).ready( () => {
    $("#cbEstruturaAll").click( function() {
        selectAllEstrutura($(this).is(":checked"));
    });
    $('#modalEntidadeEstrutura').on('shown.bs.modal', function () {
        selectAllEstrutura($("#cbEstruturaAll").is(":checked"));
    });

    $('#lista-estrutura').load(session.urlmiles + '?controller=mdm/atualizar&op=lista-estrutura');
    $('#lista-registro').load(session.urlmiles + '?controller=mdm/atualizar&op=lista-registro');
    $('#lista-arquivo').load(session.urlmiles + '?controller=mdm/atualizar&op=lista-arquivo');
});

function desenvolvimentoToProducao(){
    var entidadeestrutura 	= "";
    var entidaderegistro 	= "";
    var entidadearquivo 	= "";
    
    if ($(".entidadeestrutura:checked,.entidaderegistro:checked,.entidadearquivo:checked").length <= 0){
        bootbox.alert('Selecione uma opção.');
        return false;
    }

    $(".entidadeestrutura:checked").each(function(){
        entidadeestrutura += (entidadeestrutura==""?"":",") + $(this).data("entidade");
    });
    $(".entidaderegistro:checked").each(function(){
        entidaderegistro += (entidaderegistro==""?"":",") + $(this).data("entidade");
    });
    $(".entidadearquivo:checked").each(function(){
        entidadearquivo += (entidadearquivo==""?"":",") + $(this).data("entidade");
    });

    $.ajax({
        url:session.urlmiles,
        data:{
            controller:"mdm/atualizar",
            op:"desenvolvimentotoproducao",
            entidadesestrutura:entidadeestrutura,
            entidadesregistro:entidaderegistro,
            entidadesarquivo:entidadearquivo
        },
        beforeSend:function(){
            $("#retorno-ajax-desenvolvimentoToProducao").html('<img src="../tema/padrao/loading2.gif" id="loading" style="float:left;margin-left:48%;" />');
        },
        complete:function(){
            $("#retorno-ajax-desenvolvimentoToProducao").html("");
        }        
    });
}
$("#selecionarEntidadeEstrutura").click(function(e){
    e.preventDefault();
    $(".entidadeestrutura:checked").each(function(){
        $(this).attr("checked",false);
    });				
    $("#modalEntidadeEstrutura").modal({
        backdrop:false
    });
    $("#modalEntidadeEstrutura").modal('show');
});
$("#selecionarEntidadeRegistro").click(function(e){
    e.preventDefault();
    $(".entidaderegistro:checked").each(function(){
        $(this).attr("checked",false);
    });				
    $("#modalEntidadeRegistro").modal({
        backdrop:false
    });
    $("#modalEntidadeRegistro").modal('show');
});
$("#selecionarEntidadeArquivo").click(function(e){
    e.preventDefault();
    $(".entidadearquivo:checked").each(function(){
        $(this).attr("checked",false);
    });				
    $("#modalEntidadeArquivo").modal({
        backdrop:false
    });
    $("#modalEntidadeArquivo").modal('show');
});

function selectAllEstrutura(check){
    $(".entidadeestrutura").attr("checked",check);
}