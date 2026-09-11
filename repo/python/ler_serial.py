# =============================================================
#           SCRIPT PYTHON DE DIAGNÓSTICO
# =============================================================
import serial
import time

# --- IMPORTANTE: Verifique se esta é a porta COM correta! ---
porta_serial = 'COM4'  # Mude aqui se a porta do seu Arduino for outra
velocidade = 9600
arquivo_saida = 'dados_sensor.txt'

print("--- INICIANDO SCRIPT DE DIAGNÓSTICO ---")
print(f"Tentando se conectar na porta: {porta_serial} a {velocidade} bps...")

try:
    # Tenta estabelecer a conexão com o Arduino
    conexao = serial.Serial(porta_serial, velocidade, timeout=2)
    print(">>> SUCESSO! Conexão com a porta serial estabelecida.")
    time.sleep(2) # Espera 2 segundos para o Arduino estabilizar

    print("Entrando no loop de leitura... Pressione Ctrl+C para parar.")
    while True:
        # Tenta ler uma linha de dados vinda do Arduino
        linha = conexao.readline().decode('utf-8').strip()
        
        # Se a linha não estiver vazia, processa
        if linha:
            print(f"Dados recebidos do Arduino: '{linha}'")
            
            # Garante que os dados estão no formato esperado ("bpm,0")
            if ',' in linha:
                try:
                    # Tenta escrever os dados no arquivo de texto
                    with open(arquivo_saida, 'w') as f:
                        f.write(linha)
                    print(f">>> SUCESSO! Arquivo '{arquivo_saida}' atualizado.")
                except Exception as e:
                    print(f"!!! ERRO AO ESCREVER NO ARQUIVO: {e}")
            else:
                print("...dado recebido, mas formato incorreto (faltou a vírgula). Ignorando.")
        else:
            # Esta mensagem aparecerá se a conexão estiver aberta, mas nada chegar
            print("...ouvindo, mas nenhum dado novo foi recebido.")
        
        time.sleep(1) # Espera 1 segundo entre as leituras

except serial.SerialException as e:
    print("\n!!! ERRO CRÍTICO DE CONEXÃO SERIAL !!!")
    print(f"Detalhe: {e}")
    print("\nPossíveis Causas:")
    print("1. A porta COM está errada. Verifique na IDE do Arduino qual é a porta correta.")
    print("2. O Monitor Serial da IDE do Arduino está aberto (ele 'prende' a porta). FECHE-O!")
    print("3. O cabo USB está com mau contato ou o Arduino travou.")

except Exception as e:
    print(f"!!! OCORREU UM ERRO INESPERADO: {e}")

input("\nPressione Enter para fechar o script.") # Pausa o script no final para lermos a mensagem