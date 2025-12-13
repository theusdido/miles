<?php
    switch(tdc::r('op')){
        case 'recuperandas':
            $sql    = "
                SELECT 
                    *,
                    (SELECT c.descricao FROM td_comarca c WHERE b.comarca = c.id) comarca_desc,
                    (SELECT c.estado FROM td_comarca c WHERE b.comarca = c.id) comarca_estado,
                    (SELECT d.descricao FROM td_processostatus d WHERE d.id = b.status) status_desc,
                    a.id recuperanda_id,
                    b.id processo_id,
                    (SELECT e.descricao FROM td_juizo e WHERE e.id = b.juizo) juizo_desc,
                    (SELECT f.nome FROM td_magistrado f WHERE f.id = b.magistrado) magistrado_nome,
                    b.link
                FROM td_recuperanda a
                INNER JOIN td_processo b ON b.id = a.processo
                WHERE a.is_show_website = true
                ".(tdc::r('_id') != '' ? 'AND a.id = ' . tdc::r('_id') : '')."
                ORDER BY processo_id DESC;
            ";
            $dt     = $conn->query($sql);
            $rs     = $dt->fetchAll(PDO::FETCH_ASSOC);
            $_data  = array_map(function($e){
                $r = tdc::pa('td_recuperanda',$e['recuperanda_id']);
                $p = tdc::pa('td_processo',$e['processo_id']);
                
                $_arquivos_filtro           = tdc::f();
                $_arquivos_filtro->addFiltro('processo','=',$e['processo_id']);
                $_arquivos_filtro->order('tipo');

                $e['logo_src']            = $r['logo_src'];
                $e['datapedido']          = $r['datapedido_formated'];
                $e['datainiciohabdiv']    = $p['datainiciohabdiv_formated'];
                $e['datainiciohabimp']    = $p['datainiciohabimp_formated'];
                $e['arquivos']            = tdc::da('td_processodocumento',$_arquivos_filtro);

                $e['comarca_estado']        = tdc::utf8($e['comarca_estado']);
                $e['razaosocial']           = tdc::utf8($e['razaosocial']);
                $e['comarca_desc']          = tdc::utf8($e['comarca_desc']);
                $e['status_desc']           = tdc::utf8($e['status_desc']);
                $e['complemento']           = tdc::utf8($e['complemento']);
                $e['escrivao']              = tdc::utf8($e['escrivao']);
                $e['juizo_desc']            = tdc::utf8($e['juizo_desc']);
                $e['logradouro']            = tdc::utf8($e['logradouro']);
                $e['magistrado_nome']       = tdc::utf8($e['magistrado_nome']);
                $e['descricao']             = tdc::utf8($e['descricao']);
                $e['bairro']                = tdc::utf8($e['bairro']);


                return $e;
            },$rs);

            $_fp = fopen(PATH_CURRENT_WS_SERVICE.'processos/recuperandas.json','w');
            fwrite($_fp,json_encode($_data));
            fclose($_fp);
        break;
        case 'falidas':
            $sql    = "
                SELECT 
                    *,
                    (SELECT c.descricao FROM td_comarca c WHERE b.comarca = c.id) comarca_desc,
                    (SELECT c.estado FROM td_comarca c WHERE b.comarca = c.id) comarca_estado,
                    (SELECT d.descricao FROM td_processostatus d WHERE d.id = b.status) status_desc,
                    a.id falida_id,
                    b.id processo_id,
                    (SELECT e.descricao FROM td_juizo e WHERE e.id = b.juizo) juizo_desc,
                    (SELECT f.nome FROM td_magistrado f WHERE f.id = b.magistrado) magistrado_nome,
                    b.link
                FROM td_falencia a
                INNER JOIN td_processo b ON b.id = a.processo
                WHERE a.is_show_website = true
                ".(tdc::r('_id') != '' ? 'AND a.id = ' . tdc::r('_id') : '')."
                
            ";
            $dt     = $conn->query($sql);
            $rs     = $dt->fetchAll(PDO::FETCH_ASSOC);
            $_data  = array_map(function($e){
                $r = tdc::pa('td_falencia',$e['falida_id']);
                $p = tdc::pa('td_processo',$e['processo_id']);

                $_arquivos_filtro           = tdc::f();
                $_arquivos_filtro->addFiltro('processo','=',$e['processo_id']);
                $_arquivos_filtro->order('tipo');

                $e['logo_src']              = $r['logo_src'];
                $e['datasentenca']          = $r['datasentenca_formated'];
                $e['datainiciohabdiv']      = $p['datainiciohabdiv_formated'];
                $e['datainiciohabimp']      = $p['datainiciohabimp_formated'];
                $e['arquivos']              = tdc::da('td_processodocumento',$_arquivos_filtro);

                $e['comarca_estado']        = tdc::utf8($e['comarca_estado']);
                $e['razaosocial']           = tdc::utf8($e['razaosocial']);
                $e['comarca_desc']          = tdc::utf8($e['comarca_desc']);
                $e['status_desc']           = tdc::utf8($e['status_desc']);
                $e['complemento']           = tdc::utf8($e['complemento']);
                $e['escrivao']              = tdc::utf8($e['escrivao']);
                $e['juizo_desc']            = tdc::utf8($e['juizo_desc']);
                $e['logradouro']            = tdc::utf8($e['logradouro']);
                $e['magistrado_nome']       = tdc::utf8($e['magistrado_nome']);
                $e['descricao']             = tdc::utf8($e['descricao']);
                $e['bairro']                = tdc::utf8($e['bairro']);

                return $e;
            },$rs);
            var_dump($_data);
            $_fp = fopen(PATH_CURRENT_WS_SERVICE.'processos/falidas.json','w');
            fwrite($_fp,json_encode($_data));
            fclose($_fp);                  
        break;
    }