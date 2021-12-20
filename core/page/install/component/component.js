$(".checkbox-componente").click(function(){
    var componentPath		= $(this).attr("id");
    var componenteNome 		= $(this).data("modulonome");
    var componenteDescricao	= $(this).data("modulodescricao");

    if (this.checked){
        addComponente({
            path:componentPath,
            nome:componenteNome,
            descricao:componenteDescricao
        });
    }else{
        excluirComponenteLista(componenteNome);
    }
});

$(".checkbox-registro").click(function(){
    var registroName 	= $(this).data("entidaderegistro");
    var registroPath	= $(this).data("path");
    if (this.checked){
        registros.push({
            entidade:registroName,
            path:registroPath
        });
    }else{
        excluirRegistroLista(registroName);
    }
});

$(".checkbox-modulo-all").click(function(){
    var modulo_id = $(this).data("modulo");
    $(".check-modulo-" + modulo_id).click();
});

$(document).ready(function(){
    $.ajax({
        url:'index.php',
        data:{
            controller:'install/modulos',
            op:'load',
            package:package_selecionado,
            module:modulo_selecionado
        },
        complete:function(res){
            // $('#accordion-install-components').html(res.responseText);
        }
    });
});