$(document).ready(function(){
    displayBtnVoltar();
});
function excluirMenu(menu_id){
    bootbox.confirm("Tem certeza que deseja excluir ? ",function(result){
        if (result){
            $.ajax({
                url:session.urlmiles,
                data:{
                    controller:'mdm/menu/topo',
                    op:'excluir',
                    id:menu_id
                },
                complete:function(ret){
                    if (parseInt(ret.responseText) == 1){
                        $("#tmenutopo tbody tr[data-menu="+menu_id+"]").remove();
                    }
                }
            });
        }
    });
}

function inativarMenu(obj,menu){
    const status 		= $(obj).attr("data-inativar");
    bootbox.confirm("Tem certeza que deseja inativar ? ",function(result){
        if (result){
            $.ajax({
                url:session.urlmiles,
                data:{
                    controller:'mdm/menu/topo',
                    op:"inativar",
                    id:menu,
                    inativar:status == 1 ? false : true
                },
                complete:function(ret){
                    if (parseInt(ret.responseText) == 1){
                        $(obj).removeClass("btn-danger btn-primary");
                        $(obj).addClass("btn-" + (status == 1 ? "primary" : "danger"));
                        $(obj).attr("data-inativar",status == 1 ? 0 : 1);
                    }
                }
            });
        }
    });
}

var trs = [];

function reodernar(obj,operador){
    var trOBJ = $(obj).parents("tr");
    var trIndice = trOBJ.data("indice");
    var dadosenviar = [];
    if (trIndice <= 0 && operador == "up") return false;
    if ((trIndice + 1) >= $("#tmenutopo tbody tr").length && operador == "down") return false;				
    $("#tmenutopo tbody tr").each(function(){
        trs.push($(this));
        $(this).remove();
    });

    if (operador == 'up'){
        var de = trs[trIndice];
        var para = trs[trIndice-1];
        trs[trIndice-1] = de;
        trs[trIndice] = para;
    }else{
        var para = trs[trIndice+1];
        var de = trs[trIndice];
        trs[trIndice+1] = de;
        trs[trIndice] = para;
    }

    for (t in trs){
        $("#tmenutopo tbody").append(trs[t]);
        trs[t].attr("data-indice",t);
        trs[t].find(".btn-arrow-order").onclick = "reodernar($(this));";
        dadosenviar.push({
            menu:trs[t].data("menu"),
            ordem:t
        });
    }
    trs.splice(0,trs.length);

    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'mdm/menu/topo',
            op:"ordenacao",
            dados:dadosenviar
        }
    });
}

function versubmenu(id,descricao){
    _menutopo_pai = id;
    descricao = descricao.replace("^"," ");
    $('.titulo-pagina-mdm').html(descricao);
    displayBtnVoltar();
    loadListar();
}

function gerarConceito(_entidade){
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:"gerarconceito",
            entidade:_entidade
        },
        complete:function(retorno){

        },
        beforeSend:function(){

        }
    });				
}

function displayBtnVoltar(){
    if (_menutopo_pai == 0 && _menutopo == 0){
        $('#btn-voltar-menutopo-listar').hide();
    }else{
        $('#btn-voltar-menutopo-listar').show();
    }    
}