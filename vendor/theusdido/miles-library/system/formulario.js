var EntidadePrincipalID                 = $("#entidadeprincipalid").val();
debugger;
formulario[EntidadePrincipalID]         = new tdFormulario(EntidadePrincipalID);
// Funcionalidade tem que vir antes do registro único
if (typeof funcionalidade != 'undefined') formulario[EntidadePrincipalID].funcionalidade = funcionalidade;

// Registro Único
is_registrounico = typeof registrounico == 'undefined' ? formulario[EntidadePrincipalID].entidade.registrounico : registrounico;
if (is_registrounico){
    formulario[EntidadePrincipalID].setRegistroUnico();
}else{
    formulario[EntidadePrincipalID].loadGrade();
}

if (funcionalidade == 'consulta'){
    formulario[EntidadePrincipalID].setConsulta($('#consulta_id').val());
}
