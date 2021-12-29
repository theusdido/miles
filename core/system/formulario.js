
var EntidadePrincipalID = $("#entidadeprincipalid").val();
var formulario          = [];

formulario[EntidadePrincipalID]                 = new tdFormulario(EntidadePrincipalID);

// Funcionalidade tem que vir antes do registro único
if (typeof funcionalidade != 'undefined') formulario[EntidadePrincipalID].funcionalidade = funcionalidade;

// Registro Único
is_registrounico = typeof registrounico == 'undefined' ? formulario[EntidadePrincipalID].entidade.registrounico : registrounico;
if (is_registrounico){
    formulario[EntidadePrincipalID].setRegistroUnico();
}else{
    formulario[EntidadePrincipalID].loadGrade();
}

// Monta os formulários das entidades que compoem o relacionamento
formulario[EntidadePrincipalID].entidades_filho.forEach((entidade_id)=>{
    formulario[entidade_id] = new tdFormulario(entidade_id);
    formulario[entidade_id].loadGrade();    
});