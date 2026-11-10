
<?php
include_once 'config.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}
$rota = $_GET['rota'] ?? '';
$metodo = $_SERVER['REQUEST_METHOD'];
$corpo = json_decode(file_get_contents("php://input"), true) ?? [];
function resposta($dados, $status = 200) {
    http_response_code($status);
    echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    exit;
}
try {
    if ($rota === 'usuarios/create' && $metodo === 'POST') {
        $senha = password_hash($corpo['senha'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nome, username, email, senha)
                VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->execute([
            $corpo['nome'],
            $corpo['username'],
            $corpo['email'],
            $senha
        ]);
        resposta(["mensagem" => "Usuário cadastrado!"]);
    }
    if ($rota === 'usuarios/read' && $metodo === 'GET') {

        $id = $_GET['id'] ?? null;
        if ($id) {
            $stmt = $con->prepare("SELECT id_usuario, nome, username, email, data_cadastro FROM usuarios WHERE id_usuario = ?");
            $stmt->execute([$id]);
        } else {
            $stmt = $con->query("SELECT id_usuario, nome, username, email, data_cadastro FROM usuarios");
        }
        resposta($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    if ($rota === 'usuarios/update' && $metodo === 'PUT') {
        $sql = "UPDATE usuarios
                SET nome = ?, username = ?, email = ?
                WHERE id_usuario = ?";
        $stmt = $con->prepare($sql);
        $stmt->execute([
            $corpo['nome'],
            $corpo['username'],
            $corpo['email'],
            $corpo['id_usuario']
        ]);
        resposta(["mensagem" => "Usuário atualizado!"]);
    }
    if ($rota === 'usuarios/delete' && $metodo === 'DELETE') {
        $stmt = $con->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$corpo['id_usuario']]);
        resposta(["mensagem" => "Usuário excluído!"]);
    }
    if ($rota === 'publicacoes/create' && $metodo === 'POST') {
        $sql = "INSERT INTO publicacoes (id_usuario, legenda, imagem_url)
                VALUES (?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->execute([
            $corpo['id_usuario'],
            $corpo['legenda'] ?? null,
            $corpo['imagem_url'] ?? null
        ]);
        resposta(["mensagem" => "Publicação cadastrada!"]);
    }
    if ($rota === 'publicacoes/read' && $metodo === 'GET') {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $stmt = $con->prepare("SELECT * FROM publicacoes WHERE id_publicacao = ?");
            $stmt->execute([$id]);
        } else {
            $stmt = $con->query("SELECT * FROM publicacoes");
        }
        resposta($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    if ($rota === 'publicacoes/update' && $metodo === 'PUT') {
        $sql = "UPDATE publicacoes
                SET id_usuario = ?, legenda = ?, imagem_url = ?
                WHERE id_publicacao = ?";
        $stmt = $con->prepare($sql);
        $stmt->execute([
            $corpo['id_usuario'],
            $corpo['legenda'] ?? null,
            $corpo['imagem_url'] ?? null,
            $corpo['id_publicacao']
        ]);
        resposta(["mensagem" => "Publicação atualizada!"]);
    }
    if ($rota === 'publicacoes/delete' && $metodo === 'DELETE') {
        $stmt = $con->prepare("DELETE FROM publicacoes WHERE id_publicacao = ?");
        $stmt->execute([$corpo['id_publicacao']]);
        resposta(["mensagem" => "Publicação excluída!"]);
    }
    if ($rota === 'curtidas/create' && $metodo === 'POST') {
        $sql = "INSERT INTO curtidas (id_usuario, id_publicacao)
                VALUES (?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->execute([
            $corpo['id_usuario'],
            $corpo['id_publicacao']
        ]);
        resposta(["mensagem" => "Curtida cadastrada!"]);
    }
    if ($rota === 'curtidas/read' && $metodo === 'GET') {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $stmt = $con->prepare("SELECT * FROM curtidas WHERE id_curtida = ?");
            $stmt->execute([$id]);
        } else {
            $stmt = $con->query("SELECT * FROM curtidas");
        }
        resposta($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    if ($rota === 'curtidas/update' && $metodo === 'PUT') {
        $sql = "UPDATE curtidas
                SET id_usuario = ?, id_publicacao = ?
                WHERE id_curtida = ?";
        $stmt = $con->prepare($sql);
        $stmt->execute([
            $corpo['id_usuario'],
            $corpo['id_publicacao'],
            $corpo['id_curtida']
        ]);
        resposta(["mensagem" => "Curtida atualizada!"]);
    }
    if ($rota === 'curtidas/delete' && $metodo === 'DELETE') {
        $stmt = $con->prepare("DELETE FROM curtidas WHERE id_curtida = ?");
        $stmt->execute([$corpo['id_curtida']]);
        resposta(["mensagem" => "Curtida excluída!"]);
    }
    resposta(["erro" => "Rota não encontrada!"], 404);
} catch (PDOException $e) {
    resposta([
        "erro" => "Erro no banco de dados",
        "detalhes" => $e->getMessage()
    ], 400);
}
?>
