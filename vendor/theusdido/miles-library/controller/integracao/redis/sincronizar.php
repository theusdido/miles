<?php
    $entidade = tdc::r('entidade');
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);

    switch($entidade){
        case 'td_lista':
            $redis->del($entidade);
            foreach(tdc::da($entidade) as $d){
                $redis->hSet($entidade, $d['id'], json_encode(array(
                    'id'            => $d['id'],
                    'entidadepai'   => $d['entidadepai'],
                    'entidadefilho' => $d['entidadefilho'],
                    'regpai'        => $d['regpai'],
                    'regfilho'      => $d['regfilho'],
                    'regfilho_obj'  => tdc::dua(tdc::e($d['entidadefilho'])->nome,$d['regfilho'])
                )));
            }
        break;
        case 'delete-all':
            
        break;
        default:
            try{
                $_redis->setAll($entidade);
            }catch(Exception $e){
                echo $e->getMessage();
            }

    }