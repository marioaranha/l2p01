<?php
require_once 'config.php';

// Verifica se há uma ação
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update':
                $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
                $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
                $categorie = filter_input(INPUT_POST, 'categorie', FILTER_SANITIZE_NUMBER_INT);
                $image_type = $categorie == 1 ? WEB_PATH_GUERRE : WEB_PATH_NAVIGATION;
                $image_name = filter_input(INPUT_POST, 'image_name', FILTER_SANITIZE_STRING);
                $image_path = $image_type . $image_name . '.jpg';
                
                try {
                    $stmt = $pdo->prepare("
                        UPDATE bateaux 
                        SET nom = ?, 
                            categorie_id = ?, 
                            image_path = ?,
                            date_modification = CURRENT_TIMESTAMP
                        WHERE id = ?
                    ");
                    $stmt->execute([$nom, $categorie, $image_path, $id]);
                    $message = "Bateau mis à jour avec succès";
                } catch(PDOException $e) {
                    $error = "Erreur lors de la mise à jour: " . $e->getMessage();
                }
                break;
            
            case 'add':
                $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
                $categorie = filter_input(INPUT_POST, 'categorie', FILTER_SANITIZE_NUMBER_INT);
                $image_type = $categorie == 1 ? WEB_PATH_GUERRE : WEB_PATH_NAVIGATION;
                $image_name = filter_input(INPUT_POST, 'image_name', FILTER_SANITIZE_STRING);
                $image_path = $image_type . $image_name . '.jpg';
                
                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO bateaux (nom, categorie_id, image_path) 
                        VALUES (?, ?, ?)
                    ");
                    $stmt->execute([$nom, $categorie, $image_path]);
                    $message = "Nouveau bateau ajouté avec succès";
                } catch(PDOException $e) {
                    $error = "Erreur lors de l'ajout: " . $e->getMessage();
                }
                break;
        }
    }
}

// Buscar todos os barcos
try {
    $stmt = $pdo->query("
        SELECT b.*, 
               CASE 
                   WHEN b.categorie_id = 1 THEN 'Bateaux de Guerre'
                   WHEN b.categorie_id = 2 THEN 'Bateaux de Navigation'
               END as categorie_nom
        FROM bateaux b 
        ORDER BY b.categorie_id, b.nom
    ");
    $bateaux = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Erreur de récupération des données: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Collection de Bateaux</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <header class="header">
        <h1>Administration des Bateaux</h1>
        <a href="index.php" class="btn-retour">Retour au site</a>
    </header>

    <div class="admin-container">
        <!-- Mensagens -->
        <?php if (isset($message)): ?>
            <div class="message success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Formulário para novo barco -->
        <form class="bateau-edit-form" method="POST">
            <h2>Ajouter un nouveau bateau</h2>
            <input type="hidden" name="action" value="add">
            
            <div class="form-group">
                <label>Nom du bateau:</label>
                <input type="text" name="nom" required>
            </div>
            
            <div class="form-group">
                <label>Catégorie:</label>
                <select name="categorie" id="categorie" required>
                    <option value="1">Bateaux de Guerre</option>
                    <option value="2">Bateaux de Navigation</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Nom de l'image:</label>
                <input type="text" name="image_name" id="image_name" required>
                <small>L'extension .jpg sera ajoutée automatiquement</small>
            </div>
            
            <button type="submit" class="btn-ajouter">Ajouter le bateau</button>
        </form>

        <!-- Lista de barcos existentes -->
        <!-- ... Resto do código permanece igual ... -->
    </div>
</body>
</html>