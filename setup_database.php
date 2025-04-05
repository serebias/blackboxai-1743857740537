<?php
require 'config.php';
$conn = connectDB();

try {
    // Tabela de quartos
    $conn->exec("CREATE TABLE IF NOT EXISTS quartos (
        quarto_id INT AUTO_INCREMENT PRIMARY KEY,
        andar INT NOT NULL,
        numero VARCHAR(10) NOT NULL,
        status ENUM('disponível', 'ocupado', 'manutenção') DEFAULT 'disponível',
        cor VARCHAR(7) DEFAULT '#4CAF50',
        UNIQUE KEY (andar, numero)
    )");

    // Tabela de reservas
    $conn->exec("CREATE TABLE IF NOT EXISTS reservas (
        reserva_id INT AUTO_INCREMENT PRIMARY KEY,
        quarto_id INT,
        nome_hospede VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        telefone VARCHAR(20) NOT NULL,
        checkin DATE NOT NULL,
        checkout DATE NOT NULL,
        status ENUM('pendente', 'check-in', 'check-out') DEFAULT 'pendente',
        data_reserva TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (quarto_id) REFERENCES quartos(quarto_id)
    )");

    echo "Tabelas criadas com sucesso!";
} catch (PDOException $e) {
    die("Erro ao criar tabelas: " . $e->getMessage());
}
?>