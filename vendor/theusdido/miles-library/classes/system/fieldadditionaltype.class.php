<?php
/*
    * Framework MILES
    * @license: Teia Online.
    * @link http://www.teia.online

    * Classe Field Additional Type
    * Data de Criacao: 03/01/2025
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

*/	
class FieldAdditionalType {

	/* 
		* Método __construct
		* Data de Criacao: 03/01/2025
		* Autor @theusdido
	*/
	public function __construct(){}    

	/*  
		* Método Formatted
		* Data de Criacao: 03/01/2025
		* Autor @theusdido
		
		Retorna o valor do dado formatado
		@parms value
        @parms type
	*/
    public static function Formatted($value, $type = ''){
        switch($type){
            case 'date':
                return dateToMysqlFormat($value,true);
            break;
            case 'datetime':
                return datetimeToMysqlFormat($value,true);
            break;
            case 'money':
                return  moneyToFloat($value,true);
            break;
            default:
                return $value;
        }
    }

	/*  
		* Método getValue
		* Data de Criacao: 03/01/2025
		* Autor @theusdido
		
		Retorna o dado com no formato do campo adicional
		@parms dados:Array
        @parms key:string
        @parms value:any
	*/
    public static function getValue($dados, $key, $value, $entidade){
        $addition_field_type 	= self::getAdditionalFieldType($key);
        $original_field_name 	= self::originalFieldAdditionalName($key);

        if (isset($dados[$original_field_name])){
            $original_field_value	= $dados[$original_field_name];
        }else{
            return self::getDefaultValue($key);
        }

        switch($addition_field_type){
            case ATTR_DATEFORMATTED:
                $field_value 	= self::Formatted($original_field_value,'date');
            break;
            case ATTR_DATETIMEFORMATTED:
                $field_value 	= self::Formatted($original_field_value,'datetime');
            break;
            case ATTR_MONEYFORMATTED:
                $field_value 	= self::Formatted($original_field_value,'money');
            break;                        
            case ATTR_DESC:
                $field_value 	= self::DESC($original_field_value,$original_field_name,$entidade,$dados);
            break;
            case ATTR_OBJ:
                $field_value 	= self::OBJ($original_field_value,$original_field_name,$entidade,$dados);
            break;
            case ATTR_SRC:
                $field_value 	= self::SRC($original_field_value,$original_field_name,$entidade,$dados);
            break;
            default:
                $field_value	= $value;
        }
        return $field_value;
    }

	/*  
		* Método isField
		* Data de Criacao: 03/01/2025
		* Autor @theusdido
		
		Retorna se o campo é adicional
		@parms field_name:string
	*/
    public static function isField($field_name){
        $_is_additional_field = false;
        foreach (ADDITIONAL_FIELD_TYPES as $key => $value) {
            if (strpos($field_name,$value) !== false){
                $_is_additional_field = true;
            }
        }
        return $_is_additional_field;
    }

	/*  
		* Método getAdditionalFieldType
		* Data de Criacao: 03/01/2025
		* Autor @theusdido
		
		Retorna o tipo do campo adicional
		@parms field_name:string
	*/
    public static function getAdditionalFieldType($field_name){
        $_additional_field = '';
        foreach (ADDITIONAL_FIELD_TYPES as $key => $value) {
            if (strpos($field_name,$value) !== false){
                $_additional_field = $value;
            }
        }
        return $_additional_field;
    }

	/*  
		* Método originalFieldAdditionalName
		* Data de Criacao: 03/01/2025
		* Autor @theusdido
		
		Retorna o nome original do campo
		@parms field_name:string
	*/
    public static function originalFieldAdditionalName($field_name){
        return str_replace(ADDITIONAL_FIELD_TYPES,'',$field_name);
    }    

	/*  
		* Método Desc
		* Data de Criacao: 03/01/2025
		* Autor @theusdido
		
		Retorna o valor do dado do tipo descrição
		@parms value
	*/
    public static function DESC($value,$key,$entidade,$dados){        
        if (is_numeric_natural($value)){
            $atributoOBJ 			= tdc::p(ATRIBUTO,getAtributoId($entidade,$key));
            $campodescdefault 		= tdc::p(ATRIBUTO,getCampoDescricaoDefault($atributoOBJ->chaveestrangeira));
            if ($campodescdefault->hasData()){
                $registro 				= getRegistro(null,tdc::p(ENTIDADE,$atributoOBJ->chaveestrangeira)->nome,$campodescdefault->nome, "id={$value}" , "LIMIT 1");
                try{
                    $value = tdc::utf8($registro[$campodescdefault->nome]);
                }catch(Exception $e){
                }
            }
        }
        return $value;
    }    

    public static function OBJ($value,$key,$entidade,$dados){
        if (is_numeric_natural($value)){
            $atributoOBJ 			= tdc::p(ATRIBUTO,getAtributoId($entidade,$key));
            if ($key != 'entidade' && $atributoOBJ->chaveestrangeira != 0){
                $value = Entity::getRegisterJSON(tdc::e($atributoOBJ->chaveestrangeira)->nome, $value);
            }else{
                $value = self::EmptyFieldOBJ();
            }
        }else{
            $value = self::EmptyFieldOBJ();
        }
        return json_encode($value);
    }

    public static function ForeignKey($value,$key,$entidade,$dados){
        if (is_numeric_natural($value)){
            $atributoOBJ 			= tdc::p(ATRIBUTO,getAtributoId($entidade,$key));
            $campodescdefault 		= tdc::p(ATRIBUTO,getCampoDescricaoDefault($atributoOBJ->chaveestrangeira));
            if ($campodescdefault->hasData()){
                $valorfk 				= is_numeric_natural($value)?$value:0;
                $registro 				= getRegistro(null,tdc::p(ENTIDADE,$atributoOBJ->chaveestrangeira)->nome,$campodescdefault->nome, "id={$valorfk}" , "LIMIT 1");
                try{
                    $value = tdc::utf8($registro[$campodescdefault->nome]);
                }catch(Exception $e){

                }
            }
        }
        return $value;
    }

    public static function SRC($value,$key,$entidade,$dados){
        $file						= $key . '-' . getEntidadeId($entidade) . '-' . $dados['id'] . '.' . getExtensao($value);
        $url_file 					= URL_CURRENT_FILE . $file;
        $path_file					= PATH_CURRENT_FILE . $file;

        return file_exists($path_file) ? $url_file : URL_ASSETS . 'img/noimage.png';        
    }

    public static function getDefaultValue($field)
    {
        switch(self::getAdditionalFieldType($field)){
            case ATTR_OBJ:
                $field_value 	= self::EmptyFieldOBJ();
            break;
            default:
                $field_value	= NULL;
        }
        return $field_value;
    }

    public static function EmptyFieldOBJ(){
        return json_encode( new stdClass() );
    }
}