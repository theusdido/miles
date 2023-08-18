let mongo                   = require('../../database/mongo');
var connection_host         = 'http://179.108.169.97:57772/';

// MD5 para gerar o token do link
var MD5 = require("crypto-js/md5");

exports.importar = function(data,xhr){

    let collection      = 'extrato_impostorenda';
    let namespace       = data.namespace.toLowerCase();
    let ano             = data.ano!=0&&data.ano!=undefined?data.ano:0;
    let tipo            = data.tipo!=0&&data.tipo!=undefined?data.tipo:0;
    let cpfcnpj         = data.cpfcnpj!=0&&data.cpfcnpj!=undefined?data.cpfcnpj:'';
    let filename        = data.filename!=0&&data.filename!=undefined?data.filename:'';

    let url             = 
        connection_host + 
        'csp/' +
        namespace + 
        '/arquivos/webservice/extrato-irrf/' +
        filename
    ;

    console.log(url);
    xhr.open('GET',url);
    xhr.onreadystatechange = async function(){
        if (xhr.readyState === 4 && xhr.status == "200"){
            let retorno     = JSON.parse(xhr.responseText);
            let collection  = await mongo.collection('extrato_impostorenda');

            retorno.forEach(function(dado,index,retorno){
                retorno[index].cabecalho.token = MD5(dado.cabecalho.token).toString();
            });

            // Filtros
            let filtro = {};
            filtro['cabecalho.ano']                 = ano;
                
            if (tipo > 0){
                filtro['cabecalho.tipo_cliente']    = tipo;
            }
                
                
            if (cpfcnpj != ''){
                filtro[ (tipo==1?'proprietario':'inquilino') + '.cpf_cnpj']   = cpfcnpj;
            }


            await collection.updateMany(filtro,
                [{$set : {'cabecalho.inativo':true}}]
            );
            
            await collection.insertMany(retorno);

            console.log("## Importação Realizada => ( " + ano + " - " + tipo + " - " + cpfcnpj + ") ##");

        }
    }
    xhr.send(null);
}