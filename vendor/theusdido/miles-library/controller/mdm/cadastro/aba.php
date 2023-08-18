<?php
    switch(tdc::r('op')){
        case 'load':
            tdc::wj( tdc::rua(ABAS,tdc::r('id')));
        break;
        case 'lista-atributos-aba':
            $entidade = tdc::r('entidade');

            // Relacionamento por Generalização
            $sql = "SELECT filho FROM ".RELACIONAMENTO." WHERE pai = {$entidade} and tipo = 3";
            $query = $conn->query($sql);
            $superclasse = "";
            if ($query){
                foreach($query->fetchAll() as $linha){
                    $superclasse = " OR entidade = " . $linha["filho"];
                }
            }

            $sql = "SELECT id,descricao,nome FROM ".ATRIBUTO." WHERE entidade = {$entidade} ORDER BY ordem ASC,id ASC";
            $query = $conn->query($sql);
            foreach($query->fetchAll() as $linha){
                $selected = "";
                if (isset($_GET["atributos"])){
                    $attr = explode(",",$_GET["atributos"]);
                    foreach ($attr as $a){
                        if ($a == $linha["id"]){
                            $selected = "selected=''";
                        }
                    }
                }
                $descricao = tdc::utf8($linha['descricao']);
                $nome = tdc::utf8($linha['nome']);
                echo "<option value='{$linha['id']}' {$selected}>{$descricao} [ {$nome} ]</option>";
            }
        break;
        case 'listar-aba':
            $entidade   = tdc::r('entidade');            
            $sql        = "SELECT id,descricao,atributos FROM ".ABAS." WHERE entidade = {$entidade}";
            $query      = $conn->query($sql);
            foreach($query->fetchAll() as $linha){
                $id = $linha['id'];
                $descricao = tdc::utf8($linha['descricao']);
                echo "
                    <a id='list-item-aba-{$id}' class='list-group-item btn-link-excluir-aba'>
                        <label style='cursor:pointer' onclick='editarAba({$entidade},{$linha['id']},\"{$linha['atributos']}\");'>{$descricao}</label>
                        <button type='button' class='btn btn-default' onclick='excluirAba({$linha['id']},{$entidade});' style='float:right;margin-top:-4px'>
                            <span class='fas fa-trash-alt' aria-hidden='true'></span>
                        </button>																			
                    </a>
                ";
            }
        break;
        case 'salvar':

            // ID Último atributo
            $query_ultimo   = $conn->query("SELECT IFNULL(MAX(id),0)+1 id FROM ".ABAS);
            $linha_ultimo   = $query_ultimo->fetch();
            $idRetorno      = $linha_ultimo["id"];

            $entidade       = tdc::r('entidade');
            $descricao      = tdc::utf8($_POST["descricao"]);
            $atributos      = implode(",",$_POST["atributos"]);
            if ($_POST["id"] == ""){
                $sql = "INSERT INTO ".ABAS." (id,entidade,descricao,atributos) values ({$idRetorno},{$entidade},'{$descricao}','{$atributos}');";
                $id = $idRetorno;
            }else{
                $id = $_POST["id"];
                $sql = "UPDATE ".ABAS." SET descricao = '{$descricao}' , atributos = '{$atributos}' WHERE id = {$id};";
            }
            $query = $conn->query($sql);
            if ($query){

            }else{
                if (IS_SHOW_ERROR_MESSAGE){
                    echo $sql;
                    var_dump($conn->errorInfo());
                }
            }
        break;
        case 'excluir':
            $id = tdc::r('id');
            $sql    = "DELETE FROM ".ABAS." WHERE id = {$id}";
            $query  = $conn->query($sql);
            if ($query){
                
            }            
        break;
    }