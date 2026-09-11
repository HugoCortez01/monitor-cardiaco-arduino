# Guia de instalação e uso

## 1. Preparar o ambiente Python

Se o computador estiver em uma rede com proxy (como em laboratórios de escola), pode ser necessário desativar o proxy ou usar uma internet alternativa (ex: compartilhamento de dados do celular) apenas para instalar o Python.

1. Baixe e instale o [Python](https://www.python.org/downloads/)
2. Instale a biblioteca de comunicação serial:
   ```bash
   pip install pyserial
   ```

## 2. Preparar o Arduino

1. Abra a IDE do Arduino
2. Instale a biblioteca **PulseSensor Playground** pelo Gerenciador de Bibliotecas (Sketch → Incluir Biblioteca → Gerenciar Bibliotecas → busque "PulseSensor Playground")
3. Em **Ferramentas → Placa**, selecione **Arduino Uno**
4. Em **Ferramentas → Porta**, selecione a porta correta (ex: COM4)
5. Faça o upload do código em `arduino/monitor_cardiaco.ino`

> ⚠️ Importante: depois do upload, **feche o Monitor Serial da IDE do Arduino**. Se ele ficar aberto, a porta fica "presa" e o script Python não consegue se conectar.

## 3. Rodar o script Python

Abra o terminal na pasta do projeto e execute:

```bash
python python/ler_serial.py
```

Esse script fica escutando a porta serial e grava a leitura mais recente no arquivo `dados_sensor.txt`, que o PHP depois lê.

> Antes de rodar, confira no arquivo `ler_serial.py` se a variável `porta_serial` está com a porta COM correta do seu Arduino.

## 4. Rodar a página web

O projeto usa PHP, então precisa de um servidor local (ex: [XAMPP](https://www.apachefriends.org/)).

1. Coloque a pasta `web/` dentro de `htdocs` do XAMPP (junto com o `dados_sensor.txt` gerado pelo script Python)
2. Inicie o Apache
3. Acesse `http://localhost/web/index.php` no navegador

## Resumo do fluxo

```
Sensor de pulso → Arduino → Serial (USB) → Python (ler_serial.py)
      → dados_sensor.txt → PHP (ler_batimento.php) → Página web (index.php)
```
