/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link https://teia.tec.br

    * Classe que funciona como interface para o recurso SessionStorage
    * Data de Criacao: 30/07/2023
    * Author: @theusdido
*/
class tdSessionStorage {

    constructor() {
        this.storage = window.localStorage;
    }
    
    set(key,value){
    if (this.storage) {
        this.storage.setItem(key, JSON.stringify(value));
        return true;
    }
    return false;
    }

    get(keyg){
        if (this.storage) {
            let valor = '';
            try{
                valor = JSON.parse(String(this.storage.getItem(key)));
            }catch(e){
                console.error(e.getMessage());
            }finally{
                return valor;
            }
        }
    }

    remove(key){
        if (this.storage) {
            this.storage.removeItem(key);
            return true;
        }
        return false;
    }

    clear(){
        if (this.storage) {
            this.storage.clear();
            return true;
        }
        return false;
    }
}