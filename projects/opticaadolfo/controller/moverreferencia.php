<?php
    $pattern = '/<p>Ref.:([\s]?[0-9]+)[\.]?<\/p>/';
    foreach(tdc::d('td_ecommerce_produto') as $produto){        
        preg_match_all($pattern,$produto->descricao,$matches);
        foreach($matches as $match){
            if (sizeof($match) > 0){
                echo 'ID => ' . $produto->id .' - ';
                foreach($match as $m){
                    $produto->referencia    = $m;
                    $produto->descricao     = preg_replace($pattern,'',$produto->descricao);
                    echo $m . "<br/>------------------------------------------------------------------------------------------------------<br/>";
                }
            }
        }
        $produto->armazenar();
        echo '<br/>';
    }