<?php
    $sql = "
        SELECT * FROM td_website_assembleia
        WHERE DATE_FORMAT(now(),'%Y-%m-%d') <= DATE_FORMAT(data,'%Y-%m-%d');        
    ";

    $query              = $conn->query($sql);    
    $retorno['_data']   = $query->fetchAll(PDO::FETCH_ASSOC);