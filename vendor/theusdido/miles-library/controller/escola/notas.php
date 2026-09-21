<?php
    switch($_op){
        case 'lista-alunos':
            tdc::wj(tdc::da('td_erp_escola_aluno',SqlCriterio::$IS_CRITERIA_ACTIVE_ONLY));
        break;
        case 'carregar-notas':
            $alunos = tdc::da('td_erp_escola_aluno',SqlCriterio::$IS_CRITERIA_ACTIVE_ONLY);
            $notas = tdc::da('td_erp_escola_avaliacaonota',['avaliacao','=',tdc::r('avaliacao')]);
            tdc::wj(array(
                'alunos' => $alunos,
                'notas' => $notas
            ));
        break;        
        case 'salvar':
            $avaliacao = tdc::r('avaliacao');
            $notas = tdc::r('notas');

            foreach($notas as $nota)
            {
                $estudante = $nota['estudante'];

                // Tratamento para o valor da nota
                $nota_valor = empty($nota['nota']) ? null : (float)str_replace(',','.',$nota['nota']);
                
                // Tratamento para atualizar a nota da avaliação caso já exista uma nota para o aluno
                $criterio = tdc::f();
                $criterio->addFiltro('avaliacao','=',$avaliacao);
                $criterio->addFiltro('aluno','=',$estudante);

                // Processo que salva a nota da avaliação de cada estudante
                $nota_avaliacao = tdc::p('erp_escola_avaliacaonota')->newNotExistsCriteria($criterio);
                $nota_avaliacao->avaliacao = $avaliacao;
                $nota_avaliacao->aluno = $estudante;
                $nota_avaliacao->nota = $nota_valor;
                if ($nota_avaliacao->armazenar()){
                    $retorno = array(
                        "status" => "success",
                        "msg" => "Salvo com sucesso!"
                    );
                }else{
                    $retorno = array(
                        "status" => "error",
                        "msg" => "Erro ao salvar as notas."
                    );
                }
                tdc::wj($retorno);
            }
        break;
    }