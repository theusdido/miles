let mongo                   = require('../../database/mongo');
var connection_host         = 'http://179.108.169.97:57772/';

// MD5 para gerar o token do link
var MD5 = require("crypto-js/md5");

exports.importar = function(data,xhr){

    let namespace       = data.namespace.toLowerCase();
    let dataliberacao   = data.dataliberacao;
    let individual      = data.individual;

    let referencia      = data.referencia;
    let dia             = data.dia==undefined?0:data.dia;
    let proprietario    = data.proprietario!=0&&data.proprietario!=undefined?data.proprietario:'';

    let url             = '';
    if (individual != undefined && dataliberacao != undefined){
        url             = connection_host
        + 'csp/'+namespace+'/arquivos/webservice/extrato/' + individual + '.json';
    }else{

        url             = connection_host
        + 'csp/'+namespace+'/arquivos/webservice/extrato/extrato-'
        + referencia + '-' + dia + (proprietario==''?'':'-'+proprietario) + '.json';
    }

    console.log(url);
    xhr.open('GET',url);
    xhr.onreadystatechange = async function(){
        if (xhr.readyState === 4 && xhr.status == "200"){
            let retorno     = JSON.parse(xhr.responseText);
            let collection  = await mongo.collection('extrato_proprietario');
            
            
            retorno.forEach(function(dado,index,retorno){
                retorno[index].cabecalho.token = MD5(dado.cabecalho.token).toString();
            });

            let filtro = {};
            if (individual != undefined && dataliberacao != undefined){
                filtro['cabecalho.data_liberacao']      = dataliberacao;
            }else{
                filtro['cabecalho.referencia']      = referencia;
                filtro['cabecalho.dia']             = dia;
                if (proprietario != '' && proprietario != 0){
                    filtro['proprietario.codigo'] = proprietario;
                }
            }

            await collection.updateMany(filtro,
                [{$set : {'cabecalho.inativo':true}}]
            );
            
            await collection.insertMany(retorno);

            if (individual != undefined && dataliberacao != undefined){
                console.log("## Importação Realizada => ( " + dataliberacao + " - " + individual + " ) ##");
            }else{
                console.log("## Importação Realizada => ( " + referencia + " - " + dia + " ) ##");
            }
        }
    }
    xhr.send(null);
}