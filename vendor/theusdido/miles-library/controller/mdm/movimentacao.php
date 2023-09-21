<?php
    switch($_op){
        case 'listar-movimentacao':
            $sql    = "SELECT id,descricao,entidade FROM " . MOVIMENTACAO;
            $query  = $conn->query($sql);

            if ($query->rowCount() > 0){
                foreach ($query->fetchAll() as $linha){
                    $id         		= $linha["id"];
                    $descricao  		= tdc::utf8($linha["descricao"]);
                    $query      		= $conn->query("SELECT descricao FROM td_entidade WHERE id = {$linha["entidade"]}")->fetch();
                    $entidade_id		= $linha["entidade"];
					$entidade_descricao	= tdc::utf8($query["descricao"]);
                    echo "	
						<tr id='linha-registro-movimentacao-{$id}'>
                            <td>{$id}</td>
                            <td>{$descricao}</td>
                            <td>{$entidade_descricao}</td>
                            <td align='center'>
                                <button type='button' class='btn btn-info' onclick='gerarMovimentacao({$id},$entidade_id);'>
                                    <span class='fas fa-code' aria-hidden='true'></span>
                                </button>
                            </td>							
                            <td align='center'>
                                <button type='button' class='btn btn-primary' onclick='editarMovimentacao({$id})'>
                                    <span class='fas fa-pencil-alt' aria-hidden='true'></span>
                                </button>
                            </td>
                            <td align='center' >
                                <button type='button' class='btn btn-danger' onclick='excluirMovimentacao({$id})'>
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
			$displaybutton			= $_POST["displaybutton"];
			$entidade	 			= $_POST["entidade"];
			$motivo					= $_POST["motivo"];
			$exigirobrigatorio		= $_POST["exigirobrigatorio"] == 'true' ? 1 : 0;;
			$exibirtitulo			= $_POST["exibirtitulo"] == 'true' ? 1 : 0;;
			$exibirvaloresantigos	= $_POST["exibirvaloresantigos"] == 'true' ? 1 : 0;

			if ($id == 0){
				$query_prox = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM ".MOVIMENTACAO);
				$prox 		= $query_prox->fetch();
			 	$id 		= $prox[0];
			 	$sql 		= "
					INSERT INTO ".MOVIMENTACAO." (
						id,
						descricao,
						entidade,
						motivo,
						exigirobrigatorio,
						exibirtitulo,
						exibirvaloresantigos,
						displaybutton
					) VALUES (
						{$id},
						'{$descricao}',
						{$entidade},
						{$motivo},
						{$exigirobrigatorio},
						{$exibirtitulo},
						{$exibirvaloresantigos},
						'{$displaybutton}'
					);";
			}else{
			 	$sql 		= "
					UPDATE ".MOVIMENTACAO." SET 
						entidade = {$entidade}, 
						descricao = '{$descricao}',
						motivo = {$motivo},
						exigirobrigatorio = {$exigirobrigatorio},
						exibirtitulo = {$exibirtitulo},
						exibirvaloresantigos = {$exibirvaloresantigos},
						displaybutton = '{$displaybutton}'
					WHERE id = {$id};
				";
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
		case 'lista_atributos':
			$sql    = "SELECT id,descricao,nome FROM ".ATRIBUTO." WHERE entidade = " . $_GET["entidade"];
			$query  = $conn->query($sql);
			foreach($query->fetchAll() as $linha){
				$descricao = tdc::utf8($linha["descricao"]);
				echo '<option value="'.$linha["id"].'">'.$descricao.' [ '.$linha["nome"].' ]</option>';
			}
        break;
		case 'salvaralterar':
			$id			 			= $_POST["id"];
			$atributo	 			= $_POST["atributo"];
			$movimentacao 			= $_POST["movimentacao"];
			$legenda 				= $_POST["legenda"];

			if ($id == ""){
				$query_prox     = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM td_movimentacaoalterar");
				$prox           = $query_prox->fetch();
				$id             = $prox[0];
				$sql = "INSERT INTO td_movimentacaoalterar (id,atributo,movimentacao,legenda) VALUES ({$id},{$atributo},{$movimentacao},'{$legenda}');";
			}else{
				$sql = "UPDATE td_movimentacaoalterar SET atributo = {$atributo} , movimentacao = {$movimentacao} , legenda = '{$legenda}' WHERE id = {$id};";
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
		case 'salvarstatus':
			$id			 			= $_POST["id"];
			$operador				= $_POST["operador"];
			$valor					= $_POST["valor"];
			$atributo	 			= $_POST["atributo"];
			$movimentacao	 		= $_POST["movimentacao"];

			if ($id == ""){
				$query_prox     = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM td_movimentacaostatus");
				$prox           = $query_prox->fetch();
				$id             = $prox[0];
				$sql = "INSERT INTO td_movimentacaostatus (id,operador,valor,atributo,movimentacao) VALUES ({$id},'{$operador}','{$valor}',{$atributo},{$movimentacao});";
			}else{
				$sql = "UPDATE td_movimentacaostatus SET atributo = {$atributo} , movimentacao = {$movimentacao} , valor = '{$valor}' , operador = '{$operador}' WHERE id = {$id};";
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
		case 'salvarhistorico':
			$id			 			= $_POST["id"];
			$legenda				= $_POST["legenda"];
			$atributo	 			= $_POST["atributo"];
			$movimentacao	 		= $_POST["movimentacao"];
			$entidade				= tdc::p(MOVIMENTACAO,$movimentacao)->entidade;

			if ($id == ""){
				$query_prox = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM td_movimentacaohistorico");
				$prox = $query_prox->fetch();
				$id = $prox[0];
				$sql = "INSERT INTO td_movimentacaohistorico (id,entidade,atributo,movimentacao,legenda) VALUES ({$id},{$entidade},{$atributo},{$movimentacao},'{$legenda}');";
			}else{
				$sql = "UPDATE td_movimentacaohistorico SET entidade = {$entidade}, atributo = {$atributo} , movimentacao = {$movimentacao} , legenda = '{$legenda}' WHERE id = {$id};";
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
		case "excluiralterar":
			$sql    = "DELETE FROM td_movimentacaoalterar WHERE id = " . $_GET["id"];
			$query  = $conn->query($sql);
		break;

		case 'excluirstatus':
			$sql    = "DELETE FROM td_movimentacaostatus WHERE id = " . $_GET["id"];
			$query  = $conn->query($sql);
		break;

		case 'excluirhistorico':
			$sql    = "DELETE FROM td_movimentacaohistorico WHERE id = " . $_GET["id"];
			$query  = $conn->query($sql);
        break;

		case 'listarmovimentacao':
			$sql = "SELECT id,atributo atributo,legenda FROM td_movimentacaoalterar a WHERE movimentacao = {$_GET["movimentacao"]} ORDER BY id DESC";
			$query = $conn->query($sql);
			if ($query->rowCount() <= 0){
				echo '<div class="alert alert-warning alert-dismissible text-center" role="alert">Nenhum campo de <strong>alterar</strong> configurado.</div>';
			}
			foreach($query->fetchAll() as $linha){
				$atributo 			= $linha["atributo"];
				$legenda 			= tdc::utf8($linha["legenda"]);
				$sqlAtributo 		= "SELECT descricao FROM td_atributo WHERE id = " . $atributo;
				$queryAtributo 		= $conn->query($sqlAtributo);
				$linhaAtributo 		= $queryAtributo->fetch();
				$atributoDescricao 	= tdc::utf8($linhaAtributo["descricao"]);
				echo "<span class='list-group-item'>
						Atributo <strong>{$atributoDescricao}</strong>
						<button type='button' class='btn btn-default' onclick='excluirAlterar({$linha["id"]});' style='float:right;margin-top:-4px'>
							<span class='fas fa-trash-alt' aria-hidden='true'></span>
						</button>
						<button id='atributo-editar-{$linha["id"]}' type='button' class='btn btn-default' data-atributo='{$atributo}' data-idalterar='{$linha["id"]}' data-legenda='{$linha["legenda"]}' onclick='editarAlterar({$linha["id"]})' style='float:right;margin-top:-4px'>
							<span class='fas fa-edit' aria-hidden='true'></span>
						</button>
					</span>";
			}
        break;
		case 'listarstatus':
			$sql = "SELECT id,atributo atributo,operador,valor FROM td_movimentacaostatus a WHERE movimentacao = {$_GET["movimentacao"]}";
			$query = $conn->query($sql);
			if ($query->rowCount() <= 0){
				echo '<div class="alert alert-warning alert-dismissible text-center" role="alert">Nenhum atributo de <strong>status</strong> configurado.</div>';
			}
			foreach($query->fetchAll() as $linha){
				$atributo 				= $linha["atributo"];
				$operador 				= $linha["operador"];
				$valor 					= $linha["valor"];
				$sqlAtributo 			= "SELECT descricao FROM td_atributo WHERE id = " . $atributo;
				$queryAtributo 			= $conn->query($sqlAtributo);
				$linhaAtributo 			= $queryAtributo->fetch();
				$atributoDescricao 		= tdc::utf8($linhaAtributo["descricao"]);
				echo "<span class='list-group-item'>
						Atributo <strong>{$atributoDescricao}</strong> com  operador ( <strong>{$operador} ) </strong>. Valor: <small class='text-info'>{$valor}</small>.
						<button type='button' class='btn btn-default' onclick='excluirStatus({$linha["id"]});' style='float:right;margin-top:-4px'>
							<span class='fas fa-trash-alt' aria-hidden='true'></span>
						</button>
						<button id='atributo-editar-{$linha["id"]}' type='button' class='btn btn-default' data-atributo='{$atributo}' data-operador='{$operador}' data-valor='{$valor}' data-idstatus='{$linha["id"]}' onclick='editarStatus({$linha["id"]})' style='float:right;margin-top:-4px'>
							<span class='fas fa-edit' aria-hidden='true'></span>
						</button>
					</span>";
			}
        break;
        case 'listarhistorico':
            $sql = "SELECT id,atributo atributo,legenda FROM td_movimentacaohistorico a WHERE movimentacao = {$_GET["movimentacao"]}";
            $query = $conn->query($sql);
            if ($query->rowCount() <= 0){
                echo '<div class="alert alert-warning alert-dismissible text-center" role="alert">Nenhum atributo de <strong>histórico</strong> configurado.</div>';
            }
            foreach($query->fetchAll() as $linha){
                $atributo 			= $linha["atributo"];
                $legenda 			= $linha["legenda"];
                $sqlAtributo 		= "SELECT descricao,entidade FROM td_atributo WHERE id = " . $atributo;
                $queryAtributo 		= $conn->query($sqlAtributo);
                $linhaAtributo 		= $queryAtributo->fetch();
                $atributoDescricao 	= tdc::utf8($linhaAtributo["descricao"]);
                echo "<span class='list-group-item'>
                        Atributo <strong>{$atributoDescricao}</strong>
                        <button type='button' class='btn btn-default' onclick='excluirHistorico({$linha["id"]});' style='float:right;margin-top:-4px'>
                            <span class='fas fa-trash-alt' aria-hidden='true'></span>
                        </button>
                        <button id='atributo-editar-{$linha["id"]}' type='button' class='btn btn-default' data-atributo='{$atributo}' data-entidade='{$linhaAtributo["entidade"]}' data-legenda='{$legenda}' data-idhistorico='{$linha["id"]}' onclick='editarHistorico({$linha["id"]})' style='float:right;margin-top:-4px'>
                            <span class='fas fa-edit' aria-hidden='true'></span>
                        </button>
                    </span>";
            }
		break;
        case 'load':
            tdc::wj( tdc::rua(MOVIMENTACAO,tdc::r('id')) );
        break;
        case 'listar-atributos':
            echo '<option value="0" selected>Nenhum Selecionado</option>';
            $sql = "SELECT id,descricao FROM ".ATRIBUTO." WHERE entidade = " . tdc::r('_entidade');
            $query = $conn->query($sql);
            foreach($query->fetchAll() as $linha){
                echo '<option value="'.$linha["id"].'">'.tdc::utf8($linha["descricao"]).'</option>';
            }
        break;
        case 'excluir-movimentacao':
            $sql = "DELETE FROM td_movimentacao WHERE id = " . $_GET["id"];
            $conn->exec($sql);

            $sql = "DELETE FROM td_movimentacaostatus WHERE movimentacao = " . $_GET["id"];
            $conn->exec($sql);
            
            $sql = "DELETE FROM td_movimentacaoalterar WHERE movimentacao = " . $_GET["id"];
            $conn->exec($sql);

            $sql = "DELETE FROM td_movimentacaohistorico WHERE movimentacao = " . $_GET["id"];
            $conn->exec($sql);

            $sql = "DELETE FROM td_movimentacaohistoricoalteracao WHERE movimentacao = " . $_GET["id"];
            $conn->exec($sql);
        break;		
    }