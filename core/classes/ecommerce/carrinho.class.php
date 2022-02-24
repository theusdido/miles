<?php
	class CarrinhoCompras {		
		private $dados;
		private $id = 0;
		private $criterio;
		private $create_at;
		private $update_at;

		public function __construct(){
			if ($this->exists()){
				$this->dados = tdc::d('td_ecommerce_carrinhocompras',$this->criterio)[0];
			}else{
				$this->dados = tdc::p('td_ecommerce_carrinhocompras');
				$this->setCreateAt();
			}

			$this->save();
			$this->setDados();
			return $this->dados;
		}

		public function setQtdadeTotalItens(){
			$sql 	= "SELECT sum(qtde) total FROM td_ecommerce_carrinhoitem WHERE carrinho = " . $this->getID();
			$query 	= $this->conn->query($sql);
			$linha 	= $query->fetch();
			$this->totalitens = $linha["total"];
			$this->conn->exec("UPDATE td_ecommerce_carrinhodecompras SET qtdetotaldeitens = {$this->totalitens} WHERE id = " . $this->getID());
		}

		public function atualizarValorTotalItem(){
			$sql = "
				UPDATE td_ecommerce_carrinhoitem i
				SET i.valor = ( SELECT t.valor FROM td_ecommerce_tamanhoproduto t WHERE t.id = i.produto ) ,
				i.valortotal = i.qtde * i.valor
				WHERE i.carrinho = " . $this->getID() . ";
			";
			$query = $this->conn->exec($sql);
		}

		public function atualizarValorTotalCarrinho(){
			$sql = "
				UPDATE td_ecommerce_carrinhodecompras c 
				SET c.valortotal = ( 
					SELECT sum(i.valortotal) FROM td_ecommerce_carrinhoitem i WHERE id = " .$this->getID() ." 
				) + c.valorfrete;
			";
			$this->conn->exec($sql);
		}

		public function recalcular(){
			$this->atualizarValorTotalItem();
			$this->atualizarValorTotalCarrinho();
			$this->setQtdadeTotalItens();
		}

		function inativarCarrinho(){
			global $conn;
			$query = $conn->exec('UPDATE td_ecommerce_carrinhocompras SET inativo = true WHERE id = ' . getIdCarrinho());
			if ($query){
				$_SESSION["carrinhoid"] = 0;
				unset($_SESSION["carrinhoid"]);
			}
		}

		function getIdCarrinho(){
			if (isset($_SESSION["carrinhoid"])){
				return $_SESSION["carrinhoid"];
			}else{
				return 0;
			}
		}
		
		// Retorna o id da sessão do carrinho de compras
		public function getSessionId()
		{
			if (TdCookie::exists("carrinhocamprassessionid")){
				$session_id_carrinho = TdCookie::get("carrinhocamprassessionid");
			}else{
				$session_id_carrinho = session_id();
				TdCookie::add("carrinhocamprassessionid",$session_id_carrinho);
			}

			return $session_id_carrinho;
		}

		/*
			* Método exists
			* Data de Criacao: 19/01/2022
			* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
			* PARAMETROS
			*	:none
			* RETORNO
			*	[ boolean ]

			Retorna se existe uma sessão do carrinho na loja
		*/
		public function exists()
		{
			$this->criterio        = tdc::f();
			$this->criterio->addFiltro('sessionid','=',$this->getSessionId());
			$this->criterio->onlyActive();
			$this->criterio->onlyOne();

			if ($this->getClient() != 0){
				$this->criterio->addFiltro('cliente','=',$this->getClient(),OU);
			}

			if (tdc::c('td_ecommerce_carrinhocompras',$this->criterio) > 0){
				return true;
			}else{
				return false;
			}
		}

		/*
			* Método getClient
			* Data de Criacao: 19/01/2022
			* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
			* PARAMETROS
			*	:none
			* RETORNO
			*	[ boolean ]

			Retorna se o cliente está logado na loja
		*/
		public function getClient(){
			return isset($_SESSION["userid"])?$_SESSION["userid"]==""?0:$_SESSION["userid"]:0;
		}

		/*
			* Método setDados
			* Data de Criacao: 19/01/2022
			* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
			* PARAMETROS
			*	:none
			* RETORNO
			*	:void

			Atribui dados;
		*/
		private function setDados(){
			$this->setId($this->dados->id);
			
		}

		public function getId(){
			return $this->id;
		}

		public function setId($id){
			if (is_numeric($id)){
				if ($id > 0){
					$this->id = $id;
				}
			}
		}

		public function getQtdadeItens(){
			return tdc::c('td_ecommerce_carrinhoitem',tdc::f('id','=',$this->getId()));
		}

		public function setUpdateAt($datahora){
			$this->update_at = $datahora;
		}

		public function getUpdateAt(){
			if ($this->update_at == '' || $this->update_at == null){
				return date('Y-m-d H:i:s');
			}else{
				return $this->update_at;
			}
			
		}
		public function setCreateAt(){
			$this->dados->datahoracriacao = $this->getCreateAt();
		}
		public function getCreateAt(){
			if ($this->create_at == '' || $this->create_at == null){
				return date('Y-m-d H:i:s');
			}else{
				return $this->create_at;
			}
		}

		public function save()
		{
			$this->dados->datahoraultimoacesso = $this->getUpdateAt();
			$this->dados->armazenar();
		}
	}