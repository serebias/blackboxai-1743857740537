<?php 
require 'header.php';
require 'config.php';

$conn = connectDB();
$quartos = $conn->query("SELECT id as quarto_id, room_number as numero, room_type as tipo, floor as andar, price as preco, status FROM rooms ORDER BY floor, room_number")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="max-w-6xl mx-auto py-8">
    <h2 class="text-3xl font-bold mb-6 text-center">Mapa dos Quartos</h2>
    
    <!-- Filtros -->
    <div class="flex justify-between mb-6">
        <div class="flex space-x-4">
            <span class="flex items-center">
                <span class="w-4 h-4 bg-green-500 mr-2"></span> Disponível
            </span>
            <span class="flex items-center">
                <span class="w-4 h-4 bg-red-500 mr-2"></span> Ocupado
            </span>
            <span class="flex items-center">
                <span class="w-4 h-4 bg-yellow-500 mr-2"></span> Manutenção
            </span>
        </div>
        <div>
            <label for="andar" class="mr-2">Filtrar por andar:</label>
            <select id="andar" class="border rounded px-2 py-1">
                <option value="0">Todos</option>
                <?php
                $andares = array_unique(array_column($quartos, 'andar'));
                foreach($andares as $andar) {
                    echo "<option value='$andar'>$andar° Andar</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <!-- Mapa dos Quartos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach($quartos as $quarto): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:scale-105 
                <?php 
                if($quarto['status'] == 'disponível') echo 'border-l-4 border-green-500';
                elseif($quarto['status'] == 'ocupado') echo 'border-l-4 border-red-500';
                else echo 'border-l-4 border-yellow-500';
                ?>">
                <div class="p-4">
                    <h3 class="text-xl font-semibold">Quarto <?= $quarto['numero'] ?></h3>
                    <p class="text-gray-600"><?= $quarto['andar'] ?>° Andar</p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            <?php 
                            if($quarto['status'] == 'disponível') echo 'bg-green-100 text-green-800';
                            elseif($quarto['status'] == 'ocupado') echo 'bg-red-100 text-red-800';
                            else echo 'bg-yellow-100 text-yellow-800';
                            ?>">
                            <?= ucfirst($quarto['status']) ?>
                        </span>
                        <a href="reservar.php?quarto=<?= $quarto['quarto_id'] ?>" 
                           class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700 transition">
                            Reservar
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
// Filtro por andar
document.getElementById('andar').addEventListener('change', function() {
    const andarSelecionado = this.value;
    const quartos = document.querySelectorAll('.grid > div');
    
    quartos.forEach(quarto => {
                const andarQuarto = quarto.querySelector('p').textContent.includes(andarSelecionado + '°');
        if(andarSelecionado == 0 || andarQuarto) {
            quarto.style.display = 'block';
        } else {
            quarto.style.display = 'none';
        }
    });
});
</script>

<?php require 'footer.php'; ?>