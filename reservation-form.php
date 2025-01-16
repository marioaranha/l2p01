<?php
session_start();
require_once 'config.php';

if(isset($_GET['bateau_id'])) {
    try {
        $bateau = getBateauDetails($pdo, $_GET['bateau_id']);
        if (!$bateau) {
            $_SESSION['error'] = "Bateau non trouvé.";
            header('Location: index.php');
            exit;
        }
    } catch(Exception $e) {
        $_SESSION['error'] = "Erreur lors de la récupération des détails du bateau.";
        header('Location: index.php');
        exit;
    }
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
        .reservation-form {
            max-width: 500px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .bateau-details {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
        }
        .reservation-submit {
            width: 100%;
            padding: 1rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.1rem;
        }
        .reservation-submit:hover {
            background: var(--secondary-color);
        }
        .error-message {
            color: #d63031;
            background: #ffe5e5;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="reservation-form">
        <h2>Confirmation de Réservation</h2>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($_SESSION['error']); ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_GET['bateau_id']) && isset($bateau)): ?>
            <div class="bateau-details">
                <h3>Bateau sélectionné : <?php echo htmlspecialchars($bateau['nom']); ?></h3>
                <p>Le paiement sera effectué sur place</p>
            </div>
            
            <form action="process-reservation.php" method="POST">
                <input type="hidden" name="bateau_id" value="<?php echo htmlspecialchars($_GET['bateau_id']); ?>">
                
                <div class="form-group">
                    <label for="nom">Nom complet</label>
                    <input type="text" id="nom" name="nom" required>
                </div>
                
                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" required>
                </div>
                
                <button type="submit" class="reservation-submit">Confirmer la réservation</button>
            </form>
        <?php else: ?>
            <p>Aucun bateau sélectionné.</p>
            <a href="index.php" class="reservation-submit" style="display: inline-block; text-align: center; text-decoration: none; margin-top: 1rem;">
                Retour à l'accueil
            </a>
        <?php endif; ?>
    </div>
</body>
</html>