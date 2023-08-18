<?php
    switch(tdc::r('op')){
        case 'entidade-option':
            echo '<option value="0">Selecione</option>';
            $sql    = "SELECT id,nome,descricao FROM ".ENTIDADE." ORDER BY id DESC;";
            $query  = $conn->query($sql);
            foreach($query->fetchAll() as $linha){
                $descricao = tdc::utf8($linha["descricao"]);
                echo '<option value="'.$linha["id"].'">[ '.$linha["id"].' ] '. $descricao .' [ '.$linha["nome"].' ]</option>';
            }
        break;
        case 'atributo-option':
			echo '<option value="0"></option>';		
			$sql    = "SELECT id,descricao,nome FROM ".ATRIBUTO." WHERE entidade = " . $_GET["entidade"];
			$query  = $conn->query($sql);
			foreach($query->fetchAll() as $linha){
                $descricao = tdc::utf8($linha["descricao"]);
				echo '<option value="'.$linha["id"].'">'.$descricao.' [ '.$linha["nome"].' ]</option>';
			}            
        break;
        case 'listar-relacionamento':
            $entidade   = tdc::r('entidade');
            $sql        = "SELECT a.id,a.filho,a.pai pai,a.tipo,a.descricao,(SELECT b.descricao FROM ".ENTIDADE." b WHERE a.filho = b.id) as entidade_nome FROM ".RELACIONAMENTO." a WHERE a.pai = {$entidade}";
            $query      = $conn->query($sql);
            foreach($query->fetchAll() as $linha){
                $tipo_nome = "";
                switch ($linha["tipo"]){
                    case 1:
                        $tipo_nome = "Associa&ccedil;&atilde;o - Agrega&ccedil;&atilde;o (1:1)";
                    break;
                    case 2:
                        $tipo_nome = "Associa&ccedil;&atilde;o - Composi&ccedil;&atilde;o (1:N)";
                    break;
                    case 3:
                        $tipo_nome = "Generaliza&ccedil;&atilde;o/Especializa&ccedil;&atilde;o (1:1)";
                    break;
                    case 4:
                        $tipo_nome = "Depend&ecirc;ncia";
                    break;
                    case 5:
                        $tipo_nome = "Associa&ccedil;&atilde;o - Agrega&ccedil;&atilde;o (N:N)";
                    break;
                    case 6:
                        $tipo_nome = "Associa&ccedil;&atilde;o - Agrega&ccedil;&atilde;o (1:N)";
                    break;
                    case 7:
                        $tipo_nome = "Associa&ccedil;&atilde;o - Composi&ccedil;&atilde;o (1:1)";
                    break;
                    case 8:
                        $tipo_nome = "Generaliza&ccedil;&atilde;o/Especializa&ccedil;&atilde;o (1:N)";
                    break;
                    case 9:
                        $tipo_nome = "Generaliza&ccedil;&atilde;o/Especializa&ccedil;&atilde;o Múltipla (1:1)";
                    break;
                    case 10:
                        $tipo_nome = "Associa&ccedil;&atilde;o - Composi&ccedil;&atilde;o (N:N)";
                    break;
                    case 11:
                        $tipo_nome = "11 - Checklist Associa&ccedil;&atilde;o - Agrega&ccedil;&atilde;o (1:N)";
                    break;                    
                }
            
                $descricao      = $linha['descricao'];
                $entidade_nome  = $linha['entidade_nome'];
                $id             = $linha['id'];

                echo "<a class='list-group-item'>
                        Relacionamento de <strong>{$tipo_nome}</strong> com <strong>{$entidade_nome}</strong>. <small class='text-info'>{$descricao}</small>.
                        <button type='button' class='btn btn-default' onclick='excluirRelacionamento({$id})' style='float:right;margin-top:-4px'>
                            <span class='fas fa-trash-alt' aria-hidden='true'></span>
                        </button>
                        <button type='button' class='btn btn-default' onclick='editarRelacionamento({$id})' style='float:right;margin-top:-4px'>
                            <span class='fas fa-edit' aria-hidden='true'></span>
                        </button>
                    </a>";
            }    
        break;
        case 'salvar':
            $id			 			= $_POST["id"];
            $tipo 					= $_POST["tipo"];
            $entidade	 			= $_POST["entidade"];
            $entidadefilho			= $_POST["entidadefilho"];
            $atributo 				= isset($_POST["atributo"])?($_POST["atributo"]==""?"0":$_POST["atributo"]):"0";
            $descricao				= tdc::utf8($_POST["descricao"]);

            switch($tipo){
                case 1: case 7: case 3: case 9: $cardinalidade = "11"; break;
                case 6: case 2: case 8: $cardinalidade = "1N"; break;
                case 5: case 10: $cardinalidade = "NN"; break;
                default: $cardinalidade = "";
            }
    
            if ($id == 0){
                $query_prox     = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM ".RELACIONAMENTO);
                $prox           = $query_prox->fetch();
                $sql            = "INSERT INTO ".RELACIONAMENTO." (id,pai,tipo,filho,atributo,descricao,cardinalidade) VALUES ({$prox[0]},{$entidade},'{$tipo}','{$entidadefilho}',{$atributo},'{$descricao}','{$cardinalidade}');";
            }else{
                $sql            = "UPDATE ".RELACIONAMENTO." SET pai = {$entidade} , tipo = {$tipo} , filho = {$entidadefilho} , atributo = {$atributo} , descricao = '{$descricao}' , cardinalidade = '{$cardinalidade}' WHERE id = {$id};";
            }

            $query = $conn->query($sql);
            if($query){

            }else{
                if (IS_SHOW_ERROR_MESSAGE){
                    echo $sql;
                    var_dump($conn->errorInfo());
                }
                exit;
            }
        break;
        case 'load';
            tdc::wj( tdc::rua(RELACIONAMENTO,tdc::r('id')));
        break;
        case 'excluir':
            $sql = "DELETE FROM ".RELACIONAMENTO." WHERE id = {$_GET["id"]};";
            $query = $conn->query($sql);
            if ($query){

            }	            
        break;
    }