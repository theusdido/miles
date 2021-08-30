<?php

    switch(tdc::r("op")){
        case 'gerar':
            try {
                

                $referencia     = tdc::r('referencia');
                $dia            = (int)tdc::r('diapagamento');
                $plano          = tdc::p("td_erp_associacao_planomensalidade",tdc::r("planomensalidade"));

                // Receita
                $receita    = tdc::p("td_erp_financeiro_receita",1);
                $valor      = $receita->valor > 0 ? $receita->valor : $plano->valor;

                // Apaga a geração anterior
                $filtro = tdc::f();
                $filtro->addFiltro("td_receita","=",$receita->id);
                $filtro->addFiltro("referencia","=",$referencia);
                tdc::de("td_erp_financeiro_contasareceber",$filtro);

                // Monta as mensalidades baseado no plano selecionado
                $filtro = tdc::f("td_planomensalidade","=",$plano->id);
                foreach(tdc::d('td_erp_associacao_associado',$filtro) as $associado){

                    if ($associado->td_pessoa == 0 || $associado->td_pessoa == '') continue;

                    // Contas a Receber
                    $cp                         = tdc::p("td_erp_financeiro_contasareceber");
                    $cp->cliente                = $associado->td_pessoa;
                    $cp->documento              = '';
                    $cp->valor                  = $valor;
                    $cp->dataemissao            = date('Y-m-d');
                    $cp->datavencimento         = $dia . "/" . $referencia;
                    $cp->td_formarecebimento    = $receita->td_formarecebimento;
                    $cp->referencia             = $referencia;
                    $cp->pago                   = 0;
                    $cp->td_receita             = $receita->id;
                    $cp->armazenar();
                    
                }
                echo 1;
                Transacao::Fechar();
            }catch(Throwable $t){
                var_dump($t->getMessage());
                Transacao::Rollback();
            }
        break;
        default:
            $titulo = tdc::o("titulo",array("Mensalidade"));
            $titulo->mostrar();
        
            // Botão Gerar	
            $btn_gerar = tdClass::Criar("button");
            $btn_gerar->class = "btn btn-primary b-gerar";
            $span_gerar = tdClass::Criar("span");
            $span_gerar->class = "fas fa-file";
            $btn_gerar->add($span_gerar,"Gerar");	
            $btn_gerar->id = "b-gerar";
            
            // Loading
            $loading = Loading::requisicao();
            $loading->style = "float:right";
        
            $loading->mostrar();
            $btn_gerar->mostrar();
            
            $ncolunas       = 3; 
            $linha          = tdClass::Criar("div");
            $linha->class   = "row-fluid form_campos";
            
            ## REFERÊNCIA
            $label = tdClass::Criar("label");
            $label->add("Referência");

            $referencia = tdClass::Criar("input");
            $referencia->type   = "text";
            $referencia->class  = "form-control formato-mesano";
            $referencia->id     = "referencia";
            $referencia->name   = "referencia";
            $referencia->value    = date("m/Y");

            $coluna = tdClass::Criar("div");
            $coluna->class = "coluna";
            $coluna->data_ncolunas = $ncolunas;
            $coluna->add($label,$referencia);	
            $linha->add($coluna);

            # PLANO DE ASSINATURA
            $label = tdClass::Criar("label");
            $label->add("Plano de Mensalidade");

            $planomensalidade = tdClass::Criar("select");
            $planomensalidade->class    = "form-control";
            $planomensalidade->id       = "planomensalidade";
            $planomensalidade->name     = "planomensalidade";
            foreach (tdc::da("td_erp_associacao_planomensalidade") as $p){
                $op                     = tdClass::Criar("option");
                $op->value              = $p["id"];
                $op->data_diapagamento  = $p["diapagamento"];
                $op->add($p["nome"]);
                $planomensalidade->add($op);
            }

            $coluna = tdClass::Criar("div");
            $coluna->class = "coluna";
            $coluna->data_ncolunas = $ncolunas;
            $coluna->add($label,$planomensalidade);
            $linha->add($coluna);

            ## DIA DO PAGAMENTO
            $label = tdClass::Criar("label");
            $label->add("Dia do Pagamento");

            $diapagamento = tdClass::Criar("input");
            $diapagamento->type   = "text";
            $diapagamento->class  = "form-control";
            $diapagamento->id     = "diapagamento";
            $diapagamento->name   = "diapagamento";

            $coluna = tdClass::Criar("div");
            $coluna->class = "coluna";
            $coluna->data_ncolunas = $ncolunas;
            $coluna->add($label,$diapagamento);	
            $linha->add($coluna);

            #Exibe os campos
            $linha->mostrar();

            // Adiciona as bibliotecas JavaScript para o formulário
            addJSLIBFormSystem();

            // JavaScript
            $js = tdc::o("script");
            $js->add('
                $(document).ready( () => {
                    setDiaPagamento();
                });
                $("#planomensalidade").change( () => {
                    setDiaPagamento();
                });
                $("#referencia").mask("99/9999");
                $("#b-gerar").click( ()=> {
                    if ($("#referencia,#planomensalidade,#diapagamento").parent().hasClass("has-error")){
                        bootbox.alert("Existem campos com problemas!");
                        return false;
                    }                    
                    $.ajax({
                        url:session.urlmiles,
                        data:{
                            controller:"erp/associacao/mensalidade",
                            op:"gerar",
                            referencia:$("#referencia").val(),
                            planomensalidade:$("#planomensalidade").val(),
                            diapagamento:$("#diapagamento").val()
                        },
                        beforeSend:function(){
                            $(".td-loading2").show();
                        },
                        complete:function(ret){
                            if (parseInt(ret.responseText) == 1){
                                bootbox.alert("Mensalidades Geradas com Sucesso!");
                            }else{
                                bootbox.alert("Erro ao Gerar Mensalidades!");
                            }
                            $(".td-loading2").hide();
                        }
                    });
                });

                function setDiaPagamento(){
                    $("#diapagamento").val( $("#planomensalidade option:selected").data("diapagamento"));
                }
            ');
            $js->mostrar();           
    }