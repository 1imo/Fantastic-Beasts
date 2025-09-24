<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        require_once 'beast.php';
        
        // Retrieve the URL parameter
        $beastName = $_GET['name'];

        // Load the JSON data
        $jsonData = file_get_contents('beasts.json');
        $beasts = json_decode($jsonData, true);

        // Find the beast data
        $beastData = null;
        foreach ($beasts as $data) {
            if ($data['name'] === $beastName) {
                $beastData = $data;
                break;
            }
        }
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <meta name="description" content="<?php echo $beastData ? htmlspecialchars($beastData['description']) : 'Detailed information about magical creatures from the Fantastic Beasts universe.'; ?>">
    <title>Fantastic Beasts - <?php echo htmlspecialchars($beastName); ?></title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="specific.css">
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
        require_once 'beast_home.php';
        require_once 'header.php';

        if (!$beastData) {
            echo '<h1>Beast not found</h1>';
            exit;
        }

        $beastObj = new FantasticBeast(
            $beastData['name'],
            $beastData['classification'],
            $beastData['description']
        );
        
        displayFantasticBeasts([$beastObj]);

    ?>
</body>
</html>