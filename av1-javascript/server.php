<?php
$arqPerguntas = 'perguntas.txt';
$arqUsuarios = 'usuarios.txt';

$listaPerguntas = file_exists($arqPerguntas) ? file($arqPerguntas, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['tipo'] === 'usuario') {
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
        $novoID = count($listaPerguntas) + 1;
        $dadosPergunta = "$novoID;$textoPergunta;" . implode(';', $respostas) . ";$respostaCorreta";

        if (isset($_POST['indice']) && $_POST['indice'] >= 0) {
            $listaPerguntas[$_POST['indice']] = $dadosPergunta;
        } else {
            $listaPerguntas[] = $dadosPergunta;
        }

        file_put_contents($arqPerguntas, implode(PHP_EOL, $listaPerguntas) . PHP_EOL);
    }
    exit;
}

if (isset($_GET['delete'])) {
    $indiceExcluir = intval($_GET['delete']);
    unset($listaPerguntas[$indiceExcluir]);
    file_put_contents($arqPerguntas, implode(PHP_EOL, $listaPerguntas) . PHP_EOL);
    exit;
}

if (isset($_GET['edit'])) {
    $indiceEditar = intval($_GET['edit']);
    $dadosPergunta = explode(';', $listaPerguntas[$indiceEditar]);
    $perguntaAtual = [
        'texto' => $dadosPergunta[1],
        'respostas' => array_slice($dadosPergunta, 2, 4),
        'respostaCorreta' => $dadosPergunta[6]
    ];
    echo json_encode($perguntaAtual);
    exit;
}

foreach ($listaPerguntas as $indice => $pergunta) {
    $dadosPergunta = explode(';', $pergunta);
    echo "<tr>
            <td>{$dadosPergunta[0]}</td>
            <td>{$dadosPergunta[1]}</td>
            <td>A) {$dadosPergunta[2]}<br>B) {$dadosPergunta[3]}<br>C) {$dadosPergunta[4]}<br>D) {$dadosPergunta[5]}</td>
            <td>{$dadosPergunta[6]}</td>
            <td>
                <button class='edit-button' onclick='editarPergunta($indice)'>Editar</button>
                <button class='delete-button' onclick='excluirPergunta($indice)'>Excluir</button>
            </td>
          </tr>";
}
?>
