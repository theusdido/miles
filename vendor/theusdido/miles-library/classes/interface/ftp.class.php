<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB.
    * @link http://www.teia.tec.br
		
    * Classe que implementa a interface para o FTP
    * Data de Criacao: 12/11/2020
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
class FTP {
	
	private $host;
	private $user;
	private $password;
	private $port = 21;
	private $connection;
	private $isoperation = true;

	/*
		* Método construct
	    * Data de Criacao: 12/11//2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Inicializa os dados da classe
	*/
	public function __construct(){
		$this->setProject();
		$this->connection();
		$this->login();
	}
	
	/*
		* Método setProject
	    * Data de Criacao: 12/11//2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Seta os dados do projeto atual
	*/
	private function setProject(){
		global $connMILES;
		if ($connMILES !== false && $connMILES !== null){
		
			// Seta os dados do projeto ( Busca na base MILES )
			$sqlftp = 'SELECT * FROM td_connectionftp WHERE projeto = ' . CURRENT_PROJECT_ID . ';';
			$queryftp = $connMILES->query($sqlftp);
			$linhaftp = $queryftp->fetch();

			// Dados do servidor
			$this->setHost($linhaftp["host"]);
			$this->setUser($linhaftp["user"]);
			$this->setPassWord($linhaftp["password"]);
			return true;
		}else{
			return false;
		}
	}
	
	/*
		* Método setHost
	    * Data de Criacao: 12/11//2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Seta o host ( IP ou domínio do servidor )
	*/
	public function setHost($host){
		$this->host = $host;
	}

	/*
		* Método getHost
	    * Data de Criacao: 12/11//2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna o host
	*/
	public function getHost(){
		return $this->host;
	}
	
	/*
		* Método setUser
	    * Data de Criacao: 12/11//2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Seta o usuário
	*/
	public function setUser($user){
		$this->user = $user;
	}

	/*
		* Método getUser
	    * Data de Criacao: 12/11//2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna o usuário
	*/
	public function getUser(){
		return $this->user;
	}

	/*
		* Método setPassWord
	    * Data de Criacao: 12/11//2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Seta a senha
	*/
	public function setPassWord($password){
		$this->password = $password;
	}

	/*
		* Método getPassWord
	    * Data de Criacao: 12/11//2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna a senha
	*/
	public function getPassWord(){
		return $this->password;
	}
	
	/*
		* Método connection
	    * Data de Criacao: 12/11//2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Abre a conexão com o servidor FTP
		#retorno: true ou false
	*/
	public function connection(){
		$connection = null;
		try{
			if ($this->getHost() != null){
				$connection = ftp_connect($this->getHost());			
			}else{
				$this->isoperation = false;
				$connection = null;
			}
		}catch(Throwable $t){
			$this->isoperation = false;
			$connection = null;
		}
		$this->connection = $connection;
		return $connection;
	}

	/*
		* Método login
	    * Data de Criacao: 12/11//2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Faz o login no servidor FTP
		#retorno: true ou false
	*/
	public function login(){
		if ($this->connection() != null){
			$login = ftp_login($this->connection, $this->user, $this->password);
			ftp_pasv($this->connection, true);
			return true;
		}else{
			$this->isoperation = false;
			return false;
		}
	}
	
	/*
		* Método put
	    * Data de Criacao: 12/11//2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		// Envia o arquivo pelo FTP
		#retorno: true ou false
	*/
	public function put($localfile,$remotefile){
		if ($this->isoperation){
			return @ftp_put($this->connection, $remotefile, $localfile, FTP_BINARY);
		}else{
			return false;
		}	
	}
	/*
		* Método delete
	    * Data de Criacao: 12/11//2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		// Delete um arquivo do FTP
		#retorno: true ou false
	*/
	public function delete($remotefile){
		if ($this->isoperation){
			return @ftp_delete($this->connection,$remotefile);
		}else{
			return false;
		}
	}
}