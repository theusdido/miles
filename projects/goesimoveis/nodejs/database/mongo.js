// MongoDB
const client          = require('mongodb').MongoClient;
const db              = 'goesimoveis';
const host            = 'mongodb://localhost:27017/';
const selected_db     = host + '/' + db;

exports.conn = async function()
{
    return await client.connect(selected_db);
}

exports.collection = async function( collection_name )
{
    let instancia = await this.instance();
    return instancia.collection(collection_name);
}

exports.instance = async function(){
    let conn        = await this.conn();
    return await conn.db(db);
}
exports.createDefaultCollections = async function(){

    let collections = [ 
        'extrato_proprietario', 
        'extrato_impostorenda' 
    ];

    let instancia  = await this.instance();
    collections.forEach( (collection) =>
    {
        instancia.createCollection(collection, (err,res) => {
            console.log('Collection => [ '+ collection +' ] created!');
        });
    });
}