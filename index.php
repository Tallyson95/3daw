<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Disciplinas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Cadastro de Disciplinas</h1>

    <form action="cadastrar.php" method="post">
        <label for="nome">Nome da Disciplina:</label>
        <input type="text" id="nome" name="nome" required>
        <button type="submit">Cadastrar</button>
    </form>

    <h2>Disciplinas Cadastradas</h2>
    <table>
        <thead>
            <tr>
                <th>Nome da Disciplina</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $arquivo = 'disciplinas.csv';
            if (file_exists($arquivo)) {
                if (($handle = fopen($arquivo, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        echo "<tr><td>" . htmlspecialchars($data[0]) . "</td></tr>";
                    }
                    fclose($handle);
                }
            }
            ?>
        </tbody>
    </table>
</body>
</html>
