<?php    
    $home_js = tdc::o('script');
    $home_js->add('
        $(document).ready(function(){
            loadPage("home","#conteudoprincipal");
        });
    ');
    $home_js->mostrar();