<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Perguntas e Respostas</title>
    <style>
          body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1, h2 {
            color: #333;
        }

        form {
            margin-bottom: 20px;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
            background: #fff;
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
    <h1>Perguntas e Respostas</h1>

    <?php
    

    $arqPerguntas = 'perguntas.txt';
    $arqUsuarios = 'usuarios.txt';
    $listaPerguntas = file_exists($arqPerguntas) ? file($arqPerguntas, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
    $usuarios = file_exists($arqUsuarios) ? file($arqUsuarios, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

    $perguntaAtual = ['texto' => '', 'respostas' => [], 'respostaCorreta' => ''];
    $indiceAtual = -1;

    if (isset($_GET['edit'])) {
        $indiceAtual = intval($_GET['edit']);
        $dadosPergunta = explode(';', $listaPerguntas[$indiceAtual]);
        $perguntaAtual = [
            'texto' => $dadosPergunta[1],
            'respostas' => array_slice($dadosPergunta, 2, 4),
            'respostaCorreta' => $dadosPergunta[6]
        ];
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['tipo']) && $_POST['tipo'] === 'usuario') {

            $usuario = trim($_POST['usuario']);
            $senha = trim($_POST['senha']);
            if (!empty($usuario) && !empty($senha)) {
                file_put_contents($arqUsuarios, "$usuario;$senha" . PHP_EOL, FILE_APPEND | LOCK_EX);
            }
        } else {
            
            $textoPergunta = trim($_POST['texto']);
            $respostas = [
                trim($_POST['respostaA']),
                trim($_POST['respostaB']),
                trim($_POST['respostaC']),
                trim($_POST['respostaD'])
            ];
            $respostaCorreta = trim($_POST['respostaCorreta']);
            $indiceEdicao = isset($_POST['indice']) ? intval($_POST['indice']) : -1;

            $novoID = count($listaPerguntas) + 1;

            $dadosPergunta = "$novoID;$textoPergunta;" . implode(';', $respostas) . ";$respostaCorreta";

            if ($indiceEdicao === -1 && !empty($textoPergunta)) {
                file_put_contents($arqPerguntas, $dadosPergunta . PHP_EOL, FILE_APPEND | LOCK_EX);
            } elseif ($indiceEdicao >= 0 && !empty($textoPergunta)) {
                $listaPerguntas[$indiceEdicao] = $dadosPergunta;
                file_put_contents($arqPerguntas, implode(PHP_EOL, $listaPerguntas) . PHP_EOL);
            }
        }

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_GET['delete'])) {
        $indiceExcluir = intval($_GET['delete']);
        unset($listaPerguntas[$indiceExcluir]);
        file_put_contents($arqPerguntas, implode(PHP_EOL, $listaPerguntas) . PHP_EOL);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    ?>

    <h2>Cadastro de Usuários</h2>
    <form action="" method="post">
        <input type="hidden" name="tipo" value="usuario">
        <label for="usuario">Nome de Usuário:</label>
        <input type="text" id="usuario" name="usuario" required>
        
        <label for="senha">Senha:</label>
        <input type="text" id="senha" name="senha" required>

        <button type="submit">Cadastrar Usuário</button>
    </form>

    <h2>Perguntas Cadastradas</h2>
    <form action="" method="post">
        <label for="texto">Texto da Pergunta:</label>
        <input type="text" id="texto" name="texto" value="<?php echo ($perguntaAtual['texto']); ?>" required>

        <label>Respostas:</label>
        <label for="respostaA">A:</label>
        <input type="text" id="respostaA" name="respostaA" value="<?php echo ($perguntaAtual['respostas'][0] ?? ''); ?>" required>
        <label for="respostaB">B:</label>
        <input type="text" id="respostaB" name="respostaB" value="<?php echo ($perguntaAtual['respostas'][1] ?? ''); ?>" required>
        <label for="respostaC">C:</label>
        <input type="text" id="respostaC" name="respostaC" value="<?php echo ($perguntaAtual['respostas'][2] ?? ''); ?>" required>
        <label for="respostaD">D:</label>
        <input type="text" id="respostaD" name="respostaD" value="<?php echo ($perguntaAtual['respostas'][3] ?? ''); ?>" required>

        <label for="respostaCorreta">Resposta Correta (A, B, C ou D):</label>
        <input type="text" id="respostaCorreta" name="respostaCorreta" value="<?php echo ($perguntaAtual['respostaCorreta']); ?>" required>

        <?php if ($indiceAtual >= 0): ?>
            <input type="hidden" name="indice" value="<?php echo $indiceAtual; ?>">
            <button type="submit">Salvar</button>
        <?php else: ?>
            <button type="submit">Cadastrar</button>
        <?php endif; ?>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Pergunta</th>
                <th>Respostas</th>
                <th>Resposta Correta</th>
                <th>Opções</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaPerguntas as $indice => $pergunta):
                $dadosPergunta = explode(';', $pergunta); ?>
                <tr>
                    <td><?php echo ($dadosPergunta[0]); ?></td>
                    <td><?php echo ($dadosPergunta[1]); ?></td>
                    <td>A: <?php echo ($dadosPergunta[2]); ?>, B: <?php echo ($dadosPergunta[3]); ?>, C: <?php echo ($dadosPergunta[4]); ?>, D: <?php echo ($dadosPergunta[5]); ?></td>
                    <td><?php echo ($dadosPergunta[6]); ?></td>
                    <td>
                        <a href="?edit=<?php echo $indice; ?>"><button class="edit-button">Editar</button></a>
                        <a href="?delete=<?php echo $indice; ?>" onclick="return confirm('Tem certeza que deseja excluir esta pergunta?');">
                            <button class="delete-button">Excluir</button>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>