<?php
// --- LÓGICA DO QUESTIONÁRIO ---
$resultado_questionario = '';
$fcm = 0; 
$idade_atual = isset($_POST['idade']) ? $_POST['idade'] : '';

// --- Variáveis com valores padrão para o JavaScript ---
$zona_repouso_max = 59;
$zona_normal_max = 100;
$zona_moderada_min = 101;
$zona_moderada_max = 120;
$zona_intensa_min = 121;
$zona_intensa_max = 150;
$zona_critica_min = 165;

if (isset($_POST['idade']) && intval($_POST['idade']) > 0) {
    $idade = intval($_POST['idade']);
    $fcm = 220 - $idade;
    
    $zona_repouso_max = 59;
    $zona_normal_max = 100;
    $zona_moderada_min = round($fcm * 0.50);
    $zona_moderada_max = round($fcm * 0.70);
    $zona_intensa_min = round($fcm * 0.71);
    $zona_intensa_max = round($fcm * 0.85);
    $zona_critica_min = round($fcm * 0.95);

    $resultado_questionario = "
        <div class='resultado-container'>
            <h3>Resultados para {$idade} anos (FCM: {$fcm} BPM)</h3>
            <ul class='resultado-lista'>
                <li class='zona-repouso'><strong>Repouso:</strong> 40 a {$zona_repouso_max} BPM</li>
                <li class='zona-normal'><strong>Normal:</strong> 60 a {$zona_normal_max} BPM</li>
                <li class='zona-moderada'><strong>Ativ. Moderada:</strong> {$zona_moderada_min} a {$zona_moderada_max} BPM</li>
                <li class='zona-intensa'><strong>Ativ. Intensa:</strong> {$zona_intensa_min} a {$zona_intensa_max} BPM</li>
                <li class='zona-critica'><strong>Alerta de Risco:</strong> Acima de {$zona_critica_min} BPM</li>
            </ul>
        </div>
    ";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Cardíaco</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <style>
        :root {
            --cor-fundo: #1A1D24;
            --cor-card: #2C303A;
            --cor-texto-principal: #EAEAEA;
            --cor-texto-secundario: #9A9A9A;
            --cor-destaque: #00D1B2;
            --cor-sombra: rgba(0, 0, 0, 0.2);
            --cor-repouso: #00D1B2;
            --cor-normal: #3273DC;
            --cor-moderada: #FFDD57;
            --cor-alta: #FF3860;
            --cor-critica: #d32f2f;
            --cor-desconhecido: #7a7a7a;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--cor-fundo);
            color: var(--cor-texto-principal);
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 1150px;
        }

        .card-row {
            display: flex;
            gap: 25px;
            align-items: stretch;
        }

        .card {
            background: linear-gradient(145deg, #313541, #282c35);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 8px 8px 16px #15171c, -8px -8px 16px #373b48;
            border: 1px solid rgba(255, 255, 255, 0.05);
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .card-header i {
            font-size: 20px;
            color: var(--cor-destaque);
            margin-right: 12px;
        }

        .card-header h1,
        .form-card h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 22px;
            margin: 0;
            font-weight: 700;
        }

        #monitor-cardiaco {
            text-align: center;
            margin: 15px 0;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .stat-value {
            font-family: 'Poppins', sans-serif;
            font-size: 72px;
            font-weight: 700;
            line-height: 1;
            color: var(--cor-desconhecido);
            transition: color 0.5s ease;
            margin-bottom: 10px;
        }

        #heart-icon-animation {
            font-size: 40px;
            color: var(--cor-desconhecido);
            animation: beat 1.2s infinite ease-in-out;
            transition: color 0.5s ease;
            margin-top: 10px;
        }

        @keyframes beat {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        .status-text {
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            font-weight: 600;
            margin-top: 20px;
            height: 25px;
            text-align: center;
            transition: color 0.5s ease;
        }

        .form-card {
            justify-content: space-between;
        }

        .form-card h2 {
            text-align: center;
        }

        form {
            text-align: center;
            margin: 15px 0;
        }

        input[type=number] {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #444;
            background-color: #1A1D24;
            color: var(--cor-texto-principal);
            font-size: 16px;
            margin: 0 10px;
            width: 80px;
        }

        button {
            padding: 12px 25px;
            border-radius: 8px;
            border: none;
            background-color: var(--cor-destaque);
            color: #1A1D24;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover:not(:disabled) {
            filter: brightness(1.1);
            transform: translateY(-2px);
        }

        button:disabled {
            background-color: #555;
            color: #888;
            cursor: not-allowed;
        }

        .resultado-container h3 {
            text-align: center;
            font-weight: 400;
            color: var(--cor-texto-secundario);
            border-bottom: 1px solid #444;
            padding-bottom: 10px;
            font-size: 14px;
        }

        .resultado-lista {
            list-style: none;
            padding: 0;
            margin-top: 15px;
            font-size: 13px;
        }

        .resultado-lista li {
            background-color: rgba(0, 0, 0, 0.2);
            padding: 8px 12px;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .resultado-lista li.zona-repouso { border-left: 5px solid var(--cor-repouso); }
        .resultado-lista li.zona-normal { border-left: 5px solid var(--cor-normal); }
        .resultado-lista li.zona-moderada { border-left: 5px solid var(--cor-moderada); }
        .resultado-lista li.zona-intensa { border-left: 5px solid var(--cor-alta); }
        .resultado-lista li.zona-critica { border-left: 5px solid var(--cor-critica); }

        footer {
            text-align: center;
            padding-top: 30px;
        }

        footer img {
            max-width: 150px;
            height: auto;
            opacity: .6;
            transition: opacity .3s ease;
        }

        footer img:hover {
            opacity: 1;
        }

        #acessibilidade-painel {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        #acessibilidade-painel button {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: var(--cor-card);
            color: var(--cor-destaque);
            border: 2px solid var(--cor-destaque);
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #acessibilidade-painel button:hover {
            transform: scale(1.1);
        }

        #acessibilidade-painel button.ativo {
            background-color: var(--cor-destaque);
            color: var(--cor-fundo);
        }

        body.alto-contraste {
            --cor-fundo: #000000;
            --cor-card: #000000;
            --cor-texto-principal: #FFFFFF;
            --cor-texto-secundario: #E0E0E0;
            --cor-destaque: #0096FF;
        }
        body.alto-contraste .card {
            border: 2px solid #3FA9F5;
            background: var(--cor-fundo);
        }
        body.alto-contraste button {
            background-color: #0096FF;
            color: #FFFFFF;
            border: none;
        }
        body.alto-contraste button:hover:not(:disabled) {
            background-color: #1E90FF;
            filter: none;
            transform: translateY(-2px);
        }
        body.alto-contraste .card-header i {
            color: #40CFFF;
        }
        body.alto-contraste input[type=number] {
            background-color: #333;
            color: #FFFFFF;
            border: 1px solid #3FA9F5;
        }
        body.alto-contraste .resultado-lista li {
            border-left: 5px solid #40CFFF !important;
        }
        body.alto-contraste #acessibilidade-painel button {
            background-color: var(--cor-card);
            color: #0096FF;
            border: 2px solid #3FA9F5;
        }
        body.alto-contraste #acessibilidade-painel button.ativo {
            background-color: #0096FF;
            color: #FFFFFF;
        }

        #measurement-controls{
            text-align:center;
            margin-top:15px;
        }
        #timer-display{
            font-size:16px;
            color:var(--cor-destaque);
            font-weight:bold;
            height:25px;
            margin-top:15px;
        }

        @media (max-width: 800px) {
            .card-row {
                flex-direction: column;
            }
            .card {
                margin-bottom: 25px;
            }
            #acessibilidade-painel {
                bottom: 10px;
                left: 10px;
            }
            #acessibilidade-painel button {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
        }
    </style>
</head>

<body>
    <div id="acessibilidade-painel">
        <button id="contraste-btn" title="Ativar Alto Contraste"><i class="fas fa-eye"></i></button>
        <button id="voz-btn" title="Ativar Leitura por Voz e Comandos"><i class="fas fa-volume-up"></i></button>
    </div>

    <div class="container">
        <div class="card-row">
            <div class="card" id="monitor-card">
                <div class="card-header">
                    <i class="fas fa-heart-pulse"></i><h1>Dashboard</h1>
                </div>
                <div id="monitor-cardiaco">
                    <div id="bpm-value" class="stat-value">--</div>
                    <div id="heart-icon-animation" class="fas fa-heart"></div>
                </div>
                <p id="status-text" class="status-text">Pronto para medir</p>
                <div id="measurement-controls">
                    <button id="start-button">Iniciar Medição (10s)</button>
                    <div id="timer-display" style="display: none;"></div>
                </div>
            </div>
            
            <div class="card form-card" id="form-card">
                <h2>Zonas de Atividade</h2>
                <form id="age-form" method="POST" action="">
                    <label for="idade">Sua idade:</label>
                    <input type="number" id="idade" name="idade" required min="1" value="<?php echo $idade_atual; ?>">
                    <button type="submit">Calcular</button>
                </form>
                <div id="resultado"><?php echo $resultado_questionario; ?></div>
            </div>
        </div>
       
    </div>

    <script>
        const startButton = document.getElementById('start-button');
        const timerDisplay = document.getElementById('timer-display');
        let modoAltoContrasteAtivo = false;
        let modoVozAtivo = false;
        const botaoContraste = document.getElementById('contraste-btn');
        const botaoVoz = document.getElementById('voz-btn');
        const synth = window.speechSynthesis;
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        let recognition;

        if (SpeechRecognition) {
            recognition = new SpeechRecognition();
            recognition.lang = 'pt-BR';
            recognition.continuous = true;
            recognition.interimResults = false;
            recognition.onresult = handleVoiceCommand;
            recognition.onend = () => { if (modoVozAtivo) try { recognition.start(); } catch(e){} };
        }

        startButton.addEventListener('click', iniciarMedicao);
        botaoContraste.addEventListener('click', toggleContraste);
        botaoVoz.addEventListener('click', toggleVoz);
        
        // --- NOVO: Lógica para checar se a página foi recarregada por comando de voz ---
        window.onload = function() {
            // Verifica se a "flag" foi deixada no sessionStorage
            if (sessionStorage.getItem('promptForMeasurement') === 'true' && modoVozAtivo) {
                // Adiciona um pequeno delay para a página carregar e a voz não ser cortada
                setTimeout(() => {
                    falarTexto("Zonas de atividade calculadas. Agora, por favor, diga 'iniciar medição'.");
                }, 500);
                
                // Remove a flag para não repetir a mensagem em outros reloads
                sessionStorage.removeItem('promptForMeasurement');
            }
        };

        function iniciarMedicao() {
            startButton.disabled = true;
            timerDisplay.style.display = 'block';
            let tempoRestante = 10;
            timerDisplay.innerText = `Medindo em ${tempoRestante}s...`;
            resetarDisplay();
            const countdown = setInterval(() => {
                tempoRestante--;
                timerDisplay.innerText = `Medindo em ${tempoRestante}s...`;
                if (tempoRestante <= 0) {
                    clearInterval(countdown);
                    timerDisplay.innerText = "Calculando média...";
                    buscarResultadoFinal();
                }
            }, 1000);
        }

        function buscarResultadoFinal() {
            fetch('ler_batimento.php')
                .then(response => response.text())
                .then(data => {
                    const { bpm, statusMessage } = atualizarDisplayComDados(data);
                    startButton.disabled = false;
                    timerDisplay.innerText = "Medição concluída!";
                    if (modoVozAtivo && bpm > 0) {
                        falarTexto(`Medição concluída. A média é ${bpm} batimentos por minuto. Status: ${statusMessage}.`);
                    } else if (modoVozAtivo && bpm === 0) {
                        falarTexto("Não foi possível ler o pulso durante a medição.");
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    document.getElementById('status-text').innerText = 'Erro de conexão';
                    startButton.disabled = false;
                    if (modoVozAtivo) falarTexto("Erro ao conectar com o sensor.");
                });
        }

        function resetarDisplay(){
             document.getElementById('bpm-value').innerText = '--';
             document.getElementById('status-text').innerText = 'Aguarde, calculando média...';
             const corDesconhecido = 'var(--cor-desconhecido)';
             document.getElementById('bpm-value').style.color = corDesconhecido;
             document.getElementById('heart-icon-animation').style.color = corDesconhecido;
             document.getElementById('status-text').style.color = corDesconhecido;
        }

        function atualizarDisplayComDados(data) {
            const zonaCriticaInferior=40;let zonaRepousoMax=<?php echo $zona_repouso_max;?>,zonaNormalMax=<?php echo $zona_normal_max;?>,zonaModeradaMax=<?php echo $zona_moderada_max;?>,zonaIntensaMax=<?php echo $zona_intensa_max;?>,zonaCriticaMin=<?php echo $zona_critica_min;?>;
            const valores=data.split(','),bpm=parseInt(valores[0]),bpmValue=document.getElementById('bpm-value'),heartIcon=document.getElementById('heart-icon-animation'),statusText=document.getElementById('status-text');
            let corAtual = 'var(--cor-desconhecido)', animationSpeed = '1.2s', statusMessage = 'Leitura inválida';

            if(!isNaN(bpm)&&bpm>30){bpmValue.innerText=bpm;if(bpm<zonaCriticaInferior){corAtual='var(--cor-critica)';animationSpeed='2.5s';statusMessage='ALERTA: Ritmo Lento!'}else if(bpm<=zonaRepousoMax){corAtual='var(--cor-repouso)';animationSpeed='1.5s';statusMessage='Média: Ritmo de Repouso'}else if(bpm<=zonaNormalMax){corAtual='var(--cor-normal)';animationSpeed='1.2s';statusMessage='Média: Ritmo Normal'}else if(bpm<=zonaModeradaMax){corAtual='var(--cor-moderada)';animationSpeed='0.9s';statusMessage='Média: Atividade Moderada'}else if(bpm<=zonaIntensaMax){corAtual='var(--cor-alta)';animationSpeed='0.7s';statusMessage='Média: Atividade Intensa'}else{corAtual='var(--cor-critica)';animationSpeed='0.5s';statusMessage='ALERTA CRÍTICO (Alto)!'}}
            else if (bpm === 0) {
                bpmValue.innerText = '??'; statusMessage = 'Não foi possível ler o pulso.'; corAtual = 'var(--cor-desconhecido)';
            }
            
            bpmValue.style.color=corAtual;heartIcon.style.color=corAtual;heartIcon.style.animationDuration=animationSpeed;statusText.innerText=statusMessage;statusText.style.color=corAtual;
            
            return { bpm, statusMessage };
        }

        function falarTexto(texto) {
            if (!modoVozAtivo || !synth) return;
            synth.cancel();
            const utterance = new SpeechSynthesisUtterance(texto);
            utterance.lang = 'pt-BR'; utterance.rate = 1.1;
            synth.speak(utterance);
        }

        function handleVoiceCommand(event) {
            const ultimoComando = event.results[event.results.length - 1][0].transcript.toLowerCase().trim();
            console.log("Comando:", ultimoComando);
            if (ultimoComando.includes("minha idade é") || ultimoComando.includes("tenho")) {
                const match = ultimoComando.match(/\d+/);
                if (match) {
                    const idade = parseInt(match[0]);
                    if (!isNaN(idade)) {
                        document.getElementById('idade').value = idade;
                        falarTexto(`Ok, idade definida para ${idade} anos. Calculando...`);
                        
                        // NOVO: Deixa a "flag" antes de submeter o formulário
                        sessionStorage.setItem('promptForMeasurement', 'true');
                        
                        document.querySelector('#form-card button').click();
                    }
                } else {
                    falarTexto("Não entendi a idade. Por favor, diga, por exemplo: 'minha idade é 25'.");
                }
            } else if (ultimoComando.includes("iniciar medição")) {
                if(!startButton.disabled) {
                   falarTexto("Iniciando medição de 10 segundos. Permaneça imóvel.");
                   iniciarMedicao(); 
                } else {
                    falarTexto("Aguarde a medição atual terminar.");
                }
            } else if (ultimoComando.includes("ler batimento") || ultimoComando.includes("qual a frequência")) {
                const bpm = document.getElementById('bpm-value').innerText;
                const status = document.getElementById('status-text').innerText;
                if (bpm !== '--' && bpm !== '??') {
                    falarTexto(`A última média medida foi ${bpm} batimentos por minuto. Status: ${status}. Diga 'iniciar medição' para uma nova leitura.`);
                } else {
                    falarTexto("Nenhuma medição foi realizada ainda. Diga 'iniciar medição'.");
                }
            }
        }

        function toggleContraste() {
            modoAltoContrasteAtivo = !modoAltoContrasteAtivo;
            document.body.classList.toggle('alto-contraste');
            botaoContraste.classList.toggle('ativo');
            if (modoAltoContrasteAtivo) falarTexto("Alto contraste ativado.");
            else falarTexto("Alto contraste desativado.");
        }

        function toggleVoz() {
            modoVozAtivo = !modoVozAtivo;
            botaoVoz.classList.toggle('ativo');
            if (modoVozAtivo) {
                falarTexto("Modo de voz ativado. Diga 'iniciar medição' para começar.");
                if (recognition) try { recognition.start(); } catch(e){}
            } else {
                falarTexto("Modo de voz desativado.");
                if (recognition) recognition.stop();
                synth.cancel();
            }
        }
    </script>
</body>
</html>