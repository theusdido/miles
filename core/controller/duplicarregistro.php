<?php
$dados = array();
$id = tdClass::Read("id");
$entidadeOBJ = tdClass::Criar("persistent",array(ENTIDADE,tdClass::Read("entidade")))->contexto;

if ($conn = Transacao::get()){
	//foreach(tdClass::Read("dados") as $dado){
		$registroPrincipal = tdClass::Criar("persistent",array($entidadeOBJ->nome,$id))->contexto;
		$registroClone = tdClass::Criar("persistent",array($entidadeOBJ->nome))->contexto;
		$registroClone = clone $registroPrincipal;
		$idclone = $registroClone->id;
		foreach(getRelacionamentos($entidadeOBJ->id) as $relacionamento){
			if ($relacionamento->tipo == 1){
				$lista = getListaRegFilho($entidadeOBJ->id,$relacionamento->filho,$id);
				foreach($lista as $l){
					
					$filho = tdClass::Criar("persistent",array(tdClass::Criar("persistent",array(ENTIDADE,$relacionamento->filho))->contexto->nome,$l->regfilho))->contexto;
					$cloneFilho = clone $filho;
					
					$novalista = tdClass::Criar("persistent",array(LISTA))->contexto;
					$novalista->id = $novalista->proximoID();
					$novalista->projeto = 0;
					$novalista->empresa = 0;
					$novalista->entidadepai = $l->entidadepai;
					$novalista->entidadefilho =  $l->entidadefilho;
					$novalista->regpai = $idclone;
					$novalista->regfilho = $cloneFilho->id;
					$novalista->armazenar();
				}	
			}
		}
		Transacao::Commit();
}	
echo json_encode(array("idclone" => $idclone));
