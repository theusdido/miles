let mongo                   = require('../../database/mongo');
var connection_host         = 'http://179.108.169.97:57772/';

// MD5 para gerar o token do link
var MD5 = require("crypto-js/md5");

exports.importar = function(data,xhr){
    console.clear();
    let collection_name = 'boleto_locador';
    let namespace       = data.namespace.toLowerCase();
    let referencia      = data.referencia;
    let filename        = data.filename!=0&&data.filename!=undefined?data.filename:'';

    let url             = 
        connection_host + 
        'csp/' +
        namespace + 
        '/arquivos/webservice/boleto/proprietario/' +
        filename
    ;

    console.log(url);
    
    xhr.open('GET',url);
    xhr.onreadystatechange = async function(){
        if (xhr.readyState === 4 && xhr.status == "200"){
            
            let retorno     = JSON.parse(xhr.responseText);
            let collection  = await mongo.collection(collection_name);

            retorno.forEach(function(dado,index,retorno){
                let nosso_numero = dado.boleto.nosso_numero;
                if (nosso_numero.length > 15){
                    nosso_numero = nosso_numero.substr(2,15);
                }
                retorno[index].token                = MD5(nosso_numero).toString();
                retorno[index].boleto.nosso_numero  = nosso_numero;
            });

            // Filtros 
            let filtro = {};

            filtro['referencia']                 = referencia;
            await collection.updateMany(filtro,
                [{$set : {'inativo':true}}]
            );

            await collection.insertMany(retorno);
            console.log("## Importação Realizada => ( " + referencia + ") ##");
        }
    }
    xhr.send(null);

}