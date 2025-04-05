<?php
$dbPath = __DIR__ . '/hotel.db';

function connectDB() {
    global $dbPath;
    try {
        $conn = new PDO("sqlite:$dbPath");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

function initDatabase() {
    $conn = connectDB();
    
    $conn->exec("CREATE TABLE IF NOT EXISTS rooms (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        room_number TEXT NOT NULL UNIQUE,
        room_type TEXT NOT NULL,
        price REAL NOT NULL,
        status TEXT DEFAULT 'available'
    )");

    $conn->exec("CREATE TABLE IF NOT EXISTS reservations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        room_id INTEGER NOT NULL,
        guest_name TEXT NOT NULL,
        email TEXT NOT NULL,
        check_in DATE NOT NULL,
        check_out DATE NOT NULL,
        FOREIGN KEY (room_id) REFERENCES rooms(id)
    )");
}
?>