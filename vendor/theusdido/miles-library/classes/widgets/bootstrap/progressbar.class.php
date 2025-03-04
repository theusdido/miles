<?php
/*
<div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
    60%
  </div>
</div>
*/
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Teia OnLine
    * @link http://www.teia.online
		
    * Classe Progress Bar
    * Data de Criacao: 18/10/2018
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
class ProgressBar Extends Elemento {
	/*  
		* Método construct 
	    * Data de Criacao: 18/10/2018
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Construct 
	*/
	public $classe = "progress-bar";
	public $role = "progressbar";
	public $ariavaluenow = 0;
	private $ariavaluemin = 0;
	private $ariavaluemax = 100;
	private $div;
	public $exbirpercentual = true;
	public function __construct(){		
		parent::__construct('div');
		
		$this->class = "progress";
		$this->div = tdClass::Criar("div");
	}
	public function setStyle($style){	
		if ($style != "" && $style != null){
			$this->div->class = $style;
		}
	}
	public function setPercentual($percentual){
		if ($percentual >= 0 && $percentual <= 100){
			$this->div->style = "width:" . $percentual."%";
		}
	}
	public function mostrar(){
		
		$this->div->class = $this->classe;
		$this->div->role = $this->role;
		$this->div->aria_valuenow = $this->ariavaluenow;
		$this->div->aria_valuemin = $this->ariavaluemin;
		$this->div->aria_valuemax = $this->ariavaluemax;
		if ($this->exbirpercentual) $this->div->add($this->ariavaluenow . "%");
		$this->add($this->div);
		parent::mostrar();
	}
}