<?php
session_start(); // Deve ser a primeira linha
require_once 'config.php';

/* Fonctions */
function getImagesFromDirectory($path) {
    if (!is_dir($path)) {
        return [];
    }
    return glob($path . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
}

function formatShipName($name) {
    // Amélioration de la correction des noms de bateaux
    $name = str_replace(['_', '-'], ' ', $name);
    $name = ucwords(strtolower($name));
    
    // Corrections spécifiques pour le français
    $name = str_replace(' De ', ' de ', $name);
    $name = str_replace(' Du ', ' du ', $name);
    $name = str_replace(' Des ', ' des ', $name);
    $name = str_replace(' Le ', ' le ', $name);
    $name = str_replace(' La ', ' la ', $name);
    $name = str_replace(' L\'', ' l\'', $name);
    
    return trim($name);
}

function getBateauId($pdo, $nom) {
    $stmt = $pdo->prepare("SELECT id FROM bateaux WHERE nom = ?");
    $stmt->execute([$nom]);
    $result = $stmt->fetch();
    return $result ? $result['id'] : null;
}

function afficherGalerie($webPath, $images, $limit = 6) {
    global $pdo;
    $images = array_slice($images, 0, $limit);
    foreach ($images as $image) {
        afficherImage($webPath, $image, $pdo);
    }
}

function afficherImage($webPath, $image, $pdo) {
    $title = formatShipName(pathinfo($image, PATHINFO_FILENAME));
    $bateauId = getBateauId($pdo, $title);
    
    echo "<div class='bateau-container'>";
    echo "  <div class='image-wrapper'>";
    echo "    <img src='{$webPath}" . basename($image) . "' alt='Bateau {$title}' class='bateau-image' loading='lazy'>";
    echo "  </div>";
    echo "  <div class='image-info'>";
    echo "    <h3>{$title}</h3>";
    
    if ($bateauId) {
        echo "    <a href='reservation-form.php?bateau_id={$bateauId}' 
                    class='btn-selection' 
                    onclick='return confirmReservation();'>
                    Sélectionné
                </a>";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO bateaux (nom, categorie_id, image_path) VALUES (?, 1, ?)");
            $stmt->execute([$title, $webPath . basename($image)]);
            $bateauId = $pdo->lastInsertId();
            
            echo "    <a href='reservation-form.php?bateau_id={$bateauId}' 
                        class='btn-selection' 
                        onclick='return confirmReservation();'>
                        Sélectionné
                    </a>";
        } catch(PDOException $e) {
            echo "    <span class='btn-selection disabled'>Non disponible</span>";
        }
    }
    
    echo "  </div>";
    echo "</div>";
}

// Verificar mensagens de sessão
if (isset($_SESSION['error'])) {
    echo "<div class='alert alert-error'>" . htmlspecialchars($_SESSION['error']) . "</div>";
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    echo "<div class='alert alert-success'>" . htmlspecialchars($_SESSION['success']) . "</div>";
    unset($_SESSION['success']);
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location de Bateaux</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <script>
        function confirmReservation() {
            return confirm('Voulez-vous confirmer la réservation de ce bateau ?');
        }
    </script>
</head>
<body>
    <!-- Resto do seu HTML aqui -->
</body>
</html>
<body>
    <header class="header">
        <div class="logo">
            <h1>Collection de Bateaux</h1>
        </div>
        <nav class="navigation">
            <ul class="menu">
                <li><a href="index.html">Accueil</a></li>
                <li><a href="bateaux.html">Bateaux</a></li>
                <li><a href="contact.html">Contact</a></li>
            </ul>
        </nav>
        <div class="header-description">
            <p>Découvrez notre collection exceptionnelle de bateaux de guerre et de navigation</p>
        </div>
    </header>

    <div class="sections-container">
        <!-- Section Bateaux de Guerre -->
        <section class="section-guerre">
            <h2 class="section-title">Bateaux de Guerre</h2>
            
            <div class="promoG">
    <section class="promoG-content">
        <div class="promoG-text">
            <h2 class="promoG-title">Aventure Maritime Extraordinaire</h2>
            <div class="promoG-description">
                <p>Pourquoi ne pas louer un bateau de guerre et vivre une aventure inoubliable ?</p>
                <p>Avec notre flotte, vous pouvez imaginer envahir de nouveaux horizons, explorer des eaux inconnues et, surtout, ressentir l'excitation de la mer !</p>
                <p>Louez un bateau de navigation et partez à la découverte !   Réserver votre Invasion</p>
                <p></p>
                <p></p>
            </div>
                  </div>
    </section>
</div>

            <div class="galerie">
                <?php
                $imagesGuerre = getImagesFromDirectory(ASSETS_PATH_GUERRE);
                if (!empty($imagesGuerre)) {
                    afficherGalerie(WEB_PATH_GUERRE, $imagesGuerre);
                } else {
                    echo "<p class='notice'>Aucun bateau de guerre disponible</p>";
                }
                ?>
            </div>
        </section>

        <!-- Section Bateaux de Navigation -->
        <section class="section-navigation">
            <h2 class="section-title">Bateaux de Navigation</h2>


            <div class="promoN">
    <section class="promoN-content">
        <div class="promoN-text">
            <h2 class="promoN-title">Évasion Maritime Paisible</h2>
            <div class="promoN-description">
                <p>Embarquez pour une aventure paisible en mer avec nos bateaux de navigation !</p>
                <p>Explorez les plus belles côtes, ressentez la brise marine et laissez-vous porter par les vagues.</p>
                <p>Que ce soit pour une journée de détente ou une excursion prolongée, nos bateaux vous offrent la liberté de découvrir de nouveaux horizons en toute tranquillité.</p>
                <p>Louez un bateau de guerre et partez à l'assaut des vagues ! La liberté et l'aventure vous attendent.</p>
                <p></p>
                <p></p>
            
            </div>
        </div>
    </section>
</div>


            <div class="galerie">
                <?php
                $imagesNavigation = getImagesFromDirectory(ASSETS_PATH_NAVIGATION);
                if (!empty($imagesNavigation)) {
                    afficherGalerie(WEB_PATH_NAVIGATION, $imagesNavigation);
                } else {
                    echo "<p class='notice'>Aucun bateau de navigation disponible</p>";
                }
                ?>
            </div>
        </section>
    </div>

    <footer class="site-footer">
        <div class="footer-content">
            <p>© 2025 Collection de Bateaux. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>