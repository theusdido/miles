 <?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB.
    * @link http://www.teia.tec.br

    * Classe Theme
    * Data de Criacao: 13/11/2021
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
class Theme {
	/*
		* Método logo
	    * Data de Criacao: 13/11/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna a imagem do logo principal do sistema
	*/
    public static function logo(){
        $logo       = tdClass::Criar('imagem');
        $logo->src 	= self::logoSRC();
        return $logo;
    }
	/*
		* Método logoSRC
	    * Data de Criacao: 15/11/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna o SRC do logo principal
	*/
    public static function logoSRC(){        
        if (file_exists(PATH_CURRENT_LOGO_PADRAO)){
            return URL_CURRENT_LOGO_PADRAO;
        }else{
            return URL_LOGO;
        }
    }
}