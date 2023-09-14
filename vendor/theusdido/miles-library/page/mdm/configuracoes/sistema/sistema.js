$(document).ready(function(){
    load();
});

function load(){
    $.ajax({
        url:session.urlmiles,
        dataType:'json',
        data:{
            controller:'mdm/configuracoes/sistema',
            op:'load'
        },
        complete:function(_res){
            let _data = _res.responseJSON;

            $('#urlupload').val(_data.urlupload);
            $('#urlrequisicoes').val(_data.urlrequisicoes);
            $('#urlsaveform').val(_data.urlsaveform);
            $('#urlloadform').val(_data.urlloadform);
            $('#urluploadform').val(_data.urluploadform);
            $('#urlenderecofiltro').val(_data.urlenderecofiltro);
            $('#urlpesquisafiltro').val(_data.urlpesquisafiltro);
            $('#urlexcluirregistros').val(_data.urlexcluirregistros);
            $('#urlinicializacao').val(_data.urlinicializacao);
            $('#urlloadgradededados').val(_data.urlloadgradededados);
            $('#urlloading').val(_data.urlloading);
            $('#urlrelatorio').val(_data.urlrelatorio);
            $('#urlmenu').val(_data.urlmenu);
            $('#linguagemprogramacao').val(_data.linguagemprogramacao);
            $('#bancodados').val(_data.bancodados);
            $('#pathfileupload').val(_data.pathfileupload);
            $('#pathfileuploadtemp').val(_data.pathfileuploadtemp);
            $('#tipogradedados').val(_data.tipogradedados);
            $('#casasdecimais').val(_data.casasdecimais);
        }
    });
}

$('#btn-conf-sistema-salvar').click(function(){
    let dados = {
        urlupload:$('#urlupload').val(),
        urlrequisicoes:$('#urlrequisicoes').val(),
        urlsaveform:$('#urlsaveform').val(),
        urlloadform:$('#urlloadform').val(),
        urluploadform:$('#urluploadform').val(),
        urlenderecofiltro:$('#urlenderecofiltro').val(),
        urlpesquisafiltro:$('#urlpesquisafiltro').val(),
        urlexcluirregistros:$('#urlexcluirregistros').val(),
        urlinicializacao:$('#urlinicializacao').val(),
        urlloadgradededados:$('#urlloadgradededados').val(),
        urlloading:$('#urlloading').val(),
        urlrelatorio:$('#urlrelatorio').val(),
        urlmenu:$('#urlmenu').val(),
        linguagemprogramacao:$('#linguagemprogramacao').val(),
        bancodados:$('#bancodados').val(),
        pathfileupload:$('#pathfileupload').val(),
        pathfileuploadtemp:$('#pathfileuploadtemp').val(),
        tipogradedados:$('#tipogradedados').val(),
        casasdecimais:$('#casasdecimais').val()
    }
    $.ajax({
        url:session.urlmiles,
        dataType:'json',
        data:{
            controller:'mdm/configuracoes/sistema',
            op:'salvar',
            data:dados
        },
        complete:function(_res){
            unLoaderSalvar();
            mdmToastMessage("Salvo com Sucesso");
            load();
        },
        beforeSend:function(){
            addLoaderSalvar();
        }        
    });
});