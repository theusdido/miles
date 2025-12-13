<?php
    $entidade = tdc::r('entidade');
    $firebase = new Firebase();

    switch($entidade){
        case 'td_lista':
            $sql    = 'SELECT * FROM td_lista';
            $ds     = $conn->query($sql);
            $rs     = $ds->fetchAll(PDO::FETCH_ASSOC);
            $firebase->add($rs,$entidade);
        break;
        default:
            foreach(tdc::da($entidade) as $d){
                $firebase->add($d,$entidade . '/' . $d['id']);
            }
    }
    