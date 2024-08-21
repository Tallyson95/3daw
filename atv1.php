<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            background-color: #5849BE;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
            color: white;
            text-align: center;
        }

        .containerSection {
            background-color: #FFF;
            display: flex;
            flex-direction: column;
            width: 30vw;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        input, select, button {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
            background-color: #5849BE;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #3e36a5;
        }
    </style>

</head>

<body>
    <h1>Atividade 01.PHP</h1>
    <form class="containerSection" name="calcular" method="GET">
        <input name="num1" placeholder="Digite o primeiro número" type="number" required />
        <select name="opcao" >
            <option value="1">+</option>
            <option value="2">-</option>
            <option value="3">*</option>
            <option value="4">/</option>
        </select>
        <input name="num2" placeholder="Digite o segundo número" type="number" required />
        <button type="submit">Calcular</button>
    </form>

    <?php
        $num1 = $_GET['num1'];
        $num2 = $_GET['num2'];
        $opcao = $_GET['opcao'];

        function calcular($num1, $num2, $opcao)
        {
            switch ($opcao) {
                case 1:
                    return $num1 + $num2;
                case 2:
                    return $num1 - $num2;
                case 3:
                    return $num1 * $num2;
                case 4:
                    return $num2 != 0 ? $num1 / $num2 : "Erro: Divisão por zero";
                default:
                    return null;
            }
        }

        $resultado = calcular($num1, $num2, $opcao);
        echo "<h2>Resultado: $resultado</h2>";
    
    ?>
</body>

</html>
