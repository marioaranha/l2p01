<?php
// Configuração do banco de dados
define('DB_HOST', 'localhost:3308');
define('DB_NAME', 'bateux');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configuração dos caminhos (movido do index.php)
if (!defined('ASSETS_PATH_GUERRE')) {
    define('ASSETS_PATH_GUERRE', __DIR__ . '/assets/img/bateaux_g/');
    define('ASSETS_PATH_NAVIGATION', __DIR__ . '/assets/img/bateaux_n/');
    define('WEB_PATH_GUERRE', 'assets/img/bateaux_g/');
    define('WEB_PATH_NAVIGATION', 'assets/img/bateaux_n/');
}

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch(PDOException $e) {
    die("Erreur de connexion: " . $e->getMessage());




}

function getBateauDetails($pdo, $id) {
    try {
        $stmt = $pdo->prepare("SELECT nom FROM bateaux WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch(PDOException $e) {
        error_log("Erreur lors de la récupération des détails du bateau: " . $e->getMessage());
        return false;
    }
}
// Função para verificar se já existe uma reserva ativa
function hasActiveReservation($pdo, $telephone) {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE telephone = ? AND statut = 'en_attente'");
        $stmt->execute([$telephone]);
        return $stmt->fetchColumn() > 0;
    } catch(PDOException $e) {
        error_log("Erreur de vérification de réservation: " . $e->getMessage());
        return false;
    }
}

// Função para adicionar uma nova reserva
function ajouterReservation($pdo, $bateau_id, $nom, $telephone) {
    try {
        $stmt = $pdo->prepare("INSERT INTO reservations (bateau_id, nom_client, telephone, statut) VALUES (?, ?, ?, 'en_attente')");
        return $stmt->execute([$bateau_id, $nom, $telephone]);
    } catch(PDOException $e) {
        error_log("Erreur d'ajout de réservation: " . $e->getMessage());
        return false;
    }
}

?>