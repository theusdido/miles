<?php
    foreach(tdc::d('td_processo') as $processo)
    {
        $option         = tdc::html('option');
        $option->value  = $processo->id;
        $option->add(completaString($processo->id,3). ' - ' . $processo->descricao . ' [ ' . $processo->numeroprocesso . ' ] ');
        $option->mostrar();
    }