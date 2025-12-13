<?php
    $curso_id   = $_data->curso;

    $criterio = tdc::f();
    $criterio->addFiltro('curso','=', $curso_id);
    $niveis = tdc::da('td_raymbo_cursonivel',$criterio);
    $retorno['_data'] = array(
        'niveis' => $niveis
    );
    
    // $niveis_agrupados = array();
    // $nivels = tdc::da('td_raymbo_cursonivel');
    // foreach($nivels as $nivel){

    //     $aulas      = array();
    //     $nivel_id   = $nivel['id'];
    //     $sql = "
    //         SELECT 
    //             a.id, 
    //             a.descricao,
    //             a.instrucoes
    //         FROM td_raymbo_cursoaula a 
    //         LEFT JOIN td_raymbo_curso b ON a.curso = b.id 
    //         WHERE b.nivel = {$nivel_id}
    //         AND a.curso = {$curso_id};
    //     ";

    //     $query = $conn->query($sql);
    //     while ($row = $query->fetch(PDO::FETCH_ASSOC)){
    //         $aulas[] = array(
    //             'id' => $row['id'],
    //             'descricao' => $row['descricao'],
    //             'instrucoes' => $row['instrucoes']
    //        );
    //     }

    //     array_push($niveis_agrupados, [
    //         'id' => $nivel['id'],
    //         'descricao' => $nivel['descricao'],
    //         'capa_src' => $nivel['capa_src'],
    //         'valor_moneyformatted' => $nivel['valor_moneyformatted'],
    //         'aulas' => $aulas
    //     ]);
    // }

    // $retorno['_data']   = array(
    //     'conteudo' => tdc::pa('td_raymbo_cursoconteudo', $conteudo_id),
    //     'niveis' => $niveis_agrupados
    // );