<?php
require 'header.php';
require 'config.php';

$conn = connectDB();

// Processar ações (check-in/check-out)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $reserva_id = intval($_GET['id']);
    
    try {
        if ($_GET['action'] === 'checkin') {
            // Atualizar status da reserva e do quarto
            $conn->beginTransaction();
            
            $stmt = $conn->prepare("UPDATE reservas SET status = 'check-in' WHERE reserva_id = ?");
            $stmt->execute([$reserva_id]);
            
            $stmt = $conn->prepare("UPDATE quartos q JOIN reservas r ON q.quarto_id = r.quarto_id 
                                   SET q.status = 'ocupado' WHERE r.reserva_id = ?");
            $stmt->execute([$reserva_id]);
            
            $conn->commit();
            
            $mensagem = '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                            Check-in realizado com sucesso!
                         </div>';
        } 
        elseif ($_GET['action'] === 'checkout') {
            // Atualizar status da reserva e do quarto
            $conn->beginTransaction();
            
            $stmt = $conn->prepare("UPDATE reservas SET status = 'check-out' WHERE reserva_id = ?");
            $stmt->execute([$reserva_id]);
            
            $stmt = $conn->prepare("UPDATE quartos q JOIN reservas r ON q.quarto_id = r.quarto_id 
                                   SET q.status = 'disponível' WHERE r.reserva_id = ?");
            $stmt->execute([$reserva_id]);
            
            $conn->commit();
            
            $mensagem = '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                            Check-out realizado com sucesso!
                         </div>';
        }
    } catch (PDOException $e) {
        $conn->rollBack();
        $mensagem = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                        Erro: ' . $e->getMessage() . '
                     </div>';
    }
}

// Obter todas as reservas
$reservas = $conn->query("
    SELECT r.*, q.andar, q.numero 
    FROM reservas r
    JOIN quartos q ON r.quarto_id = q.quarto_id
    ORDER BY r.checkin, r.status
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8">Painel Administrativo</h1>
    
    <?php if (isset($mensagem)) echo $mensagem; ?>
    
    <div class="flex justify-between mb-6">
        <a href="cadastrar_quarto.php" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
           <i class="fas fa-plus mr-2"></i> Cadastrar Novo Quarto
        </a>
        
        <div class="flex space-x-4">
            <span class="flex items-center">
                <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span> Pendente
            </span>
            <span class="flex items-center">
                <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span> Check-in
            </span>
            <span class="flex items-center">
                <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span> Check-out
            </span>
        </div>
    </div>
    
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quarto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hóspede</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Período</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($reservas as $reserva): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">Andar <?= $reserva['andar'] ?> - Quarto <?= $reserva['numero'] ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900"><?= $reserva['nome_hospede'] ?></div>
                        <div class="text-sm text-gray-500"><?= $reserva['email'] ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            <?= date('d/m/Y', strtotime($reserva['checkin'])) ?> - 
                            <?= date('d/m/Y', strtotime($reserva['checkout'])) ?>
                        </div>
                        <div class="text-sm text-gray-500">
                            <?= $reserva['telefone'] ?>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php
                        $status_class = [
                            'pendente' => 'bg-yellow-100 text-yellow-800',
                            'check-in' => 'bg-blue-100 text-blue-800',
                            'check-out' => 'bg-green-100 text-green-800'
                        ];
                        ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $status_class[$reserva['status']] ?>">
                            <?= ucfirst($reserva['status']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <?php if ($reserva['status'] == 'pendente'): ?>
                            <a href="admin.php?action=checkin&id=<?= $reserva['reserva_id'] ?>" 
                               class="text-blue-600 hover:text-blue-900 mr-3"
                               onclick="return confirm('Confirmar check-in para <?= $reserva['nome_hospede'] ?>?')">
                               <i class="fas fa-sign-in-alt mr-1"></i> Check-in
                            </a>
                        <?php elseif ($reserva['status'] == 'check-in'): ?>
                            <a href="admin.php?action=checkout&id=<?= $reserva['reserva_id'] ?>" 
                               class="text-green-600 hover:text-green-900"
                               onclick="return confirm('Confirmar check-out para <?= $reserva['nome_hospede'] ?>?')">
                               <i class="fas fa-sign-out-alt mr-1"></i> Check-out
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require 'footer.php'; ?>