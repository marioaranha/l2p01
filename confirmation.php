<?php
session_start();
if (!isset($_SESSION['success'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Réservation</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .confirmation-container {
            max-width: 500px;
            margin: 3rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .success-message {
            color: #00b894;
            background: #e5ffe5;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
        }
        .return-button {
            display: inline-block;
            padding: 1rem 2rem;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        .return-button:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="confirmation-container">
        <h2>Réservation Confirmée</h2>
        <?php if(isset($_SESSION['success'])): ?>
            <div class="success-message">
                <?php 
                    echo htmlspecialchars($_SESSION['success']);
                    unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>
        <a href="index.php" class="return-button">Retour à l'accueil</a>
    </div>
</body>
</html>