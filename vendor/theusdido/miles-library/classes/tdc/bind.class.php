<?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online
		
    * Classe para trabalhar com data
    * Data de Criacao: 30/06/2020
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
class bind {
	public $id;
	public $entidade;
	public $filtros;
	public $html;
	public $binds;
	/*  
		* Método tdFor
	    * Data de Criacao: 30/06/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Verifica o padrão tdFor
		@html: HTML a ser verificado: Padrão: Obrigatório.
	*/
	public function tdfor(){
		$doc = new DOMDocument();
		$doc->loadXML ($this->html);
		$tdfor = $doc->getElementsByTagName('td-for');
		foreach($tdfor as $node){
			$filtros = tdc::f();
			foreach($node->attributes as $attr){
				if ($attr->nodeName == "entidade"){
					$entidade = $attr->nodeValue;
				}
				if ($attr->nodeName == "filtros"){
					foreach(json_decode($attr->nodeValue,true) as $f){
						$filtros->addFiltro($f["campo"],$f["operador"],$f["valor"]);
					}
				}
			}
			$dados = tdc::d($entidade,$filtros);
			$modelo = $this->primeiraTag($node->childNodes);
			$tdfortag = $node;
			foreach ($dados as $d){
				$copia = $modelo->cloneNode(true);
				$tdfortag->appendChild($copia);
				$this->bindNodeChilds($copia->childNodes,$entidade,$d->id);
			}
			$tdfortag->removeChild($modelo);
		}
		$this->html = str_replace('<?xml version="1.0"?>',"",$doc->saveXML());
	}
	private function primeiraTag($filhos){
		foreach($filhos as $filho){
			if (isset($filho->tagName)){
				return $filho;
				break;
			}
		}
	}
	private function bindNodeChilds($filhos,$entidade,$id){
		foreach($filhos as $filho){
			if ($filho->childNodes != null){
				foreach($filho->attributes as $k => $attr){
					$attr->nodeValue = strip_tags($this->getBindingValue($attr->nodeValue,$entidade,$id));
				}
				#foreach ($filho->getElementsByTagName("td-bind") as $key => $tbind){
				#	$filho->nodeValue = strip_tags($this->getBindingValue(trim($filho->nodeValue),$entidade,$id));
				#}
				//$this->bindNodeChilds($filho->childNodes,$entidade,$id);
			}
		}
	}

	/*
		* Método tdEntidade
	    * Data de Criacao: 30/06/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Verifica o padrão tdEntidade
	*/
	private function getBindEntidade($html){		
		if (preg_match('/tdEntidade=[\"|\']+[a-z0-9_.]+[\"|\']+/i', $html, $matches)){
			$entidade = "";
			foreach($matches as $mm){
				$str = str_replace(array("\"","\'"),"",$mm);
				$arrayexplode = explode("=",$str);
				$entidade = $arrayexplode[1];
			}
			$this->entidade = $entidade;
			if ($entidade != ""){
				return $entidade;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	/*
		* Método getEntidadeBindTdFor
	    * Data de Criacao: 30/06/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna o nome da entidade do padrão tdEntidade
		@str: String a ser verificada.
	*/	
	private function getEntidadeBindTdFor($html){
		if (preg_match('/tdFor=[\"|\']+[a-z0-9_.]+[\"|\']+/i', $html, $matches)){
			$entidade = "";
			foreach($matches as $mm){
				$str = str_replace(array("\"","\'"),"",$mm);
				$arrayexplode = explode("=",$str);
				$entidade = $arrayexplode[1];
			}
			$this->entidade = $entidade;
			if ($entidade != ""){
				return $entidade;
			}else{
				return false;
			}
		}else{
			return false;
		}	
	}

	/*
		* Método getTdFiltros
	    * Data de Criacao: 30/06/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Adiciona os filtros do padrão tdFiltros
	*/
	private function getTdFiltros($html){
		if (preg_match_all('/tdFiltros=[\"|\']?[^\]]*}\][\"|\']?/i', $html, $matches)){
			$filtro = tdc::f();
			foreach($matches as $mm){
				foreach($mm as $m){
					$ft = $this->getFiltroBindTdFor($m);
					foreach($ft as $f){
						$filtro->addFiltro($f["campo"],$f["operador"],$f["valor"]);
					}
				}
			}
			$this->filtros = $filtro;
			return $filtro;
		}else{
			return false;
		}
	}
	/*
		* Método getFiltroBindTdFor
	    * Data de Criacao: 30/06/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna os filtros do padrão tdFiltros
		@str: String a ser verificada.
	*/	
	private function getFiltroBindTdFor($str){
		$retirar_atributo = explode('tdfiltros=',strtolower($str));
		if (isjson($retirar_atributo[1])){
			return json_decode($retirar_atributo[1],true);
		}else{	
			$valoratributo = $retirar_atributo[1];
			$trim_aspas = substr($valoratributo,1,strlen($valoratributo)-2);
			$converter_aspas = str_replace(array('\"',"'","\'"),'"',$trim_aspas);
			return json_decode($converter_aspas,true);
		}
	}

	/*
		* Método getBindingValue
	    * Data de Criacao: 30/06/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna o html convertidos
		@html: String a ser convertida.
		@entidade: Nome da entidade em formatado de string.
		@id: Identificador único do registro
	*/
	public function getBindingValue($html,$entidadeParms = null , $id = null){
		$htmlretorno = $html;
		if (preg_match_all('/{{[#]?[a-z._0-9]+?}}/i', $html, $matches)){
			foreach($matches as $mm){
				$binds = array();
				foreach($mm as $m){			
					$entidadeId = $atributoId = 0;
					$b = str_replace(array("{{","}}"),"",$m);
					$ma = explode(".",$b);
					if (substr($ma[0],0,1) == "#"){
						$entidade = str_replace("#","",$ma[0]);
						$atributo = $ma[1];
						$atributoextra = isset($ma[2]) ? $ma[2] : '';
					}else{
						$atributo 		= $ma[0];
						$atributoextra 	= isset($ma[1]) ? $ma[1] : '';
						$entidade = $entidadeParms;
					}					
					if ($entidade == null || $entidade == "" || empty($entidade)){
						continue;
					}
					$entidadeId = getEntidadeId($entidade);
					if ($atributo == "id"){
						$valor = $id;
					}else{
						$atributoId = getAtributoId($entidade,$atributo);
						$valor = getHTMLTipoFormato(getTipoHTML($atributoId),tdc::p($entidade,$id)->{$atributo},$entidadeId,$atributoId,$id);
					}					
					switch((int)getTipoHTML($atributoId)){
						case 19:
							$jsonvalor = json_decode($valor,true);
							$valor = isset($jsonvalor[$atributoextra])?$jsonvalor[$atributoextra]:$valor;
						break;
					}

					$binds[$atributo] = $valor;
					$htmlretorno = str_replace($m,$valor,$htmlretorno);
				}
			}
		}
		return $htmlretorno;
	}
	/*
		* Método bindsAbsolutos
	    * Data de Criacao: 04/06/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Resolve os binds absolutos.
		Ex: {{#td_ecommerce.id}} = 1
	*/	
	private function bindsAbsolutos(){
		if (is_numeric($this->id)){
			if ((int)$this->id > 0){
				$this->html = $this->getBindingValue($this->html,null,$this->id);	
			}
		}
	}
	/*
		* Método bindEntidade
	    * Data de Criacao: 06/06/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Resolve os binds que atributo entidade
	*/
	private function bindEntidade(){
		if (preg_match_all('/tdentidade=[\"|\']+[a-z._0-9]+[\"|\']+/i', $this->html, $matches)){
			foreach($matches as $mm){
				$binds = array();
				foreach($mm as $m){
					$arrayAttrEntidade = explode("=",$m);
					$entidade = str_replace(array("'",'"'),"",$arrayAttrEntidade[1]);
					$doc = new DOMDocument();
					$doc->loadXML ($this->html);
					$xpath = new DOMXPath($doc);
					$query = '//*[@tdentidade="'.$entidade.'"]';
					$entries = $xpath->query($query);
					$this->bindNodeChilds($entries,$entidade,$this->id);
					$this->html = str_replace('<?xml version="1.0"?>',"",$doc->saveXML());
					foreach($entries as $node){
						
						/*
						$filtros = tdc::f();
						foreach($node->attributes as $attr){
							if ($attr->nodeName == "entidade"){
								$entidade = $attr->nodeValue;
							}
							if ($attr->nodeName == "filtros"){
								foreach(json_decode($attr->nodeValue,true) as $f){
									$filtros->addFiltro($f["campo"],$f["operador"],$f["valor"]);
								}
							}
						}
						$dados = tdc::d($entidade,$filtros);
						$modelo = $this->primeiraTag($node->childNodes);
						$tdfortag = $node;
						foreach ($dados as $d){
							$copia = $modelo->cloneNode(true);
							$tdfortag->appendChild($copia);
							$this->bindNodeChilds($copia->childNodes,$entidade,$d->id);
						}
						$tdfortag->removeChild($modelo);
						*/
					}
					/* $this->html = str_replace('<?xml version="1.0"?>',"",$doc->saveXML()); */
				}
			}
		}
	}
	/*
		* Método getConteudo
	    * Data de Criacao: 04/06/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna o conteudo em html
	*/
	private function getConteudo(){
		return $this->html;
	}
	/*
		* Método gethtml
	    * Data de Criacao: 30/06/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna o html convertidos
	*/
	public function gethtml(){
		$this->bindsAbsolutos(); # Todos os binds absolutos
		$this->bindEntidade(); # Todos os binds com atributo entidade
		$this->tdfor(); # Setar todas as tags
		return $this->getConteudo();
	}
}