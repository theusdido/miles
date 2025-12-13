<?php
    
    // Retorna o ID da entidade
    $entidade_id_associado  = getEntidadeId('td_associado');
    $entidade_id_local      = getEntidadeId('td_local');

    // Função que adiciona um "campo" de relacionamento
    $fn = function($item){
        global $entidade_id_associado;
        global $entidade_id_local;

        // Relacionamento
        $item['locais'] = getListaRegFilhoArray(
            $entidade_id_associado, # Entidade Pai
            $entidade_id_local, # Entidade Filho
            $item['id'] # Chave Primária
        );

        // Retorna a Chave Estrangeira
        return $item;
    };

    // Mapeamento de registro da tabela
    tdc::wj(array_map($fn,tdc::da('td_associado')));