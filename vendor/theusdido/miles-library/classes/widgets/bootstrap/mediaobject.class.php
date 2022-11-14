<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Media Object
    * Data de Criacao: 09/49/2017
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

*/
class MediaObject Extends Elemento {
	private $img = "";
	private $titulo = "";
	private $texto = "";
	public $medialeft;
	public $mediabody;
	public $imgsize = "64px";
	/* 
		* Método construct 
	    * Data de Criacao: 27/06/2017
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Cria um objeto Media Object do BootStrap
	*/
	public function __construct(){
		parent::__construct("div");
		$this->class = "media";
		$this->medialeft = tdClass::Criar("div");
		$this->mediabody = tdClass::Criar("div");
	}
	/*  
		* Método addMediaLeft
	    * Data de Criacao: 27/06/2017
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Adiciona o item Media Left
	*/
	public function addMediaLeft($src="",$href="#"){
		$this->medialeft->class = "media-left";
		$a = tdClass::Criar("hyperlink");
		$a->href = $href;
		$img = tdClass::Criar("imagem");
		$img->class = "media-object";
		$img->src = $src;
		$img->style = "width:" . $this->imgsize . ";height:" . $this->imgsize . ";";
		$a->add($img);
		$this->medialeft->add($a);		
	}
	/*  
		* Método addMediaBody
	    * Data de Criacao: 27/06/2017
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Adiciona o item Media Body
	*/
	public function addMediaBody($titulo="",$texto="",$n=4){
		$this->mediabody->class = "media-body";
		$h = tdClass::Criar("h",array($n));
		$h->add($titulo);

		
		if (gettype($texto) == "array"){
			$span = tdClass::Criar("span");
			foreach ($texto as $i){
				$span->add($i);
			}
			$this->mediabody->add($h,$span);
		}else{
			$this->mediabody->add($h,$texto);
		}
	}
	public function mostrar(){
		$this->add(
			$this->medialeft,
			$this->mediabody
		);
		parent::mostrar();
	}
}