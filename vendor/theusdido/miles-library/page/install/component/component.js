$(".checkbox-componente").click(function(){
    addElementComponent( this );
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

$(document).on('click','.checkbox-componente-all', function() {
    let is_add_component    = $(this).is(':checked');
    let contexto            = $(this).attr('id');
    let selector            = '.checkbox-componente[data-module-id="'+contexto+'"]:visible';

    $(selector).attr('checked',is_add_component);
    $(selector).each(function(){
        if (is_add_component){
            addElementComponent( this );
        }else{

        }
    });


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
            let retorno = JSON.parse(res.responseText);
            retorno.forEach((e) => {
                let url_compoente = 'index.php?controller=install/componentes&package=' + package_selecionado + '&component=' + modulo_selecionado;
                $('#accordion-install-components').load(url_compoente);
            });
        }
    });
});

// Adiciona o componente para instalação via componente HTML
function addElementComponent( element  )
{
    console.log(element);
    var componentPath		= $(element).attr("id");
    var componenteNome 		= $(element).data("module-name");
    var componenteDescricao	= $(element).data("module-description");

    if (element.checked){
        addComponente({
            path:componentPath,
            nome:componenteNome,
            descricao:componenteDescricao
        });
    }else{
        excluirComponenteLista(componenteNome);
    }
}

function removeElementComponent( element )
{

}
