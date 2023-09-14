$("#accordion_filtros,#accordion_status,#accordion_filtrosinicias,#panel-colunas").hide();
$('#exibirbotaoeditar,#exibirbotaoexcluir,#exibirbotaoemmassa,#exibircolunaid,#adicionaridfiltro').attr('checked',false);

$(document).ready(function(){
    $('#entidade').load(session.urlmiles + '?controller=mdm/consulta&op=listar-entidade-option');
    $('#movimentacao').load(session.urlmiles + '?controller=mdm/consulta&op=listar-movimentacao-option');        
    if (_consulta != 0){
        load();
    }
});

$('#btn-salvar-consulta').click(function(){
    if ($("#entidade").val() == "" || $("#entidade").val() == null){
        alert('Entidade não pode ser vazio');
        return;
    }    
    $.ajax({
        url:session.urlmiles,
        type:"POST",
        dataType:'json',
        data:{

            // Parametros
            controller  :'mdm/consulta',
            op          :'salvar',

            // Campos Inputs
			id              :_consulta,
			descricao       :$('#descricao').val(),
			entidade        :$('#entidade').val(),
			movimentacao    :$('#movimentacao').val(),

            // Campos Checkbox
			exibirbotaoeditar		:$('#exibirbotaoeditar').prop('checked'),
			exibirbotaoexcluir		:$('#exibirbotaoexcluir').prop('checked'),
			exibirbotaoemmassa		:$('#exibirbotaoemmassa').prop('checked'),
			exibircolunaid			:$('#exibircolunaid').prop('checked'),
			adicionaridfiltro		:$('#adicionaridfiltro').prop('checked')
        },
        complete:function(_res){
            let _retorno = _res.responseJSON;
            if (_retorno.status == 'success'){
                unLoaderSalvar();
                mdmToastMessage("Salvo com Sucesso");
                _consulta = _retorno.id;
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
            controller:'mdm/consulta',
            id:_consulta,
            op:'load'
        },
        complete:function(_res){
            let _data = _res.responseJSON;

            // Campos Inputs
            $('#descricao').val(_data.descricao);
            $('#entidade').val(_data.entidade);
            $('#movimentacao').val(_data.movimentacao);

            // Campos Checkbox
            $('#exibirbotaoeditar').attr('checked',_data.exibirbotaoeditar == 0 ? false : true);
            $('#exibirbotaoexcluir').attr('checked',_data.exibirbotaoexcluir == 0 ? false : true);
            $('#exibirbotaoemmassa').attr('checked',_data.exibirbotaoemmassa == 0 ? false : true);
            $('#exibircolunaid').attr('checked',_data.exibircolunaid == 0 ? false : true);
            $('#adicionaridfiltro').attr('checked',_data.adicionaridfiltro == 0 ? false : true);

            $("#valor").val("");
            atualizarListaFiltro(_consulta);
            atualizarListaStatus();
            atualizarListaFiltroInicial();
            $('#panel-colunas').load(session.urlmiles + "?controller=page&page=mdm/consulta/colunas");
            $("#accordion_filtros,#accordion_status,#accordion_filtrosinicias,#panel-colunas").show();
            carregarValoresAtributo($("#form-status #atributo").find("option:selected").data("chaveestrangeira"));
            $('select[id="atributo"]').load(session.urlmiles + '?controller=mdm/consulta&_entidade=' + _data.entidade + '&op=listar-atributos');
            $('#status').load(session.urlmiles + '?controller=mdm/consulta&op=listar-status');
        }
    });
}

function validar(){
    if ($("#entidade").val() == "" || $("#entidade").val() == null){
        alert('Entidade não pode ser vazio');
        return false;
    }
    return true;
}
function editarFiltro(id){
    $("#form-filtro #consulta").val(id);
    $("#form-filtro #atributo").val($("#lista-filtro #atributo-editar-" + id).data("atributo"));
    $("#form-filtro #operador").val($("#lista-filtro #atributo-editar-" + id).data("operador"));
    $("#form-filtro #legenda").val($("#lista-filtro #atributo-editar-" + id).data("legenda"));
    $("#form-filtro #idfiltro").val($("#lista-filtro #atributo-editar-" + id).data("idfiltro"));
    $("#modalCadastroFiltro").modal('show');
}
function editarFiltroInicial(id){
    $("#form-filtro-inicial #consulta").val(id);
    $("#form-filtro-inicial #atributo").val($("#lista-filtroinicial #atributo-editar-" + id).data("atributo"));
    $("#form-filtro-inicial #operador").val($("#lista-filtroinicial #atributo-editar-" + id).data("operador"));
    $("#form-filtro-inicial #valor").val($("#lista-filtroinicial #atributo-editar-" + id).data("valor"));
    $("#form-filtro-inicial #legenda").val($("#lista-filtroinicial #atributo-editar-" + id).data("legenda"));
    $("#form-filtro-inicial #idfiltro").val($("#lista-filtroinicial #atributo-editar-" + id).data("idfiltro"));
    $("#modalCadastroFiltroInicial").modal('show');
}			
function editarStatus(id){
    $("#form-status #consulta").val(id);
    $("#form-status #atributo").val($("#lista-status #atributo-editar-" + id).data("atributo"));
    $("#form-status #operador").val($("#lista-status #atributo-editar-" + id).data("operador"));
    $("#form-status #valor").val($("#lista-status #atributo-editar-" + id).data("valor"));
    $("#form-status #status").val($("#lista-status #atributo-editar-" + id).data("status"));
    $("#form-status #idstatus").val($("#lista-status #atributo-editar-" + id).data("idstatus"));
    $("#modalCadastroStatus").modal('show');
}

function novoFiltro(){
    $("#modalCadastroFiltro").modal({
        backdrop:false
    });
    $("#modalCadastroFiltro").modal('show');
    $("#form-filtro #consulta,#form-filtro #idfiltro,#form-filtro #legenda").val("");
    $("#form-filtro #operador").val("=");
    $("#form-filtro #atributo").val($("#form-filtro #atributo option:first").val());
}
function novoFiltroInicial(){
    $("#modalCadastroFiltroInicial").modal({
        backdrop:false
    });
    $("#modalCadastroFiltro").modal('show');
    $("#form-filtro #consulta,#form-filtro #idfiltro,#form-filtro #legenda").val("");
    $("#form-filtro #operador").val("=");
    $("#form-filtro #atributo").val($("#form-filtro #atributo option:first").val());
}			
function novoStatus(){
    $("#modalCadastroStatus").modal({
        backdrop:false
    });
    $("#modalCadastroStatus").modal('show');				
    $("#form-status #consulta,#form-status #valor,#form-status #idstatus").val("");
    $("#form-status #operador").val("=");
    $("#form-status #atributo").val($("#form-status #atributo option:first").val());
    $("#form-status #status").val($("#form-status #status option:first").val());
}
function atualizarListaFiltro(consulta){
    $("#lista-filtro").load(session.urlmiles + "?controller=mdm/consulta&op=listarconsulta&consulta=" + _consulta);
}
function atualizarListaFiltroInicial(){
    $("#lista-filtroinicial").load(session.urlmiles + "?controller=mdm/consulta&op=listarfiltroinicial&consulta=" + _consulta);
}
function atualizarListaStatus(consulta){
    $("#lista-status").load(session.urlmiles + "?controller=mdm/consulta&op=listarstatus&consulta=" + _consulta);
}
function excluirFiltro(id){
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'mdm/consulta',
            op:"excluirfiltro",
            id:id
        },
        complete:function(){
            atualizarListaFiltro(id);
        }
    });
}
function excluirFiltroInicial(id){
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'mdm/consulta',
            op:"excluirfiltroinicial",
            id:id
        },
        complete:function(){
            atualizarListaFiltroInicial();
        }
    });
}			
function excluirStatus(id){
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'mdm/consulta',
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
            url:sessiom.urlmiles,
            type:"GET",
            data:{
                controller:'requisicoes',
                op:"carregar_options",							
                entidade:fk,
                atributo:"",
                filtro:"",
                key:"k"
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

$("#salvarFiltro").click(function(){
    $.ajax({
        type:"POST",
        url:session.urlmiles,
        data:{
            controller:'mdm/consulta',
            op:"salvarfiltro",
            operador: $("#form-filtro #operador").val(),
            atributo: $("#form-filtro #atributo").val(),
            consulta:_consulta,
            id:$("#form-filtro #idfiltro").val(),
            legenda:$("#form-filtro #legenda").val()
        },
        complete:function(r){
            atualizarListaFiltro(_consulta);
            $("#modalCadastroFiltro").modal('hide');
        }
    });
});
$("#salvarFiltroInicial").click(function(){
    $.ajax({
        type:"POST",
        url:session.urlmiles,
        data:{
            controller:'mdm/consulta',
            op:"salvarfiltroinicial",
            operador: $("#form-filtro-inicial #operador").val(),
            atributo: $("#form-filtro-inicial #atributo").val(),
            consulta:_consulta,
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
$("#salvarStatus").click(function(){
    let id_status       = $("#form-status #idstatus").val();
    let valor_atributo  = $("#form-status #atributo").val();
    if (valor_atributo ==  0 || valor_atributo == ''){
        alert('Campo atributo é obrigatório.');
        return;
    }
    $.ajax({
        type:"POST",
        url:session.urlmiles,
        data:{
            controller:'mdm/consulta',
            op:"salvarstatus",
            operador: $("#form-status #operador").val(),
            valor: $("#form-status #valor").val(),
            atributo: valor_atributo,
            consulta:_consulta,
            id:id_status,
            status:$("#form-status #status").val()
        },
        complete:function(r){
            atualizarListaStatus();
            $("#modalCadastroStatus").modal('hide');
        }
    });
});
$("#form-status #atributo").change(function(){
    carregarValoresAtributo($(this).find("option:selected").data("chaveestrangeira"));
});

$("#lista-filtro").sortable({
    update: function( event, ui ) {
        var ordenacao = [];
        $("#lista-filtro li").each(
            (e,elemento) => {
                var id = $(elemento).data("id");
                //<span class="fas fa-ellipsis-v pontinhos" aria-hidden="true"></span>
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
                entidade:"td_consultafiltro",
                atributo:"ordem",
                ordem:ordenacao
            }
        });
    }
});