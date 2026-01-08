# Guia de Implementação e Depuração para NFS-e do Padrão Nacional

Este documento é um guia prático baseado em uma sessão real de implementação e depuração para o envio de uma Declaração de Prestação de Serviços (DPS) ao webservice do Emissor Nacional de NFS-e. Ele cobre os erros mais comuns e suas soluções definitivas.

## 1. Estrutura Final do XML (DPS)

Após um longo processo de depuração, a estrutura final correta para o XML a ser enviado (antes de ser compactado e codificado em Base64) é um documento contendo uma **única DPS**.

**Estrutura Mínima Correta:**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<DPS xmlns="http://www.sped.fazenda.gov.br/nfse" versao="1.00">
  <infDPS Id="DPS{...}">
    <!-- Conteúdo da DPS em camelCase -->
    <tpAmb>1</tpAmb>
    <dhEmi>2025-12-02T08:00:00-03:00</dhEmi>
    <serie>00001</serie>
    <nDPS>000000000000001</nDPS>
    <!-- etc... -->
  </infDPS>
  <Signature xmlns="http://www.w3.org/2000/09/xmldsig#">
    <!-- Assinatura Digital -->
  </Signature>
</DPS>
```

---

## 2. A Jornada de Depuração: Erros e Soluções

A seguir, uma lista dos erros encontrados e suas soluções.

### Erro 1: `RNG6110 | Falha Schema Xml`

Este é o erro mais genérico e, em nossa depuração, ele foi causado por múltiplos fatores.

#### Causa Raiz 1: Capitalização (Casing) e Estrutura das Tags

O validador do servidor é extremamente rígido quanto à capitalização das tags.

*   **Diagnóstico:** A validação falha se uma tag como `<serie>` for enviada como `<Serie>`, ou `<infDPS>` como `<InfDps>`.
*   **Solução:** Siga estritamente a capitalização definida nos schemas XSD. A regra geral que descobrimos foi:
    *   **Raiz:** `<DPS>` (maiúsculo)
    *   **Informações da DPS:** `<infDPS>` (camelCase)
    *   **Tags internas da `infDPS`:** `camelCase` (ex: `tpAmb`, `dhEmi`, `cLocEmi`, `prest`, `toma`, `serv`, `valores`)
    *   **Documentos:** `<CNPJ>` e `<CPF>` (maiúsculo)

#### Causa Raiz 2 (Falso-Positivo): Validador Local Incompatível

*   **Diagnóstico:** Ao usar um validador local em PHP ou Python (baseados na biblioteca `libxml2`), o mesmo erro `RNG6110` ocorre por motivos diferentes. A `libxml2` não é 100% compatível com a sintaxe de alguns padrões (regex) nos XSDs oficiais.
    1.  **Tag `<serie>`:** O XSD usa `\d`, um atalho de regex que o `libxml2` não entende.
    2.  **Tag `<nDPS>`:** O XSD proíbe números começando com zero (`[1-9]...`), mas a regra de formação do `Id` da DPS exige que este número seja preenchido com zeros à esquerda, criando uma contradição.
*   **Solução:**
    *   **Opção A (Recomendada):** Ignorar os erros de `pattern` do validador local para as tags `<serie>` e `<nDPS>`, pois são falsos-positivos. O servidor real aceita os valores.
    *   **Opção B (Para Depuração Local):** "Remendar" o arquivo `tiposSimples_v1.00.xsd` localmente, trocando `\d` por `[0-9]` e o padrão do `TSNumDPS` para `[0-9]{1,15}`. **Lembre-se que esta alteração é apenas para o ambiente de teste local.**

### Erro 2: `E0004 | Conteúdo do identificador informado na DPS difere...`

*   **Diagnóstico:** O `Id` da tag `<infDPS>` é uma chave longa formada pela concatenação de vários campos. O servidor recalcula este `Id` e o compara com o que foi enviado. Se houver qualquer diferença, o erro `E0004` ocorre. O erro mais comum é a divergência de preenchimento de zeros.
*   **Solução:** Garanta que os valores usados para gerar a string do `Id` sejam **exatamente** os mesmos que estão nas tags do corpo do XML. Em nosso caso, o valor da tag `<nDPS>` precisava ser preenchido com zeros à esquerda para ter 15 caracteres, assim como na formação do `Id`.
    ```php
    // Na geração do ID:
    $dps_id = "DPS" . $codMun . $tipoInsc . $cnpj . $serie_pad . str_pad($numero_dps, 15, "0", STR_PAD_LEFT);

    // No corpo do XML:
    // A tag <nDPS> DEVE ter o mesmo valor com padding!
    return '<nDPS>'.str_pad($numero_dps, 15, "0", STR_PAD_LEFT).'</nDPS>';
    ```

### Erro 3: `E0714 | Arquivo enviado com erro na assinatura.` (Assinatura Inválida)

Este foi o erro mais difícil de depurar. A assinatura é invalidada por qualquer modificação, por menor que seja, no conteúdo assinado.

#### Causa Raiz: Alteração de Espaços em Branco (Whitespace)

*   **Diagnóstico:** Funções de "limpeza" ou configurações do parser de XML que alteram espaços em branco ou quebras de linha no documento **ANTES** do processo de assinatura digital irão invalidar a assinatura. O processo de assinatura já inclui um passo padrão para isso, a **canonização (C14N)**. Qualquer interferência manual causa uma divergência entre o hash que você gera e o que o servidor calcula.
*   **Solução (PHP - `DOMDocument`):** Ao carregar o XML para assinar, configure o `DOMDocument` para não interferir no conteúdo.

    **Código Correto:**
    ```php
    $xml = new DOMDocument('1.0', 'UTF-8');

    // A normalização de espaços deve ser feita APENAS pelo algoritmo C14N.
    // preserveWhiteSpace=true (padrão) mantém os nós de espaço.
    // formatOutput=false evita que o saveXML() adicione indentação desnecessária.
    $xml->preserveWhiteSpace = true;
    $xml->formatOutput = false;

    $xml->loadXML($xmlContent);
    // ... prossegue com a assinatura
    ```
    Remova também qualquer chamada a funções customizadas de `inline()` ou `trim()` sobre o XML completo antes de assinar.

#### Causa Raiz Secundária: `<X509Data>` Vazio

*   **Diagnóstico:** Se a tag `<X509Data>` ou `<X509Certificate>` aparece vazia na assinatura, significa que o certificado público não foi lido corretamente.
*   **Solução:** A biblioteca de assinatura (`xmlseclibs`) espera receber o **conteúdo completo e original** do arquivo de certificado `.pem`, incluindo os marcadores `-----BEGIN CERTIFICATE-----` e `-----END CERTIFICATE-----`. Não "limpe" ou modifique a string do certificado após lê-la do arquivo.

    **Código Correto:**
    ```php
    $certContent = file_get_contents($this->publicCertPath);
    $objDSig->add509Cert($certContent, true, false);
    ```

---

### 3. Ferramentas de Depuração

Para diagnosticar erros de schema genéricos (`RNG6110`), a melhor estratégia é usar um **validador local**.

1.  **Salve o XML:** Altere sua classe temporariamente para salvar o XML final em um arquivo (ex: `debug_nfse.xml`).
2.  **Obtenha os Schemas:** Baixe o pacote de schemas XSD do portal oficial.
3.  **Use um Script Validador:** Crie um script (PHP ou Python) que use uma biblioteca XML (como `DOMDocument::schemaValidate` ou `lxml`) para validar o seu `debug_nfse.xml` contra o XSD principal (`DPS_v1.00.xsd` no nosso caso final). Isso fornecerá mensagens de erro específicas sobre a linha e a natureza da falha.

---

### 4. Conclusão

A integração com o Emissor Nacional de NFS-e exige atenção a detalhes que muitas vezes não estão claros na documentação. As chaves para o sucesso são:
1.  **Capitalização e Estrutura:** Siga o XSD à risca. Não confie em exemplos sem validá-los.
2.  **Assinatura Digital:** Não manipule o XML antes de assinar. Deixe o processo de C14N fazer seu trabalho.
3.  **Depuração Local:** Use um validador de schema local para obter feedback detalhado e não depender das mensagens genéricas do servidor.
