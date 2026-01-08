#!/usr/bin/env python3
# -*- coding: utf-8 -*-

"""
validar_xml_python.py

Um script de validação de XML contra um schema XSD, utilizando a biblioteca lxml.
Este script foi adaptado para o ecossistema da NFS-e Nacional e tenta fornecer
sugestões úteis para erros comuns de schema.
"""

import sys
from lxml import etree

def get_suggestion_for_error(error):
    """
    Analisa um erro de validação da lxml e tenta fornecer uma sugestão útil.
    """
    message = error.message

    # Mapa de tipos de dados comuns e suas regras
    type_suggestions = {
        'TSDec1V2': "O valor deve ser um número com até 1 dígito na parte inteira e 2 casas decimais. Ex: 5.00",
        'TSDec2V2': "O valor deve ser um número com até 2 dígitos na parte inteira e 2 casas decimais. Ex: 99.99",
        'TSDec15V2': "O valor deve ser um número com até 15 dígitos na parte inteira e 2 casas decimais. Ex: 150.00",
        'TSData': "A data deve estar no formato AAAA-MM-DD.",
        'TSDateTimeUTC': "A data e hora deve estar no formato UTC completo: AAAA-MM-DDThh:mm:ssZ ou AAAA-MM-DDThh:mm:ss-03:00.",
        'TSCNPJ': "O CNPJ deve conter 14 dígitos numéricos, sem formatação.",
        'TSCPF': "O CPF deve conter 11 dígitos numéricos, sem formatação.",
        'TSCodMunIBGE': "O código do município deve ter 7 dígitos, conforme tabela do IBGE.",
    }

    # 1. Erros conhecidos de incompatibilidade do XSD com a libxml2
    if "TSSerieDPS" in message and "is not accepted by the pattern" in message:
        return ("FALSO-POSITIVO PROVÁVEL: O validador local (libxml2) não entende o atalho '\d' usado no padrão regex do XSD original para a tag <serie>. "
                "O servidor do governo provavelmente aceita este valor. Este erro pode ser ignorado na validação local.")

    if "TSNumDPS" in message and "is not accepted by the pattern" in message:
        return ("FALSO-POSITIVO PROVÁVEL: O validador local (libxml2) rejeita números com zeros à esquerda para a tag <nDPS> devido a uma inconsistência no XSD. "
                "O servidor do governo, no entanto, exige os zeros à esquerda para a validação do ID. Este erro pode ser ignorado na validação local.")

    # 2. Erros de tipo de dado
    for type_name, suggestion in type_suggestions.items():
        if type_name in message and "not a valid value" in message:
            return suggestion

    # 3. Erros de elemento não esperado (casing, ordem)
    if "This element is not expected" in message:
        # Extrai o nome do elemento encontrado e do esperado se possível
        # Ex: "Element '{...}InfDps': This element is not expected. Expected is ( {http://www.sped.fazenda.gov.br/nfse}infDPS )."
        import re
        match = re.search(r"Element '(\{.*\})([^']+)': This element is not expected. Expected is \( '(\{.*\})([^']*)' \)", message)
        if match:
            found_tag, found_ns, expected_tag, expected_ns = match.groups()
            return f"A tag <{found_tag}{found_ns}> não era esperada. O schema esperava a tag <{expected_tag}{expected_ns}>. Verifique a capitalização (maiúsculas/minúsculas) e a ordem dos elementos."
        return "Um elemento inesperado foi encontrado. Verifique a ordem e a capitalização (maiúsculas/minúsculas) das suas tags XML."
        
    # 4. Erro de raiz de validação
    if "No matching global declaration available for the validation root" in message:
        return "O elemento raiz do seu XML não corresponde ao schema que você está usando para validar. Verifique se você está usando o arquivo XSD correto para o XML gerado (ex: GerarNfseEnvio_v1.00.xsd para um XML que começa com <GerarNfseEnvio>)."


    return None

def main():
    """
    Função principal do script de validação.
    """
    if len(sys.argv) < 3:
        print("Erro: Argumentos insuficientes.\n")
        print(f"Uso: python3 {sys.argv[0]} <caminho_para_xml> <caminho_para_xsd>")
        print(f"Exemplo: python3 {sys.argv[0]} debug_nfse.xml vendor/theusdido/miles-library/controller/integracao/sn_nfse/xsd/DPS_v1.00.xsd")
        sys.exit(1)

    xml_path = sys.argv[1]
    xsd_path = sys.argv[2]

    try:
        with open(xsd_path, 'rb') as f:
            schema_doc = etree.XML(f.read())
        schema = etree.XMLSchema(schema_doc)

        parser = etree.XMLParser(remove_blank_text=True)
        xml_doc = etree.parse(xml_path, parser)

        print("=============================================")
        print("Iniciando validação do Schema XML (Python/lxml)")
        print("=============================================")
        print(f"Arquivo XML: {xml_path}")
        print(f"Arquivo XSD: {xsd_path}")
        print("---------------------------------------------\
")
        
        schema.assertValid(xml_doc)
        
        print("\x1b[32mSUCESSO: O XML é válido de acordo com o schema XSD.\x1b[0m")

    except (IOError, etree.XMLSyntaxError) as e:
        print(f"\x1b[31mERRO: Não foi possível ler ou parsear o arquivo XML/XSD.\x1b[0m")
        print(f"Detalhe: {e}")
        sys.exit(1)

    except etree.DocumentInvalid as e:
        print(f"\x1b[31mFALHA: O XML é inválido. Erros encontrados:\x1b[0m\n")
        for error in e.error_log:
            print("---------------------------------------------")
            print(f"Nível: {error.level_name}")
            print(f"Código: {error.type_name}")
            print(f"Linha: {error.line}")
            print(f"Coluna: {error.column}")
            print(f"Mensagem: {error.message.strip()}")
            
            suggestion = get_suggestion_for_error(error)
            if suggestion:
                print(f"\n\x1b[32mSugestão: {suggestion}\x1b[0m")
        print("---------------------------------------------")
    
    finally:
        print("\nValidação finalizada.")


if __name__ == "__main__":
    main()
