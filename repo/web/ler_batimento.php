<?php
/**
 * LER BATIMENTO - Versão Final (Dados Reais)
 * Este script lê o conteúdo do arquivo 'dados_sensor.txt',
 * que é atualizado constantemente pelo script Python.
 */

$arquivo_dados = 'dados_sensor.txt';

if (file_exists($arquivo_dados)) {
    // Lê e envia o conteúdo do arquivo (ex: "85,0")
    $dados = file_get_contents($arquivo_dados);
    echo trim($dados);
} else {
    // Se o arquivo não for encontrado, envia um valor de erro
    echo "0,0"; 
}
?>