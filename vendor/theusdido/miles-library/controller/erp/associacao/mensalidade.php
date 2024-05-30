<?php
    $op = tdc::r("op");

    switch($op){
        case 'gerar':
            // Plano de Mensalidade dos associados
            $plano_mensalidade  = tdc::p("td_erp_associacao_planomensalidade",tdc::r("plano"));
            $filtro             = tdc::f("planomensalidade","=",$plano_mensalidade->id);
            $referencia         = tdc::r("referencia");
            $referencia_explode = explode("/",$referencia);
            $mes_referencia     = $referencia_explode[0];
            $ano_referencia     = $referencia_explode[1];
            foreach(tdc::d('td_erp_associacao_associado',$filtro) as $associado){
                // Contas a Receber
                $cr = tdc::p("td_erp_financeiro_contasareceber");
                $cr->cliente            = $associado->pessoa;
                $cr->documento          = '';
                $cr->valor              = $plano_mensalidade->valor;
                $cr->dataemissao        = date("Y-m-d");
                $cr->datavencimento     = $ano_referencia ."-". $mes_referencia ."-". $plano_mensalidade->diapagamento;
                $cr->receita            = 1;
                $cr->pago               = 0;
                $cr->referencia         = $referencia;
                $cr->armazenar();
            }
        break;
        default:
            $titulo = tdc::o("titulo",array("Mensalidade"));
            $titulo->mostrar();
        
            // Botão Gerar	
            $btn_gerar          = tdClass::Criar("button");
            $btn_gerar->class   = "btn btn-primary b-gerar";
            $span_gerar         = tdClass::Criar("span");
            $span_gerar->class  = "fas fa-file";
            $btn_gerar->id      = "b-gerar";
            $btn_gerar->add($span_gerar,"Gerar");
        
        
            $form 					= tdClass::Criar("tdformulario");
            $form->addBotao($btn_gerar);
            $form->addCampo(Campos::Lista(
                "planomensalidade",
                "planomensalidade",
                "Plano de Mensalidade"
            ));
            $form->addCampo(Campos::TextoLongo(
                "referencia",
                "referencia",
                "Referência",
                date("m/Y")
            ));

            $form->mostrar();
        
            $js = tdc::o("script");
            $js->add('
                $(document).ready(function(){
                    $("#referencia").mask("99/9999");
                    carregarOptions("#planomensalidade",getEntidadeId("td_erp_associacao_planomensalidade"),0,"",true);
                });
        
                $("#b-gerar").click(function(){
                    $.ajax({
                        url:session.urlmiles,
                        data:{
                            controller:"erp/associacao/mensalidade",
                            op:"gerar",
                            plano:$("#planomensalidade").val(),
                            referencia:$("#referencia").val()
                        },
                        beforeSend:function(){
                            $(".loading2").show();
                        },
                        complete:function(){
                            $(".loading2").hide();
                            bootbox.alert("Mensalidade Gerada! ");
                        }
                    });
                });
            ');
            $js->mostrar();        

    }
