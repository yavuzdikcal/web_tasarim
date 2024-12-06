<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dinamik Tablo Oluşturma</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            border: 1px solid black;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>
    <h1>Dinamik Tablo Oluşturma</h1>
    <form method="POST" action="">
        <label for="satir">Satır Sayısı:</label>
        <input type="number" id="satir" name="satir" min="1" required>
        <br><br>
        <label for="sutun">Sütun Sayısı:</label>
        <input type="number" id="sutun" name="sutun" min="1" required>
        <br><br>
        <button type="submit">Tablo Oluştur</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $satir = intval($_POST["satir"]);
        $sutun = intval($_POST["sutun"]);

        echo "<h2>Oluşturulan Tablo ($satir x $sutun)</h2>";
        echo "<table>";
        for ($i = 0; $i < $satir; $i++) {
            echo "<tr>";
            for ($j = 0; $j < $sutun; $j++) {
                $rastgeleSayi = rand(1, 100);
                echo "<td>$rastgeleSayi</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    ?>
</body>
</html>
