var EntidadePrincipalID = $("#entidadeprincipalid").val();
var formulario          = [];

formulario[EntidadePrincipalID] = new tdFormulario(EntidadePrincipalID);
formulario[EntidadePrincipalID].loadGrade();

formulario[EntidadePrincipalID].entidades_filho.forEach((entidade_id)=>{
    formulario[entidade_id] = new tdFormulario(entidade_id);
    formulario[entidade_id].loadGrade();    
});