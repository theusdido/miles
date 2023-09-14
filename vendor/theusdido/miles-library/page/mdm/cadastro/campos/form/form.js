$(document).ready(function(){
    $('#atributodependencia').load(session.urlmiles + '?controller=mdm/cadastro&_entidade=' + _entidade + '&op=listar-atributos');
    $('#chaveestrangeira').load(session.urlmiles + '?controller=mdm/cadastro&op=listar-option-entidade');
    
    if (_atributo == 0){
        novo();
    }else{
        $('#html-id-atributo').html(_atributo);
        load();
    }

    // Quando mudar o tipo de elemento HTML
    document.getElementById("tipohtml").onchange = function(){
        if (document.getElementById("nome").value == ""){
            switch(this.value){
                case "1":
                    document.getElementById("tipo").value = "varchar";
                    document.getElementById("tamanho").value = "35";
                break;
                case "2":
                    document.getElementById("tipo").value = "varchar";
                    document.getElementById("tamanho").value = "120";
                break;						
                case "3":
                    document.getElementById("tipo").value = "varchar";
                    document.getElementById("tamanho").value = "200";
                break;
                case "4":
                    document.getElementById("tipo").value = "smallint";
                    document.getElementById("tamanho").value = "";
                break;						
                case "5":
                    document.getElementById("tipo").value = "varchar";
                    document.getElementById("tamanho").value = "200";
                break;
                case "6":
                    document.getElementById("tipo").value = "varchar";
                    document.getElementById("tamanho").value = "64";
                    document.getElementById("nome").value = "senha";
                    document.getElementById("descricao").value = "Senha";
                break;
                case "7":
                    document.getElementById("tipo").value = "boolean";
                    document.getElementById("tamanho").value = "";								
                break;
                case "8":
                    document.getElementById("tipo").value = "varchar";
                    document.getElementById("tamanho").value = "25";
                    document.getElementById("nome").value = "telefone";
                    document.getElementById("descricao").value = "Telefone";							
                break;
                case "9":
                    document.getElementById("tipo").value = "varchar";
                    document.getElementById("tamanho").value = "9";
                    document.getElementById("nome").value = "cep";
                    document.getElementById("descricao").value = "CEP";							
                break;
                case "10":
                    document.getElementById("tipo").value = "varchar";
                    document.getElementById("tamanho").value = "14";
                    document.getElementById("nome").value = "cpf";
                    document.getElementById("descricao").value = "CPF";							
                break;
                case "11":
                    document.getElementById("tipo").value = "date";
                    document.getElementById("tamanho").value = "";
                    document.getElementById("nome").value = "data";
                    document.getElementById("descricao").value = "Data";							
                break;
                case "12":
                    document.getElementById("tipo").value = "varchar";
                    document.getElementById("tamanho").value = "200";
                    document.getElementById("nome").value = "email";
                    document.getElementById("descricao").value = "E-Mail";
                break;
                case "13":
                    document.getElementById("tipo").value = "float";
                    document.getElementById("tamanho").value = "";
                    document.getElementById("nome").value = "valor";
                    document.getElementById("descricao").value = "Valor";
                break;
                case "14":
                    document.getElementById("tipo").value = "text";
                    document.getElementById("tamanho").value = "";
                break;
                case "15":
                    document.getElementById("tipo").value 		= "varchar";
                    document.getElementById("tamanho").value 	= "19";
                    document.getElementById("nome").value 		= "cnpj";
                    document.getElementById("descricao").value 	= "CNPJ";
                break;
                case "16":
                    document.getElementById("tipo").value = "int";
                    document.getElementById("tamanho").value = "";
                break;
                case "17":
                    document.getElementById("tipo").value = "varchar";
                    document.getElementById("tamanho").value = "60";
                    document.getElementById("nome").value = "cpfj";
                    document.getElementById("descricao").value = "CPF / CNPJ";
                break;
                case "19":
                    document.getElementById("tipo").value = "text";
                    document.getElementById("tamanho").value = "";
                    document.getElementById("nome").value = "arquivo";
                    document.getElementById("descricao").value = "Arquivo";
                break;					
                case "21":
                    document.getElementById("tipo").value = "text";
                    document.getElementById("tamanho").value = "";
                    document.getElementById("nome").value = "texto";
                    document.getElementById("descricao").value = "Texto";
                break;
                case "22":
                    document.getElementById("tipo").value = "int";
                    document.getElementById("tamanho").value = "";
                break;
                case "23":
                    document.getElementById("tipo").value = "datetime";
                    document.getElementById("tamanho").value = "";
                    document.getElementById("nome").value = "datahora";
                    document.getElementById("descricao").value = "Data/Hora";								
                break;
                case "24":
                    document.getElementById("tipo").value = "int";
                    document.getElementById("tamanho").value = "";
                break;							
                case "25":
                    document.getElementById("tipo").value = "int";
                    document.getElementById("tamanho").value = "";
                break;
                case "29":
                    document.getElementById("tipo").value = "varchar";
                    document.getElementById("tamanho").value = "7";
                    document.getElementById("nome").value = "mesano";
                    document.getElementById("descricao").value = "Mês/Ano";
                break;
                case "31":
                    document.getElementById("tipo").value 		= "float";
                    document.getElementById("tamanho").value 	= 0;
                break;							
            }
        }
        habilitaCheckbox();
    }
    $("#tipo").change(function(){
        if (this.value == "int" || this.value == "tinyint" || this.value == "smallint" || this.value == "mediumint" || this.value == "bigint"){
            $("#tamanho").val("");
        }
    });
    document.getElementById("collection").value = "utf8_general_ci";
    habilitaCheckbox();    
});

function novo(){
    $('#nome').val('');
    $('#descricao').val('');
    $('#tipo').val('');
    $('#tamanho').val('');
    $('#tipohtml').val('');    
    $('#chaveestrangeira').val('');
    $('#inicializacao').val('');
    $('#indice').val('');
    $('#tipoinicializacao').val(1);
    $('#atributodependencia').val('');
    $('#labelzerocheckbox').val('');
    $('#labelumcheckbox').val('');
    $('#legenda').val('');

    $('#readonly').attr('checked',false);
    $('#exibirgradededados').attr('checked',false);
    $('#nulo').attr('checked',false);
    $('#dataretroativa').attr('checked',false);
    $('#desabilitar').attr('checked',false);
    $('#criarsomatoriogradededados').attr('checked',false);
    $('#naoexibircampo').attr('checked',false);
    $('#is_unique_key').attr('checked',false);
}

function habilitaCheckbox(){
    if ($("#tipohtml").val() == "7"){
        $("#labelzerocheckbox").parents(".form-group").first().show();
        $("#labelumcheckbox").parents(".form-group").first().show();

        if ($("#labelzerocheckbox").val() == ""){
            $("#labelzerocheckbox").val("Não");
        }
        if ($("#labelumcheckbox").val() == ""){
            $("#labelumcheckbox").val("Sim");
        }
    }else{
        $("#labelzerocheckbox").parents(".form-group").first().hide();
        $("#labelumcheckbox").parents(".form-group").first().hide();
        $("#labelzerocheckbox,#labelumcheckbox").val("");
    }
}

function mudartipoinicializacao(tipo,obj){
    $("#spantipoinicializacao").html($(obj).html());
    $("#tipoinicializacao").val(tipo);
}

$('#btn-salvar-campo').click(function(){
    
    $.ajax({
        url:session.urlmiles,
        type:"POST",
        dataType:'json',
        data:{
            // Parametros
            controller:'mdm/cadastro',
            entidade:_entidade,
            atributo:_atributo,
            op:'salvar-campo',

            // Campos Inputs
            nome:$('#nome').val(),
            descricao:$('#descricao').val(),
            tipo:$('#tipo').val(),
            tamanho:$('#tamanho').val(),
            tipohtml:$('#tipohtml').val(),
            chaveestrangeira:$('#chaveestrangeira').val(),
            inicializacao:$('#inicializacao').val(),
            indice:$('#indice').val(),
            tipoinicializacao:$('#tipoinicializacao').val(),
            atributodependencia:$('#atributodependencia').val(),
            labelzerocheckbox:$('#labelzerocheckbox').val(),
            labelumcheckbox:$('#labelumcheckbox').val(),
            legenda:$('#legenda').val(),

            // Campos Checkbox
            readonly:$('#readonly').prop('checked'),
            exibirgradededados:$('#exibirgradededados').prop('checked'),
            nulo:$('#nulo').prop('checked'),
            dataretroativa:$('#dataretroativa').prop('checked'),
            desabilitar:$('#desabilitar').prop('checked'),
            criarsomatoriogradededados:$('#criarsomatoriogradededados').prop('checked'),
            naoexibircampo:$('#naoexibircampo').prop('checked'),
            is_unique_key:$('#is_unique_key').prop('checked')
        },
        complete:function(_res){
            unLoaderSalvar();
            mdmToastMessage("Salvo com Sucesso");
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
            controller:'mdm/cadastro',
            _atributo:_atributo,
            op:'load-atributo'
        },
        complete:function(_res){
            let _data = _res.responseJSON;

            // Campos Inputs
            $('#nome').val(_data.nome),
            $('#descricao').val(_data.descricao),
            $('#tipo').val(_data.tipo),
            $('#tamanho').val(_data.tamanho),
            $('#tipohtml').val(_data.tipohtml),
            $('#chaveestrangeira').val(_data.chaveestrangeira),
            $('#inicializacao').val(_data.inicializacao),
            $('#indice').val(_data.indice),
            $('#tipoinicializacao').val(_data.tipoinicializacao),
            $('#atributodependencia').val(_data.atributodependencia),
            $('#labelzerocheckbox').val(_data.labelzerocheckbox),
            $('#labelumcheckbox').val(_data.labelumcheckbox),
            $('#legenda').val(_data.legenda),

            // Campos Checkbox
            $('#nulo').attr('checked',getCampoCheckbox(_data.nulo));
            $('#autoincrement').attr('checked',getCampoCheckbox(_data.nulo));
            $('#exibirgradededados').attr('checked',getCampoCheckbox(_data.exibirgradededados));
            $('#dataretroativa').attr('checked',getCampoCheckbox(_data.dataretroativa));
            $('#readonly').attr('checked',getCampoCheckbox(_data.readonly));
            $('#desabilitar').attr('checked',getCampoCheckbox(_data.desabilitar));
            $('#criarsomatoriogradededados').attr('checked',getCampoCheckbox(_data.criarsomatoriogradededados));
            $('#naoexibircampo').attr('checked',getCampoCheckbox(_data.naoexibircampo));
            $('#is_unique_key').attr('checked',getCampoCheckbox(_data.is_unique_key));

        }
    });
}

function getCampoCheckbox(_valor){
    if (_valor == null || _valor == undefined || _valor == 0){
        return false;
    }else{
        return true;
    }
}