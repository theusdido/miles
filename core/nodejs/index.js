
// Carrega o framework Express
const express               = require('express');
const http                  = require('http');
const reload                = require('reload');
// Cria aplicação Express
const app                   = express();
const url                   = require('url');

const XMLHttpRequest        = require('xhr2');
var xhr                     = new XMLHttpRequest();
var cors                    = require('cors');
var mongo                   = require('mongodb').MongoClient;
const mongo_host            = 'mongodb://localhost:27017';
const mongo_db              = 'locativa';
const mongo_selected_db     = mongo_host + '/' + mongo_db;


app.use(cors());

// Inicia a aplicação
app.get('/', function(req,res){
    res.send('<script src="/reload/reload.js"></script>');
    res.end();
});

// Importação de Extrato
app.get('/importarextratojson', function(req,res){
    var extrato = require('../../projects/1/controller/importarextratojson.js');    
    extrato.importar(url.parse(req.url, true).query,xhr);
    res.end();
});

// Cria o banco
app.get('/createdatabase', (req,res) =>{
    let resposta = res;
    mongo.connect(mongo_selected_db, function(err, db) {
        if (err) throw err;
        let msg = 'Database => ' + mongo_db + ' created!';
        console.log(msg);
        resposta.send(msg);
        let dbo = db.db(mongo_db);
        dbo.createCollection('extrato', (err,res) => {
            console.log('Collection => extrato created!');
            db.close();
        });
    });
});

app.get('/pesquisarextrato', cors(), function(req,res){   
    
    let retorno                 = [];
    let resposta                = res;               
    mongo.connect(mongo_selected_db, function(err, db) {
        if (err) throw err;
        let dbo             = db.db(mongo_db);
        let filtros         = {};
        let params          = url.parse(req.url, true).query;

        if (!is_empty(params.locador)){
            if (new RegExp('[0-9]+','g').exec(params.locador) == null){
                filtros['proprietario.nome'] = eval('/' + params.locador + '/i');
            }else{
                filtros['proprietario.codigo'] = params.locador;
            }
        }

        let p_referencia = params['referencia[]'];
        if (!is_empty(p_referencia)){
            let referencia  = typeof p_referencia === 'string' ? [p_referencia] : p_referencia;
            filtros['cabecalho.referencia'] = { $in: referencia};
        }

        if (!is_empty(params.diapagamento)){
            filtros['cabecalho.dia'] = params.diapagamento;
        }

        if (!is_empty(params.pendente)){
            filtros['cabecalho.pendente'] = params.pendente == 0 ? 'N' : 'S';
        }

        if (!is_empty(params.dataliberacao)){
            filtros['titulo.datageracao'] = params.dataliberacao;
        }

        if (!is_empty(params.titulo)){
            filtros['titulo.numero'] = params.titulo;            
        }

        let p_istitulo = params.comtitulo;
        if (!is_empty(p_istitulo) && p_istitulo == 0){
            filtros['titulo.comtitulo'] = { $ne : '' };
        }

        let p_geracao = params['geracao[]'];
        if (!is_empty(p_geracao)){
            let geracao  = typeof p_geracao === 'string' ? [p_geracao] : p_geracao;
            filtros['cabecalho.gerador'] = { $in: geracao};
        }

        let p_formapagamento = params['formapagamento[]'];
        if (!is_empty(p_formapagamento)){
            let formapagamento  = typeof p_formapagamento === 'string' ? [p_formapagamento] : p_formapagamento;
            filtros['formapagamento.codigo'] = { $in: formapagamento};
        }

        let p_negativo = params['negativo'];
        if (!is_empty(p_negativo) && p_negativo != '-1'){
            filtros['cabecalho.isnegativo'] = p_negativo;
        }
        dbo.collection('extrato').find(filtros).toArray(function(err,result){
            retorno = result;
            resposta.json(retorno);
            res.end();
            db.close();
        });
    });
});

const server = http.createServer(app);
server.listen(2711 , () => {
    console.log('NodeJS App Miles Avaiable !');
});

reload(app);

function is_empty(value){
    if (value == '' || value == undefined || value == null){
        return true;
    }else{
        return false;
    }
}