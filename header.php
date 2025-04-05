<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Hotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .quarto-disponivel { background-color: #4CAF50; }
        .quarto-ocupado { background-color: #FF4444; }
        .quarto-manutencao { background-color: #FFD700; }
    </style>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 p-4 text-white shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold flex items-center">
                <i class="fas fa-hotel mr-2"></i> Hotel VIP
            </h1>
            <ul class="flex space-x-6">
                <li><a href="index.php" class="hover:underline"><i class="fas fa-map mr-1"></i> Mapa</a></li>
                <li><a href="reservar.php" class="hover:underline"><i class="fas fa-calendar-check mr-1"></i> Reservar</a></li>
                <li><a href="admin.php" class="hover:underline"><i class="fas fa-user-cog mr-1"></i> Admin</a></li>
            </ul>
        </div>
    </nav>
    <main class="container mx-auto p-4">