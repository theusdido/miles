# Análise dos Schemas XSD para Emissão de NFS-e (Padrão Nacional)

Este documento descreve a estrutura e a lógica para a geração de arquivos XML para a emissão de Nota Fiscal de Serviço Eletrônica (NFS-e) no padrão do Sistema Nacional. A análise é baseada nos arquivos XSD fornecidos.

## Fluxo Geral de Emissão

O processo de emissão de uma NFS-e segue um fluxo de duas etapas principais:

1.  **Geração da DPS (Declaração de Prestação de Serviços)**: O contribuinte (prestador de serviço) gera um documento eletrônico chamado DPS. Este documento contém todas as informações relativas a uma prestação de serviço.
2.  **Envio e Geração da NFS-e**: A DPS é assinada digitalmente e enviada para o ambiente da SEFIN Nacional. A SEFIN valida a DPS e, se estiver correta, a converte em uma NFS-e, que é o documento fiscal oficial. A NFS-e gerada é então assinada pela SEFIN e retornada ao contribuinte.

Eventos posteriores, como cancelamento ou carta de correção, são tratados como mensagens XML separadas, vinculadas a uma NFS-e existente.

---

## Estrutura dos Arquivos XML

Existem dois documentos principais no fluxo: a **DPS** e a **NFS-e**. Além deles, existem os documentos de **Eventos**.

### 1. DPS - Declaração de Prestação de Serviços (`DPS_v1.00.xsd`)

A DPS é o documento que o emissor gera e envia. Sua estrutura principal é definida pelo tipo `TCDPS`.

-   **`<DPS>`**: Elemento raiz.
    -   **`<infDPS>` (`TCInfDPS`)**: Contém todas as informações da declaração. Este é o grupo que deve ser assinado digitalmente.
    -   **`<Signature>`**: Assinatura digital (padrão `xmldsig`) do grupo `<infDPS>`.

#### Detalhes do grupo `<infDPS>` (`tiposComplexos_v1.00.xsd`)

O tipo `TCInfDPS` contém os seguintes grupos principais de informações:

| Tag           | Tipo              | Descrição                                                                                                  |
| :------------ | :---------------- | :--------------------------------------------------------------------------------------------------------- |
| `Id`          | `TSIdDPS`         | (Atributo) Identificador único da DPS, com 45 caracteres, prefixado por "DPS".                             |
| `tpAmb`       | `TSTipoAmbiente`  | Identificação do Ambiente (1 - Produção; 2 - Homologação).                                                 |
| `dhEmi`       | `TSDateTimeUTC`   | Data e hora da emissão da DPS no formato UTC.                                                              |
| `serie`       | `TSSerieDPS`      | Série da DPS (até 5 dígitos).                                                                              |
| `nDPS`        | `TSNumDPS`        | Número sequencial da DPS (até 15 dígitos).                                                                 |
| `dCompet`     | `TSData`          | Data da competência da prestação do serviço (AAAA-MM-DD).                                                  |
| `prest`       | `TCInfoPrestador` | **Informações do Prestador de Serviço** (CNPJ/CPF, Inscrição Municipal, Razão Social, Endereço, etc.).       |
| `toma`        | `TCInfoPessoa`    | **Informações do Tomador de Serviço** (CNPJ/CPF, Razão Social, Endereço, etc.). Opcional.                    |
| `interm`      | `TCInfoPessoa`    | **Informações do Intermediário** (se houver). Opcional.                                                    |
| `serv`        | `TCServ`          | **Detalhes do Serviço Prestado**.                                                                          |
| `valores`     | `TCInfoValores`   | **Valores do serviço e tributos**.                                                                         |

#### Detalhes do grupo `<serv>` (`TCServ`)

| Tag         | Tipo        | Descrição                                                                                             |
| :---------- | :---------- | :---------------------------------------------------------------------------------------------------- |
| `locPrest`  | `TCLocPrest`| Local da prestação do serviço (código IBGE do município ou código do país para exterior).               |
| `cServ`     | `TCCServ`   | Códigos e descrição do serviço.                                                                       |
| `obra`      | `TCInfoObra`| Informações de obra (código CNO/CEI), se aplicável.                                                   |

Dentro de `<cServ>` (`TCCServ`):

| Tag           | Tipo            | Descrição                                                                                                                             |
| :------------ | :-------------- | :------------------------------------------------------------------------------------------------------------------------------------ |
| `cTribNac`    | `TSCodTribNac`  | **Código de Tributação Nacional** (Item da Lista de Serviços da LC 116/2003 com desdobramento nacional). Campo obrigatório.             |
| `cNBS`        | `TSCodNBS`      | Código da Nomenclatura Brasileira de Serviços (NBS). Opcional.                                                                        |
| `xDescServ`   | `TSDesc2000`    | **Discriminação do Serviço**. Texto detalhado descrevendo o serviço prestado.                                                         |

#### Detalhes do grupo `<valores>` (`TCInfoValores`)

| Tag               | Tipo                | Descrição                                                                    |
| :---------------- | :------------------ | :--------------------------------------------------------------------------- |
| `vServPrest`      | `TCVServPrest`      | Grupo com o valor do serviço (`vServ`).                                      |
| `vDescCondIncond` | `TCVDescCondIncond` | Valores de descontos condicionados e incondicionados. Opcional.              |
| `vDedRed`         | `TCInfoDedRed`      | Valores de dedução/redução da base de cálculo. Opcional.                     |
| `trib`            | `TCInfoTributacao`  | **Grupo de Tributos**.                                                       |

Dentro de `<trib>` (`TCInfoTributacao`):

| Tag       | Tipo              | Descrição                                                                                                                                              |
| :-------- | :---------------- | :----------------------------------------------------------------------------------------------------------------------------------------------------- |
| `tribMun` | `TCTribMunicipal` | **Tributos Municipais (ISSQN)**. Contém a tributação do ISSQN, tipo de retenção (`tpRetISSQN`), e alíquota (`pAliq`).                                     |
| `tribFed` | `TCTribFederal`   | Tributos Federais (PIS, COFINS, IRRF, CSLL, etc.). Opcional.                                                                                           |
| `totTrib` | `TCTribTotal`     | Valores totais aproximados dos tributos (conforme Lei da Transparência).                                                                               |

---

### 2. NFS-e - Nota Fiscal de Serviço Eletrônica (`NFSe_v1.00.xsd`)

A NFS-e é o documento retornado pela SEFIN após a validação da DPS. Ele formaliza a operação. Sua estrutura é definida pelo tipo `TCNFSe`.

-   **`<NFSe>`**: Elemento raiz.
    -   **`<infNFSe>` (`TCInfNFSe`)**: Contém as informações da nota fiscal gerada. Este grupo é assinado pela SEFIN.
    -   **`<Signature>`**: Assinatura digital da SEFIN sobre o grupo `<infNFSe>`.

#### Detalhes do grupo `<infNFSe>` (`TCInfNFSe`)

Este grupo contém os dados da DPS original, além de informações adicionadas pela SEFIN.

| Tag             | Tipo          | Descrição                                                                      |
| :-------------- | :------------ | :----------------------------------------------------------------------------- |
| `Id`            | `TSIdNFSe`    | (Atributo) Chave de acesso única da NFS-e, com 53 caracteres, prefixada por "NFS". |
| `nNFSe`         | `TSNNFSe`     | **Número oficial da NFS-e** gerado pela SEFIN.                                 |
| `cStat`         | `TStat`       | Código do Status da NFS-e (ex: 100 - NFS-e Gerada).                              |
| `dhProc`        | `TSDateTimeUTC`| Data e hora do processamento/geração da NFS-e.                                 |
| `emit`          | `TCEmitente`  | Dados do emitente da NFS-e.                                                    |
| `valores`       | `TCValoresNFSe`| Valores consolidados da NFS-e.                                               |
| **`<DPS>`**     | `TCDPS`       | **A DPS original** que deu origem a esta NFS-e é incluída integralmente aqui.      |

---

### 3. Eventos (`evento_v1.00.xsd`)

Após a emissão da NFS-e, é possível vincular eventos a ela, como um cancelamento. A estrutura geral é:

-   **`<evento>`**: Elemento raiz para um evento.
    -   **`<infEvento>` (`TCInfEvento`)**: Informações do evento.
        -   **`<pedRegEvento>` (`TCPedRegEvt`)**: O pedido de registro do evento em si.
            -   **`<infPedReg>` (`TCInfPedReg`)**: Detalhes do pedido, como o autor, a chave da NFS-e (`chNFSe`) e o tipo de evento.
            -   **`<Signature>`**: Assinatura do autor do evento.
    -   **`<Signature>`**: Assinatura do ambiente que processa o evento.

Tipos de evento comuns (dentro de `<infPedReg>`):

-   `e101101`: Cancelamento. Requer um código de motivo (`cMotivo`) e uma justificativa (`xMotivo`).
-   `e105102`: Cancelamento por Substituição. Usado quando uma nova NFS-e substitui a atual.

---

### 4. Tipos de Dados e Formatação (`tiposSimples_v1.00.xsd`)

Este arquivo define os formatos para todos os campos básicos. Alguns dos mais importantes são:

-   **Datas e Horas**:
    -   `TSData`: Data no formato `AAAA-MM-DD`.
    -   `TSDateTimeUTC`: Data e hora no formato `AAAA-MM-DDThh:mm:ssTZD` (com fuso horário).
-   **Identificadores**:
    -   `TSIdDPS`: Formato `DPS` + 42 dígitos numéricos.
    -   `TSIdNFSe`: Formato `NFS` + 50 dígitos numéricos.
    -   `TSCNPJ`: 14 dígitos numéricos.
    -   `TSCPF`: 11 dígitos numéricos.
-   **Valores Numéricos**:
    -   `TSDec15V2`: Valor decimal com até 15 dígitos na parte inteira e 2 casas decimais (ex: `1234.56`). Usado para valores monetários.
    -   `TSDec3V2`: Decimal com até 3 dígitos e 2 casas decimais. Usado para alíquotas.
-   **Códigos**:
    -   `TSCodMunIBGE`: Código IBGE do município com 7 dígitos.
    -   `TSCodTribNac`: Código de tributação nacional com 6 dígitos.
    -   `TSTipoAmbiente`: `1` para Produção, `2` para Homologação.
    -   `TSTribISSQN`: `1` (Tributável), `2` (Imunidade), `3` (Exportação), `4` (Não Incidência).
    -   `TSTipoRetISSQN`: `1` (Não Retido), `2` (Retido pelo Tomador), `3` (Retido pelo Intermediário).

### 5. Assinatura Digital (`xmldsig-core-schema.xsd`)

Todos os documentos principais (DPS, NFS-e, Eventos) exigem uma assinatura digital XML (`XMLDSig`). A assinatura garante a integridade e a autoria do documento. É crucial que o grupo de informações (`<infDPS>`, `<infNFSe>`, etc.) seja corretamente canonizado e assinado, e que o certificado digital (X.509) seja incluído na tag `<KeyInfo>`.
