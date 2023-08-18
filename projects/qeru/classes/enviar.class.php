<?php
	include_once PATH_LIB . "phpmailer/PHPMailerAutoload.php";

	class Enviar
	{
		private $destinatario 	= [];
		private $header			= '';
		private $body			= '';
		private	$footer			= '';
		public $subject			= 'Automático';

		// Configuração da conta e E-Mail
		public $username	= 'theusdido';
		public $email		= 'automatico@qeru.com.br';
		public $senha 		= 'edQDTsfr6954';
		public $host		= 'smtplw.com.br';
		public $sprotocol	= '';
		public $auto_tls	= true;
		public $auth_type	= '';
		public $port		= '587';
		public $from_name	= 'Qeru - Automático';
		public $mailer		= 'smtp';
		public $debug		= 0;
		public $is_auth		= true;

		public function send()
		{
			$mail 					= new PHPMailer();			
			$mail->Mailer			= $this->mailer;
			$mail->SMTPDebug 		= $this->debug;
			$mail->SMTPAuth 		= $this->is_auth;
			$mail->Username 		= $this->username; 
			$mail->Password 		= $this->senha;			
			$mail->SMTPSecure 		= $this->sprotocol;
			$mail->SMTPAutoTLS		= $this->auto_tls;
			$mail->AuthType 		= $this->auth_type;
			$mail->Host 			= $this->host;
			$mail->Port 			= $this->port;
			$mail->FromName 		= $this->from_name;

			$mail->SetFrom($this->email,utf8charset($this->from_name,'D'));
			$mail->IsSMTP();

			$email					= $this->destinatario[0];
			$nome					= $this->destinatario[1];
			$mail->AddAddress("{$email}","{$nome}");

			$mail->IsHTML(true);
			$mail->Subject 	= utf8charset($this->subject,'D');
			$mail->Body 	= utf8charset($this->getHeader() . $this->getBody() . $this->getFooter(),'D');

			return $mail->Send();
		}
		public function setHeader($nome = "",$titulo = "",$subtitulo = "")
		{	
			global $_url_root;
			
			$_logo_src	= $_url_root.'assets/img/logo/logo.png';
			$_logo		= '<img src="'.$_logo_src.'" width="200" />';
			$_nome  	= $nome!="" ? "<br/><br/><p>Olá, <b>{$nome}</b>.</p>" : "";
			$_titulo	= $titulo!="" ? "<br/><h2>{$titulo}</h2>" : "";
			$_subtitulo	= $subtitulo!="" ? "<small style='margin-top:-10px'>{$subtitulo}</small>" : "";
	
			$this->header = "{$_logo}{$_nome}{$_titulo}{$_subtitulo}<br/><br/><br/>";
		}
		public function setFooter($_footer)
		{
			$this->footer = "
				<p>
					<small>Você está recebendo este e-mail porque se registrou na Qeru.</small>
					<br/>
					<i>
						<small>Este e-mail é enviado automaticamente, favor não responder.</small>
					</i>
				</p>
				<p>
					<b>Qeru</b>
					<br />
					<a href='{$_url_root}'>https://qeru.com.br</a><br />
				</p>
			";
		}
		public function alterarSenha($hash)
		{
			$this->subject = 'Recuperaçao de Senha';
			$this->body = "
				<h1>Alteração de Senha</h1>
				<small>Você solicitou uma alteração de senha, caso não tenho sido você, basta ignorar este e-mail.</small>
				<br/><br/>
				<a 
					href='https://www.qeru.com.br/alteracaosenha/{$hash}'
					target='_blank'
					style='background-color:##f9503f;color:#FFF;width:100px;padding:10px;'
				>Clique Aqui</a>

				<span>para alterar sua senha.</span>
				<br/>
			";
			return $this->send();
		}
		
		public function AddAddress($email,$nome)
		{
			$this->destinatario = array( $email, $nome );
		}
		public function setBody($body){
			$this->body = $body;
		}
		public function getHeader(){
			return $this->header;
		}
		public function getBody(){
			return $this->body;
		}
		public function getFooter(){
			return $this->footer;
		}
	}