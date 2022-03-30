var quantidade_notas = 0;
var meuDropzone = new Dropzone("#formulario" , {
    url:session.urlmiles + "?page=nfse/importar",
    paramName: "arquivo", 
    dictDefaultMessage: "Arraste seus arquivos para cá!",
    maxFilesize: 300, 
    accept: function(file, done) {        
        if (file.name == "olamundo.png") {
            done("Arquivo não aceito.");
        } else {
            statusEnviar();
            done();
        }
    },
    init: function () {
        this.on("queuecomplete", function (file) {
            
        });
    },
    params:function(files, xhr, chunk){
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                quantidade_notas = parseInt(xhr.response);
                iniciarSalvamento(); 
            }
        }
    }
});
function statusEnviar(){
    $("#status1").html('<img src="'+session.urlloading+'" width="25" height="25"/>');
}

function iniciarSalvamento(){
    if (quantidade_notas > 0){
        $("#status1").html('<img src="'+session.urlcurrenttheme+'check.gif" />');
        $("#status2").html('<img src="'+session.urlloading+'" width="25" height="25"/>');
        salvar();
    }
}

function salvar(){
    $.ajax({
        url:session.urlmiles + "?page=nfse/importar",
        data: {
            op:"salvar",
            indice: quantidade_notas
        },
        complete: function(ret){
            if (parseInt(ret.responseText) == 1){
                quantidade_notas--;
                if (quantidade_notas >= 1){
                    salvar();
                }else{
                    $("#status2").html('<img src="'+session.urlcurrenttheme+'check.gif" />');
                    //parent.$("#enviar").button('reset');
                    //parent.$("#enviar").attr("class","btn btn-success");
                    //parent.$("#enviar").html("Enviado");ss
                }
            }else{
                $("#status2").html('<img src="'+session.urlcurrenttheme+'erro.gif" width="25" />');                              
            }
        }
    });
}