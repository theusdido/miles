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

let mongo                   = require('./database/mongo');
const res                   = require('express/lib/response');
app.use(cors());

// Inicia a aplicação
app.get('/', function(req,res){
    res.send('<script src="/reload/reload.js"></script>');
    res.end();
});

// Importação de Boleto de Proprietário
app.get('/importarboletoproprietario', function(req,res){
    let boleto = require('./boleto/proprietario/importar.js');
    boleto.importar(url.parse(req.url, true).query,xhr);
    res.end();
});

// Importação de Extrato
app.get('/importarextratoproprietario', function(req,res){
    let extrato = require('./extrato/proprietario/importar.js');    
    extrato.importar(url.parse(req.url, true).query,xhr);
    res.end();
});

// Importação de Extrato
app.get('/importarextratoirrf', function(req,res){
    let extrato = require('./extrato/impostorenda/importar.js');
    extrato.importar(url.parse(req.url, true).query,xhr);
    res.end();
});

// Cria o banco
app.get('/createdatabase', (req,res) => {
    mongo.createDefaultCollections();
    res.end();
});

app.get('/pesquisarboletoproprietario', cors(), function(req,res){
    require('./boleto/proprietario/consultar.js')
    .consultar(url.parse(req.url, true).query,xhr)
    .then( function(dados){ 
        res.json(dados);
        res.end();
    });

});

app.get('/pesquisarboleto', cors(), function(req,res){
    require('./boleto/proprietario/consultar.js')
    .consultar(url.parse(req.url, true).query,xhr)
    .then( function(dados){ 
        res.json(dados);
        res.end();
    });
});

app.get('/pesquisarextrato', cors(), function(req,res){

    require('./extrato/proprietario/consultar.js')
    .consultar(url.parse(req.url, true).query,xhr)
    .then( function(dados){ 
        res.json(dados);
        res.end();
    });

});

app.get('/pesquisarextratoirrf', cors(), function(req,res){   
    require('./extrato/impostorenda/consultar.js')
    .consultar(url.parse(req.url, true).query,xhr)
    .then( function(dados){ 
        res.json(dados);
        res.end();
    });
});

const server = http.createServer(app);
server.listen(2711, () => {
    console.log('NodeJS App Miles Avaiable !');
});

reload(app);