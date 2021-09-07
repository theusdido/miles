<?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online
		
    * Classe Entidade
    * Data de Criacao: 05/03/2018
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

*/	
class Entity{
	/*  
		* Método setDescriptionField()
		* Data de Criacao: 05/03/2018
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Set the description field  of entity
	*/
	
	public static function setDescriptionField($conn,$entityParms,$fieldParms,$install = false){
		if ($install){
			$sql = "UPDATE td_entidade SET campodescchave = $fieldParms WHERE id = $entityParms;";
			$conn->exec($sql);
		}else{
			if (is_numeric($entityParms)){
				$entity = tdClass::Criar("persistent",array("entidade",$entiadeParms))->contexto;
			}else if(typeof($entityParms) === "string" ){
				$entityOBJ = tdClass::Criar("persistent",array(ENTIDADE))->contexto->getOBJ($entityParms)->contexto;
				$entity = tdClass::Criar("persistent",array(ENTIDADE,$entityOBJ->getID()))->contexto;
			}
			if (gettype($entity) === "object"){
				$entity->campodescchave = $fieldParms;
				$entity->armazenar();
			}else{
				echo 'Entidade não encontrada';
				return false;	
			}			
		}

	}
}