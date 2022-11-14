<?php    
    // **** VERSÃO 2.0 ****
	/*
    try{
        // Altera o campo td_entidade para entidade na tabela td_atributo
        $instrucao  = "ALTER TABLE ".ATRIBUTO." CHANGE td_entidade entidade INT NOT NULL;";
        $execute    = $conn->exec($instrucao);
    }catch(Throwable $t){
        transacao::rollback();
        if (IS_SHOW_ERROR_MESSAGE){
            var_dump($t->getMessage());
        }
    }
    */
	
    try{
        // Retira o td_ do campo de chave estrangeira
		/*
        foreach (tdc::d(ATRIBUTO) as $atributo){               
            $entidade = tdc::e($atributo->entidade);
            if (substr($atributo->nome,0,3) == 'td_'){
                $definition = SQL::definition($atributo);
                $name_old   = $atributo->nome;
                $name_new   = str_replace("td_","",$atributo->nome);
                try{
                    $instrucao_change   = "ALTER TABLE {$entidade->nome} CHANGE {$name_old} {$name_new} {$definition};";
                    $execute            = $conn->exec($instrucao_change);
                }catch(Throwable $t){

                }finally{
                    $atributo->nome = $name_new;
                    $atributo->armazenar();
                }
            }
        }
		*/
		/*
        if (!SQL::entity_exists('td_ecommerce_carrinhoitem')){
            // Nome da entidade de itens do pedido
            $instrucao = "ALTER TABLE td_ecommerce_carrinhoitem RENAME TO td_ecommerce_pedidoitem;";
            $execute = $conn->exec($instrucao);
            if ($execute){
                $pedidoitem = tdc::d(ENTIDADE,tdc::f('nome','=','td_ecommerce_carrinhoitem'));
                $pedidoitem->nome = 'td_ecommerce_pedidoitem';
                $pedidoitem->armazenar();
            }
        }
		*/
		/*
        // Adiciona tipo aba na entidade
        $instrucao  = "ALTER TABLE td_entidade ADD COLUMN tipoaba VARCHAR(25) NULL;";
        $execute    = $conn->exec($instrucao);
		*/
    }catch(Throwable $t){
        transacao::rollback();
        if (IS_SHOW_ERROR_MESSAGE){
            var_dump($t->getMessage());
        }
    }