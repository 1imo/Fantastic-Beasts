<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fantastic Beasts - <?php echo $_GET['name'] ?></title> <!-- Probably not the best way, unsanitised and executed input -->
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="specific.css">
</head>
<body>
    <?php
        require_once 'beast_home.php';
        require_once 'beast.php';
        require_once 'header.php';

        // Retrieve the URL parameter
        $beastName = $_GET['name'];

        // Load the JSON data
        $jsonData = file_get_contents('beasts.json');
        $beasts = json_decode($jsonData, true); // Decode as an associative array

        // Find the beast object with the matching name
        $beastObject = [];
        foreach ($beasts as $beastData) {
            if ($beastData['name'] === $beastName) {
                $beastObj = new FantasticBeast(
                    $beastData['name'],
                    $beastData['classification'],
                    $beastData['description']
                );
                $beastObject[] = $beastObj;
                break;
            }
        }

        if (empty($beastObject)) {
            echo '<h1>Beast not found</h1>';
            exit;
        }

        displayFantasticBeasts($beastObject);

    ?>
</body>
</html>