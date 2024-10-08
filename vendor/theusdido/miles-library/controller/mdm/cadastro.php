<?php
    switch(tdc::r('op')){
	    case 'listar-entidades':
            $_where = '';
            $termo 	= isset($_GET['termo']) ? $_GET['termo'] : '';

            if ($termo != ''){
                if (is_numeric($termo)){
                    $_where = "WHERE id = $termo";
                }else{
                    $_where = "WHERE nome LIKE '%$termo%' OR descricao LIKE '%$termo%' ";
                }
            }

            $sql = "SELECT id,nome,descricao FROM ".ENTIDADE." $_where ORDER BY id DESC";
            $query = $conn->query($sql);
            foreach ($query->fetchAll() as $linha){
                $id             = $linha["id"];
                $nome           = $linha["nome"];
                $descricao      = tdc::utf8($linha["descricao"]);

                echo "	<tr id='linha-registro-entidade-{$id}'>
                            <td>{$id}</td>
                            <td>{$descricao}</td>
                            <td>{$nome}</td>

                            <td align='center'>
                                <button type='button' class='btn btn-primary' onclick=editarCadastro({$id});>
                                    <span class='fas fa-pencil-alt' aria-hidden='true'></span>
                                </button>	
                            </td>

                            <td align='center'>
                                <button type='button' class='btn btn-warning' onclick='listarCampos({$id});'>
                                    <span class='fas fa-list-alt' aria-hidden='true'></span>
                                </button>	
                            </td>
                            
                            <td align='center'>
                                <button type='button' class='btn btn-default' onclick='goAbasCadastro({$id});'>
                                    <span class='fas fa-list-alt' aria-hidden='true'></span>
                                </button>	
                            </td>

                            <td align='center'>
                                <button type='button' class='btn btn-default' onclick='goRelacionamentos({$id},\"$descricao\");'>
                                    <span class='fas fa-sitemap' aria-hidden='true'></span>
                                </button>	
                            </td>                            

                            <td align='center'>
                                <button type='button' class='btn btn-info' onclick='gerarCadastro({$id},\"$descricao\");'>
                                    <span class='fas fa-code' aria-hidden='true'></span>
                                </button>
                            </td>
                            
                            <td align='center' >
                                <button type='button' class='btn btn-danger' onclick='excluirEntidade({$id});'>
                                    <span class='fas fa-trash-alt' aria-hidden='true'></span>
                                </button>
                            </td>
                        </tr>
                ";
            }
        break;
        case 'listar-atributos':
            echo '<option value="0" selected>Nenhum Selecionado</option>';
            $sql = "SELECT id,descricao FROM ".ATRIBUTO." WHERE entidade = " . tdc::r('_entidade');
            $query = $conn->query($sql);
            foreach($query->fetchAll() as $linha){
                echo '<option value="'.$linha["id"].'">'.tdc::utf8($linha["descricao"]).'</option>';
            }
        break;
        case 'load';
            tdc::w( Entity::getJSON(tdc::r('_entidade')) );
        break;
		case 'alterar_id':
            $_entidade      = tdc::r('_entidade');
            $_entidade_new  = tdc::r('_entidade_new');
			$conn->exec('UPDATE td_atributo SET entidade=' . $_entidade_new . ' WHERE entidade = ' . $_entidade);
			$conn->exec('UPDATE td_entidade SET id=' . $_entidade_new . ' WHERE id = ' . $_entidade);
		break;
        case 'salvar':
            $id                         = tdc::r('entidade');
            $nome			 			= isset($_POST["nome"])?$_POST["nome"]:'';		
            $descricao 					= tdc::utf8($_POST["descricao"]);
            $ncolunas					= $_POST["ncolunas"]==''?0:$_POST["ncolunas"];
            $exibirmenuadministracao 	= $_POST["exibirmenuadministracao"];
            $exibircabecalho 			= $_POST["exibircabecalho"];
            $campodescchave				= $_POST["campodescchave"]==''?'0':$_POST["campodescchave"];
            $atributogeneralizacao		= $_POST["atributogeneralizacao"]==''?'0':$_POST["atributogeneralizacao"];
            $exibirlegenda				= $_POST["exibirlegenda"];
            $criarprojeto				= $_POST["criarprojeto"];
            $criarempresa				= $_POST["criarempresa"];
            $criarauth					= $_POST["criarauth"];
            $registrounico				= $_POST["registrounico"];
            $carregarlibjavascript		= $_POST["carregarlibjavascript"];
            $tipoaba					= isset($_POST["tipoaba"])?$_POST["tipoaba"]:'';
            $entidadeauxiliar			= $_POST["entidadeauxiliar"];

            $entidade_id = criarEntidade(
                $conn,
                $nome,
                $descricao,
                $ncolunas,
                $exibirmenuadministracao,
                $exibircabecalho,
                $campodescchave,
                $atributogeneralizacao,
                $exibirlegenda,
                $criarprojeto,
                $criarempresa,
                $criarauth,
                $registrounico,
                $carregarlibjavascript,
                $criarinativo = true,
                $tipoaba = 'tabs'
            );

            tdc::wj(['id' => $entidade_id , '_data' => Entity::getJSON($entidade_id)]);
        break;

        case 'listar-campos':
            $entidade   = tdc::r('entidade');
            $sql        = "SELECT id,nome,descricao,ordem FROM ".ATRIBUTO." WHERE entidade = {$entidade} ORDER BY ordem ASC";
            $query      = $conn->query($sql);
            foreach ($query->fetchAll() as $linha){
                $id         = $linha["id"];
                $nome       = $linha["nome"];
                $descricao  = tdc::utf8($linha["descricao"]);
                $ordem      = $linha["ordem"];
                echo "
                    <tr id='linha-registro-atributo-{$id}'>
                        <td>{$id}</td>
                        <td>{$descricao}</td>
                        <td>{$nome}</td>
                        <td align='center'><input type='text' class='form-control text-center' style='width:50px' onblur='salvarOrdem({$id},this.value);' value='{$ordem}'></td>
                        <td align='center'>
                            <button type='button' class='btn btn-primary' onclick='editarCampo({$id});'>
                                <span class='fas fa-pencil-alt' aria-hidden='true'></span>
                            </button>
                        </td>
                        <td align='center'>
                            <button type='button' class='btn btn-info' onclick='abrirTransferencia({$id},\"$nome\");'>
                                <span class='fas fa-exchange-alt' aria-hidden='true'></span>
                            </button>
                        </td>
                        <td align='center'>
                            <button type='button' class='btn btn-danger' onclick='excluirCampo({$id});'>
                                <span class='fas fa-trash-alt' aria-hidden='true'></span>
                            </button>
                        </td>
                    </tr>
                ";
            }
        break;
        case 'salvar-campo':
            $_entidade  = tdc::r('entidade');
            $id         = tdc::r('atributo');
            $nome 		= $_POST["nome"];
            $descricao	= tdc::utf8($_POST["descricao"]);
            $tipo 		= $_POST["tipo"];
            $tamanho 	= $_POST["tamanho"];
            // $tamanho 	= isset($_POST["tamanho"])?$_POST["tamanho"]:0;
            // if ($tipo == "char" || $tipo == "varchar"){
            //     if ((int)$tamanho <= 0){
            //         $tamanho = "(200)";
            //     }else{
            //         $tamanho = "({$_POST["tamanho"]})";
            //     }
            // }else{
            //     $tamanho = '';
            // }
            $tamanhoSQL 			= (is_numeric($_POST["tamanho"])?$_POST["tamanho"]:0);
            // $nulo_                  = $_POST["nulo"];
            // $nulo 					= isset($nulo_)?'NULL':'NOT NULL';
            $nulo                   = $_POST["nulo"];
            $tipohtml 				= $_POST["tipohtml"];
            $exibirgradededados 	= $_POST["exibirgradededados"];
            $dataretroativa 		= $_POST["dataretroativa"];
            
            $chaveestrangeira       = $_POST["chaveestrangeira"];
            // if (isset($_POST["chaveestrangeira"])){
            //     $chaveestrangeira = ($_POST["chaveestrangeira"]=="")?0:($_POST["chaveestrangeira"]);
            // }else{
            //     $chaveestrangeira = 0;
            // }
            $indice                 = $_POST["indice"];
            
            $atributodependencia    = $_POST["atributodependencia"];
            // if (isset($_POST["atributodependencia"])){
            //     $atributodependencia = ($_POST["atributodependencia"]==""?0:$_POST["atributodependencia"]);
            // }else{
            //     $atributodependencia = 0;
            // }
            
            $labelzerocheckbox 			= $_POST["labelzerocheckbox"];
            $labelumcheckbox 			= $_POST["labelumcheckbox"];
            
            
            $desabilitar 				= $_POST["desabilitar"];
            $criarsomatoriogradededados = $_POST["criarsomatoriogradededados"];
            
            $is_unique_key				= $_POST["is_unique_key"];
            $inicializacao              = str_replace("'","\'",$_POST["inicializacao"]);
            $tipoinicializacao      = $_POST["tipoinicializacao"];
            $readonly 				= $_POST["readonly"];
            $legenda 					= $_POST["legenda"];
            $naoexibircampo				= $_POST["naoexibircampo"];


            $entidade       = tdc::e($_entidade);
            $_entidade_nome = $entidade->nome;

            $atributo       = tdc::a($id);
            $atributo_nome  = $atributo->nome;

            $_table_schema  = Conexao::getDados()['base'];            
            $sqlExisteFisicamente         = "
                SELECT 1 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = '{$_table_schema}' 
                AND TABLE_NAME = '{$_entidade_nome}' 
                AND  COLUMN_NAME = '{$nome}';
            ";
            $queryExisteFisicamente = $conn->query($sqlExisteFisicamente);           
            if ($queryExisteFisicamente->rowCount() > 0 && $id == 0){
                // Exclui o campo caso ele exista
                $conn->exec("ALTER TABLE {$_entidade_nome} DROP COLUMN {$nome};");                
            }

            $atributo_id = criarAtributo(
                $conn,
                $_entidade,
                $nome,
                $descricao,
                $tipo,
                $tamanho,
                $nulo,
                $tipohtml,
                $exibirgradededados,
                $chaveestrangeira,
                $dataretroativa,
                $inicializacao,
                $tipoinicializacao,
                $readonly,
                $legenda,
                $naoexibircampo = false
            );
            
            /*
            if ($id == 0){

                // Cria ou altera o campo no MySQL
                $sql    = "ALTER TABLE {$_entidade_nome} ADD COLUMN {$nome} {$tipo}{$tamanho} {$nulo};";                
                $conn->query($sql);

                // ID Último atributo
                $query_ultimo   = $conn->query("SELECT IFNULL(MAX(id),0)+1 id FROM ".ATRIBUTO);
                $linha_ultimo   = $query_ultimo->fetchAll();
                $id_retorno     = $linha_ultimo[0]["id"];
                
                $sql = "INSERT INTO ".ATRIBUTO." (
                    id,
                    entidade,
                    nome,
                    descricao,
                    tipo,
                    tamanho,
                    nulo,
                    tipohtml,
                    exibirgradededados,
                    chaveestrangeira,
                    dataretroativa,
                    inicializacao,
                    readonly,
                    indice,
                    tipoinicializacao,
                    atributodependencia,
                    labelzerocheckbox,
                    labelumcheckbox,
                    legenda,
                    desabilitar,
                    criarsomatoriogradededados,
                    naoexibircampo,
                    is_unique_key
                    ) VALUES (
                    {$id_retorno},
                    '{$_entidade}',
                    '{$nome}',
                    '{$descricao}',
                    '{$tipo}',
                    ".$tamanhoSQL.",
                    ".$nulo_.",
                    '{$tipohtml}',
                    {$exibirgradededados},
                    {$chaveestrangeira},
                    {$dataretroativa},
                    '{$inicializacao}',
                    {$readonly},
                    '{$indice}',
                    {$tipoinicializacao},
                    {$atributodependencia},
                    '{$labelzerocheckbox}',
                    '{$labelumcheckbox}',
                    '{$legenda}',
                    {$desabilitar},
                    {$criarsomatoriogradededados},
                    {$naoexibircampo},
                    {$is_unique_key}
                );";
                $query = $conn->query($sql);
            }else{

                $sql    = "ALTER TABLE {$_entidade_nome} CHANGE {$atributo_nome} {$nome} {$tipo}{$tamanho} {$nulo};";
                $conn->query($sql);

                $id_retorno = $id;
                $sql = ("UPDATE ".ATRIBUTO."
                    SET 
                    entidade='{$_entidade}',
                    nome='{$nome}',
                    descricao='{$descricao}',
                    tipo='{$tipo}',
                    tamanho={$tamanhoSQL},
                    nulo=".$nulo_.",
                    tipohtml = '{$tipohtml}',
                    exibirgradededados = {$exibirgradededados},
                    chaveestrangeira = {$chaveestrangeira},
                    dataretroativa = {$dataretroativa},
                    inicializacao = '{$inicializacao}',
                    readonly = {$readonly},
                    indice = '{$indice}',
                    tipoinicializacao = {$tipoinicializacao},
                    atributodependencia = {$atributodependencia},
                    labelzerocheckbox = '{$labelzerocheckbox}',
                    labelumcheckbox = '{$labelumcheckbox}',
                    legenda = '{$legenda}',
                    desabilitar = $desabilitar,
                    criarsomatoriogradededados = $criarsomatoriogradededados,
                    naoexibircampo = {$naoexibircampo},
                    is_unique_key = {$is_unique_key}
                    WHERE id = {$id};
                ");
                $query = $conn->query($sql);
            }
            
            $error = $conn->errorInfo();
            if ($error[0] != "00000"){
                if (IS_SHOW_ERROR_MESSAGE){
                    var_dump($error);
                }
            }else{
                tdc::wj(['id' => $id , '_data' => Field::getJSON($id)]);
            }
            */
            tdc::wj(['id' => $atributo_id , '_data' => Field::getJSON($atributo_id)]);
        break;
        case 'load-atributo';
            tdc::w( Field::getJSON(tdc::r('_atributo')) );
        break;
        case 'excluir-campo':
            $atributo   = tdc::a(tdc::r('_atributo'));
            $entidade   = tdc::e($atributo->entidade);            
            
            try {
                $sql    = "ALTER TABLE {$entidade->nome} DROP COLUMN {$atributo->nome};";
                $query  = $conn->query($sql);
            }catch(Exception $e){
                
            }
        
            $sql    = "DELETE FROM ".ATRIBUTO." WHERE id = {$atributo->id};";
            $query  = $conn->query($sql);
        break;
        case 'listar-cadastro-option':
            echo '<option value="0" selected>Nenhum Selecionado</option>';
            $sql = "SELECT id,descricao FROM ".ENTIDADE;
            $query = $conn->query($sql);
            foreach($query->fetchAll() as $linha){
                echo '<option value="'.$linha["id"].'">'.tdc::utf8($linha["descricao"]).'</option>';
            }
        break;
        case 'transferir-campo':

            // Verifica se o campo já existe na entidade destino
            $verificaSQL    = "SELECT 1 FROM ".ATRIBUTO." WHERE entidade = " . $_GET["entidade"] . " AND nome = '".$_GET["nome"]."';";
            $verificaQUERY  = $conn->query($verificaSQL);
            if ($verificaQUERY->rowCount() <= 0 ){
                
                // Seleciona os dados do atributo de origem			
                $atributoAtualSQL   = "
                    SELECT (
                        SELECT nome FROM ".ENTIDADE." b WHERE a.entidade = b.id ) nomeentidade,
                        a.nome,
                        a.descricao,
                        a.tipo,
                        a.tamanho,
                        a.nulo,
                        a.tipohtml,
                        a.exibirgradededados,
                        a.chaveestrangeira,
                        a.dataretroativa,
                        a.inicializacao                         
                    FROM " . ATRIBUTO . " a,".ENTIDADE." b 
                    WHERE a.entidade = b.id AND a.id=" . $_GET["atributo"];
                $atributoAtualQUERY = $conn->query($atributoAtualSQL);			
                if ($atributoAtualQUERY->rowCount() > 0){
                    $atributoAtualLINHA = $atributoAtualQUERY->fetch();
                    // Verifica se o atributo existe fisicamente
                    $sqlExisteFisicamente = "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$atributoAtualLINHA["nomeentidade"]}' AND  COLUMN_NAME = '{$_GET["nome"]}';";
                    $queryExisteFisicamente = $conn->query($sqlExisteFisicamente);
                    if ($queryExisteFisicamente->rowCount() > 0){
                        // Exclui o atributo fisicamente
                        $alterFisico = "ALTER TABLE {$atributoAtualLINHA["nomeentidade"]} DROP COLUMN {$_GET["nome"]};";
                        $queryAlterFisico = $conn->query($alterFisico);
                    }
                
                    // Cria o atributo no destino
                    criarAtributo(
                        $conn,
                        $_GET["entidade"],
                        $atributoAtualLINHA["nome"],
                        $atributoAtualLINHA["descricao"],
                        $atributoAtualLINHA["tipo"],
                        $atributoAtualLINHA["tamanho"],
                        $atributoAtualLINHA["nulo"],
                        $atributoAtualLINHA["tipohtml"],
                        $atributoAtualLINHA["exibirgradededados"],
                        $atributoAtualLINHA["chaveestrangeira"],
                        $atributoAtualLINHA["dataretroativa"],
                        $atributoAtualLINHA["inicializacao"]
                    );
                    
                    // Exclui atributo da entidade atual
                    $atualizaSQL    = "DELETE FROM " . ATRIBUTO . " WHERE id = " . $_GET["atributo"];
                    $atualizaQUERY  = $conn->query($atualizaSQL);
                    if ($atualizaQUERY){
                        echo 1;
                    }else{
                        echo 5;
                    }
                }else{
                    // Atributo não existe mais na entidade de origem
                    echo 3;
                    exit;
                }
            }else{
                echo 2;
            }
        break;
        case 'salvar-ordem':
            $sql = "UPDATE " . ATRIBUTO . " SET ordem = {$_GET["ordem"]} WHERE id = {$_GET["id"]}";
            $query = $conn->exec($sql);    
        break;
        case 'excluir-entidade':
            $entidade   = $_GET["entidade"];
            $linha_nome = $conn->query("SELECT nome FROM ".ENTIDADE." WHERE id = {$entidade};")->fetchAll();	
            if (sizeof($linha_nome) <= 0){
                echo 'Entidade n&atilde;o encontrada';
                exit;
            }
            
            $sql_atributos = "SELECT id,nome FROM " . ATRIBUTO . " WHERE entidade = " . $entidade.";";
            $query_atributos = $conn->query($sql_atributos);
            foreach($query_atributos->fetchAll() as $linha_atributos){
                try{
                    $sql = "DELETE FROM ".ATRIBUTO." WHERE id = {$linha_atributos["id"]};";
                    $query = $conn->query($sql);

                    $sql = "ALTER TABLE {$linha_nome[0]["nome"]} DROP {$linha_atributos["nome"]};";
                    $query = $conn->query($sql);
                    if ($query){
        
                    }
                }catch(Exception $e){
                }
            }
            
            $sql_drop = "DROP TABLE IF EXISTS {$linha_nome[0]["nome"]}";
            $conn->query($sql_drop);
            
            $sql_delete = "DELETE FROM " . ENTIDADE . " WHERE id = " . $entidade;
            $conn->query($sql_delete);

            $sql_relacionamentos = "DELETE FROM " . RELACIONAMENTO . " WHERE pai = {$entidade} OR filho = {$entidade}";
            $conn->query($sql_relacionamentos);
        break;
        case 'listar-option-entidade':
            echo '<option value="0" selected>Nenhum Selecionado</option>';
            $sql    = "SELECT id,descricao FROM ".ENTIDADE;
            $query  = $conn->query($sql);
            foreach($query->fetchAll() as $linha){
                echo '<option value="'.$linha["id"].'">'.tdc::utf8($linha["descricao"]).'</option>';
            }
        break;
    }