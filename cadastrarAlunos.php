<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Alunos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1,
        h2 {
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="date"] {
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

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
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
    <h1>Cadastro de Alunos</h1>

    <?php
    $arquivoAlunos = 'alunos.txt';
    $listaAlunos = file_exists($arquivoAlunos) ? file($arquivoAlunos, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

    $alunoAtual = ['nome' => '', 'cpf' => '', 'matricula' => '', 'nascimento' => ''];
    $indiceAtual = -1;

    if (isset($_GET['edit'])) {
        $indiceAtual = intval($_GET['edit']);
        $dadosAluno = explode(';', $listaAlunos[$indiceAtual]);
        $alunoAtual = [
            'nome' => $dadosAluno[0],
            'cpf' => $dadosAluno[1],
            'matricula' => $dadosAluno[2],
            'nascimento' => $dadosAluno[3]
        ];
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nomeAluno = trim($_POST['nome']);
        $cpfAluno = trim($_POST['cpf']);
        $matriculaAluno = trim($_POST['matricula']);
        $nascimentoAluno = trim($_POST['nascimento']);
        $indiceEdicao = isset($_POST['indice']) ? intval($_POST['indice']) : -1;

        $dadosAluno = "$nomeAluno;$cpfAluno;$matriculaAluno;$nascimentoAluno";

        if ($indiceEdicao === -1 && !empty($nomeAluno)) {
            file_put_contents($arquivoAlunos, $dadosAluno . PHP_EOL, FILE_APPEND | LOCK_EX);
        }

        if ($indiceEdicao >= 0 && !empty($nomeAluno)) {
            $listaAlunos[$indiceEdicao] = $dadosAluno;
            file_put_contents($arquivoAlunos, implode(PHP_EOL, $listaAlunos) . PHP_EOL);
        }

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_GET['delete'])) {
        $indiceExcluir = intval($_GET['delete']);
        unset($listaAlunos[$indiceExcluir]);
        file_put_contents($arquivoAlunos, implode(PHP_EOL, $listaAlunos) . PHP_EOL);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    ?>

    <form action="" method="post">
        <label for="nome">Nome do Aluno:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($alunoAtual['nome']); ?>" required>

        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($alunoAtual['cpf']); ?>" required>

        <label for="matricula">Matrícula:</label>
        <input type="text" id="matricula" name="matricula"
            value="<?php echo htmlspecialchars($alunoAtual['matricula']); ?>" required>

        <label for="nascimento">Data de Nascimento:</label>
        <input type="date" id="nascimento" name="nascimento"
            value="<?php echo htmlspecialchars($alunoAtual['nascimento']); ?>" required>

        <?php if ($indiceAtual >= 0): ?>
            <input type="hidden" name="indice" value="<?php echo $indiceAtual; ?>">
            <button type="submit">Salvar</button>
        <?php else: ?>
            <button type="submit">Cadastrar</button>
        <?php endif; ?>
    </form>

    <h2>Alunos Cadastrados</h2>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Matrícula</th>
                <th>Data de Nascimento</th>
                <th>Opções</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaAlunos as $indice => $aluno):
                $dadosAluno = explode(';', $aluno); ?>
                <tr>
                    <td><?php echo htmlspecialchars($dadosAluno[0]); ?></td>
                    <td><?php echo htmlspecialchars($dadosAluno[1]); ?></td>
                    <td><?php echo htmlspecialchars($dadosAluno[2]); ?></td>
                    <td><?php echo htmlspecialchars($dadosAluno[3]); ?></td>
                    <td>
                        <a href="?edit=<?php echo $indice; ?>"><button class="edit-button">Editar</button></a>
                        <a href="?delete=<?php echo $indice; ?>"
                            onclick="return confirm('Tem certeza que deseja excluir este aluno?');"><button
                                class="delete-button">Excluir</button></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>