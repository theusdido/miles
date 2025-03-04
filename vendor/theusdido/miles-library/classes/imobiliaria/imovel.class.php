<?php
    class Imovel {

        public static function Destaque(){
            global $conn;
        
            $sql = self::getFieldsView() . "
                WHERE lancamento = 1
                OR ofertasemana = 1
                ORDER BY imovel DESC;
            ";
        
            $rs = $conn->query($sql);
            return self::EnderecoRow($rs);
        }

        public static function Home(){
            global $conn;
            $sql = self::getFieldsView() . "
                WHERE lancamento <> 1
                OR ofertasemana <> 1
                ORDER BY imovel DESC
                LIMIT 4;
            ";
            $rs = $conn->query($sql);
            return self::EnderecoRow($rs);
        }

        private static function EnderecoRow($result_set){
            $_res                   = array();
            while ($row = $result_set->fetch(PDO::FETCH_ASSOC)){

                if ($row['bairro'] != NULL){
                    $bairro         = tdc::pa('td_imobiliaria_bairro',$row['bairro']);
                    $cidade         = tdc::pa('td_imobiliaria_cidade',$bairro['cidade']);
                    $estado         = tdc::pa('td_imobiliaria_estado',$cidade['estado']);

                    $row['bairro_desc']         = $bairro['nome'];
                    $row['cidade_desc']         = $cidade['nome'];
                    $row['estado_desc']         = $estado['nome'];
                    $row['estado_sigla']        = $estado['sigla'];
                    $row['localizacao_curta']   = $bairro['nome'] . ' / ' . $row['cidade_desc'];
                }else{
                    $row['bairro_desc']         = '';
                    $row['cidade_desc']         = '';
                    $row['estado_desc']         = '';
                    $row['estado_sigla']        = '';
                    $row['localizacao_curta']   = '';
                }

                array_push($_res,$row);
            }
            return $_res;
        }

        public static function Get($imovel_id){
            global $conn;

            $sql = self::getFieldsView() . "
                WHERE imovel = {$imovel_id};
            ";

            $rs     = $conn->query($sql);
            $_res   = self::EnderecoRow($rs); 
            return $_res[0];
        }

        public static function List(){
            global $conn;

            $sql = self::getFieldsView() . "
                ORDER BY imovel DESC
                LIMIT 10;
            ";

            $rs = $conn->query($sql);
            return self::EnderecoRow($rs);
        }

        public static function getFieldsView(){
            return '
               SELECT                                 
                    # Imovel
                    imovel,
                    empreendimento,
                    empreendimento_desc,
                    administradoracondominio_desc,
                    tipoimovel,
                    tipoimovel_desc,
                    tipopiso,
                    mobiliado,
                    mobiliado_desc,
                    valoraluguel,
                    valoraluguel_moneyformatted,
                    descricao,
                    longitutelatitute,
                    finalidade,
                    lancamento,
                    ofertasemana,
                    fotocapa,
                    fotocapa_src,
                    areaconstruida,
                    areaprivativa,
                    areatotal,
                    areautil,

                    # Endereço
                    endereco_imovel,
                    numero,
                    complemento,
                    tipoendereco,
                    tipoendereco_desc,
                    logradouro,
                    cep,
                    bairro
                FROM
                    imovel_list                
            ';
        }
    }