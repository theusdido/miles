var conceitoCompilar = [];
$(document).ready(function(){
    carregarConceitos();
});
function carregarConceitos(){
    $.ajax({
        url:session.urlsystem,
        dataType:"JSON",
        data:{
            controller:"compilar",
            op:"lista-conceito"
        },
        complete:function(ret){
            var retorno = ret.responseJSON;
            retorno.forEach(function(entidade){
                $("#conceitos").append('<li class="list-group-item">'+entidade.descricao+'<span class="badge"><input type="checkbox" class="conceito-check" data-entidade="'+entidade.id+'" data-compiled="false"/></span></li>');
            });
        }
    });
}

$("#checkbox-all").click(function(){
    $(".conceito-check").prop("checked",$(this).is(":checked"));
});

$("#btn-compilar").click(function(){
    if ($(".conceito-check:checked").length <= 0){
        bootbox.alert("Nenhum item selecionado.");
        return false;
    }
    $(".conceito-check:checked").each(function(){
        conceitoCompilar.push($(this).data("entidade"));
    });
    compilarConceito();
});

var indiceCompilado = 0;
function compilarConceito(){
    
    var id = conceitoCompilar[indiceCompilado];
    if (id != undefined){
        $(".conceito-check[data-entidade=" + id +"]").attr("data-compiled",true);
        $.ajax({
            url:session.urlsystem,
            data:{
                controller:"gerarcadastro",
                entidade:id,
                principal:"",
                acao:"compilar"
            },
            complete:function(){
                $(".conceito-check[data-entidade=" + id +"]").parents("li").addClass("list-group-item-success");
                indiceCompilado++;
                compilarConceito();
            }
        });
    }else{
        bootbox.alert("Conceitos Compilados!");
    }
}