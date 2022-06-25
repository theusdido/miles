<?php
class Checkout {
	public $conn;
	private $userid;
	private $carrinhoid;
	public function __construct(){
		global $conn;
		$this->conn = $conn;
	}
	public function checarEtapas(){
		$etapa = 0;
		if ($this->isResumo()){
			if ($this->isAutenticacao()){
				if ($this->isEndereco()){
					if ($this->isEntrega()){
						$etapa = 5;
					}else{	
						$etapa = 4;
					}
				}else{
					$etapa = 3;
				}
			}else{
				$etapa = 2;
			}
		}else{
			$etapa = 1;
		}
		return $etapa;
	}
	public function isResumo(){		
		$sql = "
		SELECT 1 FROM td_ecommerce_carrinhoitem a
		INNER JOIN td_ecommerce_carrinhocompras b ON a.carrinho = b.id
		WHERE b.cliente = " . Usuario::id() . "
		AND (b.inativo = false OR b.inativo IS NULL);
	";
	$query = $this->conn->query($sql);
	if ($query->rowCount() > 0){
		return true;
	}else{
		return false;
	}
	}
	public function isAutenticacao(){
		$autenticado = false;
		if (isset($_SESSION["autenticado"])){
			if ((int)$_SESSION["autenticado"] == 1){
				$autenticado = true;
			}
		}
		return $autenticado;
	}
	public function isEndereco(){
		$sqlListaEndereco = "SELECT regfilho FROM td_lista WHERE entidadepai = ".getEntidadeId("ecommerce_cliente")." AND entidadefilho = ".getEntidadeId("ecommerce_endereco")." AND regpai = " . Usuario::id();
		$queryListaEndereco = $this->conn->query($sqlListaEndereco);
		if ($queryListaEndereco->rowcount() > 0){
			return true;
		}else{
			return false;
		}
	}
	public function isEntrega(){
		return true;
		if ($this->getUsuario() <= 0){
			return false;
		}else{
			return true;
		}
	}

	public function isPagamento(){
		return true;
	}
	public function setUsuario(){
		 $this->userid = isset($_SESSION["userid"])?$_SESSION["userid"]:0;
	}
	public function getUsuario(){
		return $this->userid;
	}
	public function setCarrinho($idcarrinho){
	    $this->carrinhoid = $idcarrinho;
    }
    public function getCarrinho(){
	    return $this->carrinhoid;
    }
}