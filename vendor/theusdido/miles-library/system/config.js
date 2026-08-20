var td_entidadeauxiliar = [];
function entidadesAuxiliares(){
    if (typeof td_entidade === 'undefined') return;
    for(ea in td_entidade){
        if (td_entidade[ea].entidadeauxiliar == 1){
            var atributos = "id";
            for(a in td_atributo){
                if (td_atributo[a].entidade == td_entidade[ea].id){
                    if (td_atributo[a].nome != "empresa" && td_atributo[a].nome != "projeto"){
                        atributos += "," + td_atributo[a].nome;
                    }
                }
            }
            $.ajax({
                url:config.urlrequisicoes,
                data:{
                    op:"retorna_dados_entidade",
                    entidade:td_entidade[ea].nomecompleto.replace("-","."),
                    atributos:atributos,
                    entidadeid:td_entidade[ea].id
                },
                dataType:"json",
                complete:function(ret){                    
                    try{
                        var retorno = JSON.parse(ret.responseText);				
                        td_entidadeauxiliar[retorno.entidadeid] = retorno.dados;
                    }catch(e){
                        //console.log(ret.responseText);
                        //console.log(retorno);
                        console.warn(e);
                    }

                }
            });
        }
    }
}

// Carrega Entidades Auxiliares
entidadesAuxiliares();

// Sessão
// let time_session = setInterval(()=>{
//     $.ajax({
//         url:config.urlrequisicoes,
//         data:{
//             op:"is_session_active"
//         },
//         dataType:'json',
//         complete:function(res){
//             if (!res.responseJSON){
//                 clearInterval(time_session);
//                 bootbox.alert('Sua sessão expirou!',() => {
//                     location.href = session.urlmiles;
//                 });
//             }
//         }
//     });
// },60000);

// Global com a grade de dados da movimentação
var _gradedados_mov_current;

// SessionStorage
var _session = new tdSessionStorage();

// Ouça eventos de mudanças no sessionStorage e localStorage
window.addEventListener("storage", function (event) {

    if (event.key === '_monitor_mdm' && event.newValue){
        try {
            let _obj            = JSON.parse(event.newValue);
            let _content_obj    = typeof _obj._data === 'string' ? JSON.parse(_obj._data) : _obj._data;

            if (_content_obj && _content_obj.id !== undefined && _obj._conceito) {
                let _varName = 'td_' + _obj._conceito;
                if (typeof window[_varName] === 'undefined' || window[_varName] === null) {
                    window[_varName] = [];
                }
                window[_varName][_content_obj.id] = _content_obj;
            }
        } catch (err) {
            console.error("Erro ao processar _monitor_mdm no storage event:", err);
        }
    }

});


var is_session_active = false;
var timeout_session;
let inactivityTime = function () {

    // 30 minutos em milissegundos
    const timeout = 30 * 60 * 1000;

    // Função para redefinir o temporizador
    const resetTimer = function() {
        clearTimeout(timeout_session);
        timeout_session = setTimeout(logout, timeout);
    };

    // Função a ser chamada após o tempo de inatividade
    const logout = function() {
        window.sessionStorage.setItem("is_session_active",false);

        clearTimeout(timeout_session);
        bootbox.alert('Sua sessão expirou!',() => {
            location.href = session.urlmiles + '?controller=logout';
        });
    };

    // Eventos de atividade do mouse e do teclado
    window.onload           = resetTimer;
    document.onmousemove    = resetTimer;
    document.onkeypress     = resetTimer;
    document.onclick        = resetTimer;
    document.onscroll       = resetTimer;

    // Adicionar eventos para toque em dispositivos móveis
    document.ontouchstart = resetTimer;
};

if (JSON.parse(window.sessionStorage.getItem("is_session_active"))){
    // Inicia o tempo de inatividade do usuário
    inactivityTime();
}