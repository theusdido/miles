$("#accordion_filtros,#accordion_status,#accordion_filtrosinicias,#panel-colunas").hide();
$(document).ready(function(){
    $('#entidade').load(session.urlmiles + '?controller=mdm/relatorio&op=listar-entidade-option');
    if (_relatorio != 0){
        load();
    }
});
$('#btn-salvar-relatorio').click(function(){
    if ($("#entidade").val() == "" || $("#entidade").val() == null){
        alert('Entidade não pode ser vazio');
        return false;
    }

    $.ajax({
        url:session.urlmiles,
        type:"POST",
        dataType:'json',
        data:{

            // Parametros
            controller  :'mdm/relatorio',
            op          :'salvar',

            // Campos Inputs
			id                  :_relatorio,
			descricao           :$('#descricao').val(),
			entidade            :$('#entidade').val(),
			urlpersonalizada    :$('#urlpersonalizada').val()
        },
        complete:function(_res){
            let _retorno = _res.responseJSON;
            if (_retorno.status == 'success'){
                unLoaderSalvar();
                mdmToastMessage("Salvo com Sucesso");
                _relatorio = _retorno.id;
                load();
            }
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
            controller:'mdm/relatorio',
            id:_relatorio,
            op:'load'
        },
        complete:function(_res){
            let _data = _res.responseJSON;

            // Campos Inputs
			$('#descricao').val(_data.descricao);
			$('#entidade').val(_data.entidade);
			$('#urlpersonalizada').val(_data.urlpersonalizada);

            $('#panel-colunas').load(session.urlmiles + "?controller=page&page=mdm/relatorio/colunas");
            $("#accordion_filtros,#accordion_status,#accordion_filtrosinicias,#panel-colunas").show();
            atualizarListaFiltro();
            atualizarListaStatus();
            atualizarListaFiltroInicial();
            $('#panel-colunas').load(session.urlmiles + "?controller=page&page=mdm/relatorio/colunas");
            $('select[id="atributo"]').load(session.urlmiles + '?controller=mdm/relatorio&_entidade=' + _data.entidade + '&op=listar-atributos');
            $('#status').load(session.urlmiles + '?controller=mdm/relatorio&op=listar-status');
    }
    });
}
function editarFiltro(id){
    $("#form-filtro #relatorio").val(id);
    $("#form-filtro #atributo").val($("#lista-filtro #atributo-editar-" + id).data("atributo"));
    $("#form-filtro #operador").val($("#lista-filtro #atributo-editar-" + id).data("operador"));
    $("#form-filtro #legenda").val($("#lista-filtro #atributo-editar-" + id).data("legenda"));
    $("#form-filtro #idfiltro").val($("#lista-filtro #atributo-editar-" + id).data("idfiltro"));
    $("#modalCadastroFiltro").modal('show');
}
function editarStatus(id){
    $("#form-status #relatorio").val(id);
    $("#form-status #atributo").val($("#lista-status #atributo-editar-" + id).data("atributo"));
    $("#form-status #operador").val($("#lista-status #atributo-editar-" + id).data("operador"));
    $("#form-status #valor").val($("#lista-status #atributo-editar-" + id).data("valor"));
    $("#form-status #status").val($("#lista-status #atributo-editar-" + id).data("status"));
    $("#form-status #idstatus").val($("#lista-status #atributo-editar-" + id).data("idstatus"));
    $("#modalCadastroStatus").modal('show');
}
function editarFiltroInicial(id){
    $("#form-filtro-inicial #relatorio").val(id);
    $("#form-filtro-inicial #atributo").val($("#lista-filtroinicial #atributo-editar-" + id).data("atributo"));
    $("#form-filtro-inicial #operador").val($("#lista-filtroinicial #atributo-editar-" + id).data("operador"));
    $("#form-filtro-inicial #valor").val($("#lista-filtroinicial #atributo-editar-" + id).data("valor"));
    $("#form-filtro-inicial #legenda").val($("#lista-filtroinicial #atributo-editar-" + id).data("legenda"));
    $("#form-filtro-inicial #idfiltro").val($("#lista-filtroinicial #atributo-editar-" + id).data("idfiltro"));
    $("#modalCadastroFiltroInicial").modal('show');
}
function novoFiltroInicial(){
    $("#modalCadastroFiltroInicial").modal({
        backdrop:false
    });
    $("#modalCadastroFiltro").modal('show');
    $("#form-filtro #relatorio,#form-filtro #idfiltro,#form-filtro #legenda").val("");
    $("#form-filtro #operador").val("=");
    $("#form-filtro #atributo").val($("#form-filtro #atributo option:first").val());
}
function excluirFiltroInicial(id){
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'mdm/relatorio',
            op:"excluirfiltroinicial",
            id:id
        },
        complete:function(){
            atualizarListaFiltroInicial();
        }
    });
}


$("#salvarFiltro").click(function(){
    $.ajax({
        type:"POST",
        url:session.urlmiles,
        data:{
            controller:'mdm/relatorio',
            op:"salvarfiltro",
            operador: $("#form-filtro #operador").val(),
            atributo: $("#form-filtro #atributo").val(),
            relatorio:_relatorio,
            id:$("#form-filtro #idfiltro").val(),
            legenda:$("#form-filtro #legenda").val()
        },
        complete:function(r){
            atualizarListaFiltro();
            $("#modalCadastroFiltro").modal('hide');
        }
    });
});
$("#salvarStatus").click(function(){
    $.ajax({
        type:"POST",
        url:session.urlmiles,
        data:{
            controller:'mdm/relatorio',
            op:"salvarstatus",
            operador: $("#form-status #operador").val(),
            valor: $("#form-status #valor").val(),
            atributo: $("#form-status #atributo").val(),
            relatorio:_relatorio,
            id:$("#form-status #idstatus").val(),
            status:$("#form-status #status").val()
        },
        complete:function(r){
            atualizarListaStatus();
            $("#modalCadastroStatus").modal('hide');
        }
    });
});
$("#salvarFiltroInicial").click(function(){
    $.ajax({
        type:"POST",
        url:session.urlmiles,
        data:{
            controller:'mdm/relatorio',
            op:"salvarfiltroinicial",
            operador: $("#form-filtro-inicial #operador").val(),
            atributo: $("#form-filtro-inicial #atributo").val(),
            relatorio:_relatorio,
            id:$("#form-filtro-inicial #idfiltro").val(),
            valor:$("#form-filtro-inicial #valor").val(),
            legenda:$("#form-filtro-inicial #legenda").val()
        },
        complete:function(r){
            atualizarListaFiltroInicial();
            $("#modalCadastroFiltroInicial").modal('hide');
        }
    });
});
$("#form-status #atributo").change(function(){
    carregarValoresAtributo($(this).find("option:selected").data("chaveestrangeira"));
});
carregarValoresAtributo($("#form-status #atributo").find("option:selected").data("chaveestrangeira"));
$("#lista-filtro").sortable({
    update: function( event, ui ) {
        var ordenacao = [];
        $("#lista-filtro li").each(
            (e,elemento) => {
                var id = $(elemento).data("id");
                if (id != undefined){
                    ordenacao.push({
                        id:id,
                        order:e+1
                    });
                }					
            }
        );
        $.ajax({
            url:session.urlmiles,
            data:{                
                op:"ordenar",
                controller:"sortable",
                entidade:"td_relatoriofiltro",
                atributo:"ordem",
                ordem:ordenacao
            }
        });
    }
});

function novoFiltro(){
    $("#modalCadastroFiltro").modal({
        backdrop:false
    });
    $("#modalCadastroFiltro").modal('show');
    $("#form-filtro #relatorio,#form-filtro #idfiltro,#form-filtro #legenda").val("");
    $("#form-filtro #operador").val("=");
    $("#form-filtro #atributo").val($("#form-filtro #atributo option:first").val());
}
function novoStatus(){
    $("#modalCadastroStatus").modal({
        backdrop:false
    });
    $("#modalCadastroStatus").modal('show');				
    $("#form-status #relatorio,#form-status #valor,#form-status #idstatus").val("");
    $("#form-status #operador").val("=");
    $("#form-status #atributo").val($("#form-status #atributo option:first").val());
    $("#form-status #status").val($("#form-status #status option:first").val());
}
function atualizarListaFiltro(){
    $("#lista-filtro").load(session.urlmiles + "?controller=mdm/relatorio&op=listarrelatorio&relatorio=" + _relatorio);
}
function atualizarListaStatus(){
    $("#lista-status").load(session.urlmiles + "?controller=mdm/relatorio&op=listarstatus&relatorio=" + _relatorio);
}			
function excluirFiltro(id){
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'mdm/relatorio',
            op:"excluirfiltro",
            id:id
        },
        complete:function(){
            atualizarListaFiltro();
        }
    });
}
function excluirStatus(id){
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'mdm/relatorio',
            op:"excluirstatus",
            id:id
        },
        complete:function(){
            atualizarListaStatus();
        }
    });
}
function carregarValoresAtributo(fk){
    if (fk > 0){
        $(".form-control[data-tipoatributo=lista]").attr("id","valor");
        $(".form-control[data-tipoatributo=lista]").attr("name","valor");
        $(".form-control[data-tipoatributo=lista]").show();
        $(".form-control[data-tipoatributo=input]").attr("id","");
        $(".form-control[data-tipoatributo=input]").attr("name","");
        $(".form-control[data-tipoatributo=input]").hide();
        $.ajax({
            url:session.urlmiles,
            type:"GET",
            data:{
                controller:'requisicoes',
                op:"carregar_options",
                entidade:fk,
                atributo:"",
                filtro:""
            },
            complete:function(ret){
                $("#form-status #valor").html(ret.responseText);
            }
        });
    }else{
        $(".form-control[data-tipoatributo=input]").attr("id","valor");
        $(".form-control[data-tipoatributo=input]").attr("name","valor");
        $(".form-control[data-tipoatributo=input]").show();
        $(".form-control[data-tipoatributo=lista]").attr("id","");
        $(".form-control[data-tipoatributo=lista]").attr("name","");
        $(".form-control[data-tipoatributo=lista]").hide();
    }
    
}

function atualizarListaFiltroInicial(){
    $("#lista-filtroinicial").load(session.urlmiles + "?controller=mdm/relatorio&op=listarfiltroinicial&relatorio=" + _relatorio);
}	