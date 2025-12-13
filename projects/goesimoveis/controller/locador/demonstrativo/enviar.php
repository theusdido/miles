<?php

    $demonstrativo_configuracoes = tdc::ru('td_demonstrativo_configuracoes');
    $demonstrativo = tdc::r('demonstrativo');
    $link_donwload = 'http://teiasrv/miles/demonstrativo/' . $demonstrativo['link'];
    
    require 'vendor/phpmailer/PHPMailerAutoload.php';

    $mail = new PHPMailer(true);
    $mail->SetLanguage("en","phpmailer/language/");
    $mail->IsSMTP(); 
    
    $mail->CharSet      = 'UTF-8';
    $mail->Encoding     = 'base64'; # Garante codificação base64 (ajuda a evitar quebras em alguns clientes)
    $mail->Host 		= "smtplw.com.br";
    $mail->SMTPAuth 	= true; 
    $mail->Username 	= "goesimoveis"; 
    $mail->Password 	= "iYmvnWPi3961";
    $mail->SMTPDebug 	= 0;
    #$mail->SMTPDebug = SMTP::DEBUG_SERVER;
    #$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    #$mail->SMTPSecure 	= 'tls';
    $mail->Port 		= 587;

    $mail->From = "boletos@goesimoveis.com";
    $mail->FromName = "Demonstrativo de Pagamento de Proprietário";

    //Enderecos que devem ser enviadas as mensagens
    #$mail->AddAddress("edilson@teia.tec.br","Teia Tecnologia");
    $mail->AddAddress("edilsonbitencourt@hotmail.com","Teia Tecnologia");
    #$mail->AddAddress("theusdido@gmail.com","Teia Tecnologia");

    #var_dump($demonstrativo_configuracoes->email_teste);
    if ($demonstrativo_configuracoes->email_teste != ''){
        #$mail->AddAddress($demonstrativo_configuracoes->email_teste, 'Locador Teste');        
    }

    if ($demonstrativo_configuracoes->is_producao == 1){
        //$mail->AddAddress($demonstrativo['email'],$demonstrativo['locador']);
    }

    $mail->WordWrap = 50;
    $mail->IsHTML(true);

    #$logon = explode("^",$this->getSenha());
    $msg 	= '
        <p>
            Ol&aacute; , <b>'.$demonstrativo['locador'].'</b> <br /><br /><br />
            <a href="'. $link_donwload . '"> Clique aqui para abrir o Demonstrativo</a>
        </p>
        <br/><br/>
        <p>Para acessar o demonstrativo no site basta ir na área do cliente na opção Demonstrativo de Pagamento:</p>
        <p>Código: </p>
        <p>Senha: </p>
        <br/>
        <br/>
        <p>Para qualquer dúvida entrar em contato com o setor de cobrança em <b><a href="mailto://atendimento@goesimoveis.com.br">atendimento@goesimoveis.com.br</a></b> e/ou <b><a href="mailto://atendimento1@goesimoveis.com.br">atendimento1@goesimoveis.com.br</a></b>.</p>
        <hr />
        <p>&nbsp;<img title="" src="logo.png" alt=" - Logo" /></p>
        <p><strong><span style="font-size: small;"></span><br /></strong>
            Rua Mal. Deodoro, 355 - Centro<br/>
            88.815-000 Criciuma/SC<br/>
            (48) 3437.2552
        </p>
        <p><em>Este e-mail &eacute; enviado automaticamente pelo nosso sistema, favor n&atilde;o responder este email.</em> <br /><br /></p>
    ';
        
    $mail->Subject = 'Góes Imóveis';
    $mail->Body = $msg;
    
    try{
        if($mail->Send()){
            $d = tdc::p('td_demonstrativo',$demonstrativo['id']);
            $d->enviada = 1;
            $d->salvar();
            echo tdc::wj(array(
                'status' => 'success',
                'message' => 'Enviado!'
            ));
        }else{ 
            echo tdc::wj(array(
                'status' => 'danger',
                'message' => $mail->ErrorInfo
            ));
        }


    }catch(Throwable $e){
        echo tdc::wj(array(
            'status' => 'danger',
            'message' => 'Erro: ' . $e->getMessage()
        ));
    }