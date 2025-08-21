<?php
    /*
        * Framework MILES
        * @license : Teia Tecnologia WEB.
        * @link http://www.teia.tec.br

        * Classe Monitory
        * Data de Criacao: 29/11/2024
        * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
    */
    class Monitory {
        /*
            * add
            * Data de Criacao: 29/11/2024
            * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
            * PARAMETROS
            *	@params: string file:"Nome da constante com o nome do arquivo"
            * RETORNO
            *	@return: PATH de um arquivo
        */
        public static function add($operacao,$entidade,$atributo,$valorid,$consumidor = 0){
            global $conn;
            
            $sql    = "SELECT id FROM " . MONITOR . " WHERE operacao = '{$operacao}' AND entidade = {$entidade} AND atributo = {$atributo} AND valorid = {$valorid} AND consumidor = {$consumidor}";
            $query  = $conn->query($sql);

            
            if ($query->rowCount() > 0){
                #$linha  = $query->fetch();
                #$id     = $linha["id"];
                #$sql    = "UPDATE " . MONITOR . " SET datahoraconsumo = NOW() WHERE id = {$id}";
                #$query  = $conn->exec($sql);
            }else{
                $id     = getProxId(MONITOR,$conn);
                $sql    = "
                    INSERT INTO " . MONITOR . "
                    (
                        id,
                        operacao,
                        entidade,
                        atributo,
                        valorid,
                        consumidor,
                        datahoracriacao,
                        datahoraconsumo
                    ) VALUES (
                        {$id},
                        '{$operacao}',
                        {$entidade},
                        {$atributo},
                        {$valorid},                 
                        {$consumidor},
                        NOW(),
                        NOW()
                    );
                ";
                try{
                    $query  = $conn->exec($sql);
                }catch(Throwable $t){
                    if (IS_SHOW_ERROR_MESSAGE){
                        Debug::console(array(
                            $t->getMessage(),
                            $sql->getInstrucao()
                        ),'Classe Monitor - Método add');
                    }
                }
            }
        }
    }