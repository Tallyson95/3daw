<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomeDisciplina = trim($_POST['nome']);
    
    if (!empty($nomeDisciplina)) {
        $arquivo = 'disciplinas.csv';
        $handle = fopen($arquivo, 'a');
        fputcsv($handle, [$nomeDisciplina]);
        fclose($handle);
    }
    
    header('Location: index.php');
    exit;
}
?>
