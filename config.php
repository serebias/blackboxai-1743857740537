<?php
$host = 'localhost';
$dbname = 'qtonnpuq_hotel';
$username = 'qtonnpuq_hotel';
$password = 'eb3kdKQSG4MNQ37ekuZj';

function connectDB() {
    // Tentativa com MySQLi (solução alternativa)
    $conn = new mysqli($GLOBALS['host'], $GLOBALS['username'], 
                     $GLOBALS['password'], $GLOBALS['dbname']);
    
    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8");
    return $conn;
}
?>
