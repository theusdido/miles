$(function(){
    const URL_IMPORTAR = '?controller=locador/demonstrativo/importar';
    var quantidade_registros = 0;
    var indice_atual = 0;
    var contrato_atual = 0;
    var locador_atual = 0;
    var meuDropzone = new Dropzone("#formulario" , {
        url:session.urlmiles + URL_IMPORTAR,
        paramName: "arquivo", 
        dictDefaultMessage: "Arraste seus arquivos para cá!",
        maxFilesize: 300, 
        accept: function(file, done) {
            quantidade_registros    = 0;
            indice_atual            = 0;
            contrato_atual          = 0;
            locador_atual           = 0;
            $("#status1,#status2,.td-importar-error").html('');
            if (file) {
                statusEnviar();
                done();
            }else{
                done("Arquivo não aceito.");
            }
        },
        init: function () {
            this.on("queuecomplete", function (file) {
                
            });
        },
        params:function(files, xhr, chunk){
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    quantidade_registros = parseInt(xhr.response);
                    iniciarSalvamento(); 
                }
            }
        }
    });
    function statusEnviar(){
        $("#status1").html('<img src="'+session.urlloading+'" width="25" height="25"/>');
    }

    function iniciarSalvamento(){
        if (quantidade_registros > 0){
            $("#status1").html('<img src="'+session.urlcurrenttheme+'check.gif" />');
            $("#status2").html('<img src="'+session.urlloading+'" width="25" height="25"/>');
            salvar();
        }
    }

    function salvar(){
        $.ajax({
            url:session.urlmiles + URL_IMPORTAR,
            data: {
                op:"salvar",
                indice: indice_atual,
                contrato: contrato_atual,
                locador: locador_atual
            },
            dataType:"json",
            complete: function(ret){
                indice_atual++;
                let _res = ret.responseJSON;
                if (parseInt(_res.status) == 1){
                    if (indice_atual < quantidade_registros){
                        contrato_atual  = _res.contrato;
                        locador_atual   = _res.locador;
                        salvar();
                    }else{
                        $("#status2").html('<img src="'+session.urlcurrenttheme+'check.gif" />');
                    }
                }else{
                    $('#error-salvar').html(_res.msg);
                    $("#status2").html('<img src="'+session.urlcurrenttheme+'erro.gif" width="25" />');                              
                }
            }
        });
    }
});