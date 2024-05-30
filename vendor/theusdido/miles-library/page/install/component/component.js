var componentes_carregados = [];
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
        url:session.urlmiles,
        data:{
            controller:'install/modulos',
            op:'load',
            package:package_selecionado,
            module:modulo_selecionado
        },
        complete:function(res){
            componentes_carregados =  JSON.parse(res.responseText);
            setCompoenent();
        }
    });
});

// Adiciona o componente para instalação via componente HTML
function addElementComponent( element  )
{
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

$(document).on('click','.generate-menu[data-module='+package_selecionado+'-'+modulo_selecionado+']', function() {

    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'install/modulos',
            op:'generate-menu',
            package:package_selecionado,
            module:modulo_selecionado,
            module_name:modulo_name_selecionado,
            components:componentes_carregados
        },
        complete:function(res){
            let retorno = JSON.parse(res.responseText);
        }
    });

});

function setCompoenent(){
    componentes_carregados.forEach((e) => {
        let url_compoente = session.urlmiles + '?controller=install/componentes&package=' + package_selecionado + '&component=' + modulo_selecionado;
        $('#accordion-install-components').load(url_compoente);
    });
}