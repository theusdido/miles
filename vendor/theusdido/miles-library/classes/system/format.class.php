<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB.
    * @link http://www.teia.tec.br

    * Classe Format
    * Data de Criacao: 15/02/2022
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
class Format {

	/*
		* Método CPF
	    * Data de Criacao: 15/02/2022
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna a formatação CPF
	*/
    public static function CPF($str,$formatar=true){
        if ($str=="") return "";
        $str = str_replace(array("-",".","/"),"",$str);
        if ($formatar){
            if (strlen($str) == 11){
                return substr($str,0,3) . "." . substr($str,3,3) . "." . substr($str,6,3) . "-" . substr($str,9,2);
            }
        }else{
            return str_replace(array(".","-"),"",$str);
        }
    }

	/*
		* Método CNPJ
	    * Data de Criacao: 15/02/2022
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna a formatação CNPJ
	*/
    public static function CNPJ($str,$formatar=true){
        if ($str=="") return "";
        $str = str_replace(array("-",".","/"),"",$str);
        if ($formatar){
            if (strlen($str) == 14){
                return substr($str,0,2) . "." . substr($str,2,3) . "." . substr($str,5,3) . "/" . substr($str,8,4)  . "-" . substr($str,12,2);
            }
        }else{
            return str_replace(array(".","/","-"),"",$str);
        }
    }
    
	/*
		* Método CPFJ
	    * Data de Criacao: 15/02/2022
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna a formatação de CPF ou CNPJ
	*/
    public static function CPFJ($cpfj){
        if (isCPF($cpfj)) return self::CPF($cpfj);
        else return self::CNPJ($cpfj);
    }
}