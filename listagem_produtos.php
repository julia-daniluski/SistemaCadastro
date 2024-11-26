<?php include('valida_sessao.php'); ?> <!-- Inclui o arquivo que verifica se o usuário tem uma sessão válida. -->
<?php include('conexao.php'); ?> <!-- Inclui o arquivo que faz a conexão com o banco de dados. -->

<?php
// Verifica se foi passado um ID para exclusão via GET
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id']; // Recebe o ID do produto que será excluído
    $sql = "DELETE FROM produtos WHERE id = '$delete_id'"; // Cria a query SQL para deletar o produto com o ID fornecido
    if ($conn->query($sql) === TRUE) { // Executa a query
        $mensagem = "Produto excluído com sucesso!"; // Se a exclusão for bem-sucedida, define a mensagem de sucesso
    } else {
        $mensagem = "Erro ao excluir produto: " . $conn->error; // Caso ocorra um erro, define a mensagem de erro com a descrição do erro
    }
}

// Realiza uma consulta no banco de dados para listar todos os produtos e seus respectivos fornecedores
$produtos = $conn->query("SELECT p.id, p.nome, p.descricao, p.preco, p.imagem, f.nome AS fornecedor_nome FROM produtos p JOIN fornecedores f ON p.fornecedor_id = f.id");
?>

<!DOCTYPE html>
<html lang="pt-br"> <!-- Define o idioma da página como português do Brasil -->
<head>
    <meta charset="UTF-8"> <!-- Define o conjunto de caracteres como UTF-8 para suportar caracteres especiais -->
    <title>Listagem de Produtos</title> <!-- Título da página -->
    <link rel="stylesheet" href="styles.css"> <!-- Inclui o arquivo de estilo CSS -->
</head>
<body>
    <div class="container"> <!-- Div principal que envolve o conteúdo -->
        <h2>Listagem de Produtos</h2> <!-- Título da página -->
        
        <!-- Exibe a mensagem de sucesso ou erro, se existirem -->
        <?php if (isset($mensagem)) echo "<p class='message " . ($conn->error ? "error" : "success") . "'>$mensagem</p>"; ?>
        
        <!-- Tabela que irá listar os produtos -->
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Fornecedor</th>
                <th>Imagem</th>
                <th>Ações</th>
            </tr>
            
            <!-- Loop que percorre os resultados da consulta e exibe cada produto em uma linha da tabela -->
            <?php while ($row = $produtos->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td> <!-- Exibe o ID do produto -->
                <td><?php echo $row['nome']; ?></td> <!-- Exibe o nome do produto -->
                <td><?php echo $row['descricao']; ?></td> <!-- Exibe a descrição do produto -->
                <td><?php echo $row['preco']; ?></td> <!-- Exibe o preço do produto -->
                <td><?php echo $row['fornecedor_nome']; ?></td> <!-- Exibe o nome do fornecedor -->
                <td>
                    <!-- Verifica se o produto tem uma imagem associada e exibe a imagem ou um texto "Sem imagem" -->
                    <?php if ($row['imagem']): ?>
                        <img src="<?php echo $row['imagem']; ?>" alt="Imagem do produto" style="max-width: 120px;"> <!-- Exibe a imagem com largura máxima de 120px -->
                    <?php else: ?>
                        Sem imagem <!-- Exibe o texto "Sem imagem" caso não exista uma imagem -->
                    <?php endif; ?>
                </td>
                <td>
                    <!-- Links para editar ou excluir o produto -->
                    <a href="cadastro_produto.php?edit_id=<?php echo $row['id']; ?>">Editar</a> <!-- Link para editar o produto -->
                    <a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a> <!-- Link para excluir o produto, com confirmação antes da exclusão -->
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        
        <!-- Link para voltar à página principal -->
        <a href="index.php" class="back-button">Voltar</a>
    </div>
</body>
</html>
