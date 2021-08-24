<?php
    $op = tdc::r("op");

    if ($op == 'gerar'){
        foreach(tdc::d('td_erp_associacao_associado') as $associado){

            // Plano de Mensalidade dos associados
            $plano_mensalidade = tdc::p("td_erp_associacao_planomensalidade",$associado->td_planomensalidade);

            // Criar Receita
            $receita                    = tdc::p("td_erp_financeiro_receita");
            $receita->descricao         = 'Pagamento de Mensalidade';
            $receita->fonterenda        = 1; // Mensalidade
            $receita->valor             = $plano_mensalidade->valor;
            $receita->formarecebimento  = 1; // Espécie
            $receita->receitafixa       = 1;
            $receita->armazenar();

            // Contas a Receber
            $cp = tdc::p("td_erp_financeiro_contasreceber");
            $cp->cliente            = $associado->td_pessoa;
            $cp->documento          = '';
            $cp->valor              = $plano_mensalidade->valor;
            $cp->dataemissao        = date("Y-m-d");
            $cp->datavencimento     = date("Y-m-") . $plano_mensalidade->diapagamento;
            $cp->formarecebimento   = 1;
            $cp->armazenar();
        }
        exit;
    }

    $titulo = tdc::o("titulo",array("Mensalidade"));
    $titulo->mostrar();

	// Botão Gerar	
	$btn_gerar = tdClass::Criar("button");
	$btn_gerar->class = "btn btn-primary b-gerar";
	$span_gerar = tdClass::Criar("span");
	$span_gerar->class = "fas fa-file";
	$btn_gerar->add($span_gerar,"Gerar");	
	$btn_gerar->id = "b-gerar";
    $btn_gerar->mostrar();