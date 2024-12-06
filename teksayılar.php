<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tek Sayılar</title>
</head>
<body>
    <h1>1-100 Arası Tek Sayılar</h1>
    <ul>
        <?php
        for ($i = 1; $i <= 100; $i++) {
            if ($i % 2 != 0) {
                echo "<li>$i</li>";
            }
        }
        ?>
    </ul>
</body>
</html>
