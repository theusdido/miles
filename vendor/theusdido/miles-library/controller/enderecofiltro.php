<?php

	// Entidades do Endereço
	// if (isset($mjc->system->entidades_endereco)){
	// 	$_entidade_endereco_pais 	= getSystemPREFIXO() . $mjc->system->entidades_endereco->pais;
	// 	$_entidade_endereco_estado 	= getSystemPREFIXO() . $mjc->system->entidades_endereco->estado;
	// 	$_entidade_endereco_cidade 	= getSystemPREFIXO() . $mjc->system->entidades_endereco->cidade;
	// 	$_entidade_endereco_bairro 	= getSystemPREFIXO() . $mjc->system->entidades_endereco->bairro;
	// }else{

		$_entidade_endereco_base 				= getSystemPREFIXO() . 'endereco';
		$_entidade_endereco_base_pais 			= getSystemPREFIXO() . 'pais';
		$_entidade_endereco_base_estado 		= getSystemPREFIXO() . 'uf';
		$_entidade_endereco_base_cidade 		= getSystemPREFIXO() . 'cidade';
		$_entidade_endereco_base_bairro 		= getSystemPREFIXO() . 'bairro';
		$_entidade_endereco_base_logradouro		= $_entidade_endereco_base;
		$_atributo_endereco_base_localidade		= 'cidade';
		
	// }

	// Endereço Projeto
	$_entidade_endereco_projeto 				= getSystemPREFIXO() . 'imobiliaria_endereco';
	$_entidade_endereco_projeto_pais 			= getSystemPREFIXO() . 'imobiliaria_pais';
	$_entidade_endereco_projeto_estado 			= getSystemPREFIXO() . 'imobiliaria_estado';
	$_entidade_endereco_projeto_cidade 			= getSystemPREFIXO() . 'imobiliaria_cidade';
	$_entidade_endereco_projeto_bairro 			= getSystemPREFIXO() . 'imobiliaria_bairro';
	$_entidade_endereco_projeto_logradouro		= $_entidade_endereco_projeto;
	$_atributo_endereco_projeto_localidade		= 'cidade';
	

	// Correios
	// $_entidade_endereco_pais 		= 'pais';
	// $_entidade_endereco_estado 		= 'estado';
	// $_entidade_endereco_cidade 		= 'localidade';
	// $_entidade_endereco_bairro 		= 'bairro';
	// $_entidade_endereco_logradouro	= 'logradouro';
	// $_atributo_endereco_localidade	= 'localidade';
	

	$op 			= tdClass::Read("op");
	$conn 			= Transacao::Get();
	$connCorreios 	= Conexao::Abrir("correios") ? Conexao::Abrir("correios") : Transacao::Get();
	$selecionado 	= tdClass::Read("selecionado");

	if ($op == "retorna_lista_uf")
	{
		$sql 	= "SELECT id,nome,sigla FROM $_entidade_endereco_base_estado WHERE id > 0;";
		$query 	= $connCorreios->query($sql);
		while ($linha = $query->fetch()){
			echo "<option value='" . strtoupper($linha["sigla"]) . "' " . ($selecionado==$linha["sigla"]?"selected":"") .">" . tdc::utf8($linha["nome"]) . "</option>";
		}
		exit;
	}
	else if ($op == "retorna_lista_localidade")
	{
		$sql = "select a.id,TRIM(a.nome) nome,a.uf from $_entidade_endereco_base_cidade a,$_entidade_endereco_base_estado b WHERE a.uf = b.id AND b.sigla = '" . $_GET["uf"] . "'";
		$query = $connCorreios->query($sql);
		while ($linha = $query->fetch()){
			echo "<option value='" . tdc::utf8($linha["nome"]) . "' " . (strtoupper(retirarAcentos($selecionado))==strtoupper(retirarAcentos(tdc::utf8($linha["nome"])))?"selected":"")  . ">" . tdc::utf8($linha["nome"]) . "</option>";
		}
		exit;
	}
	elseif ($op == "retorna_lista_bairro")
	{
		$sql = "SELECT id,nome FROM $_entidade_endereco_base_bairro WHERE $_atributo_endereco_base_localidade = " . getIdLocalidade($_GET["localidade"],$_GET["uf"]); ##class(Correios.Localidade).getIDLocalidade(%request.Get("localidade"),%request.Get("uf"))
		$query = $connCorreios->query($sql);
		while ($linha = $query->fetch()){
			echo "<option value='" .tdc::utf8($linha["nome"]) . "' ".(strtoupper(retirarAcentos($selecionado))==strtoupper(retirarAcentos(tdc::utf8($linha["nome"])))?"selected":"") .">".tdc::utf8($linha["nome"])."</option>";
		}
		
		$sql = "SELECT id,nome FROM $_entidade_endereco_projeto_bairro WHERE cidade = " . getIdCidade($_GET["localidade"],$_GET["uf"]); 
		$query = $conn->query($sql);
		while ($linha = $query->fetch()){
			echo "<option value='" .tdc::utf8($linha["nome"]) . "' ". ($selecionado == $linha["id"]?"selected":"") .">".tdc::utf8($linha["nome"])."</option>";
		}		
		exit;
	}
	elseif ($op == "add_bairro")
	{
		
		$descricao 	= ($_GET["descricao"]);
		$cidade 	= getIdCidade($_GET["cidade"],$_GET["uf"]);

		$sqlBairro 		= "SELECT id FROM $_entidade_endereco_projeto_bairro WHERE nome = '".tdc::utf8($descricao)."' AND cidade = " . $cidade;
		$queryBairro 	= $conn->query($sqlBairro);
		if ($queryBairro->rowCount() <= 0){
			$bairro 			= tdClass::Criar("persistent",array($_entidade_endereco_projeto_bairro))->contexto;
			$id 				= $bairro->proximoID();
			$bairro->id 		= $id;
			$bairro->projeto 	= 1;
			$bairro->empresa 	= 1;
			$bairro->nome 		= $descricao;
			$bairro->cidade 	= $cidade;
			$salvar 			= $bairro->armazenar();
			Transacao::Commit();
			if ($salvar){

				// Salva o bairro na "base" dos correios
				// Retorna ID do novo bairro
				
				//$localidade = ##class(Correios.Localidade).getIDLocalidade(%request.Get("cidade"),%request.Get("uf"))
				
				echo $id;
				/*
				if ($_GET["retorno"] == "C"){
					echo $id;
				}else{
					//echo  ##class(Correios.Bairro).add(%request.Get("descricao"),localidade)
				}
				*/
			}else{
				//D $System.Status.DisplayError(salvar)
			}
		}else{
			/*
			$localidade = ##class(Correios.Localidade).getIDLocalidade(%request.Get("cidade"),%request.Get("uf"))
			I ##class(Correios.Bairro).existeBairro(%request.Get("descricao"),localidade) = 0 {
				D ##class(Correios.Bairro).add(%request.Get("descricao"),localidade)
			}
		 	W rs.id
			*/
			echo $queryBairro->fetch()[0];
		}
	}
	elseif ($op == "salva_endereco")
	{
		$enderecoOBJ = tdClass::Criar('persistent',array($_entidade_endereco_projeto))->contexto;
		$id = $enderecoOBJ->proximoID();
		#$cidade = getIdCidade(tdClass::Read("localidade"),tdClass::Read("uf"));
		#$bairro = getIdBairro(tdClass::Read("bairro"),$cidade);

		$bairro 		= is_numeric(tdClass::Read("bairro"))?tdClass::Read("bairro"):getIdBairro(tdClass::Read("bairro"),getIdCidade(tdClass::Read("localidade"),getIdEstado(tdClass::Read("uf"))));
		$logradouro 	= tdClass::Read("logradouro");
		$cep 			= tdClass::Read("cep");

		$sql 	= "SELECT id FROM $_entidade_endereco_projeto WHERE bairro = {$bairro} AND logradouro = '{$logradouro}' AND cep = '{$cep}'";
		$query 	= $conn->query($sql);
		if ($query->rowCount() > 0)
		{
			$idSel = $query->fetch()[0];
			echo $idSel;
		}else{

			$end 				= tdClass::Criar("persistent",array($_entidade_endereco_projeto))->contexto;
			$end->id 			= $id;			
			$end->bairro 		= $bairro;
			$end->logradouro 	= $logradouro;
			$end->cep 			= $cep;
			$end->empresa 		= 1;
			$end->projeto 		= 1;
			$end->armazenar();
			Transacao::Commit();
			echo $id;
		}
	}
	elseif ($op == "busca_geral")
	{
		
		$termo = tdClass::Read("termo");
		#$where = " WHERE tags %CONTAINS ("_WHERE_")"
		$limit = $termo==""?"LIMIT 30":"";
		$sql = " SELECT 
					a.$_atributo_endereco_base_localidade,
					(SELECT c.nome FROM $_entidade_endereco_base_bairro c WHERE c.id = a.bairro_inicial) bairro ,
					a.cep,
					a.nome,
					TRIM(a.tipo) tipo,
					(SELECT nome FROM $_entidade_endereco_base_cidade b WHERE b.id= a.localidade) cidade
				FROM $_atributo_endereco_base_logradouro a " . $limit;

		echo $sql;
		$cidade = $estado = $bairro = "";
		$query = $connCorreios->query($sql);
		While ($linha = $query->fetch())
		{
			$UF = $connCorreios->query("SELECT (SELECT b.sigla FROM $_entidade_endereco_base_estado b WHERE a.uf = b.id) sigla FROM $_entidade_endereco_base_cidade a WHERE a.id = {$linha["localidade"]} LIMIT 1")->fetch()[0];
			
			/*
			I cidade.uf > 0 {
				S estado = ##class(Correios.UF).%OpenId(cidade.uf)
			}
			I rs.bairroinicial > 0 {
				S bairro = ##class(Correios.Bairro).%OpenId(rs.bairroinicial)
			}
			*/			
			echo $linha["cep"] . "^" . $UF . "^" . tdc::utf8($linha["cidade"]) . "^" . tdc::utf8($linha["bairro"]) . "^" . tdc::utf8($linha["tipo"]) . " " . tdc::utf8($linha["nome"]) . "^" . "^^^^" . "~";
		}
	}
	function getIdCidade($cidade,$uf){
		global $conn;
		global $_entidade_endereco_projeto_cidade;

		$ufID 	= is_numeric($uf)?$uf:getIdEstado($uf);
		$sql 	= "SELECT id FROM $_entidade_endereco_projeto_cidade WHERE nome LIKE '%{$cidade}%' AND estado = '{$ufID}';";
		$query 	= $conn->query($sql);
		if ($query->rowCount() > 0){
			return $query->fetch()[0];
		}else{
			$cidadeOBJ 				= tdClass::Criar("persistent",array($_entidade_endereco_projeto_cidade))->contexto;
			$idCidade 				= $cidadeOBJ->proximoID();
			$cidadeOBJ->id 			= $idCidade;
			$cidadeOBJ->empresa 	= Session::Get()->empresa;
			$cidadeOBJ->projeto 	= CURRENT_PROJECT_ID;
			$cidadeOBJ->estado 		= $ufID;
			$cidadeOBJ->nome 		= $cidade;
			//$cidadeOBJ->sigla = getSiglaLocalidade($cidade,$uf);
			$cidadeOBJ->armazenar();
			
			return $idCidade;
		}	
	}
	function getIdEstado($uf){
		global $conn;
		global $_entidade_endereco_projeto_estado;
		
		$sql 	= "SELECT id FROM $_entidade_endereco_projeto_estado WHERE sigla = '{$uf}';";
		$query 	= $conn->query($sql);
		if ($query->rowCount() > 0){
			return $query->fetch()[0];
		}else{
			$ufOBJ 					= tdClass::Criar("persistent",array($_entidade_endereco_projeto_estado))->contexto;
			$idUF 					= $ufOBJ->proximoID();
			$ufOBJ->id 				= $idUF;
			$ufOBJ->empresa 		= Session::Get()->empresa;
			$ufOBJ->projeto 		= CURRENT_PROJECT_ID;
			$ufOBJ->pais 			= 1;
			$ufOBJ->nome 			= getNomeUF($uf);
			$ufOBJ->sigla 			= $uf;
			$ufOBJ->denominacao 	= '';
			$ufOBJ->armazenar();	
			return $idUF;
		}
	}
	function getIdLocalidade($cidade,$uf){
		global $connCorreios;
		global $_entidade_endereco_base_cidade;

		$ufID 	= getIdUF($uf);
		$sql 	= "SELECT id FROM $_entidade_endereco_base_cidade WHERE nome LIKE '%{$cidade}%' AND uf = '{$ufID}';";
		$query 	= $connCorreios->query($sql);
		return $query->fetch()[0];
	}
	function getIdUF($uf){
		global $connCorreios;
		global $_entidade_endereco_base_estado;
		$sql 	= "SELECT id FROM $_entidade_endereco_base_estado WHERE sigla = '{$uf}';";
		$query 	= $connCorreios->query($sql);
		return $query->fetch()[0];
	}
	function getNomeUF($sigla){
		global $connCorreios;
		global $_entidade_endereco_base_estado;

		$sql 	= "SELECT nome FROM $_entidade_endereco_base_estado WHERE sigla = '{$sigla}';";
		$query 	= $connCorreios->query($sql);
		return $query->fetch()[0];		
	}
	function getSiglaLocalidade($cidade,$uf){
		global $connCorreios;
		global $_entidade_endereco_base_cidade;

		$ufID 	= is_numeric($uf)?$uf:getIdUF($uf);
        $sql 	= "SELECT sigla FROM $_entidade_endereco_base_cidade WHERE nome LIKE '%{$cidade}%' AND uf = '{$ufID}';";
		$query 	= $connCorreios->query($sql);
		return $query->fetch()[0];
	}
	
	function getIdBairro($bairro,$cidade){
		global $conn;
		global $_entidade_endereco_projeto_bairro;
		$sql = "SELECT id FROM $_entidade_endereco_projeto_bairro WHERE nome LIKE '%{$bairro}%' AND cidade = {$cidade};";
		$query = $conn->query($sql);
		if ($query->rowCount() > 0){
			return $query->fetch()[0];
		}else{
			return 0;
		}
	}