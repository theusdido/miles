<?php

    function retornaEstado($sigla){
        $retorno = 0;
        if ($conn = Transacao::Get()){
            $sql 		= "SELECT id FROM td_estado WHERE sigla = '$sigla'";
            $query 		= $conn->query($sql);
            $linha 		= $query->fetch();
            $retorno 	= $linha["id"];
        }else{
            $retorno = 0;
        }
        return $retorno;
    }	
    function trocavazio($str,$replace = ""){
        return trim(str_replace("#",$replace,$str));
    }
    function msgErroValidacao($erroMSG,$td){
        global $error;
        echo '<tr>' . $td . '<tr/>';
        echo '<tr><td colspan="20"><div class="alert alert-danger" role="alert"><small>Erro('.$error.'):</small> '.$erroMSG.'</div></td></tr>';
        hideProgressBar();
    }
    function msgError($msg){
        echo '<div class="alert alert-danger" role="alert">'.$msg.'</div>';
    }
    function validaColuna($celula,$indice){
        if (isset($celula[$indice])){
            if ($celula[$indice]->Data == '#'){
                return '';
            }else{
                return $celula[$indice]->Data;
            }
        }else{
            return '';
        }
    }

    function getCidadeId($_cidade,$_estado)
    {
        if (is_numeric($_cidade) && $_cidade != 0){
            return $_cidade;
        }
        global $conn;
        $sql = "
            SELECT a.id FROM td_cidade a 
            INNER JOIN td_estado b ON b.id = a.estado
            WHERE a.nome = '$_cidade' AND b.sigla = '$_estado';
        ";
        $query = $conn->query($sql);
        if ($query->rowCount() > 0){
            $row = $query->fetch();
            return $row['id'];
        }else{
            $prox_id 			= getProxId('td_cidade');
            $cidade 			= tdc::p('td_cidade');
            $cidade->id			= $prox_id;
            $cidade->nome 		= $_cidade;
            $cidade->estado 	= tdc::du('td_estado',tdc::f('sigla','=',$_estado))->id;

            return $prox_id;
        }
    }

    function verificaLayout($_linhas){
        $cLinha = 0;
        foreach ($_linhas as $cell){
            $cLinha++;
            #$codigo = $cell->Cell[0]->Data;
            #if ($cLinha<=1 || $codigo=="") continue; # Pula a primeira linha

            #for($i=0;$i<=18;$i++){
                //validaColuna($cell->Cell,$i);
            #}
        }
    }

    function setErrorImportacao($_msg){
        showMessage($_msg);
        hideProgressBar();

        $btn_voltar 			= tdc::html('button');
        $btn_voltar->class 		= 'btn btn-link';
        $btn_voltar->onclick	= "parent.carregar('index.php?controller=importarRelacaoCredoresExecel','#conteudoprincipal');";
        $btn_voltar->add('Voltar');
        $btn_voltar->mostrar();        
    }

    function hideProgressBar(){
        $script = tdc::html('script');
        $script->add('				
            parent.document.querySelector("#progress-importar-credores").style.display = "none";
        ');
        $script->mostrar();
    }