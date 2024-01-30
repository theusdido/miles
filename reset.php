<?php
    $project_identify   = 'armazemcarvoeiro';
    $htaccessFile       = '.htaccess';
    $_directory         = './projects/' . $project_identify;

    // Excluir o arquivo .htaccess
    if (file_exists($htaccessFile)) {
        if (unlink($htaccessFile)) {
            echo "O arquivo $htaccessFile foi excluído com sucesso.<br>";
        } else {
            echo "Ocorreu um erro ao tentar excluir o arquivo $htaccessFile.<br>";
        }
    } else {
        echo "O arquivo $htaccessFile não existe.<br>";
    }

    // Excluir o diretório ./projects/cedup e seus arquivos
    if (is_dir($_directory)) {
        // Função para excluir o diretório e seus conteúdos
        function deleteDirectory($dir) {
            if (!file_exists($dir)) return;
            if (!is_dir($dir)) return unlink($dir);
            foreach (scandir($dir) as $item) {
                if ($item == '.' || $item == '..') continue;
                if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) return false;
            }
            return rmdir($dir);
        }

        if (deleteDirectory($_directory)) {
            echo "O diretório $_directory foi excluído com sucesso.<br>";
        } else {
            echo "Ocorreu um erro ao tentar excluir o diretório $_directory e seus conteúdos.<br>";
        }
    } else {
        echo "O diretório $_directory não existe.<br>";
    }

    // Conexão com o banco de dados MySQL usando PDO
    $servername     = "localhost";
    $username       = "root";
    $password       = "spespcfc10"; // Deixar vazia para senha em branco
    $dbname         = $project_identify;
    $port           = 3306;

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;port=$port", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Excluir o schema cedupinstall
        $sql = "DROP DATABASE `$dbname`;";
        $conn->exec($sql);

        echo "O schema $dbname foi excluído com sucesso.";
    } catch(PDOException $e) {
        echo "Erro ao excluir o schema: " . $e->getMessage();
    }

    $conn = null; // Fechar a conexão