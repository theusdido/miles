<?php
    
    $op = tdc::r('op');
    switch($op){
        case 'upload':
            set_time_limit(3600);
            $link = PATH_CURRENT_FILE_TEMP . 'demonstravio-'. date('Y-m-d h:i:s') . ".txt";
            Session::append("DEMONSTRATIVO_TXT_FILE",$link);
            if (file_exists($link)){
                unlink($link);
            }
            try{
                $uploaded   = move_uploaded_file(tdc::r("arquivo")["tmp_name"],$link);
            }catch(Throwable $e){
                echo $e->getMessage();
            }

            if (file_exists($link)){
                $config = file ($link);
                $demonstrativo_configuracoes = tdc::ru('td_demonstrativo_configuracoes')->is_apagar_importacao;                
                if ($demonstrativo_configuracoes == 1){
                    Transacao::get()->exec("
                        TRUNCATE TABLE td_demonstrativo;
                        TRUNCATE TABLE td_demonstrativo_totais;
                        TRUNCATE TABLE td_demonstrativo_locador;
                        TRUNCATE TABLE td_demonstrativo_contrato;
                        TRUNCATE TABLE td_demonstrativo_locatario;
                        TRUNCATE TABLE td_demonstrativo_imovel;
                        TRUNCATE TABLE td_demonstrativo_evento;
                    ");
                }
                echo sizeof($config);
            }else{
                echo 0;
            }
        break;
        case 'salvar':
            $link = Session::Get('DEMONSTRATIVO_TXT_FILE');
            if (!file_exists($link)) {
                echo "Arquivo não encontrado!";
                exit;
            }
            
            Transacao::abrir("current");
            try {
                $linhas = file($link);
                $current_locador_codigo = isset($_GET['locador']) ? $_GET['locador'] : 0;
                $curernt_contrato_numero = isset($_GET['contrato']) ? $_GET['contrato'] : 0;
                $demonstrativo = null;
                $contratos = [];
                $is_success = false;

                
                $linha_str = file($link)[$_GET['indice']];
                $linha = str_getcsv($linha_str, '^');

                if (count($linha) < 20) exit;
    
                $locador_codigo     = $linha[1];
                $locador_nome       = $linha[2];
                $contrato_numero    = $linha[5];                
                if ($current_locador_codigo !== $locador_codigo || $current_locador_codigo == 0) {

                    // Novo Demonstrativo
                    $demonstrativo                                  = tdc::p('td_demonstrativo');
                    $demonstrativo->data_emissao                    = date("Y-m-d");
                    $demonstrativo->data_emissao_dateformatted      = date("d/m/Y");
                    $demonstrativo->link                            = md5($locador_codigo . date('Ym'));
                    $demonstrativo->inativo                         = false;
                    $demonstrativo->enviada                         = false;
                    $demonstrativo->anomes                          = $linha[0];
                    $demonstrativo->locador_codigo                  = $locador_codigo;
                    $demonstrativo->locador_nome                    = $locador_nome;

                    if (!$demonstrativo->armazenar()){
                        $retorno = array(
                            'status' => 2,
                            'msg' => 'Erro ao salvar demonstrativo!'
                        );
                        Transacao::rollback();
                        exit;
                    }

                    $demonstrativo_id           = $demonstrativo->id;
    
                    $locador                    = tdc::p('td_demonstrativo_locador');
                    $locador->codigo            = $locador_codigo;
                    $locador->nome              = $locador_nome;
                    $locador->endereco          = $linha[3];
                    $locador->email             = $linha[4];
                    $locador->inativo           = false;
                    $locador->demonstrativo     = $demonstrativo_id;
                    $is_success = $locador->armazenar();
                }else{
                    $contrato_demonstrativo = tdc::du("td_demonstrativo_contrato",tdc::f('numero','=',$curernt_contrato_numero));
                    $demonstrativo          = tdc::p('td_demonstrativo',$contrato_demonstrativo->demonstrativo);   
                    $demonstrativo_id       = $demonstrativo->id;
                }

                $contrato                           = tdc::p('td_demonstrativo_contrato');
                $contrato->numero                   = $contrato_numero;
                $contrato->mes_referencia           = $linha[6];
                $contrato->mes_garantia             = $linha[7];
                $contrato->inicio_contrato          = $linha[8];
                $contrato->proximo_reajuste         = $linha[9];
                $contrato->forma_pagamento          = $linha[10];
                $contrato->pasta                    = $linha[11];
                $contrato->demonstrativo            = $demonstrativo_id;
                $contrato->inativo                  = false;
                $is_success = $contrato->armazenar();

                $contrato_id                        = $contrato->id;
    
                $locatario                          = tdc::p('td_demonstrativo_locatario');
                $locatario->codigo                  = $linha[12];
                $locatario->nome                    = $linha[13];                    
                $locatario->contrato                = $contrato_id;
                $is_success = $locatario->armazenar();

                $locatario_id                       = $locatario->id;
                $contrato->locatario                = $locatario_id;

                $imovel                             = tdc::p('td_demonstrativo_imovel');
                $imovel->codigo                     = $linha[14];
                $imovel->endereco                   = $linha[15];
                $imovel->contrato                   = $contrato_id;
                $imovel->inativo                    = false;
                $is_success = $imovel->armazenar();

                $imovel_id                          = $imovel->id;
                $contrato->imovel                   = $imovel_id;
    
                $eventos_str                        = $linha[16];
                $eventos_json                       = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $eventos_str), true);                

                if (is_array($eventos_json)) {
                    foreach ($eventos_json as $e) {
                        $evento                 = tdc::p('td_demonstrativo_evento');
                        $evento->codigo         = $e['codigo'] ?? '';
                        $evento->descricao      = $e['descricao'] ?? '';
                        $evento->debito         = (float)str_replace(',', '.', $e['debito'] ?? 0);
                        $evento->credito        = (float)str_replace(',', '.', $e['credito'] ?? 0);
                        $evento->saldo          = (float)str_replace(',', '.', $e['saldo'] ?? 0);
                        $evento->contrato       = $contrato_id;
                        $is_success = $evento->armazenar();
                    }
                }

                if ($is_success){
                    Transacao::commit();
                    $retorno = array(
                        'status' => 1,
                        'contrato' => $contrato_numero,
                        'locador' => $locador_codigo
                    );
                }else{
                    Transacao::rollback();
                    $retorno = array(
                        'status' => 3,
                        'msg' => 'Erro ao importar demonstrativo.'
                    );
                }
            } catch (Exception $e) {
                $retorno = array(
                    'status' => 3,
                    'msg' => "Ocorreu um erro durante a importação. Nenhuma informação foi salva. Erro: " . $e->getMessage()
                );
                Transacao::rollback();
            }finally{
                echo tdc::wj($retorno);
            }
        }