<?php

    $connProd       = Conexao::Abrir('producao');

    $op             = tdc::r('op');
    $de_entidade    = tdc::r('de');
    $para_entidade  = tdc::r('para');
    $entidade       = tdc::r('entidade');
    $charset        = 6;

    if ($entidade != ''){
        $de_entidade    = 'td_' . $entidade;
        $para_entidade  = 'td_' . $entidade;
    }
    
    switch($op){
        case 'default':
            // Limpa a tabela destino
            $conn->exec("TRUNCATE TABLE {$para_entidade};");

            // Usuário
            if ($entidade == 'usuario')
            {
                $conn->exec('ALTER TABLE '.$para_entidade.' CHANGE login login VARCHAR(200) NULL;');
            }

            try{
                // Seleciona os dados da tabela de origem
                $sql        = "SELECT * FROM {$de_entidade};";
                $query      = $connProd->query($sql);
            }catch(Throwable $t){
                if (IS_SHOW_ERROR_MESSAGE){
                    echo $sql;
                    var_dump($t);
                }
            }

            // Adiciona registros na tabela de destino
            while ($linha = $query->fetch(PDO::FETCH_ASSOC))
            {
                $para = tdc::p($para_entidade);
                foreach($linha as $key => $value){
                    
                    $value      = tdc::utf8($value,6);
                    $field_old  = $key;
                    $field_new  = str_replace('td_','',$key);

                    if ($field_new == 'criarsomotoriogradededados' && $de_entidade == 'td_atributo')
                    {
                        $para->criarsomatoriogradededados = $value;
                        continue;
                    }

                    if ($field_new == 'text' && $de_entidade == 'td_website_geral_blog')
                    {
                        $para->texto = $value;
                        continue;
                    }

                    if ($field_new == 'email' && $de_entidade == 'td_usuario')
                    {
                        $para->login = $value;
                        continue;
                    }

                    if ($field_new == 'conteudo' && $de_entidade == 'td_quemsomos')
                    {
                        $para->texto = $value;
                        continue;
                    }

                    if ($field_new == 'tipooperacaoestoque' && $de_entidade == 'td_ecommerce_statuspedido')
                    {
                        $para->operacaoestoque = $value;
                        continue;
                    }

                    if ($field_new == 'qtdetotalitens' && $de_entidade == 'td_ecommerce_pedido')
                    {
                        $para->qtdetotalitens = $value;
                        continue;
                    }
                    
                    if ($field_new == 'carrinhodecompras' && $entidade == 'ecommerce_pedido')
                    {
                        $para->carrinhocompras = $value;
                        continue;
                    }

                    if ($field_new == 'inscricao' && $entidade == 'td_ecommerce_cliente')
                    {
                        $para->inscricaoestadual = $value;
                        continue;
                    }

                    $para->{$field_new} = $value;
                    //echo "$field_new => $value <br/> ----------------------------------------------- <br/>";
                }
                $para->armazenar();
            }

        break;
        default:
            include 'migracao/' . $op . '.php';
    }

    Transacao::Commit();
    echo ':: FIM ::';