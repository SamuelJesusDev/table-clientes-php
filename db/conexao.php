<?php 
$servidor ="localhost";
$usuario="";
$senha="";
$banco="primeiro_banco";

//conexão
$pdo = new PDO("mysql:host=$servidor; dbname=$banco", $usuario, $senha);

//função para sanitizar (limpar entradas)
function limparPost($dado){
    $dado = trim($dado);
    $dado = stripslashes($dado);
    $dado = htmlspecialchars($dado);
    return $dado;
}
?>