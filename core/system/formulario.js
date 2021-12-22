var EntidadePrincipalID = $("#entidadeprincipalid").val();
var formulario          = [];

formulario[EntidadePrincipalID] = new tdFormulario(EntidadePrincipalID);
formulario[EntidadePrincipalID].loadGrade();