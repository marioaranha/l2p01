<?php
/* Configuration principale */
define('ASSETS_PATH_GUERRE', __DIR__ . '/assets/img/bateaux_g/');
define('ASSETS_PATH_NAVIGATION', __DIR__ . '/assets/img/bateaux_n/');
define('WEB_PATH_GUERRE', 'assets/img/bateaux_g/');
define('WEB_PATH_NAVIGATION', 'assets/img/bateaux_n/');

/* Fonctions */
function getImagesFromDirectory($path) {
    if (!is_dir($path)) {
        return [];
    }
    return glob($path . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
}

function afficherImage($webPath, $image) {
    $title = pathinfo($image, PATHINFO_FILENAME);
    echo "<div class='bateau-container'>";
    echo "  <div class='image-wrapper'>";
    echo "    <img src='{$webPath}" . basename($image) . "' alt='Bateau {$title}' class='bateau-image' loading='lazy'>";
    echo "  </div>";
    echo "  <div class='image-info'>";
    echo "    <h3>{$title}</h3>";
    echo "  </div>";
    echo "</div>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection de Bateaux - Guerre et Navigation</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <header class="header">
        <h1>Collection de Bateaux</h1>
        <p>Découvrez notre collection exceptionnelle de bateaux de guerre et de navigation</p>
    </header>

    <div class="sections-container">
        <!-- Section Bateaux de Guerre -->
        <section class="section-guerre">
            <h2 class="section-title">Bateaux de Guerre</h2>
            <div class="galerie">
                <?php
                $imagesGuerre = getImagesFromDirectory(ASSETS_PATH_GUERRE);
                if (!empty($imagesGuerre)) {
                    foreach ($imagesGuerre as $image) {
                        afficherImage(WEB_PATH_GUERRE, $image);
                    }
                } else {
                    echo "<p class='notice'>Aucun bateau de guerre disponible</p>";
                }
                ?>
            </div>
        </section>

        <!-- Section Bateaux de Navigation -->
        <section class="section-navigation">
            <h2 class="section-title">Bateaux de Navigation</h2>
            <div class="galerie">
                <?php
                $imagesNavigation = getImagesFromDirectory(ASSETS_PATH_NAVIGATION);
                if (!empty($imagesNavigation)) {
                    foreach ($imagesNavigation as $image) {
                        afficherImage(WEB_PATH_NAVIGATION, $image);
                    }
                } else {
                    echo "<p class='notice'>Aucun bateau de navigation disponible</p>";
                }
                ?>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion des erreurs d'images
            document.querySelectorAll('.bateau-image').forEach(img => {
                img.addEventListener('error', function() {
                    this.src = 'assets/img/placeholder.jpg';
                    this.alt = 'Image non disponible';
                    this.closest('.bateau-container').classList.add('image-error');
                });
            });

            // Animation au défilement
            const observer = new IntersectionObserver(
                entries => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }
                    });
                },
                { threshold: 0.1 }
            );

            document.querySelectorAll('.bateau-container').forEach(container => {
                container.style.opacity = '0';
                container.style.transform = 'translateY(20px)';
                observer.observe(container);
            });
        });
    </script>
</body>
</html>