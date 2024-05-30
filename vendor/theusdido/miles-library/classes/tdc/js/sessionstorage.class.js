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
            let _old_value      = this.get(key);
            let _new_value      = JSON.stringify(value);
            let _storageEvent   = new StorageEvent('storage', {
                key             : key,
                oldValue        : _old_value,
                newValue        : _new_value,
                url             : session.urlalias,
                storageArea     : localStorage
            });
            this.storage.setItem(key, _new_value);
            window.dispatchEvent( _storageEvent );
            return true;
        }
        return false;
    }

    get(key){
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