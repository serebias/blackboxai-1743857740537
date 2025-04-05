<?php
require 'header.php';
require 'config.php';

$conn = connectDB();

// Se um quarto específico foi selecionado
$quarto_id = isset($_GET['quarto']) ? intval($_GET['quarto']) : null;
$quarto_info = null;

if ($quarto_id) {
    $stmt = $conn->prepare("SELECT * FROM quartos WHERE quarto_id = ?");
    $stmt->execute([$quarto_id]);
    $quarto_info = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Processar reserva
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quarto_id = $_POST['quarto_id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];

    try {
        // Verificar disponibilidade
        $stmt = $conn->prepare("SELECT * FROM reservas WHERE quarto_id = ? AND (
            (checkin <= ? AND checkout >= ?) OR
            (checkin <= ? AND checkout >= ?) OR
            (checkin >= ? AND checkout <= ?)
        )");
        $stmt->execute([$quarto_id, $checkin, $checkin, $checkout, $checkout, $checkin, $checkout]);
        
        if ($stmt->rowCount() > 0) {
            $mensagem = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                            <p>Quarto não disponível nas datas selecionadas.</p>
                         </div>';
        } else {
            // Fazer reserva
            $stmt = $conn->prepare("INSERT INTO reservas 
                                  (quarto_id, nome_hospede, email, telefone, checkin, checkout) 
                                  VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$quarto_id, $nome, $email, $telefone, $checkin, $checkout]);
            
            $mensagem = '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                            <p>Reserva realizada com sucesso!</p>
                         </div>';
        }
    } catch (PDOException $e) {
        $mensagem = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                        <p>Erro ao processar reserva: ' . $e->getMessage() . '</p>
                     </div>';
    }
}
?>

<div class="max-w-4xl mx-auto py-8 px-4">
    <h1 class="text-3xl font-bold text-center mb-8">Reservar Quarto</h1>
    
    <?php if (isset($mensagem)) echo $mensagem; ?>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <?php if ($quarto_info): ?>
            <div class="mb-6 p-4 border border-gray-200 rounded-lg">
                <h2 class="text-xl font-semibold mb-2">Quarto Selecionado</h2>
                <p><span class="font-medium">Número:</span> <?= $quarto_info['numero'] ?></p>
                <p><span class="font-medium">Andar:</span> <?= $quarto_info['andar'] ?>°</p>
                <p><span class="font-medium">Status:</span> 
                    <span class="<?= 
                        $quarto_info['status'] == 'disponível' ? 'text-green-600' : 
                        ($quarto_info['status'] == 'ocupado' ? 'text-red-600' : 'text-yellow-600') ?>">
                        <?= ucfirst($quarto_info['status']) ?>
                    </span>
                </p>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <?php if ($quarto_id): ?>
                <input type="hidden" name="quarto_id" value="<?= $quarto_id ?>">
            <?php else: ?>
                <div class="mb-4">
                    <label for="quarto_id" class="block text-gray-700 font-medium mb-2">Selecione o Quarto:</label>
                    <select name="quarto_id" id="quarto_id" required
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <?php
                        $quartos = $conn->query("SELECT * FROM quartos WHERE status = 'disponível' ORDER BY andar, numero");
                        foreach ($quartos as $quarto) {
                            echo "<option value='{$quarto['quarto_id']}'>Andar {$quarto['andar']} - Quarto {$quarto['numero']}</option>";
                        }
                        ?>
                    </select>
                </div>
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nome" class="block text-gray-700 font-medium mb-2">Nome Completo:</label>
                    <input type="text" id="nome" name="nome" required
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="email" class="block text-gray-700 font-medium mb-2">E-mail:</label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="telefone" class="block text-gray-700 font-medium mb-2">Telefone:</label>
                    <input type="tel" id="telefone" name="telefone" required
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="checkin" class="block text-gray-700 font-medium mb-2">Data de Check-in:</label>
                    <input type="date" id="checkin" name="checkin" required min="<?= date('Y-m-d') ?>"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="checkout" class="block text-gray-700 font-medium mb-2">Data de Check-out:</label>
                    <input type="date" id="checkout" name="checkout" required
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div class="mt-8 text-center">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                    <i class="fas fa-calendar-check mr-2"></i> Confirmar Reserva
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Validação de datas
document.getElementById('checkin').addEventListener('change', function() {
    const checkin = new Date(this.value);
    const checkoutField = document.getElementById('checkout');
    
    if (this.value) {
        const minCheckout = new Date(checkin);
        minCheckout.setDate(minCheckout.getDate() + 1);
        checkoutField.min = minCheckout.toISOString().split('T')[0];
        
        if (checkoutField.value && new Date(checkoutField.value) <= checkin) {
            checkoutField.value = '';
        }
    }
});
</script>

<?php require 'footer.php'; ?>