<?php
		$email 		= tdc::r('email');
		$sql 		= "SELECT id,nome FROM td_ecommerce_cliente WHERE email = '{$email}' AND inativo <> 1;";
		$query 		= $conn->query($sql);
		if ($query->rowCount() <= 0){
            $retorno['status']  = 'error';
            $retorno['msg']     = 'O E-Mail informado não está cadastrado';
            echo json_encode($retorno);
			exit;
		}

		$linha 			= $query->fetch();
		$cliente 		= $linha["id"];
		$nomecliente 	= utf8_encode($linha["nome"]);
        $now_format     = 'Y-m-d H:i:s';
        $now_value      = date($now_format);
		$hash			= md5($cliente.date($now_value));

        // Recuperação de Senha
        $recuperacaosenha                       = tdc::p('td_usuariorecuperacaosenha');
        $recuperacaosenha->hash                 = $hash;
        $recuperacaosenha->usuario              = $cliente;
        $recuperacaosenha->datahoraenvio        = $now_value;
        $recuperacaosenha->datahoravalidade     = date($now_format,strtotime($now_value.' +1 day'));
        $recuperacaosenha->email                = $email;
        $recuperacaosenha->ip                  = $_SERVER["REMOTE_ADDR"];
        $recuperacaosenha->dadosnavegacao      = $_SERVER["HTTP_USER_AGENT"];
        $query = $recuperacaosenha->armazenar();

        if (!$query){
            $retorno['status']  = 'error';
            $retorno['msg']     = 'Não conseguimos recuperar a senha, favor tentar mais tarde.';
            echo json_encode($retorno);
			exit;
        }

		// $linkenviar = 'https://www.bixoferpa.com.br/recuperarsenha/' . $hash ;

		// include("../miles/lib/phpmailer/PHPMailerAutoload.php");

		// $mail = new PHPMailer();
		// $mail->SetLanguage("en","../miles/lib/phpmailer/language/");
		// $mail->CharSet = 'UTF-8';
		// $mail->SMTPDebug = 0;
		// $mail->IsSMTP(); 
		// $mail->SMTPAuth = true;
		// $mail->Username = "bixoferpa"; 
		// $mail->Password = "XXIctMpL5279";			
		// $mail->SMTPSecure = 'tls';
		// $mail->Host = "smtplw.com.br";
		// $mail->Port = 587;
		// $mail->From = "contato@bixoferpa.com.br";
		// $mail->FromName = "Bixoferpa ( Contato )";

		// $mail->isHTML(true); //enviar em HTML
		// $mail->Subject = "Recuperação de Senha";

		// $mail->AddAddress($email,$nomecliente);
		// $mail->WordWrap = 50;

		// $msg = "<img src='https://www.bixoferpa.com.br/loja/images/icons/logo.png' alt='Logo Bixoferpa'/><br/>";
		// $msg .= '
		// 	<br/><br/>
		// 	Olá, <b>'. $nomecliente .'</b>.
		// 	<br/>
		// 	<br/><small style="color:#999;"><i>Se não foi você que solicitou ignore esse mensagem.<i/></small>
		// 	<br/>
		// 	<br/>Você solicitou uma recuperação de senha: 
		// 	<br/><a href="'.$linkenviar.'">Clique para alterar sua senha</a>
		// 	<br/><br/><br/>
		// 	Copiar Link
		// 	<br/> <a href="" style="color:#666;">'.$linkenviar.'</a>
		// 	<br/><br/><p style="color:#FF0000"><i>Este link tem validade de 24 horas.</i></p>
		// ';

		// $rodapeS = "<br/><br/><br/><br/><hr/><br/>BIXOFERPA<Br>Rod BR101, s/n | km 455 | Vila Concei&ccedil;&atilde;o | São Jo&atilde;o do Sul-SC | CEP 88975-000";
		// $msg .=	$rodapeS;

		// $mail->Body = $msg;

		// if(!$mail->Send()){
		// 	echo '<center><h4 style="color:#FF0000;font-weight:bold;font-size:16px;">Erro ao enviar E-Mail. Motivo: '.$mail->ErrorInfo.'</h4></center>';
		// 	exit;
		// }else{
		// 	echo '<div class="alert alert-success" role="alert">Link para recuperação de senha enviada com sucesso.</b>.</div>';
		// }    

        $retorno['status']  = 'success';