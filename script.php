<?php
// Configurações de conexão com o banco de dados
$host = "localhost";
$username = "seu_usuario";
$password = "sua_senha";
$dbname = "nome_do_banco_de_dados"; // Nome do seu banco de dados

try {
    // Conexão com o banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para buscar a última atualização de qualquer tabela no banco de dados
    $sql = "
        SELECT table_name AS tabela, update_time AS ultima_modificacao
        FROM information_schema.tables
        WHERE table_schema = :dbname
        AND update_time IS NOT NULL
        ORDER BY update_time DESC
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':dbname', $dbname, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Formatar a data e hora para o padrão brasileiro
        $data_hora = new DateTime($result['ultima_modificacao']);
        $data_hora_formatada = $data_hora->format('d/m/Y H:i:s');

        // Exibir informações
        echo "<h2>Última atualização no banco de dados '$dbname':</h2>";
        echo "<p><strong>Tabela:</strong> {$result['tabela']}</p>";
        echo "<p><strong>Data e Hora da Última Modificação:</strong> $data_hora_formatada</p>";
    } else {
        echo "Nenhuma tabela foi modificada recentemente no banco de dados '$dbname'.";
    }

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
