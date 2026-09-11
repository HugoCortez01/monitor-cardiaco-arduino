<p align="center">
  <img src="assets/logo.png" alt="Cortez Dev" width="180"/>
</p>

<h1 align="center">💓 Monitor Cardíaco com Arduino</h1>

<p align="center">
  Projeto apresentado na <strong>Feteps</strong> (Feira Tecnológica do Centro Paula Souza) —
  Etec Professor José Ignácio Azevedo Filho, Ituverava-SP
</p>

---

## 📋 Sobre o projeto

Este projeto mede os batimentos cardíacos em tempo real usando um **sensor de pulso** (fotopletismografia por infravermelho) conectado a um **Arduino Uno**, e exibe o resultado em um **dashboard web** interativo — com **modo de alto contraste** e **comandos de voz** para acessibilidade.

O usuário informa sua idade, e o sistema calcula automaticamente as zonas de frequência cardíaca (repouso, normal, atividade moderada, intensa e alerta de risco), com base na fórmula da Frequência Cardíaca Máxima (FCM = 220 − idade).

## 🔄 Como funciona

```
Sensor de pulso → Arduino → Serial (USB) → Python (ler_serial.py)
      → dados_sensor.txt → PHP (ler_batimento.php) → Página web (index.php)
```

1. O **Arduino** lê o sensor de pulso e calcula a média de BPM a cada ciclo de 10 segundos, enviando o resultado pela porta serial (e tocando um beep a cada batida detectada)
2. Um **script Python** (`ler_serial.py`) escuta a porta serial e grava a leitura mais recente em um arquivo de texto
3. Um **script PHP** (`ler_batimento.php`) lê esse arquivo e disponibiliza o dado para a página
4. A **página web** (`index.php`) busca o dado via JavaScript e atualiza o dashboard em tempo real, colorindo o resultado de acordo com a zona de frequência cardíaca


## ✨ Funcionalidades

- 📊 Cálculo de zonas de frequência cardíaca personalizadas por idade
- 🔊 Feedback sonoro (buzzer) a cada batida detectada no Arduino
- 🎙️ **Comandos de voz** (ex: "minha idade é 20", "iniciar medição", "qual a frequência")
- ♿ **Modo de alto contraste** para acessibilidade
- 🖥️ Dashboard responsivo com animação do ícone de coração sincronizada com o BPM

## 🛠️ Tecnologias utilizadas

<p>
  <img src="https://img.shields.io/badge/Arduino-00979D?style=for-the-badge&logo=arduino&logoColor=white"/>
  <img src="https://img.shields.io/badge/Python-3776AB?style=for-the-badge&logo=python&logoColor=white"/>
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white"/>
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black"/>
  <img src="https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white"/>
  <img src="https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white"/>
</p>

**Hardware:** Arduino Uno + Sensor de Pulso (Pulse Sensor Amped) + Buzzer + LED

**Biblioteca Arduino:** [PulseSensor Playground](https://github.com/WorldFamousElectronics/PulseSensorPlayground) (instalar pelo Gerenciador de Bibliotecas da IDE do Arduino)

## 📁 Estrutura do projeto

```
├── arduino/
│   └── monitor_cardiaco.ino    # Código do Arduino (leitura do sensor + BPM)
├── python/
│   └── ler_serial.py           # Script que lê a porta serial e grava os dados
├── web/
│   ├── index.php                # Dashboard principal
│   └── ler_batimento.php        # Endpoint que retorna o BPM mais recente
├── docs/
│   └── GUIA_DE_USO.md           # Passo a passo de instalação e execução
└── assets/                      # Imagens usadas neste README
```

## 🚀 Como rodar

Veja o passo a passo completo em [`docs/GUIA_DE_USO.md`](docs/GUIA_DE_USO.md).

## 👤 Autor

**Hugo Cortez** — Estudante de Ciência da Computação (UNIFRAN) | Técnico em Informática (Etec Ituverava)

[LinkedIn](https://www.linkedin.com/in/hugo-cortez-8418103a5/) · [GitHub](https://github.com/HugoCortez01)
