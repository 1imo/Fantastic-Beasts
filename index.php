<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <meta name="description" content="Explore the magical world of Fantastic Beasts and discover detailed information about magical creatures.">
    <title>Fantastic Beasts</title>
    <link rel="canonical" href="https://fantasticbeasts.world/">
    <link rel="stylesheet" href="styles.css">
    <meta name="author" content="Timo Hoyland">
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "Fantastic Beasts",
        "author": {
            "@type": "Person",
            "name": "Timo Hoyland"
        }
    }
    </script>
</head>
<body>
    <?php
        require 'header.php';
        require_once 'beast.php';
        require_once 'json.php';
        require_once 'beast_home.php';
        require_once 'search.php';

        // Load all beasts from json.php
        $allBeasts = $fantasticBeasts;

        // If search query exists, filter beasts using semantic search
        if(isset($_GET['search'])) {
            $searchQuery = $_GET['search'];
            $filteredBeasts = semanticSearchBeasts($allBeasts, $searchQuery, 100);
        } else {
            $filteredBeasts = $allBeasts;
        }

        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'asc';

        // Sort beasts
        switch($sort) {
            case 'asc':
                break;
            case 'desc':
                usort($filteredBeasts, function($a, $b) {
                    return strcmp($b->getName(), $a->getName());
                });
                break;
            case '1to5':
                usort($filteredBeasts, function($a, $b) {
                    return $a->getClassification() - $b->getClassification();
                });
                break;
            case '5to1':
                usort($filteredBeasts, function($a, $b) {
                    return $b->getClassification() - $a->getClassification();
                });
                break;
        }     
    ?>

    <!-- Filter buttons -->
    <div class="filter-buttons">
        <h3>Filter</h3>
        <a href="?sort=asc" class="<?php echo ($sort == 'asc') ? 'selected' : ''; ?>">A-Z</a>
        <a href="?sort=desc" class="<?php echo ($sort == 'desc') ? 'selected' : ''; ?>">Z-A</a>
        <a href="?sort=1to5" class="<?php echo ($sort == '1to5') ? 'selected' : ''; ?>">1-5</a>
        <a href="?sort=5to1" class="<?php echo ($sort == '5to1') ? 'selected' : ''; ?>">5-1</a>
    </div>

    <?php displayFantasticBeasts($filteredBeasts); ?>
</body>
</html>
