<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
        require 'header.php';
        require_once 'beast.php';
        require_once 'json.php';
        require_once 'beast_home.php';

        // Load all beasts from json.php
        $allBeasts = $fantasticBeasts;

        // If search query exists, filter beasts
        if(isset($_GET['search'])) {
            $searchQuery = strtolower($_GET['search']);
            $filteredBeasts = array_filter($allBeasts, function($beast) use ($searchQuery) {
                return strpos(strtolower($beast->getName()), $searchQuery) !== false;
            });
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
