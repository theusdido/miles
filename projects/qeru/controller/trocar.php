<?php

	switch($op){
		case 'load':
            echo tdc::dj("td_ecommerce_produto",tdc::f('is_permite_trocar_pontos','=',true));
        break;
        case 'add':
            $troca              = tdc::p('ecommerce_trocarprodutospontos');
            $troca->produto     = $dados['produto_id'];
            $troca->cliente     = $dados['cliente_id'];
            $troca->pontos      = $dados['pontos'];
            $troca->datahora    = date('Y-m-d H:i:s');
            $troca->armazenar();

            $cliente_desc = $dados['cliente_id'] . ' - ' . tdc::p('td_ecommerce_cliente',$dados['cliente_id'])->nome;
            $produto_desc = $dados['produto_id'] . ' - ' . tdc::p('td_ecommerce_produto',$dados['produto_id'])->nome;

            # /-- E-Mail de Troca de Produto
            $mail 			= new Enviar();
            $mail->debug 	= 0;
            $mail->subject 	= "Solcitação de Troca de Produto";
            $mail->AddAddress("edilson@teia.tec.br","Admin");
            $mail->setHeader(
                "<h3>Troca de pontos solicitada</h3>",
            );
            $mail->setBody(
                '                
                    <table border="1">
                        <tr>
                            <th>Cliente</th>
                            <th>Produto</th>
                            <th>Pontos</th>
                        </tr>
                        <tr>
                            <td>'.$cliente_desc.'</td>
                            <td>'.$produto_desc.'</td>
                            <td>'.$dados['pontos'].'</td>
                        </tr>
                    </table>
                '
            );
            if($mail->Send()){

            }            
        break;
	}