<?php
// Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "anket";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Bağlantı başarısız: " . $conn->connect_error);
}

// Sorular ve seçenekler
$sorular = [
    "PHP'yi ne kadar seviyorsunuz?",
    "Bu sektörde çalışmak ister miydiniz?",
    "Veritabanı çalışmayı seviyor musunuz?",
    "VSCode tecrübeniz var mı?",
    "Programlamada tecrübeniz nedir?"
];

$secenekler = ["Çok", "Az", "Hiç", "Bilmiyorum"];

// Oylama gönderildiğinde işleme
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['oyla'])) {
    foreach ($sorular as $index => $soru) {
        $cevap = $_POST['cevap_' . $index];
        $sql = "INSERT INTO anket_sonuclari (soru, cevap) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $soru, $cevap);
        $stmt->execute();
    }
    echo "<div class='success'>Oylarınız başarıyla kaydedildi!</div>";
}

// Sonuçları alma
$sonuclar = [];
foreach ($sorular as $soru) {
    $sql = "SELECT cevap, COUNT(*) as sayi FROM anket_sonuclari WHERE soru = ? GROUP BY cevap";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $soru);
    $stmt->execute();
    $result = $stmt->get_result();

    $toplam = 0;
    $cevap_sayilari = [];
    while ($row = $result->fetch_assoc()) {
        $cevap_sayilari[$row['cevap']] = $row['sayi'];
        $toplam += $row['sayi'];
    }

    $yuzdeler = [];
    foreach ($secenekler as $secenek) {
        $sayi = $cevap_sayilari[$secenek] ?? 0;
        $yuzde = $toplam > 0 ? ($sayi / $toplam) * 100 : 0;
        $yuzdeler[$secenek] = round($yuzde, 2);
    }

    $sonuclar[$soru] = $yuzdeler;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anket</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f7f7f7;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0;
        }
        h1 {
            text-align: left;
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            margin-top: 15px;
            display: block;
            color: #555;
        }
        input[type="radio"] {
            margin-right: 10px;
        }
        button {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        button:hover {
            background: #0056b3;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
        }
        .sonuclar {
            margin-top: 30px;
        }
        .sonuclar h2 {
            text-align: left;
            color: #333;
            font-size: 20px;
            margin-bottom: 15px;
        }
        .bar {
            height: 20px;
            background: linear-gradient(to right, #4caf50, #81c784);
            border-radius: 5px;
            margin: 5px 0;
        }
        .bar-wrapper {
            background: #e9ecef;
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 10px;
        }
        .bar-text {
            font-size: 14px;
            padding: 5px;
            color: #333;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Anket</h1>
        <form method="POST">
            <?php foreach ($sorular as $index => $soru) : ?>
                <label><?= htmlspecialchars($soru) ?></label>
                <?php foreach ($secenekler as $secenek) : ?>
                    <input type="radio" name="cevap_<?= $index ?>" value="<?= htmlspecialchars($secenek) ?>" required>
                    <?= htmlspecialchars($secenek) ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <button type="submit" name="oyla">Oy Ver</button>
        </form>

        <div class="sonuclar">
            <h2>Sonuçlar</h2>
            <?php foreach ($sonuclar as $soru => $yuzdeler) : ?>
                <h3><?= htmlspecialchars($soru) ?></h3>
                <?php foreach ($yuzdeler as $secenek => $yuzde) : ?>
                    <div class="bar-wrapper">
                        <div class="bar" style="width: <?= $yuzde ?>%;"></div>
                        <div class="bar-text">%<?= htmlspecialchars($yuzde) ?> - <?= htmlspecialchars($secenek) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
