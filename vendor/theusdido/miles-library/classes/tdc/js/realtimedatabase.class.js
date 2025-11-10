/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link https://teia.tec.br

    * Classe Realdatatime ( Firebase Realtime Database )
    * Data de Criacao: 30/07/2023
    * Author: @theusdido
*/

class tdFirebaseRealtime {
    constructor(collectionName) {

        // Inicializa o Firebase
        if (!firebase.apps.length) {
            firebase.initializeApp(firebaseConfig);
        }

        // Obtém a referência para o banco de dados e a coleção (nó)
        this.database = firebase.database();
        this.collectionRef = this.database.ref('prod/' + collectionName);
    }

    /**
     * Adiciona um novo registro à coleção.
     * @param {Object} data O objeto de dados a ser salvo.
     * @returns {Promise<Object>} Uma Promise que resolve com o novo registro.
     */
    async create(data) {
        try {
            // Usa .push() para gerar uma chave única e adicionar o registro
            const newRef = this.collectionRef.push();
            await newRef.set(data);
            console.log("Registro adicionado com sucesso com a chave:", newRef.key);
            
            // Retorna o objeto com a chave
            return { id: newRef.key, ...data };
        } catch (error) {
            console.error("Erro ao adicionar registro:", error);
            throw error; // Propaga o erro para ser tratado por quem chamou a função
        }
    }

    /**
     * Lê todos os registros da coleção.
     * @returns {Promise<Array<Object>>} Uma Promise que resolve com um array de registros.
     */
    async readAll() {
        try {
            const snapshot = await this.collectionRef.once('value');
            const data = snapshot.val();
            const records = [];

            if (data) {
                // Converte o objeto de registros em um array
                Object.keys(data).forEach(key => {
                    records.push({ id: key, ...data[key] });
                });
            }

            console.log("Registros lidos com sucesso.");
            return records;
        } catch (error) {
            console.error("Erro ao ler registros:", error);
            throw error;
        }
    }

    /**
     * Lê um registro específico pelo seu ID.
     * @param {string} id O ID (chave) do registro.
     * @returns {Promise<Object|null>} Uma Promise que resolve com o registro ou null se não for encontrado.
     */
    async readById(id) {
        try {
            const snapshot = await this.collectionRef.child(id).once('value');
            const record = snapshot.val();
            
            if (record) {
                console.log("Registro encontrado com sucesso:", id);
                return { id, ...record };
            } else {
                console.log("Registro não encontrado com o ID:", id);
                return null;
            }
        } catch (error) {
            console.error("Erro ao ler registro:", error);
            throw error;
        }
    }

    /**
     * Atualiza um registro existente na coleção.
     * @param {string} id O ID (chave) do registro a ser atualizado.
     * @param {Object} data Os dados a serem atualizados.
     * @returns {Promise<void>} Uma Promise que resolve quando a operação é concluída.
     */
    async update(id, data) {
        try {
            await this.collectionRef.child(id).update(data);
            console.log("Registro atualizado com sucesso:", id);
        } catch (error) {
            console.error("Erro ao atualizar registro:", error);
            throw error;
        }
    }

    /**
     * Exclui um registro da coleção.
     * @param {string} id O ID (chave) do registro a ser excluído.
     * @returns {Promise<void>} Uma Promise que resolve quando a operação é concluída.
     */
    async delete(id) {
        try {
            await this.collectionRef.child(id).remove();
            console.log("Registro excluído com sucesso:", id);
        } catch (error) {
            console.error("Erro ao excluir registro:", error);
            throw error;
        }
    }
}