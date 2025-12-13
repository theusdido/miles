<?php
    switch($_op){
        case 'listar':
            tdc::wj(tdc::da('td_associado'));
        break; 
        
        case 'procurar-pelo-id':
            $id                 = tdc::r('id');
          
            $criterio           = ($id == '') ? null : tdc::f('id','=',tdc::r('id'));

            tdc::wj(tdc::da('td_associado',$criterio));
        break;    
    }



