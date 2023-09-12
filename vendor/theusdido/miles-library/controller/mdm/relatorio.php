<?php

    switch($_op){
        case 'salvar_colunas_configuracoes':
            $atributo               = tdc::r('atributo');
            $coluna                 = tdc::p('td_relatoriocoluna')->newNotExists('atributo','=',$atributo);
            $coluna->relatorio      = tdc::r('relatorio');
            $coluna->atributo       = $atributo;            
            $coluna->alinhamento    = tdc::r('alinhamento');
            $coluna->descricao      = tdc::r('descricao');
            $coluna->exibirid       = json_decode(tdc::r('exibirid'),true);            
            $coluna->is_somatorio   = json_decode(tdc::r('is_somatorio'),true);
            $coluna->armazenar();
        break;
        case 'get_config_colunas':
            $filtro = tdc::f();
            $filtro->addFiltro('relatorio','=',tdc::r('relatorio'));
            $filtro->addFiltro('atributo','=',tdc::r('atributo'));
            $_coluna = tdc::d('td_relatoriocoluna',$filtro);

            tdc::wj(array(
                'alinhamento'   => $_coluna[0]->alinhamento,
                'exibirid'      => $_coluna[0]->exibirid,
                'descricao'     => $_coluna[0]->descricao,
                'is_somatorio'  => $_coluna[0]->is_somatorio
            ));
        break;
        case 'get_colunas':
            $_retorno   = [];
            $filtro     = tdc::f('relatorio','=',tdc::r('relatorio'));
            $filtro->setPropriedade('order','ordem ASC');
            $_colunas   = tdc::d('td_relatoriocoluna',$filtro);

            foreach ($_colunas as $c){
                array_push($_retorno,array(
                    'id'            => $c->id,
                    'alinhamento'   => $c->alinhamento,
                    'exibirid'      => $c->exibirid,
                    'atributo'      => tdc::pa(ATRIBUTO,$c->atributo),
                    'ordem'         => $c->ordem,
                    'descricao'     => $c->descricao,
                    'is_somatario'  => $c->is_somatario
                ));
            }

            tdc::wj($_retorno);
        break;
        case 'add_coluna':
            $coluna                 = tdc::p('td_relatoriocoluna');
            $coluna->relatorio      = tdc::r('relatorio');
            $coluna->atributo       = tdc::r('atributo');
            $coluna->alinhamento    = 'left';
            $coluna->exibirid       = false;
            $coluna->armazenar();
        break;
        case 'del_coluna':
            tdc::p('td_relatoriocoluna',tdc::r('id'))->deletar();
        break;
        case 'listar-relatorio':
            $sql    = "SELECT id,descricao,entidade FROM " . RELATORIO;
            $query  = $conn->query($sql);
            if ($query->rowCount() > 0){
                foreach ($query->fetchAll() as $linha){
                    $id                     = $linha["id"];
                    $descricao              = tdc::utf8($linha["descricao"]);
                    $query                  = $conn->query("SELECT descricao FROM td_entidade WHERE id = {$linha["entidade"]}")->fetch();
                    $entidade_id		    = $linha["entidade"];
                    $entidade_descricao     = tdc::utf8($query["descricao"]);
                    echo "	
                        <tr id='linha-registro-relatorio-{$id}'>
                            <td>{$id}</td>
                            <td>{$descricao}</td>
                            <td>{$entidade_descricao}</td>
                            <td align='center'>
                                <button type='button' class='btn btn-primary' onclick='editarRelatorio($id);'>                                                                                                                                          
                                    <span class='fas fa-pencil-alt' aria-hidden='true'></span>
                                </button>
                            </td>
                            <td align='center'>
                                <button type='button' class='btn btn-info' onclick='gerarRelatorio({$id},$entidade_id);'>
                                    <span class='fas fa-code' aria-hidden='true'></span>
                                </button>
                            </td>                            
                            <td align='center' >
                                <button type='button' class='btn btn-danger' onclick='excluirRelatorio({$id})'>
                                    <span class='fas fa-trash-alt' aria-hidden='true'></span>
                                </button>
                            </td>
                        </tr>
                    ";
                }

            }else{
                echo "
                    <tr>
                        <td colspan='6' class='warning text-center' >Nenhum Registro Encontrado</td>
                    </tr>
                ";
            }
        break;
        case 'listar-entidade-option':
            echo '<option value="0" >-- Selecione --</option>';
            $sql = "SELECT id,nome,descricao FROM ".ENTIDADE;
            $query = $conn->query($sql);
            foreach($query->fetchAll() as $linha){
                echo '<option value="'.$linha["id"].'" data-nome="'.$linha["nome"].'">'.$linha["descricao"].' [ '.$linha["nome"].' ]</option>';
            }
        break;
        case 'salvar':
			$id			 			= $_POST["id"];
			$descricao				= $_POST["descricao"];
			$entidade	 			= $_POST["entidade"];
			$urlpersonalizada		= $_POST["urlpersonalizada"];

			if ($id == 0){
				$query_prox     = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM ".RELATORIO);
				$prox           = $query_prox->fetch();
				$id             = $prox[0];
				$sql            = "INSERT INTO ".RELATORIO." (id,descricao,entidade,urlpersonalizada) VALUES ({$id},'{$descricao}',{$entidade},'{$urlpersonalizada}');";
			}else{
				$sql            = "UPDATE ".RELATORIO." SET entidade = {$entidade} , descricao = '{$descricao}' , urlpersonalizada = '{$urlpersonalizada}' WHERE id = {$id};";
			}

			$query = $conn->query($sql);            
			if($query){
                tdc::wj(array(
                    'status' 	=> 'success',
                    'id'		=> $id
                ));
            }else{                
				if (IS_SHOW_ERROR_MESSAGE){
					var_dump($conn->errorInfo());
				}else{
					tdc::wj(array(
						'status' 	=> 'error',
						'id'		=> $id
					));                    
                }
			}
        break;
        case 'load':
            tdc::wj( tdc::rua(RELATORIO,tdc::r('id')) );
        break;
        case 'listar-atributos':
            echo '<option value="0" selected>Nenhum Selecionado</option>';
            $sql = "SELECT id,descricao FROM ".ATRIBUTO." WHERE entidade = " . tdc::r('_entidade');
            $query = $conn->query($sql);
            foreach($query->fetchAll() as $linha){
                echo '<option value="'.$linha["id"].'">'.tdc::utf8($linha["descricao"]).'</option>';
            }
        break;
        case 'listarrelatorio':
			$sql = "SELECT id,atributo atributo,operador,legenda FROM td_relatoriofiltro a WHERE relatorio = {$_GET["relatorio"]} ORDER BY id DESC";
			$query = $conn->query($sql);
			if ($query->rowCount() <= 0){
				echo '<div class="alert alert-warning alert-dismissible text-center" role="alert">Nenhum campo de <strong>filtro</strong> configurado.</div>';
			}
			foreach($query->fetchAll() as $linha){
				$atributo 			= $linha["atributo"];
				$operador 			= $linha["operador"];
				$legenda 			= $linha["legenda"];
				$sqlAtributo 		= "SELECT descricao FROM td_atributo WHERE id = " . $atributo;
				$queryAtributo 		= $conn->query($sqlAtributo);
				$linhaAtributo 		= $queryAtributo->fetch();
				$atributoDescricao 	= $linhaAtributo["descricao"];
				echo "<li class='list-group-item' data-id='".$linha["id"]."'>
						Atributo <strong>{$atributoDescricao}</strong> com  operador ( <strong>{$operador} ) </strong>
						<button type='button' class='btn btn-default' onclick='excluirFiltro({$linha["id"]});' style='float:right;margin-top:-4px'>
							<span class='fas fa-trash-alt' aria-hidden='true'></span>
						</button>
						<button id='atributo-editar-{$linha["id"]}' type='button' class='btn btn-default' data-atributo='{$atributo}' data-operador='{$operador}' data-idfiltro='{$linha["id"]}' data-legenda='{$linha["legenda"]}' onclick='editarFiltro({$linha["id"]})' style='float:right;margin-top:-4px'>
							<span class='fas fa-edit' aria-hidden='true'></span>
						</button>
					</li>";
			}
        break;
        case 'listarstatus':
			$sql 	= "SELECT id,atributo atributo,operador,valor,status FROM td_relatoriostatus a WHERE relatorio = {$_GET["relatorio"]}";
			$query 	= $conn->query($sql);
			if ($query->rowCount() <= 0){
				echo '<div class="alert alert-warning alert-dismissible text-center" role="alert">Nenhum filtro de <strong>status</strong> configurado.</div>';
			}
			foreach($query->fetchAll() as $linha){
				$atributo 			= $linha["atributo"];
				$operador 			= $linha["operador"];
				$valor 				= $linha["valor"];
				$sqlAtributo 		= "SELECT descricao FROM td_atributo WHERE id = " . $atributo;
				$queryAtributo 		= $conn->query($sqlAtributo);
				$linhaAtributo 		= $queryAtributo->fetch();
				$atributoDescricao 	= $linhaAtributo["descricao"];
				echo "<span class='list-group-item'>
						Atributo <strong>{$atributoDescricao}</strong> com  operador ( <strong>{$operador} ) </strong>. Valor: <small class='text-info'>{$valor}</small>.
						<button type='button' class='btn btn-default' onclick='excluirStatus({$linha["id"]});' style='float:right;margin-top:-4px'>
							<span class='fas fa-trash-alt' aria-hidden='true'></span>
						</button>
						<button id='atributo-editar-{$linha["id"]}' type='button' class='btn btn-default' data-atributo='{$atributo}' data-operador='{$operador}' data-valor='{$valor}' data-idstatus='{$linha["id"]}' data-status='{$linha["status"]}' onclick='editarStatus({$linha["id"]})' style='float:right;margin-top:-4px'>
							<span class='fas fa-edit' aria-hidden='true'></span>
						</button>
					</span>";
			}
        break;
        case 'listarfiltroinicial':
            $sql = "SELECT id,atributo atributo,operador,legenda,valor FROM td_relatoriorestricao a WHERE relatorio = ".$_GET["relatorio"]." ORDER BY id DESC";
            $query = $conn->query($sql);
            if ($query->rowCount() <= 0){
                echo '<div class="alert alert-warning alert-dismissible text-center" role="alert">Nenhum campo de <strong>filtro</strong> configurado.</div>';
            }
            foreach($query->fetchAll() as $linha){
                $atributo 			= $linha["atributo"];
                $operador 			= $linha["operador"];
                $legenda 			= $linha["legenda"];
                $sqlAtributo 		= "SELECT descricao FROM td_atributo WHERE id = " . $atributo;
                $queryAtributo 		= $conn->query($sqlAtributo);
                $linhaAtributo 		= $queryAtributo->fetch();
                $atributoDescricao 	= $linhaAtributo["descricao"];
                $valor 				= $linha["valor"];

                echo "<span class='list-group-item'>
                        Atributo <strong>{$atributoDescricao}</strong> com  operador ( <strong>{$operador} ) </strong>
                        <button type='button' class='btn btn-default' onclick='excluirFiltroInicial({$linha["id"]});' style='float:right;margin-top:-4px'>
                            <span class='fas fa-trash-alt' aria-hidden='true'></span>
                        </button>
                        <button id='atributo-editar-{$linha["id"]}' type='button' class='btn btn-default' data-atributo='{$atributo}' data-operador='{$operador}' data-idfiltro='{$linha["id"]}' data-legenda='{$linha["legenda"]}' data-valor='{$valor}' onclick='editarFiltroInicial({$linha["id"]})' style='float:right;margin-top:-4px'>
                            <span class='fas fa-edit' aria-hidden='true'></span>
                        </button>
                    </span>";
            }                
        break;
        case 'excluirfiltroinicial':
            $sql = "DELETE FROM td_relatoriorestricao WHERE id = " . $_GET["id"];
            $query = $conn->query($sql);                
        break;
        case 'salvarfiltro':
			$id			 			= $_POST["id"];
			$operador				= $_POST["operador"];
			$atributo	 			= $_POST["atributo"];
			$relatorio	 			= $_POST["relatorio"];
			$legenda 				= $_POST["legenda"];

			if ($id == ""){
				$query_prox = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM td_relatoriofiltro");
				$prox = $query_prox->fetch();
				$id = $prox[0];
				$sql = "INSERT INTO td_relatoriofiltro (id,operador,atributo,relatorio,legenda) VALUES ({$id},'{$operador}',{$atributo},{$relatorio},'{$legenda}');";
			}else{
				$sql = "UPDATE td_relatoriofiltro SET atributo = {$atributo} , relatorio = {$relatorio} , operador = '{$operador}' , legenda = '{$legenda}' WHERE id = {$id};";
			}			
			$query = $conn->query($sql);
			if($query){
				// Alterações no atributo
				$conn->query("UPDATE td_atributo SET legenda = '{$legenda}' WHERE id=" . $atributo);
				echo 1;
			}else{
				if (IS_SHOW_ERROR_MESSAGE){
					var_dump($conn->errorInfo());
				}
			}
        break;
        case 'listar-status':
            $sql = "SELECT id,descricao FROM td_status";
            $query = $conn->query($sql);
            foreach($query->fetchAll() as $linha){
                echo '<option value="'.$linha["id"].'">'.$linha["descricao"].'</option>';
            }
        break;
		case 'excluirfiltro':
			$sql = "DELETE FROM td_relatoriofiltro WHERE id = " . $_GET["id"];
			$query = $conn->query($sql);
        break;
		case 'salvarstatus':
			$id			 			= $_POST["id"];
			$operador				= $_POST["operador"];
			$valor					= $_POST["valor"];
			$atributo	 			= $_POST["atributo"];
			$relatorio	 			= $_POST["relatorio"];
			$status 				= $_POST["status"];

			if ($id == ""){
				$query_prox = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM td_relatoriostatus");
				$prox = $query_prox->fetch();
				$id = $prox[0];
				$sql = "INSERT INTO td_relatoriostatus (id,operador,valor,atributo,relatorio,status) VALUES ({$id},'{$operador}','{$valor}',{$atributo},{$relatorio},{$status});";
			}else{
				$sql = "UPDATE td_relatoriostatus SET atributo = {$atributo} , relatorio = {$relatorio} , valor = '{$valor}' , operador = '{$operador}' , status = {$status} WHERE id = {$id};";
			}
			$query = $conn->query($sql);
			if($query){
				echo 1;
			}else{
				if (IS_SHOW_ERROR_MESSAGE){
					var_dump($conn->errorInfo());
				}
			}
        break;
        case 'excluirstatus':
			$sql = "DELETE FROM td_relatoriostatus WHERE id = " . $_GET["id"];
			$query = $conn->query($sql);
        break;
        case 'salvarfiltroinicial':
			$id			 			= $_POST["id"];
			$operador				= $_POST["operador"];
			$atributo	 			= $_POST["atributo"];
			$relatorio	 			= $_POST["relatorio"];
			$legenda 				= $_POST["legenda"];
			$valor 					= $_POST["valor"];

			if ($id == ''){
				$query_prox = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM td_relatoriorestricao");
				$prox = $query_prox->fetch();
				$id = $prox[0];
				$sql = "INSERT INTO td_relatoriorestricao (id,operador,atributo,relatorio,legenda,valor) VALUES ({$id},'{$operador}',{$atributo},{$relatorio},'{$legenda}','{$valor}');";
			}else{
				$sql = "UPDATE td_relatoriorestricao SET atributo = {$atributo} , relatorio = {$relatorio} , operador = '{$operador}' , legenda = '{$legenda}' , valor = '{$valor}' WHERE id = {$id};";
			}
			$query = $conn->query($sql);
			if($query){
				// Alterações no atributo
				$conn->query("UPDATE td_atributo SET legenda = '{$legenda}' WHERE id=" . $atributo);
				echo 1;
			}else{
				if (IS_SHOW_ERROR_MESSAGE){
					var_dump($conn->errorInfo());
				}
			}            
        break;
        case 'excluir-relatorio':
            $sql = "DELETE FROM td_relatorio WHERE id = " . $_GET["id"];
            $conn->exec($sql);

            $sql = "DELETE FROM td_relatoriostatus WHERE relatorio = " . $_GET["id"];
            $conn->exec($sql);
            
            $sql = "DELETE FROM td_relatoriorestricao WHERE relatorio = " . $_GET["id"];
            $conn->exec($sql);

            $sql = "DELETE FROM td_relatoriofiltro WHERE relatorio = " . $_GET["id"];
            $conn->exec($sql);

            $sql = "DELETE FROM td_relatoriocoluna WHERE relatorio = " . $_GET["id"];
            $conn->exec($sql);

        break;        
    }