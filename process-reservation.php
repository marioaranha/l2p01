<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bateau_id = $_POST['bateau_id'] ?? null;
    $nom = $_POST['nom'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    
    // Validação dos campos
    if (!$bateau_id || !$nom || !$telephone) {
        $_SESSION['error'] = "Tous les champs sont obligatoires.";
        header('Location: reservation-form.php?bateau_id=' . $bateau_id);
        exit;
    }

    try {
        // Verifica se já existe uma reserva ativa para este telefone
        if (hasActiveReservation($pdo, $telephone)) {
            $_SESSION['error'] = "Vous avez déjà une réservation en cours avec ce numéro de téléphone.";
            header('Location: reservation-form.php?bateau_id=' . $bateau_id);
            exit;
        }
        
        // Tenta fazer a reserva
        if (ajouterReservation($pdo, $bateau_id, $nom, $telephone)) {
            $_SESSION['success'] = "Votre réservation a été confirmée. Le paiement sera effectué sur place.";
            header('Location: confirmation.php');
        } else {
            throw new Exception("Erreur lors de la réservation");
        }
    } catch(Exception $e) {
        $_SESSION['error'] = "Une erreur est survenue lors de la réservation. Veuillez réessayer.";
        header('Location: reservation-form.php?bateau_id=' . $bateau_id);
    }
    exit;
} else {
    // Se alguém tentar acessar este arquivo diretamente
    header('Location: index.php');
    exit;
}
?>  