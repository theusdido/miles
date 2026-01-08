# Alterações no Projeto Goes Imóveis - Resumo Diário

Este documento resume as modificações e melhorias implementadas no projeto `goesimoveis` em 04 de dezembro de 2025.

## `projects/goesimoveis/page/locador/demonstrativo/enviar/enviar.js`

*   **Implementação de Envio Sequencial:**
    *   A função `enviarTodos` foi reestruturada para processar o envio de demonstrativos de forma sequencial, garantindo que cada envio seja concluído antes que o próximo seja iniciado.
    *   A função `enviar` foi modificada para aceitar um `callback`, permitindo a execução assíncrona controlada.
*   **Adição de Caixas de Diálogo de Confirmação (Bootbox):**
    *   Foram adicionadas caixas de diálogo `bootbox.confirm` para as ações de envio individual e envio em lote, solicitando confirmação do usuário antes de prosseguir.
    *   A caixa de diálogo de exclusão, que já existia, também foi padronizada.
*   **Personalização das Caixas de Diálogo:**
    *   Os botões das caixas de diálogo `bootbox.confirm` foram personalizados com os rótulos "Sim" (`btn-success`) e "Não" (`btn-danger`).
    *   Títulos e mensagens descritivas foram adicionados às caixas de diálogo para melhorar a clareza e a experiência do usuário.

## `projects/goesimoveis/tema/desktop/color.css`

*   **Personalização do Botão de Fechar da Modal:**
    *   Foram adicionadas regras CSS para o seletor `.btn-close` para transformar o ícone do botão de fechar das modais em branco (`filter: invert(1) grayscale(100%) brightness(200%)`).
    *   Um efeito de hover foi adicionado para aumentar o brilho do botão ao passar o mouse.
