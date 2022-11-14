<?php
    // Função invocada a cada exceção
    set_error_handler(
        function ($err_severity, $err_msg, $err_file, $err_line, array $err_context = [])
        {
            throw new ErrorException( $err_msg, 0, $err_severity, $err_file, $err_line);
        },
        E_WARNING
    );