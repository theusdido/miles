# -*- coding: utf-8 -*-
import base64
import gzip
import json
import os
import re
import unicodedata
from lxml import etree
import mysql.connector # or your preferred database driver
import requests
from dotenv import load_dotenv
from signxml import XMLSigner, XMLVerifier, methods

# Load environment variables from .env file
load_dotenv()

class SnNfse:
    """
    A Python class to generate, sign, and send a National NFS-e (NFSE) batch (Lote DPS).
    This class is a Python conversion of the provided PHP snNFSE class.
    """

    def __init__(self):
        """
        Initializes the SnNfse client with configuration from environment variables.
        """
        # --- Configuration ---
        self.endpoint = os.getenv("ENDPOINT")
        self.private_key_path = os.getenv("PRIVATE_KEY_PATH")
        self.public_cert_path = os.getenv("PUBLIC_CERT_PATH")
        self.client_cert_pass = os.getenv("CERT_PASS")
        self.ca_bundle_path = os.getenv("CA_BUNDLE_PATH") # For verifying the server's certificate
        self.signed_xml_path = os.getenv("SIGNED_XML_PATH", "lote_dps.xml")

        # --- Database Configuration ---
        self.db_config = {
            'host': os.getenv("DB_HOST"),
            'user': os.getenv("DB_USER"),
            'password': os.getenv("DB_PASSWORD"),
            'database': os.getenv("DB_NAME"),
        }

        # --- Internal State ---
        self._lote_rps = []
        self._rps_lote_id = 0
        self.xmlns = 'http://www.sped.fazenda.gov.br/nfse'

    def add_lote_rps(self, lote_rps: list):
        """
        Adds a list of RPS numbers to be processed in the batch.

        Args:
            lote_rps: A list of integers representing RPS numbers.
        """
        if not all(isinstance(item, int) for item in lote_rps):
            raise ValueError("lote_rps must be a list of integers.")
        self._lote_rps = lote_rps

    def send(self) -> dict:
        """
        Main method to create, sign, and send the NFS-e batch.
        Orchestrates the entire process.
        """
        response = {}
        msgs = ""
        status = ""
        try:
            # 1. Create and sign the XML batch
            self._create_lote()

            # 2. Send the signed XML to the endpoint
            resp = self._send_signed_lote_rps()
            
            try:
                resp_json = resp.json()
            except json.JSONDecodeError:
                resp_json = None

            # 3. Process the HTTP response
            if resp.status_code == 400:
                status = 'error'
                if resp_json and 'erros' in resp_json and isinstance(resp_json['erros'], list):
                    for error in resp_json['erros']:
                        complemento = error.get('Complemento', '')
                        msgs += f"ERRO: {error.get('Codigo')} | {error.get('Descricao')} - {complemento}\n"
                else:
                    msgs += f"ERRO HTTP 400: Resposta inesperada ou vazia. Raw response: {resp.text}\n"

            elif resp.status_code == 200:
                if resp_json:
                    msgs += 'NFSe enviada com Sucesso!\n'
                    msgs += 'Detalhes da Resposta: ' + json.dumps(resp_json, indent=2) + '\n'
                    status = 'success'
                else:
                    msgs += 'Alerta: NFSe enviada, mas a resposta está vazia.\n'
                    status = 'warning'
            
            else:
                status = 'error'
                msgs += f"ERRO HTTP {resp.status_code}: Resposta inesperada. Raw response: {resp.text}\n"

        except requests.exceptions.RequestException as e:
            status = 'error'
            msgs += f"ERRO na requisição HTTP: {e}\n"
        except Exception as e:
            status = 'error'
            msgs += f"ERRO na execução: {e}\n"
        finally:
            response['message'] = msgs
            response['status'] = status
            # Here you would typically update the database with the status of the batch
            # For example: self._update_lote_status(status)

        return response

    def _send_signed_lote_rps(self) -> requests.Response:
        """
        Sends the signed and gzipped XML batch to the national NFS-e service.
        """
        if not os.path.exists(self.signed_xml_path):
            raise FileNotFoundError(f"Arquivo XML assinado não encontrado: {self.signed_xml_path}")

        with open(self.signed_xml_path, 'rb') as f:
            xml_content_raw = f.read()

        if not xml_content_raw:
            raise ValueError("Arquivo XML assinado está vazio.")

        # Prepare the JSON payload
        dps_xml_gzip_b64 = self._prepare_xml_nfse(xml_content_raw)
        payload = {"dpsXmlGZipB64": dps_xml_gzip_b64}
        payload_json = json.dumps(payload)

        headers = {
            'Content-Type': 'application/json; charset=utf-8',
            'Accept': 'application/json',
        }
        
        # Setup client-side certificates for mTLS
        cert = (self.public_cert_path, self.private_key_path)

        print("Enviando lote DPS para o endpoint...")
        # Make the POST request
        response = requests.post(
            self.endpoint,
            data=payload_json,
            headers=headers,
            cert=cert,
            verify=self.ca_bundle_path, # Server certificate verification
            # If your private key is encrypted, you need to decrypt it first.
            # The 'requests' library does not support passwords for keys directly.
            # You would use a library like 'cryptography' to load the key with a password.
        )
        
        response.raise_for_status() # Raise an exception for bad status codes (4xx or 5xx)
        return response

    def _get_db_connection(self):
        """
        Placeholder for getting a database connection.
        
        !!! IMPORTANT !!!
        Adapt this method to your project's database connection handling.
        This is a basic example using mysql-connector.
        """
        try:
            conn = mysql.connector.connect(**self.db_config)
            return conn
        except mysql.connector.Error as err:
            print(f"Erro de banco de dados: {err}")
            raise

    def _get_next_id(self, conn, table_name: str) -> int:
        """
        Placeholder for a function similar to the original 'getProxId'.
        
        !!! IMPORTANT !!!
        You MUST implement the logic to get the next available ID for a table
        according to your application's rules. This is a dummy implementation.
        """
        # This is a highly simplified and likely incorrect way to get a new ID.
        # Replace it with your application's logic (e.g., sequences, auto-increment fields, etc.)
        cursor = conn.cursor()
        cursor.execute(f"SELECT MAX(id) + 1 FROM {table_name}")
        result = cursor.fetchone()
        next_id = result[0] if result and result[0] is not None else 1
        cursor.close()
        return next_id

    def _create_lote(self):
        """
        Fetches data from the database, builds the XML for the batch, and signs it.
        """
        if not self._lote_rps:
            raise ValueError("Nenhum RPS adicionado ao lote. Use add_lote_rps().")

        conn = self._get_db_connection()
        cursor = conn.cursor(dictionary=True)

        placeholders = ','.join(['%s'] * len(self._lote_rps))
        sql = f"""
            SELECT *
            FROM td_erp_nfse_nota a
            LEFT JOIN td_erp_nfse_servico b ON b.nfse = a.id
            LEFT JOIN td_erp_nfse_item c ON c.nfse = a.id
            LEFT JOIN td_erp_nfse_tomador d ON d.nfse = a.id
            WHERE (a.inativo <> 1 OR a.inativo IS NULL)
            AND a.rpsnumero IN ({placeholders});
        """

        cursor.execute(sql, tuple(self._lote_rps))
        resultset = cursor.fetchall()

        if not resultset:
            raise ValueError("Nenhum registro encontrado para os RPS informados.")
            
        # --- Lote ID and Database insertion ---
        self._rps_lote_id = self._get_next_id(conn, 'td_erp_nfse_lote')
        
        # Example: Insert the new batch record
        insert_sql = "INSERT INTO td_erp_nfse_lote (id, data_envio) VALUES (%s, NOW());"
        cursor.execute(insert_sql, (self._rps_lote_id,))
        conn.commit() # Don't forget to commit! 
        
        cursor.close()
        conn.close()
        
        rps_numero = resultset[0]['rpsnumero']
        
        # Generate the unique ID for the DPS
        # Example fixed values from the PHP code. Adjust as needed.
        dps_id = f"DPS{'4204608'}{'2'}{'83248021000158'}{'00001':0>5}{rps_numero:0>15}"

        # --- Build XML structure ---
        # Using lxml for a more robust XML construction
        lote_dps_el = etree.Element("LoteDPS", Id="Lote1", xmlns=self.xmlns)
        etree.SubElement(lote_dps_el, "idLote").text = str(self._rps_lote_id)
        etree.SubElement(lote_dps_el, "qtdDps").text = str(len(resultset))
        lista_dps_el = etree.SubElement(lote_dps_el, "listaDps")
        
        dps_el = etree.SubElement(lista_dps_el, "DPS", versao="1.00")
        inf_dps_el = etree.SubElement(dps_el, "infDPS", Id=dps_id)

        for value in resultset:
            # The layout is generated and appended inside inf_dps_el
            self._generate_internal_xml(inf_dps_el, value)
            
        # --- Sign the XML ---
        signed_xml_tree = self._sign_xml(etree.tostring(lote_dps_el, encoding='unicode'), dps_id)
        
        # Save the signed XML to a file
        with open(self.signed_xml_path, "wb") as f:
            f.write(etree.tostring(signed_xml_tree, pretty_print=True, xml_declaration=True, encoding='UTF-8'))

    def _sign_xml(self, xml_string: str, reference_id: str) -> etree.Element:
        """
        Signs the XML document using XML-DSig (enveloped signature).

        Args:
            xml_string: The raw XML string to be signed.
            reference_id: The 'Id' of the element to be referenced in the signature.
        
        Returns:
            An lxml Element object representing the signed XML tree.
        """
        # Parse the XML string
        root = etree.fromstring(xml_string.encode('utf-8'))
        
        # Find the element to be signed
        element_to_sign = root.find(f".//*[@Id='{reference_id}']")
        if element_to_sign is None:
            raise ValueError(f"Element with Id='{reference_id}' not found in the XML.")

        # Load private key and public certificate
        with open(self.private_key_path, "rb") as f:
            key_data = f.read()
        with open(self.public_cert_path, "rb") as f:
            cert_data = f.read()
            
        # If your key is password protected, you must decrypt it first using a library like cryptography.
        # Example:
        # from cryptography.hazmat.primitives import serialization
        # pkey = serialization.load_pem_private_key(key_data, password=self.client_cert_pass.encode())
        # key_data = pkey.private_bytes(...)
        
        # Sign the XML
        signer = XMLSigner(
            method=methods.enveloped,
            reference_uri=f"#{reference_id}",
            c14n_algorithm=methods.C14N.EXCLUSIVE_1_0,
            signature_algorithm="rsa-sha256",
            digest_algorithm="sha256"
        )
        
        signed_root = signer.sign(
            root,
            key=key_data,
            cert=cert_data
        )

        # Verify signature (optional but recommended)
        # XMLVerifier().verify(signed_root, x509_cert=cert_data, ca_pem_file=self.ca_bundle_path)
        
        return signed_root

    def _prepare_xml_nfse(self, xml_content: bytes) -> str:
        """
        Gzips and Base64 encodes the XML content.
        
        Args:
            xml_content: The XML content as bytes.
            
        Returns:
            A Base64 encoded string of the gzipped XML.
        """
        # Remove XML declaration for the payload
        xml_content = re.sub(b'<?xml.*?>', b'', xml_content).strip()
        
        # Gzip and Base64 encode
        gzipped_content = gzip.compress(xml_content, compresslevel=9)
        base64_content = base64.b64encode(gzipped_content)
        return base64_content.decode('ascii')

    def _clean_string_xml(self, text: str) -> str:
        """
        Cleans a string for use in XML, ensuring it's valid UTF-8 and removing control chars.
        """
        if not text:
            return ''
        
        # Ensure UTF-8, converting from latin-1 if necessary as a fallback
        try:
            text.encode('utf-8')
        except UnicodeEncodeError:
            text = text.encode('iso-8859-1').decode('utf-8')

        # Remove control characters except for tab, newline, and carriage return
        return "".join(ch for ch in text if unicodedata.category(ch)[0] != "C" or ch in ('\t', '\n', '\r'))

    def _generate_internal_xml(self, parent_element: etree.Element, value: dict):
        """
        Generates the inner XML structure for a single DPS record.
        This is based on the 'layoutRPSXML' and 'generateInternalXml' from the PHP code.
        
        Args:
            parent_element: The parent lxml Element (infDPS) to append the data to.
            value: A dictionary containing the data for one RPS from the database.
        """
        rps_numero = self._clean_string_xml(str(value.get('rpsnumero', '')))
        
        # This is a static structure based on your PHP code.
        # You should make this dynamic based on the 'value' dictionary.
        etree.SubElement(parent_element, "tpAmb").text = "1"
        etree.SubElement(parent_element, "dhEmi").text = "2025-12-18T00:00:00"
        etree.SubElement(parent_element, "verAplic").text = "1.0.0"
        etree.SubElement(parent_element, "serie").text = "00001"
        etree.SubElement(parent_element, "nDPS").text = rps_numero
        etree.SubElement(parent_element, "dCompet").text = "2025-12"
        etree.SubElement(parent_element, "tpEmit").text = "1"
        etree.SubElement(parent_element, "cLocEmi").text = "4204608"

        prest = etree.SubElement(parent_element, "prest")
        etree.SubElement(prest, "CNPJ").text = "83248021000158"
        reg_trib = etree.SubElement(prest, "regTrib")
        etree.SubElement(reg_trib, "opSimpNac").text = "3"
        etree.SubElement(reg_trib, "regApTribSN").text = "1"
        etree.SubElement(reg_trib, "regEspTrib").text = "0"

        toma = etree.SubElement(parent_element, "toma")
        etree.SubElement(toma, "CPF").text = self._clean_string_xml(value.get('cpf', '99933063987'))
        etree.SubElement(toma, "xNome").text = self._clean_string_xml(value.get('nome', 'VIVIANE GRUNDLER VEFAGO'))
        end = etree.SubElement(toma, "end")
        etree.SubElement(end, "xLgr").text = "RUA EXEMPLO"
        etree.SubElement(end, "nro").text = "100"
        etree.SubElement(end, "xBairro").text = "CENTRO"
        etree.SubElement(end, "cMun").text = "4204608"
        etree.SubElement(end, "CEP").text = "88900000"

        serv = etree.SubElement(parent_element, "serv")
        loc_prest = etree.SubElement(serv, "locPrest")
        etree.SubElement(loc_prest, "cLocPrestacao").text = "4204608"
        c_serv = etree.SubElement(serv, "cServ")
        etree.SubElement(c_serv, "cTribNac").text = "100501"
        etree.SubElement(c_serv, "cNBS").text = "110012200"
        etree.SubElement(c_serv, "xDescServ").text = self._clean_string_xml(value.get('descricao', 'PRESTACAO DE SERVICOS'))

        valores = etree.SubElement(parent_element, "valores")
        v_serv_prest = etree.SubElement(valores, "vServPrest")
        etree.SubElement(v_serv_prest, "vServ").text = str(value.get('valor_servico', '185.61'))
        
        # ... and so on for the rest of the XML structure.

# --- Example of how to use the class ---
if __name__ == "__main__":
    print("Executando exemplo de uso da classe SnNfse...")

    # Create an instance of the class
    nfse_client = SnNfse()

    # Add the RPS numbers you want to include in the batch
    # In a real application, you would get these from your system
    rps_to_process = [123, 124, 125] # Replace with actual RPS numbers from your DB
    nfse_client.add_lote_rps(rps_to_process)

    # Execute the main send method
    # This will trigger the database query, XML generation, signing, and sending.
    
    # IMPORTANT: The following line is commented out because it performs real actions:
    # - Connects to a database
    # - Writes a file (lote_dps.xml)
    # - Sends a request to an external server
    # 
    # To run this, you must:
    # 1. Ensure your .env file is correctly configured for your database and certificates.
    # 2. Make sure you have a database with the expected tables and data.
    # 3. Uncomment the line below.
    
    # result = nfse_client.send()
    
    # print("\n--- Resultado ---")
    # print(f"Status: {result.get('status')}")
    # print(f"Mensagem: {result.get('message')}")
    print("\nExemplo concluído.")
    print("Para executar de verdade, configure seu .env e descomente as linhas finais em main.py.")
    print("Lembre-se de instalar as dependências com: pip install -r requirements.txt")
