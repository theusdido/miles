<?php
$input_file = PATH_CURRENT_FILE . 'nfsxmlaDez25.txt';
$output_file = PATH_CURRENT_FILE . 'nfsxmla_Dez25_modificado.txt';
$rps_counter = 800;
$nova_serie = 1;
$data_emissao = '2025-12-31';
$data_competencia = '2025-12-31';

$handle_in = fopen($input_file, "r");
$handle_out = fopen($output_file, "w");

if ($handle_in && $handle_out) {
    while (($line = fgets($handle_in)) !== false) {
        // 1. Alterar a Série U para 00001
        // Usamos regex para garantir que altera apenas dentro da tag correta
        $line = preg_replace('/<RPSSerie>[^<]+<\/RPSSerie>/', "<RPSSerie>$nova_serie</RPSSerie>", $line);

        // 3. Alterar a data de emissão
        $line = preg_replace('/<dEmis>[^<]+<\/dEmis>/', "<dEmis>$data_emissao</dEmis>", $line);

        // 4. Alterar a data de competência
        $line = preg_replace('/<dCompetencia>[^<]+<\/dCompetencia>/', "<dCompetencia>$data_competencia</dCompetencia>", $line);

        // 2. Encontrar o número do RPS atual nesta linha
        if (preg_match('/<RPSNumero>(\d+)<\/RPSNumero>/', $line, $matches)) {
            $rps_antigo = $matches[1];
            $rps_novo = (string)$rps_counter;

            // Substitui na TAG XML
            $line = str_replace(
                "<RPSNumero>$rps_antigo</RPSNumero>", 
                "<RPSNumero>$rps_novo</RPSNumero>", 
                $line
            );

            // Substitui no INÍCIO DA LINHA (Preservando o layout/alinhamento)
            // Se o número antigo tinha 6 dígitos (ex: 108329) e o novo tem 1 (ex: 2),
            // preenchemos com 5 espaços à esquerda para manter a coluna alinhada.
            $pad_length = strlen($rps_antigo);
            $rps_novo_pad = str_pad($rps_novo, $pad_length, " ", STR_PAD_LEFT);
            
            // Regex: Procura o número antigo apenas se ele estiver no começo da linha (após espaços opcionais)
            // O limitador '1' garante que só substitui a primeira ocorrência (o prefixo)
            $line = preg_replace(
                '/^(\s*)' . preg_quote($rps_antigo, '/') . '/', 
                '$1' . $rps_novo_pad, 
                $line, 
                1
            );

            $rps_counter++;
        }

        fwrite($handle_out, $line);
    }

    fclose($handle_in);
    fclose($handle_out);
    echo "Arquivo gerado com sucesso: $output_file";
} else {
    echo "Erro ao abrir os arquivos.";
}