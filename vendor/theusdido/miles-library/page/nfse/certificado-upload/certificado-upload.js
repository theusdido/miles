$(function(){
    const URL_IMPORTAR = '?controller=nfse/certificado-upload';

    $("#btn-show-password").click(function(){
        let input = $("#senha");
        let icon = $(this).find("i");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
            icon.removeClass("fa-eye").addClass("fa-eye-slash");
        } else {
            input.attr("type", "password");
            icon.removeClass("fa-eye-slash").addClass("fa-eye");
        }
    });

    var meuDropzone = new Dropzone("#formulario" , {
        url:session.urlmiles + URL_IMPORTAR,
        paramName: "arquivo", 
        dictDefaultMessage: "Arraste o certificado (.pfx) para cá!",
        maxFilesize: 50,
        acceptedFiles: ".pfx",
        accept: function(file, done) {
            var senha = $("#senha").val();
            if (!senha) {
                bootbox.alert("Por favor, digite a Senha do Certificado antes de enviar o arquivo.");
                done("O campo Senha é obrigatório.");
                this.removeFile(file);
            } else {
                $('#senha_dz').val(senha);
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
                    $("#status").html('<img src="'+session.urlcurrenttheme+'check.gif" />');
                    try {
                        let res = JSON.parse(xhr.responseText);
                        setStatus(res.status);
                        bootbox.alert({
                            title: "Mensagem",
                            message: res.message                        
                        });
                    } catch (e) {
                        setStatus('error');
                    }
                }else{
                    setStatus('error');
                }
            }
        }
    });
});

function setStatus(status){
    let icone = status == 'success' ? 'check.gif' : 'erro.gif';
    $("#status").html('<img src="'+session.urlcurrenttheme+icone+'" width="25" />');
}