<?php
    $entidade = tdc::r('entidade');
    $firebase = new Firebase();

    switch($entidade){
        case 'td_lista':
            $firebase->del($entidade);
            foreach(tdc::da($entidade) as $d){
                $firebase->add(array(
                    'id'            => $d['id'],
                    'entidadepai'   => $d['entidadepai'],
                    'entidadefilho' => $d['entidadefilho'],
                    'regpai'        => $d['regpai'],
                    'regfilho'      => $d['regfilho'],
                    'regfilho_obj'  => tdc::dua(tdc::e($d['entidadefilho'])->nome,$d['regfilho'])
                ),$entidade . '/' . $d['id']);
            }
        break;
        case 'delete-all':
            
        break;
        default:

            try{
                $firebase->del($entidade);
                foreach(tdc::da($entidade) as $d){
                    $id_    = $d['id'];
                    $ref_   = $entidade . '/' . $id_;
                    $firebase->add($d,$ref_);
                }
            }catch(Exception $e){
                echo $e->getMessage();
            }

    }