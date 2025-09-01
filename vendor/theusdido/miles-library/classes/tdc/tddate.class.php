<?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online
		
    * Classe para trabalhar com data
    * Data de Criacao: 23/04/2020
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
class tdDate {
	/*  
		* Método getPartDate 
	    * Data de Criacao: 23/04/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna uma parte da Data
		@parte: D-Dia,M-Mês,Y-Ano,H-Hora,I:Minutos,S:Segundos. Padrão: Obrigatório.
		@data: Data de retorno, Padrão: Data atual.
	*/
	public static function getPartDate($parte,$data = null){
		$data = $data == null ? date("Y-m-d") : $data;
		if (is_date($data)){
			$datae = explode("-",$data);
			switch(strtoupper($parte)){
				case 'Y': return $datae[0]; break;
				case 'M': return $datae[1]; break;
				case 'D': return $datae[2]; break;
				default:
					return false;
			}
		}else{
			return false;
		}
	}
}