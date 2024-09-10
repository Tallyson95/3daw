<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Disciplinas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 15px;
            background-color: #5cb85c;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #4cae4c;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .edit-button {
            background-color: #f0ad4e;
        }

        .delete-button {
            background-color: #d9534f;
        }

        .edit-button:hover {
            background-color: #ec971f;
        }

        .delete-button:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <h1>Cadastro de Disciplinas</h1>

    <?php
    $arquivo = 'disciplinas.txt';
    $disciplinas = file_exists($arquivo) ? file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

    // Variáveis para edição
    $disciplinaParaEditar = '';
    $indiceParaEditar = -1;

    // Verifica se está editando
    if (isset($_GET['edit'])) {
        $indiceParaEditar = intval($_GET['edit']);
        $disciplinaParaEditar = $disciplinas[$indiceParaEditar];
    }

    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nomeDisciplina = trim($_POST['nome']);
        $indiceEdicao = isset($_POST['indice']) ? intval($_POST['indice']) : -1;

        // Cadastrar nova disciplina
        if ($indiceEdicao === -1 && !empty($nomeDisciplina)) {
            file_put_contents($arquivo, $nomeDisciplina . PHP_EOL, FILE_APPEND | LOCK_EX);
        }

        // Editar disciplina existente
        if ($indiceEdicao >= 0 && !empty($nomeDisciplina)) {
            $disciplinas[$indiceEdicao] = $nomeDisciplina;
            file_put_contents($arquivo, implode(PHP_EOL, $disciplinas) . PHP_EOL);
        }

        // Redireciona para evitar reenvio do formulário
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    // Excluir disciplina
    if (isset($_GET['delete'])) {
        $indiceParaExcluir = intval($_GET['delete']);
        unset($disciplinas[$indiceParaExcluir]);
        file_put_contents($arquivo, implode(PHP_EOL, $disciplinas) . PHP_EOL);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    ?>

    <!-- Formulário para cadastrar ou editar -->
    <form action="" method="post">
        <label for="nome">Nome da Disciplina:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($disciplinaParaEditar); ?>" required>
        <?php if ($indiceParaEditar >= 0): ?>
            <input type="hidden" name="indice" value="<?php echo $indiceParaEditar; ?>">
            <button type="submit">Salvar</button>
        <?php else: ?>
            <button type="submit">Cadastrar</button>
        <?php endif; ?>
    </form>

    <h2>Disciplinas Cadastradas</h2>
    <table>
        <thead>
            <tr>
                <th>Nome da Disciplina</th>
                <th>Opções</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($disciplinas as $indice => $disciplina): ?>
                <tr>
                    <td><?php echo htmlspecialchars($disciplina); ?></td>
                    <td>
                        <a href="?edit=<?php echo $indice; ?>"><button class="edit-button">Editar</button></a>
                        <a href="?delete=<?php echo $indice; ?>" onclick="return confirm('Tem certeza que deseja excluir esta disciplina?');"><button class="delete-button">Excluir</button></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
