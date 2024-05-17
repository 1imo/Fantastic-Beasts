<?php
function displayFantasticBeasts($beasts) {
    echo '<div class="fantastic-beasts">';
    foreach ($beasts as $beast) {
        echo '<div class="beast-card">';
        echo '<div class="beast-name-container">';
        echo '<h2 class="beast-name">' . $beast->getName() . '</h2>';
        $classificationLevel = $beast->getClassification();
        $classification = str_repeat("X", $classificationLevel);
        $classificationClass = 'classification-' . strtolower($classification);
        echo '<span class="beast-classification ' . $classificationClass . '">' . $classification . '</span>';
        echo '</div>';
        echo '<p class="beast-description">' . $beast->getDescription() . '</p>';
        echo '<a href="beast-details.php?name=' . urlencode($beast->getName()) . '">View Details</a>';
        echo '</div>';
    }
    echo '</div>';
}
?>