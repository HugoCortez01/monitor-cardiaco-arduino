/*
 * =============================================================
 * CÓDIGO FINAL - VERSÃO TIMER DE 10 SEGUNDOS
 * =============================================================
 * Sensor: Pulse Sensor Amped (3 fios)
 * Funcionalidade: Calcula o BPM médio durante um ciclo de 10 segundos.
 * Extras: Buzzer para feedback sonoro a cada batida.
 * =============================================================
*/

#define USE_ARDUINO_INTERRUPTS true
#include <PulseSensorPlayground.h>

// --- Configurações do Hardware ---
const int PULSE_INPUT_PIN = A0;
const int LED_PISCANDO_PIN = 13;
const int BUZZER_PIN = 8;
const int THRESHOLD = 550;

PulseSensorPlayground pulseSensor; 

// --- Configurações da Média ---
const int TEMPO_DE_MEDICAO_MS = 10000; // ATUALIZADO: 10 segundos
float somaBPM = 0;
int leiturasValidas = 0;
long tempoInicioCiclo = 0;

void setup() {
  Serial.begin(9600);
  pulseSensor.analogInput(PULSE_INPUT_PIN);
  pulseSensor.blinkOnPulse(LED_PISCANDO_PIN); 
  pulseSensor.setThreshold(THRESHOLD);
  pulseSensor.begin();

  tempoInicioCiclo = millis(); // Inicia o primeiro ciclo
}

void loop() {
  
  // 1. Detecta um batimento
  if (pulseSensor.sawStartOfBeat()) {
    
    // Toca o bipe do hospital
    tone(BUZZER_PIN, 1500, 100); 
    
    // Adiciona o BPM à soma para o cálculo da média
    int myBPM = pulseSensor.getBeatsPerMinute();
    if (myBPM > 30) {
      somaBPM += myBPM;
      leiturasValidas++;
    }
  }

  // 2. Verifica se os 10 segundos já se passaram
  if (millis() - tempoInicioCiclo >= TEMPO_DE_MEDICAO_MS) {
    
    // 3. Calcula a média
    float mediaBPM = 0;
    if (leiturasValidas > 0) {
      mediaBPM = somaBPM / leiturasValidas;
    }

    // 4. Envia o resultado ÚNICO pela serial
    Serial.print((int)mediaBPM);
    Serial.print(",0");
    Serial.println();

    // 5. Zera as variáveis e reinicia o ciclo
    somaBPM = 0;
    leiturasValidas = 0;
    tempoInicioCiclo = millis();
  }

  delay(20);
}