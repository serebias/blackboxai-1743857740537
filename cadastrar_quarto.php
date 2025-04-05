<?php
require 'header.php';

// Processar formulário se enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'config.php';
    $conn = connectDB();
    
    $andar = $_POST['andar'];
    $numero = $_POST['numero'];
    $status = $_POST['status'];
    
    try {
        $stmt = $conn->prepare("INSERT INTO quartos (andar, numero, status) VALUES (?, ?, ?)");
        $stmt->execute([$andar, $numero, $status]);
        
        echo '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>Quarto cadastrado com sucesso!</p>
              </div>';
    } catch (PDOException $e) {
        echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>Erro ao cadastrar quarto: ' . $e->getMessage() . '</p>
              </div>';
    }
}
?>

<div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl my-8">
    <div class="p-8">
        <div class="uppercase tracking-wide text-sm text-blue-600 font-semibold mb-1">Painel Administrativo</div>
        <h1 class="block mt-1 text-lg leading-tight font-medium text-black">Cadastrar Novo Quarto</h1>
        
        <form method="POST" class="mt-6">
            <div class="mb-4">
                <label for="andar" class="block text-gray-700 text-sm font-bold mb-2">Andar:</label>
                <input type="number" id="andar" name="andar" required 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-4">
                <label for="numero" class="block text-gray-700 text-sm font-bold mb-2">Número do Quarto:</label>
                <input type="text" id="numero" name="numero" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-6">
                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status Inicial:</label>
                <select id="status" name="status" class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                    <option value="disponível">Disponível</option>
                    <option value="ocupado">Ocupado</option>
                    <option value="manutenção">Manutenção</option>
                </select>
            </div>
            
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    <i class="fas fa-save mr-2"></i> Cadastrar Quarto
                </button>
                <a href="admin.php" class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800">
                    Voltar ao Painel
                </a>
            </div>
        </form>
    </div>
</div>

<?php require 'footer.php'; ?>