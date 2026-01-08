<?php
    $filtro = tdc::f('link','=',$_GET['link']);
    $demonstrativo = tdc::du('td_demonstrativo',$filtro);
    $locador = tdc::du('td_demonstrativo_locador',tdc::f("demonstrativo","=",$demonstrativo->id));
    $contratos = tdc::d('td_demonstrativo_contrato',tdc::f("demonstrativo","=",$demonstrativo->id));
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Demonstrativo - FABIANA ZANATTA</title>   
    <style type="text/css">
        /* Layout de página A4 */
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #111;
            margin: 0;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto 12mm;
            box-sizing: border-box;
            background: #fff;
        }

        @media print {
            .page {
                page-break-after: always;
                border: none;
            }
        }

        /* Cabeçalho */
        .header-table {
            width: 100%;
            border-collapse: collapse;                 
        }

        .header-table td {
            vertical-align: middle;
        }

        .title {
            font-weight: 700;
            font-size: 16px;
        }

        /* Seções do proprietário / imóvel */
        .owner-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom:1px solid #000;
            border-top: 3px solid #000;       
            margin-top:5px;
        }

        .owner-table td {
            vertical-align: top;
        }

        /* Tabela de eventos/transações */
        .events {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        .events th,
        .events td {
            padding: 6px 8px;    
        }
        .events tbody tr td
        {
            border-top: 1px solid #dcdcdc;
        }

        .events th {
            background: #f6f6f6;
            font-weight: 700;
        }

        .right {
            text-align: right;
            white-space: nowrap;
        }
        .left {
            text-align: left;
        }

        /* Totais */
        .totals {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;    
        }

        .totals td {
            padding: 6px 8px;
            /* border: 1px solid #dcdcdc; */
            font-weight: 700;
            border-top: 1px solid #000 !important;
        }

        .signature {
            margin-top: 30px;
            width:50%;
            margin-left:25%;
        }

        .signature p
        {
            text-align: center;
            float:left;
            width:50%;
            margin:0;
            padding:0;
        }


        .page-number {
            text-align: right;
            font-size: 11px;
            color: #666;
        }

        .date-emit
        {
            padding: 0 10px;
            text-align: right;
        }

        .logo {
            height: 50px;
        }

        .date-emit strong,
        .page-number strong
        {
            
            display: inline-block;
            text-align: right;
            width:1.3cm;
        }

        .forma-pagamento
        {
            text-align: right;
        }

        .contract .imovel > div,
        .contract .data > div
        {
            float:left;
        }

        /*
            Larguras das colunas do contrato
        */
        .contract .data .mes-referencia
        {
            width:30%;
        }

        .contract .data .locatario
        {
            width: 70%;
        }

        .contract .data .contrato
        {
            width: 15%;
        }

        .contract .data .pasta
        {
            width: 10%;
        }

        .contract .data .inicio-contrato,
        .contract .data .prox-reajuste,
        .contract .data .mes-garantia
        {
            width: 25%;
            text-align: right;
        }

        .total-geral
        {
            width: 100%;
            border-collapse: collapse;
        }        
    </style>    
</head>

<body>

    <!-- === Página 1 === -->
    <div class="page">
        <div class="item">
            <table class="header-table">
                <tr>
                    <td>
                        <img class="logo" src="http://teiasrv/miles/projects/goesimoveis/assets/img/logo.png" />
                    </td>
                    <td>
                        <h1 class="title">PAGAMENTO A PROPRIETÁRIO</h1>
                    </td>
                    <td>
                        <div class="date-emit"><span>Emissão:</span> <strong><?=dateToMysqlFormat($demonstrativo->data_emissao,true)?></strong></div>
                        <!-- <div class="page-number"><span>Página:</span> <strong>1</strong></div> -->
                    </td>
                </tr>
            </table>
            <?php 
                $total_debito = $total_credito = $total_saldo = 0;
                foreach($contratos as $contrato) { 
                    $locatario = tdc::du('td_demonstrativo_locatario',tdc::f('contrato','=',$contrato->id));
                    $imovel = tdc::du('td_demonstrativo_imovel',tdc::f('contrato','=',$contrato->id));
            ?>
            <!-- Proprietário 1 -->
            <table class="owner-table">
                <tr>
                    <td>
                        <div><strong>Proprietário:</strong><span><?=$locador->codigo?> <?=$locador->nome?></span></div>                                        
                    </td>
                    <td>
                        <div class="forma-pagamento"><strong>Forma de Pago:</strong><?=$contrato->forma_pagamento?></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div><strong>Endereço:</strong><?=$locador->endereco?>C</div>
                    </td>
                </tr>
            </table>

            <table class="contract">
                <tr>
                    <td>
                        <div class="imoveil">
                            <div class=""><strong>Imóvel:</strong><span><?=$imovel->codigo?></span></div>
                            <div class=""><?=$imovel->endereco?></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="data">
                            <div class="mes-referencia"><strong>Mês referência:</strong><span><?=$demonstrativo->anomes?></span></div>
                            <div class="locatario"><strong>Locatário:</strong><span><?=$locatario->nome?></span></div>
                            <div class="contrato"><strong>Contrato:</strong><span><?=$contrato->numero?></span></div>
                            <div class="pasta"><strong>Pasta:</strong><span><?=$contrato->pasta?></span></div>
                            <div class="mes-garantia"><strong>Meses de Garantia:</strong><span><?=$contrato->mes_garantia?></span></div>
                            <div class="inicio-contrato"><strong>Início do Contrato:</strong><span><?=dateToMysqlFormat($contrato->inicio_contrato,true)?></span></div>
                            <div class="prox-reajuste"><strong>Próx. Reajuste:</strong><span><?=dateToMysqlFormat($contrato->proximo_reajuste,true)?></span></div>
                        </div>
                    </td>
                </tr>
            </table>
            <!-- Eventos / transações proprietário 1 -->
            <table class="events">
                <thead>
                    <tr>
                        <th style="width:18%;" class="left">Evento</th>
                        <th style="width:42%;" class="left">Descrição do Evento</th>
                        <th style="width:13%;" class="right">Débito</th>
                        <th style="width:13%;" class="right">Crédito</th>
                        <th style="width:14%;" class="right">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $subtotal_debito = $subtotal_credito = $subtotal_saldo = 0;
                        $eventos = tdc::d('td_demonstrativo_evento',tdc::f("contrato","=",$contrato->id));
                        foreach($eventos as $evento){
                            $subtotal_debito += $evento->debito;
                            $subtotal_credito += $evento->credito;
                            $subtotal_saldo += $evento->credito - $evento->debito;
                    ?>
                        <tr>
                            <td><?=$evento->codigo?></td>
                            <td><?=$evento->descricao?></td>
                            <td class="right"><?=moneyToFloat($evento->debito,true)?></td>
                            <td class="right"><?=moneyToFloat($evento->credito,true)?></td>                    
                            <td class="right"><?=moneyToFloat($evento->saldo,true)?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr class="totals">
                        <td colspan="2">TOTAL - <small> CONTRATO </small></td>
                        <td class="right"><?=moneyToFloat($subtotal_debito,true)?></td>
                        <td class="right"><?=moneyToFloat($subtotal_credito,true)?></td>
                        <td class="right"><?=moneyToFloat($subtotal_saldo,true)?></td>
                    </tr>
                </tfoot>
            </table>        
            <?php
                $total_debito += $subtotal_debito;
                $total_credito += $subtotal_credito;
                $total_saldo = $total_credito - $total_debito;
            ?>
            <?php } ?>

            <table class="total-geral">
                <tr class="totals">
                    <td style="width:60%;">TOTAL - <small>GERAL</small></td>
                    <td style="width:13%;" class="right"><?=moneyToFloat($total_debito,true)?></td>
                    <td style="width:13%;" class="right"><?=moneyToFloat($total_credito,true)?></td>
                    <td style="width:14%;" class="right"><?=moneyToFloat($total_saldo,true)?></td>
                </tr>
            </table>

            <div class="signature">
                <p>____/____/____ <br/> Data</p>
                <p>______________________ <br/> Assinatura</p>
            </div>        
        </div>
    </div> <!-- fim page 1 -->
</body>

</html>