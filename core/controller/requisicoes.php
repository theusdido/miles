<?php
	if (isset($_GET["op"])){
		$op = $_GET["op"];
	}else if(isset($_POST["op"])){
		$op = $_POST["op"];
	}else{
		exit;
	}
	switch($op){
		case 'datetime':
			# Retorna data e hora do servidor
			# Se pasar o formato por parametro retorna data com o formato solicitado
			if (isset($_GET["formato"])) echo date($_GET["formato"]);
			else echo date("Y-m-d H:i:s");
		break;
		case 'valida-data':
			# Verifica se a data é realmente existente
			if (isset($_GET["data"])){
				$data = explode("/",$_GET["data"]);
				$d = $data[0];
				$m = $data[1];
				$y = $data[2];		
				if (checkdate($m,$d,$y)){
					echo 1;
				}else{
					echo 0;
				}
			}		
		break;
		case 'data-retroativa':
			# Verifica se a data é realmente existente
			if (isset($_GET["data"])){
				$data = explode("/",$_GET["data"]);
				$data1 = $data[2]."-".$data[1]."-".$data[0];
				$data2 = date("Y-m-d");

				// Comparando as Datas
				if(strtotime($data1) < strtotime($data2)){
					echo 0;
				}else{
					echo 1;
				}
			}		
		break;
		case 'atualiza_tabela_filho':
			$entidade 	= tdClass::Criar("persistent",array(ENTIDADE,$_GET["entidade"]));
			$atributo 	= $_GET["atributo"];
			$valor 		= $_GET["valor"];
			
			$sql = tdClass::Criar("sqlcriterio");
			$sql->add(tdClass::Criar("sqlfiltro",array($atributo,'=',session_id())));
			$dataset = tdClass::Criar("repositorio",array($entidade->contexto->nome))->carregar($sql);
			if ($conn = Transacao::get()){
				foreach ($dataset as $registro){
					$registro->{$atributo} = $valor;
					$registro->armazenar();
				}
				$conn->commit();		
			}
		break;
		case 'existe_registro_filho':
			$entidade 	= tdClass::Criar("persistent",array(ENTIDADE,$_GET["entidade"]));
			$atributo 	= $_GET["atributo"];
			$valor 		= $_GET["valor"];
			
			$sql = tdClass::Criar("sqlcriterio");
			$sql->add(tdClass::Criar("sqlfiltro",array($atributo,'=',$valor)));
			$dataset = tdClass::Criar("repositorio",array($entidade->contexto->nome));
			$dataset->carregar($sql);
			echo $dataset->quantia($sql);
		break;
		// Salva os dados do formulário
		case 'salvar':
			include 'salvarform.php';
			return false;
			$entidade = tdClass::Criar("persistent",array(ENTIDADE,$_POST["entidade"]));
			$id = $_POST["id"];
			$dados = tdClass::Criar("persistent",array($entidade->contexto->nome));
			if ($id>0) $dados->contexto->id = $id; #Marca como edição
			
			foreach($_POST["dados"] as $chave => $valor){
				if ($chave!="undefined"){				
					$dados->contexto->{$chave} = Config::Integridade($entidade->contexto->id,$chave,$valor,($id>0?$id:($dados->contexto->getUltimo()+1)));
				}	
			}
			try{
				$dados->contexto->armazenar();
				
				// IMPORTANTE ( Retorna o ID do registro não ocorrer replicação na base de dados )
				echo json_encode(array('id' => $dados->contexto->id));			
				
				/* 
					TRIGGER
					- Atualiza as entidades da composição 
				*/
				if ($id <= 0){				
					$sql = tdClass::Criar("sqlcriterio");
					$sql->add(tdClass::Criar("sqlfiltro",array("tipo",'=',2)));
					$sql->add(tdClass::Criar("sqlfiltro",array("pai",'=',$entidade->contexto->id)));			
					$rel = tdClass::Criar("repositorio",array(RELACIONAMENTO))->carregar($sql);			
					foreach ($rel as $relacionamento){
						$ent_rel	= tdClass::Criar("persistent",array(ENTIDADE,$relacionamento->filho));
						$atributo 	= tdClass::Criar("persistent",array(ATRIBUTO,$relacionamento->atributo))->contexto->nome;
						$valor 		= $dados->contexto->id;
						
						$sql = tdClass::Criar("sqlcriterio");
						$sql->add(tdClass::Criar("sqlfiltro",array($atributo,'=',session_id())));
						$dataset = tdClass::Criar("repositorio",array($ent_rel->contexto->nome))->carregar($sql);
						foreach ($dataset as $registro){
							$registro->{$atributo} = $valor;
							$registro->armazenar();
						}
					}
				}			
				/* 
					TRIGGER
					- Atualiza os registros da entidade LISTA 
				*/
				
				$sql = tdClass::Criar("sqlcriterio");
				$sql->addFiltro("regfilho",'=',session_id());
				$sql->addFiltro("entidadefilho",'=',$entidade->contexto->id);
				$dataset = tdClass::Criar("repositorio",array(LISTA))->carregar($sql);			
				if ($conn = Transacao::Get()){
					foreach ($dataset as $item){
						$item->armazenar();
					}
				}
				
				/* 
					TRIGGER
					- Atualiza os registros das entidades de GENERALIZAÇÃO
				*/
				$sql = tdClass::Criar("sqlcriterio");
				$sql->add(tdClass::Criar("sqlfiltro",array("pai",'=',$entidade->contexto->id)));
				$sql->add(tdClass::Criar("sqlfiltro",array("tipo",'=',3)));
				$dataset = tdClass::Criar("repositorio",array(RELACIONAMENTO))->carregar($sql);
				if ($conn = Transacao::Get()){
					foreach ($dataset as $item){
						$filho = tdClass::Criar("persistent",array(ENTIDADE,$item->filho));
						$atributo = tdClass::Criar("persistent",array(ATRIBUTO,$filho->contexto->atributogeneralizacao))->contexto->nome;
						$conn->exec("UPDATE {$filho->contexto->nome} SET {$atributo} = {$dados->contexto->id} WHERE {$atributo} = '".session_id()."'");
					}
				}			
				Transacao::Commit();
			}catch(Exception $e){
				echo $e->getMessage();
				Transacao::rollback();
			}				
		break;
		case 'exclui_temp_filhos':
			$entidade = tdClass::Criar("persistent",array(ENTIDADE,(int)$_POST["entidade"]));						
			$sql = tdClass::Criar("sqlcriterio");
			$pai = tdClass::Criar("persistent",array(ENTIDADE,$_POST["pai"]));
			$sql->add(tdClass::Criar("sqlfiltro",array(ENTIDADE,'=',$entidade->contexto->id)));
			$sql->add(tdClass::Criar("sqlfiltro",array("chaveestrangeira",'=',$pai->contexto->id)));
			
			$atributos = tdClass::Criar("repositorio",array(ATRIBUTO))->carregar($sql);
			
			$campo_pai_relacionamento = $atributos[0]->nome; #pega o campo que faz a vinculação
			
			$sql = tdClass::Criar("sqlcriterio");
			$sql->add(tdClass::Criar("sqlfiltro",array($campo_pai_relacionamento,'=',session_id())));
			$dataset = tdClass::Criar("repositorio",array($entidade->contexto->nome));
			$dataset->deletar($sql);
			if ($conn = Transacao::get()){
				$conn->commit();
			}
		break;
		case 'paginacao':
			$entidade = tdClass::Criar("persistent",array(ENTIDADE,(int)$_GET["entidade"]));
			$max_bloco 			= isset($_GET["max_bloco"])?(int)$_GET["max_bloco"]:10;
			$total_registros 	= isset($_GET["total_registros"])?(int)$_GET["total_registros"]:10;
			$bloco				= isset($_GET["bloco"])?(int)$_GET["bloco"]:1;
			$inicio = ($bloco*$max_bloco)-$max_bloco;
			
			$sql = tdClass::Criar("sqlcriterio");
			$sql->setPropriedade("limit","{$inicio},{$max_bloco}");
			
			// Filtro
			if (isset($_GET["filtro"])){
				$fts = explode("~",$_GET["filtro"]);
				foreach($fts as $ft){
					$f = explode("^",$ft);
					$filtro = tdClass::Criar("sqlfiltro",array($f[0],'=',$f[1]));
					$sql->add($filtro);
				}
			}
			
			// Filtro da Pesquisa 
			if (isset($_GET["filtro_pesquisa"])){			
				$f = explode("^",$_GET["filtro_pesquisa"]);
				if (strtolower(retornar("atributo_tipo")) == "varchar"){
					$t = str_replace("_"," ", $f[1]);
					$filtro = tdClass::Criar("sqlfiltro",array($f[0],"like","'%{$t}%"));
				}else{
					$filtro = tdClass::Criar("sqlfiltro",array($f[0],'=',$f[1]));
				}
				$sql->add($filtro);
			}		
			
			// Não Filtrar se os usuários forem 1 e 2
			if (Session::get()->userid != 1 && Session::get()->userid != 2){
				/* Filtro de projeto */
				$sql_attr = tdClass::Criar("sqlcriterio");			
				$sql_attr->add(tdClass::Criar("sqlfiltro",array(ENTIDADE,'=',$entidade->contexto->id)));
				$sql_attr->add(tdClass::Criar("sqlfiltro",array("nome",'=','projeto')));
		
				$attr_empresa = tdClass::Criar("repositorio",array(ATRIBUTO))->carregar($sql_attr);			
				if ($attr_empresa){
					$sql->add(tdClass::Criar("sqlfiltro",array("projeto",'=',CURRENT_PROJECT_ID)));
				}
				
				/* Filtro de empresa */
				$sql_attr = tdClass::Criar("sqlcriterio");			
				$sql_attr->add(tdClass::Criar("sqlfiltro",array(ENTIDADE,'=',$entidade->contexto->id)));
				$sql_attr->add(tdClass::Criar("sqlfiltro",array("nome",'=',EMPRESA)));
				
				$attr_empresa = tdClass::Criar("repositorio",array(ATRIBUTO))->carregar($sql_attr);			
				if ($attr_empresa){
					$sql->add(tdClass::Criar("sqlfiltro",array(EMPRESA,'=',Session::get()->empresa)));
				}						
				/* Filtro de empresa */
			}	
			

			/* Filtro de Inativo */
			$sql_attr = tdClass::Criar("sqlcriterio");			
			$sql_attr->add(tdClass::Criar("sqlfiltro",array(ENTIDADE,'=',$entidade->contexto->id)));
			$sql_attr->add(tdClass::Criar("sqlfiltro",array("nome",'=','inativo')));
			$attr_inativo = tdClass::Criar("repositorio",array(ATRIBUTO))->carregar($sql_attr);
			if ($attr_inativo){
				$sql->add(tdClass::Criar("sqlfiltro",array("inativo",'<>',1)));											
			}			
			/* Filtro de Inativo */
							
			if ($entidade->contexto->campodescchave != "" and $entidade->contexto->campodescchave != null and $entidade->contexto->campodescchave != 0){
				/* Ordena por ordem alfabetica */
				$campodescchave = tdClass::Criar("persistent",array(ATRIBUTO,$entidade->contexto->campodescchave));
				
				if ($campodescchave->contexto!=null){
					$sql->setPropriedade("order",$campodescchave->contexto->nome . " ASC");	
				}
			}	

			/* Filtro de Relacionamento de Agregação N:N */
			if (isset($_GET["filtro_rel_nn"])){			
				$sql_lista = tdClass::Criar("sqlcriterio");
				foreach (explode("~",$_GET["filtro_rel_nn"]) as $ft){
					$f = explode("^",$ft);
					$filtro = tdClass::Criar("sqlfiltro",array($f[0],'=',$f[1]));
					$sql_lista->add($filtro);

					if ($f[0]=="entidadefilho")
						$entidadefilho = $f[1];
					
					if ($f[0]=="entidadepai")
						$entidadepai = $f[1];
				}			
				//$sql_lista->add(tdClass::Criar("sqlfiltro",array("entidadepai",'=",$entidade->contexto->id)));
				$lista_obj = tdClass::Criar("repositorio",array(LISTA));		
				$lista = $lista_obj->carregar($sql_lista);
				$registros_lista = array();
				foreach($lista as $lt){
					array_push($registros_lista,$lt->regfilho);
				}			
				if (sizeof($registros_lista)>0){
					$filtro_ids = new sqlfiltro("id",'in',$registros_lista);				
					$sql->add($filtro_ids);				
				}
				
				$sql_rel = tdClass::Criar("sqlcriterio");
				$sql_rel->add(tdClass::Criar("sqlfiltro",array("tipo",'=',5)));
				$sql_rel->add(tdClass::Criar("sqlfiltro",array("pai",'=',$entidadepai)));
				$sql_rel->add(tdClass::Criar("sqlfiltro",array("filho",'=',$entidadefilho)));
				$relacionamento_obj = tdClass::Criar("repositorio",array(RELACIONAMENTO));
				$relacionamento = $relacionamento_obj->carregar($sql_rel);
				if ($relacionamento_obj->quantia($sql_rel) > 0 && $lista_obj->quantia($sql_lista) <= 0){
					$filtro_ids = new sqlfiltro("id",'=',-1);
					$sql->add($filtro_ids);
				}
			}
			
			$dataset = tdClass::Criar("repositorio",array($entidade->contexto->nome))->carregar($sql);		
			$gd = tdClass::Criar("gradededados");
			$gd->entidade = $entidade;
			$gd->dataset = $dataset;
			$gd->filtro_rel_nn = retornar("filtro_rel_nn");
			$gd->contexto = retornar("contexto");
			if (retornar("retornaregistro") != ""){
				$gd->retornaregistro = true;
				$gd->exibireditar = false;
				$gd->exibirexcluir = false;
				$gd->exibirselecionar = false;
				$gd->exibiroutrajanela = false;
				$gd->funcaoretornaregistro = retornar("retornaregistro");
			} 
			
			if (Session::get()->permitirexclusao==0){
				$gd->exibirexcluir = false;
			}
			if (isset($_GET["filtro"])) $gd->filtro = $_GET["filtro"];
			if (isset($_GET["dados"])){
				if ($_GET["dados"] == "no"){
					$gd->exibircorpo = false;
				}
				if ($_GET["dados"] == "only"){
					$gd->exibircorpo = true;
					$gd->exibircabecalho = false;
					$gd->exibirrodape = false;
					$gd->tbody()->mostrar();				
				}else{
					$gd->mostrar();
				}
			}else{
				$gd->mostrar();
			}
			
		break;
		case 'salvar_lista':
			if ($conn = Transacao::get()){
				try{				
					$lista = tdClass::Criar("persistent",array(LISTA));		
					$lista->contexto->entidadepai = (int)$_GET["entidadepai"];
					$lista->contexto->entidadefilho = (int)$_GET["entidadefilho"];
					$lista->contexto->regpai = $_GET["regpai"];
					$lista->contexto->regfilho = $_GET["regfilho"];
					$lista->contexto->armazenar();
					$conn->commit();
				}catch(Exception $e){
					echo $e->getMessage();
					$conn->rollback();
				}
			}	
			
		break;
		case "retorna_descricao_filtro":
			if (isset($_GET["filtro"])){
				if (!is_numeric($_GET["termo"])){
					exit;
				}
				
				if ($_GET["filtro"] == "" || !is_numeric($_GET["filtro"])){
					$filtro = "";
				}else{
					$filtro = explode("^",$_GET["filtro"]);
					
					$sql = tdClass::Criar("sqlcriterio");
					$sql->addFiltro($filtro[0],"=",$filtro[1]);
					$sql->addFiltro("id","=",$_GET["termo"]);
					if (tdClass::Criar("repositorio",array($_GET["entidade"]))->quantia($sql) <= 0){
						echo "";
						return false;
					}
				}
			}
			$sql = tdClass::Criar("sqlcriterio");
			$sql->addFiltro("nome",'=',$_GET["entidade"]);
			$dataset = tdClass::Criar("repositorio",array(ENTIDADE))->carregar($sql);
			if (sizeof($dataset) <=0){
				echo "Campo não encontrado";
				exit;
			}
			if ($dataset[0]->campodescchave == 0 || $dataset[0]->campodescchave == null || $dataset[0]->campodescchave == ""){
				// Pega o primeiro campo que é utilizado na grade de dados
				$sql_cd = tdClass::Criar("sqlcriterio");			
				$sql_cd->addFiltro(ENTIDADE,'=',$dataset[0]->id);
				$sql_cd->addFiltro("exibirgradededados",'=',1);			
				$dataset_cd = tdClass::Criar("repositorio",array(ATRIBUTO))->carregar($sql_cd);
				if (sizeof($dataset_cd) < 1){
					echo 'Nenhum campo "Exbir Grades de Dados" Configurado';
					exit;
				}else{
					$campodescchave = $dataset_cd[0]->id;
				}	
			}else{
				$campodescchave = $dataset[0]->campodescchave;
			}
			$atributo = tdClass::Criar("persistent",array(ATRIBUTO,$campodescchave));
			// Se o atributo não for uma chave estrangeira
			if ($atributo->contexto->chaveestrangeira == "" || $atributo->contexto->chaveestrangeira == 0){
				$entidade 	= tdClass::Criar("persistent",array($_GET["entidade"],$_GET["termo"]));
				$valorfinal = $entidade->contexto->{$atributo->contexto->nome};
				echo utf8charset($valorfinal,5);
			}else{
				// Entidade da chave estrangeira
				$entidade_fk = tdClass::Criar("persistent",array(ENTIDADE,$atributo->contexto->chaveestrangeira));			
				if ($conn = Transacao::get()){
					if ($entidade_fk->contexto->campodescchave == "" || !$entidade_fk->contexto->campodescchave){
						// Pega o primeiro campo que é utilizado na grade de dados
						$sql_cd = tdClass::Criar("sqlcriterio");			
						$sql_cd->addFiltro(ENTIDADE,'=',$entidade_fk->contexto->id);
						$sql_cd->addFiltro("exibirgradededados",'=',1);
						$dataset_cd = tdClass::Criar("repositorio",array(ATRIBUTO))->carregar($sql_cd);
						if (!empty($dataset_cd)){
							$campodescchave = $dataset_cd[0]->id;
						}else{
							$campodescchave = "";
						}
					}else{
						$campodescchave = $entidade_fk->contexto->campodescchave;
					}
					if (
						$campodescchave == '' || $campodescchave == 0 || !$campodescchave || 
						( $entidade_fk->contexto->nome == '' || $entidade_fk->contexto->nome == null)
					){
						echo 'Sem Descrição';
						exit;
					}else{
						$atributo 		= tdClass::Criar("persistent",array(ATRIBUTO,$campodescchave));
						$entidade 		= tdClass::Criar("persistent",array($entidade_fk->contexto->nome,$_GET["termo"]));
						$valorfinal 	= $entidade->contexto->{$atributo->contexto->nome};
						if ($valorfinal != ''){
							echo utf8charset($valorfinal,5);
						}else{
							// Registro não encontrado
							echo '';
						}						
					}
				}
			}	
		break;
		case "exclui_registros_temporarios_composicao":	
			$sql = tdClass::Criar("sqlcriterio");
			$nome_entidade_rel = tdClass::Criar("persistent",array(ENTIDADE,$_POST["entidade_rel"]));
			$dataset = tdClass::Criar("repositorio",array($nome_entidade_rel->contexto->nome));
			$filhos = $dataset->carregar($sql);		
			try{
				$conn = Transacao::Get();			
				foreach ($filhos as $filho){
					$campo_rel_int = $filho->{$_POST["campo_rel"]};								
					if (!is_numeric($campo_rel_int) && strlen($_POST["id_pai"]) > 25){										
						$sql = "DELETE FROM {$nome_entidade_rel->contexto->nome} WHERE {$_POST["campo_rel"]} = '{$_POST["id_pai"]}'";
						Transacao::log($sql);
						$conn->exec($sql);
						$filho->deletar();
					}				
				}
				Transacao::Commit();
			}catch(Exception $e){
				Transacao::rollback();
				echo $e->getMessage();
			}
		break;
		case "load_gradededados":
			include 'gradededados.php';
		break;
		case "carregar_options":
			$entidade = tdClass::Criar("persistent",array(ENTIDADE,$_GET["entidade"]));
			$sql = tdClass::Criar("sqlcriterio");
			if (isset($_GET["filtro"])){

				if ($_GET["filtro"] != ""){
					$ft = explode("^",$_GET["filtro"]);
					switch($ft[1]){
						case "=":
							$operador = "=";
						break;
						case "!":
							$operador = "<>";
						break;
						default:
							$operador = "";
					}
					$valorfiltro = is_numeric($ft[2])?(int)$ft[2]:$ft[2];
					$sql->addFiltro($ft[0],$operador,$valorfiltro);
				}
			}
			$dataset = tdClass::Criar("repositorio",array($entidade->contexto->nome))->carregar($sql);
			$atributo = tdClass::Read("atributo");
			if ($atributo != "" && is_numeric($_GET["atributo"]) && (int)$atributo != 0){
				$campo_descricao = tdClass::Criar("persistent",array(ATRIBUTO,$atributo))->contexto->nome;
			}else{
				if ($entidade->contexto->campodescchave == "" || $entidade->contexto->campodescchave <= 0){
					$sqlAttr = tdClass::Criar("sqlcriterio");
					$sqlAttr->addFiltro("exibirgradededados","=",1);
					$sqlAttr->addFiltro(ENTIDADE,"=",$entidade->contexto->id);
					
					$attrCampoGrade = tdClass::Criar("repositorio",array(ATRIBUTO))->carregar($sqlAttr);
					if (sizeof($attrCampoGrade) > 0){
						$campo_descricao = $attrCampoGrade[0]->nome;
					}else{
						$campo_descricao = "";
					}
				}else{
					$campo_descricao = tdClass::Criar("persistent",array(ATRIBUTO,$entidade->contexto->campodescchave))->contexto->nome;
				}
			}	
			if ($campo_descricao == "") return false;
			$selecionado = "";
			foreach ($dataset as $dado){
				if (isset($_GET["valor"])){
					$selecionado = $dado->id == (int)$_GET["valor"]?"selected":"";
				}
				echo '<option value="'.$dado->id.'" '.$selecionado.'>'.tdc::utf8($dado->{$campo_descricao}).'</option>';
			}		
		break;
		case "carregar_options_checkbox":
			if (isset($_GET["atributo"])){
				$valor = isset($_GET["valor"])?$_GET["valor"]:"";
				$atributo = (int)$_GET["atributo"];
				if ($atributo != "" &&  $atributo > 0){
					$campo = tdClass::Criar("persistent",array(ATRIBUTO,$atributo))->contexto;
					if ($campo->labelzerocheckbox != "" && $campo->labelumcheckbox != ""){
						echo '<option value="0" '.($valor==0?'selected="selected"':'').'>'.utf8encode($campo->labelzerocheckbox).'</option>';
						echo '<option value="1" '.($valor==1?'selected="selected"':'').'>'.utf8encode($campo->labelumcheckbox).'</option>';
					}else{
						echo '<option value="0" '.($valor==0?'selected="selected"':'').'>Não</option>';
						echo '<option value="1" '.($valor==1?'selected="selected"':'').'>Sim</option>';
					}
					exit;
				}
			}
			echo '<option value="">Nenhum Item Encontrado</option>';
		break;	
		case "retorno_entidades_relacionamento_nome":		
			$entidadeID = tdClass::Criar("persistent",array(tdClass::Read("entidade")))->contexto->getID();		
			echo tdFormulario::getEntidadesRelacionamentos($entidadeID);
		break;
		case "perfilusuario":
			$usuario 	= tdc::r('usuario');
			$perfil 	= tdc::r('perfil');

			if ($usuario == ''){
				echo json_encode('Usuário não encontrado.');
				return false;
			}

			if ($perfil == ''){
				echo json_encode('Perfil do usuário não encontrado.');
				return false;
			}

			$projeto = 1;
			$empresa = 1;

			if ($conn = Transacao::Get()){

				// Entidade ( Permissão )
				$sql = "SELECT id,descricao FROM ".ENTIDADE." ORDER BY id ASC";
				$query = $conn->query($sql);
				While ($linha = $query->fetch()){
					// Dados do Perfil
					// [Entidade Permissão]
					$inserirEntidade = $excluirEntidade = $editarEntidade = $visualizarEntidade = 0;
					$sqlPerfilEntidade = "SELECT id,projeto,empresa,entidade,IFNULL(inserir,0) inserir,IFNULL(excluir,0) excluir,IFNULL(editar,0) editar,IFNULL(visualizar,0) visualizar FROM ".PERMISSOES." WHERE usuario = ".$perfil." AND entidade = ".$linha["id"];
					$queryPerfilEntidade = $conn->query($sqlPerfilEntidade);
					if ($linhaPerfilEntidade = $queryPerfilEntidade->fetch()){
						$inserirEntidade = $linhaPerfilEntidade["inserir"];
						$excluirEntidade = $linhaPerfilEntidade["excluir"];
						$editarEntidade = $linhaPerfilEntidade["editar"];
						$visualizarEntidade = $linhaPerfilEntidade["visualizar"];
					}

					$sqlUsuario = "SELECT id FROM ".PERMISSOES." WHERE usuario = ".$usuario." AND entidade = ".$linha["id"];
					$queryUsuario = $conn->query($sqlUsuario);
					if ($queryUsuario->rowcount() <= 0){
						
						$sqlInsertEntidadePermissao = "INSERT INTO ".PERMISSOES." (id,projeto,empresa,entidade,usuario,inserir,excluir,editar,visualizar) VALUES (".getProxId("entidadepermissoes",$conn).",1,1,".$linha["id"].",".$usuario.",".$inserirEntidade.",".$excluirEntidade.",".$editarEntidade.",".$visualizarEntidade.");";
						$queryInsertEntidadePermissao = $conn->query($sqlInsertEntidadePermissao);

					}else{
						$linhaUsuario = $queryUsuario->fetch();
						$sqlUpdateEntidadePermissao = "UPDATE ".PERMISSOES." SET projeto = 1, empresa = 1
						, inserir = {$inserirEntidade} , excluir = {$excluirEntidade} , editar = {$editarEntidade} , visualizar = {$visualizarEntidade} 
						WHERE id = " . $linhaUsuario["id"];

						$queryUpdateEntidadePermissao = $conn->query($sqlUpdateEntidadePermissao);
					}		
				}
				
				// Atributo ( Permissão )
				$sql = "SELECT id,descricao FROM ".ATRIBUTO." ORDER BY id ASC";
				$query = $conn->query($sql);
				While ($linha = $query->fetch()){

					// Dados do Perfil
					// [Atributo Permissão]
					$inserirAtributo = $excluirAtributo = $editarAtributo = $visualizarAtributo = 0;
					$sqlPerfilAtributo = "
						SELECT 
							id,
							projeto,
							empresa,
							atributo,
							IFNULL(inserir,0) inserir,
							IFNULL(excluir,0) excluir,
							IFNULL(editar,0) editar,
							IFNULL(visualizar,0) visualizar 
						FROM  ".PERMISSOESATRIBUTO."
						WHERE usuario = ".$perfil." 
						AND atributo = ".$linha["id"];
					$queryPerfilAtributo = $conn->query($sqlPerfilAtributo);
					if ($linhaPerfilAtributo = $queryPerfilAtributo->fetch()){
						$inserirAtributo 		= $linhaPerfilAtributo["inserir"];
						$excluirAtributo 		= $linhaPerfilAtributo["excluir"];
						$editarAtributo 		= $linhaPerfilAtributo["editar"];
						$visualizarAtributo 	= $linhaPerfilAtributo["visualizar"];
					}

					$sqlUsuario = "SELECT id FROM ".PERMISSOESATRIBUTO." WHERE usuario = ".$usuario." AND atributo = ".$linha["id"];
					$queryUsuario = $conn->query($sqlUsuario);
					if ($queryUsuario->rowcount() <= 0){
						$sqlInsertAtributoPermissao = "INSERT INTO ".PERMISSOESATRIBUTO." (id,projeto,empresa,atributo,usuario,inserir,excluir,editar,visualizar) VALUES (".getProxId("atributopermissoes",$conn).",1,1,".$linha["id"].",".$usuario.",".$inserirAtributo.",".$excluirAtributo.",".$editarAtributo.",".$visualizarAtributo.");";
						$queryInsertAtributoPermissao = $conn->query($sqlInsertAtributoPermissao);

					}else{

						$linhaUsuario = $queryUsuario->fetch();
						$sqlUpdateAtributoPermissao = "UPDATE ".PERMISSOESATRIBUTO." SET projeto = 1, empresa = 1
						, inserir = {$inserirAtributo} , excluir = {$excluirAtributo} , editar = {$editarAtributo} , visualizar = {$visualizarAtributo} 
						WHERE id = " . $linhaUsuario["id"];
						$queryUpdateAtributoPermissao = $conn->query($sqlUpdateAtributoPermissao);
					}
				}

				// Função ( Permissão )
				$sql = "SELECT id,descricao FROM ".FUNCAO." ORDER BY id ASC";
				$query = $conn->query($sql);
				While ($linha = $query->fetch()){
					// Dados do Perfil
					// [Função Permissão]
					$permissao = 0;
					$sqlPerfilFuncao = "SELECT id,projeto,empresa,funcao,permissao FROM ".FUNCOESPERMISSOES." WHERE usuario = ".$perfil." AND funcao = ".$linha["id"];
					$queryPerfilFuncao = $conn->query($sqlPerfilFuncao);
					if ($linhaPerfilFuncao = $queryPerfilFuncao->fetch()){
						$permissao = $linhaPerfilFuncao["permissao"];
					}

					$sqlUsuario = "SELECT id FROM ".FUNCOESPERMISSOES." WHERE usuario = ".$usuario." AND funcao = ".$linha["id"];
					$queryUsuario = $conn->query($sqlusuario);
					if ($queryUsuario->rowcount() <= 0){
						$sqlInsertFuncaoPermissao = "INSERT INTO ".FUNCOESPERMISSOES." (id,projeto,empresa,funcao,usuario,permissao) VALUES (".getProxId("funcaoPermissoes",$conn).",1,1,".$linha["id"].",".$usuario.",".$permissao.");";
						$queryInsertFuncaoPermissao = $conn->query($sqlInsertFuncaoPermissao);

					}else{

						$linhaUsuario = $queryUsuario->fetch();
						$sqlUpdateFuncaoPermissao = "UPDATE ".FUNCOESPERMISSOES." SET projeto = 1, empresa = 1
						, permissao = {$permissao} 
						WHERE id = " . $linhaUsuario["id"];

						$queryUpdateFuncaoPermissao = $conn->query($sqlUpdateFuncaoPermissao);
					}		
				}

				// Menu ( Permissão )
				$sql = "SELECT id,descricao FROM ".MENU." ORDER BY id ASC";
				$query = $conn->query($sql);
				While ($linha = $query->fetch()){
					// Dados do Perfil
					// [Função Permissão]
					$permissao = 0;
					$sqlPerfilMenu = "SELECT id,projeto,empresa,menu,permissao FROM ".MENUPERMISSOES." WHERE usuario = ".$perfil." AND menu = ".$linha["id"];
					$queryPerfilMenu = $conn->query($sqlPerfilMenu);
					if ($linhaPerfilMenu = $queryPerfilMenu->fetch()){
						$permissao = $linhaPerfilMenu["permissao"];
					}

					$sqlUsuario = "SELECT id FROM ".MENUPERMISSOES." WHERE usuario = ".$usuario." AND menu = ".$linha["id"];
					$queryUsuario = $conn->query($sqlUsuario);
					if ($queryUsuario->rowcount() <= 0){
						$sqlInsertMenuPermissao = "INSERT INTO ".MENUPERMISSOES." (id,projeto,empresa,menu,usuario,permissao) VALUES (".getProxId("menuPermissoes",$conn).",1,1,".$linha["id"].",".$usuario.",".$permissao.");";
						$queryInsertMenuPermissao = $conn->query($sqlInsertMenuPermissao);

					}else{
						$linhaUsuario = $queryUsuario->fetch();
						$sqlUpdateMenuPermissao = "UPDATE ".MENUPERMISSOES." SET projeto = 1, empresa = 1
						, permissao = {$permissao} 
						WHERE id = " . $linhaUsuario["id"];
						
						$queryUpdateMenuPermissao = $conn->query($sqlUpdateMenuPermissao);
					}		
				}
				Transacao::Commit();
			}else{
				echo 'Não há conexão com o banco de dados';
			}		
		break;
		case "retorna_dados_entidade":
			$conn = Transacao::Get();
			$atributos = $_GET["atributos"];
			$sql = "SELECT ".$atributos." FROM ".$_GET["entidade"]." WHERE id > 0  LIMIT 300";
			$query = $conn->query($sql);
			echo '{"entidadeid":"'.$_GET["entidadeid"].'","dados":[';
			$iCampo = 1;	
			$campos = explode(",",$atributos);
			$tCampos = sizeof($campos);
			$iRegistro = 1;
			While($linha = $query->fetch()){
				echo "{";
				For ($i=0;$i<$tCampos;$i++){
					$attr = $campos[$i];
					echo '"'.$attr.'":"'.$linha[$attr].'"';
					if ($i < $tCampos) echo ",";
					$iCampo++;
				}
				echo "}";
				if ($iRegistro < $query->rowcount()) echo ",";
				$iRegistro++;
			}
			echo "]}";

		break;
		case "setar_todas_permissoes":
			if ((int)tdClass::Read("installsystem") == 1){
				$tipo		= tdClass::Read("tipo");
				$host		= tdClass::Read("host");
				$base		= tdClass::Read("base");
				$usuario	= tdClass::Read("usuario");
				$senha		= tdClass::Read("senha");
				$porta		= tdClass::Read("porta");
				$conn = Conexao::getConnection($tipo,$host,$base,$usuario,$senha,$porta);
				$usuario = 1;
			}else{
				$conn = Transacao::Get();
				$usuario = isset($_GET["usuario"])?$_GET["usuario"]:1;
			}
			if ($conn){
				$sqlEntidade = "SELECT id FROM " . ENTIDADE;
				$queryEntidade = $conn->query($sqlEntidade);
				while($linhaEntidade = $queryEntidade->fetch()){
					
					$sqlDelEntidadePermissao = "DELETE FROM ".PERMISSOES." WHERE entidade = " . $linhaEntidade["id"] . " AND usuario = " . $usuario;
					$queryDelEntidadePermissao = $conn->query($sqlDelEntidadePermissao);

					$sqlInsertEntidadePermissao = "INSERT INTO ".PERMISSOES." (id,entidade,usuario,inserir,excluir,editar,visualizar) VALUES (
					".getProxId("entidadepermissoes",$conn).",".$linhaEntidade["id"].",".$usuario.",".$_GET["permissao"].",".$_GET["permissao"].",".$_GET["permissao"].",".$_GET["permissao"].");";
					$queryInsertEntidadePermissao = $conn->query($sqlInsertEntidadePermissao);

				}
				
				$sqlAtributo = "SELECT id FROM " . ATRIBUTO;
				$queryAtributo = $conn->query($sqlAtributo);
				while($linhaAtributo = $queryAtributo->fetch()){
					
					$sqlDelAtributoPermissao = "DELETE FROM ".PERMISSOESATRIBUTO." WHERE atributo = " . $linhaAtributo["id"] . " AND usuario = " . $usuario;
					$queryDelAtributoPermissao = $conn->query($sqlDelAtributoPermissao);
					
					$sqlInsertAtributoPermissao = "INSERT INTO ".PERMISSOESATRIBUTO." (id,projeto,empresa,atributo,usuario,inserir,excluir,editar,visualizar) VALUES (
					".getProxId("atributopermissoes",$conn).",1,1,".$linhaAtributo["id"].",".$usuario.",".$_GET["permissao"].",".$_GET["permissao"].",".$_GET["permissao"].",".$_GET["permissao"].");";
					$queryInsertAtributoPermissao = $conn->query($sqlInsertAtributoPermissao);			
				}

				$sqlMenu = "SELECT id FROM " . MENU;
				$queryMenu = $conn->query($sqlMenu);
				while($linhaMenu = $queryMenu->fetch()){
					
					$sqlDelMenuPermissao = "DELETE FROM ".MENUPERMISSOES." WHERE menu = " . $linhaMenu["id"] . " AND usuario = " . $usuario;
					$queryDelMenuPermissao = $conn->query($sqlDelMenuPermissao);
					
					$sqlInsertMenuPermissao = "INSERT INTO ".MENUPERMISSOES." (id,projeto,empresa,menu,usuario,permissao) VALUES (
					".getProxId("menupermissoes",$conn).",1,1,".$linhaMenu["id"].",".$usuario.",".$_GET["permissao"].");";
					$queryInsertMenuPermissao = $conn->query($sqlInsertMenuPermissao);			
				}
				
				$sqlMenu = "SELECT id FROM " . MENU;
				$queryMenu = $conn->query($sqlMenu);
				while($linhaMenu = $queryMenu->fetch()){
					
					$sqlDelMenuPermissao = "DELETE FROM ".MENUPERMISSOES." WHERE menu = " . $linhaMenu["id"] . " AND usuario = " . $usuario;
					$queryDelMenuPermissao = $conn->query($sqlDelMenuPermissao);
					
					$sqlInsertMenuPermissao = "INSERT INTO ".MENUPERMISSOES." (id,projeto,empresa,menu,usuario,permissao) VALUES (
					".getProxId("menupermissoes",$conn).",1,1,".$linhaMenu["id"].",".$usuario.",".$_GET["permissao"].");";
					$queryInsertMenuPermissao = $conn->query($sqlInsertMenuPermissao);
				}

				Transacao::Commit();
			}else{
				echo 'Sem conexão com o banco de dados';
			}
		break;
		case "logmenu":
			if ($conn =Transacao::Get()){
				$log 				= tdClass::Criar("persistent",array(LOG))->contexto;
				$log->id 			= $log->getUltimo() + 1;
				$log->usuario		= Session::get()->userid;
				$log->projeto		= PROJECT_ID;
				$log->empresa		= Session::get()->empresa;
				$log->entidade		= getEntidadeId("menu",$conn);
				$log->valorid 		= $_GET["menu"];
				$log->datahora		= date("Y-m-d H:i:s");
				$log->acao 			= 4;
				$log->armazenar();
				Transacao::Commit();
			}
		break;
		case "createprojetct":
			$idproject = tdClass::Read("id");

			$pathdir = PATH_PROJECT . $idproject;
			if (!file_exists($pathdir)){
				// Cria a pasta do projeto
				tdFile::mkdir($pathdir,755);
			}

			$pathdirarquivos = $pathdir . "/arquivos";
			if (!file_exists($pathdirarquivos)){
				// Cria a pasta de arquivos dentro do projeto
				tdFile::mkdir($pathdirarquivos,755);
			}

			$pathdirfilestemp = $pathdir . "/arquivos/temp";
			if (!file_exists($pathdirfilestemp)){
				// Cria a pasta temp dentro de arquivos dentro do projeto
				tdFile::mkdir($pathdirfilestemp,755);
			}

			$pathdirconfig = $pathdir . "/config";
			if (!file_exists($pathdirconfig)){
				// Cria a pasta config
				tdFile::mkdir($pathdirconfig,755);
			}
		
			$pathdircontroller = $pathdir . "/controller";
			if (!file_exists($pathdircontroller)){
				// Cria a pasta controller
				tdFile::mkdir($pathdircontroller,755);
			}

			$pathdirfiles = $pathdir . "/files";
			if (!file_exists($pathdirfiles)){
				// Cria a pasta files
				tdFile::mkdir($pathdirfiles,755);
			}

			$pathdirfilespage = $pathdir . "/cadastro/page";
			if (!file_exists($pathdirfilespage)){
				// Cria a pasta page dentro da pasta files
				tdFile::mkdir($pathdirfilespage,755);
			}
			
			$pathdirfilesconsulta = $pathdir . "/files/consulta";
			if (!file_exists($pathdirfilesconsulta)){
				// Cria a pasta consulta dentro da pasta files
				tdFile::mkdir($pathdirfilesconsulta,755);
			}

			$pathdirfilesmovimentacao = $pathdir . "/files/movimentacao";
			if (!file_exists($pathdirfilesmovimentacao)){
				// Cria a pasta movimentacao dentro da pasta files
				tdFile::mkdir($pathdirfilesmovimentacao,755);
			}

			$pathdirfilesrelatorio = $pathdir . "/files/relatorio";
			if (!file_exists($pathdirfilesrelatorio)){
				// Cria a pasta relatorio dentro da pasta files
				tdFile::mkdir($pathdirfilesrelatorio,755);
			}

			$pathdirimagens = $pathdir . "/imagens";
			if (!file_exists($pathdirimagens)){
				// Cria a pasta controller
				tdFile::mkdir($pathdirimagens,755);
			}

			$pathdirmodel = $pathdir . "/model";
			if (!file_exists($pathdirmodel)){
				// Cria a pasta controller
				tdFile::mkdir($pathdirmodel,755);
			}

			$pathdirtema = $pathdir . "/tema";
			if (!file_exists($pathdirtema)){
				// Cria a pasta controller
				tdFile::mkdir($pathdirtema,755);
			}

			$pathdirview = $pathdir . "/tema";
			if (!file_exists($pathdirview)){
				// Cria a pasta controller
				tdFile::mkdir($pathdirview,755);
			}
		break;
		case "alterproject":
			$tipo = getDescTipoConnection(tdClass::Read("typedatabase"));

			// Redefine sessão do Projeto		
			$sqlProjetoMiles = "SELECT * FROM ".PROJETO." WHERE  id = " . tdClass::Read("project") . ";";
			$queryProjetoMiles = $connMILES->query($sqlProjetoMiles);
			$projetocurrent = (object)$queryProjetoMiles->fetch();

			if (PROJECT_ID == 1 && (int)Session::get()->usergroup == 1){
				setConfigSessionDefault();			
				#setConfigFileDefault();
				#setCurrentFileDatabseDefault();
				echo 1;
				exit;
			}

			$sqlDatabase = "SELECT * FROM ".CONNECTIONDATABASE." WHERE  id = " . (int)tdClass::Read("databaseid") . ";";
			$queryDatabase = $connMILES->query($sqlDatabase);
			$database = (object)$queryDatabase->fetch();
		
			if (tdInstall::isInstalled($database)){
				Session::append("currenttypedatabase",$tipo);
				Session::append("currentproject",$projetocurrent->projectdiretorio);
				Session::append("projeto",$projetocurrent->id);
				Session::append("currentprojectname",$projetocurrent->nome);
				echo 1;
			}else{
				echo 2;
			}
		break;

		case 'retorna_ids_lista':
			
			$entidadepai = tdClass::Read("entidadepai");
			$entidadefilho = tdClass::Read("entidadefilho");
			$regpai = tdClass::Read("regpai");

			$sql = tdClass::Criar("sqlcriterio");
			$sql->addFiltro("entidadepai","=",$entidadepai);		
			$sql->addFiltro("entidadefilho","=",$entidadefilho);
			$sql->addFiltro("regpai","=",$regpai);
			$lista = tdClass::Criar("repositorio",array(LISTA))->carregar($sql);
			$rlista = array();
			foreach($lista as $l){
				array_push($rlista,$l->regfilho);
			}
			echo implode(",",$rlista);
		break;
	}
