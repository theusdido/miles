<?php


    $nome       = $_data->nome;
    $email      = $_data->email;
    $telefone   = $_data->telefone;
    $assunto    = $_data->assunto;
    $mensagem   = $_data->mensagem;

    $website_config = tdc::rua('td_website_geral_configuracoes');

    include PATH_ROOT . 'miles/vendor/phpmailer/PHPMailerAutoload.php';

    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug    = 0;
    $mail->SMTPAuth     = true;
    $mail->SMTPSecure   = 'ssl';
    $mail->Host         = "email-ssl.com.br";
    $mail->Port         = 465;

    $mail->Username     = "contact@raymbo.com";
    $mail->Password     = "c@Teia#25";
    $mail->FromName     = "Contact Raymbo";
    $mail->From         = "contact@raymbo.com";

    $mail->AddAddress("edilsonbitencourt@hotmail.com","{$nome}");
    $mail->WordWrap     = 50;

    $mail->IsHTML(true); //enviar em HTML
    $mail->Subject      = tdc::utf8("Contact Site");
    $mail->Body         = tdc::utf8('
        <img src="'.$website_config['logohome_src'].'" width="200" />
        <br/><br/><br/>
        <h3>Form Data:</h3>
        <table cellspadding="0" colspacing="0" width="500" border="0">
            <tbody>
                <tr>
                    <td>Name</td>
                    <td>'.$nome.'</td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td>'.$telefone.'</td>
                </tr>						
                <tr>
                    <td>E-Mail</td>
                    <td>'.$email.'</td>
                </tr>
                <tr>
                    <td>Subject</td>
                    <td>'.$assunto.'</td>
                </tr>                
                <tr>
                    <td>Message</td>
                    <td>'.$mensagem.'</td>
                </tr>
                <tr>
                    <td>Date/Time</td>
                    <td>'.date('d/m/Y H:i:s').'</td>
                </tr>
            </tbody>
        </table>
        <br/>
        <p><i>This is a message automatic. Please, don\'t answer.</i></p>			
    ');
    if(!$mail->Send()){
        $retorno['_msg'] = '<center><h4 style="color:#FF0000;font-weight:bold;font-size:16px;">Erro ao enviar E-Mail. Motivo: '.$mail->ErrorInfo.'</h4></center>';
        exit;
    }else{
        $retorno['_msg'] = 'Success';
    }