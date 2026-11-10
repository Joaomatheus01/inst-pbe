<?php
$host = "localhost";
$banco = "instagram_db";
$usuario = "root";
$senha = "";
try {
    $con = new PDO(
        "mysql:host=$host;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha
    );
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}
?>