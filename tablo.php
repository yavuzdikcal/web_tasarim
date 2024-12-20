<?php
// Veritabanı bağlantısı
$servername = "localhost";
$username = "root"; // Veritabanı kullanıcı adı
$password = "";     // Veritabanı şifresi
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

// Veritabanına kayıt ekleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ad"]) && isset($_POST["soyad"]) && isset($_POST["email"])) {
    $ad = $_POST["ad"];
    $soyad = $_POST["soyad"];
    $email = $_POST["email"];

    $sql = "INSERT INTO kisi (ad, soyad, email) VALUES ('$ad', '$soyad', '$email')";
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>Kayıt başarıyla eklendi!</p>";
    } else {
        echo "<p style='color:red;'>Hata: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

// Veritabanında arama işlemi
$bulunan = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["arama"])) {
    $arama = $_POST["arama"];
    $sql = "SELECT soyad, email FROM kisi WHERE ad = '$arama'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $bulunan = $result->fetch_assoc();
    } else {
        echo "<p style='color:red;'>Kayıt bulunamadı.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kişi Ekle ve Ara</title>
</head>
<body>
    <h1>Kişi Veritabanı</h1>

    <!-- 1. Form: Kayıt Ekleme -->
    <h2>Kişi Ekle</h2>
    <form method="POST">
        <label for="ad">Ad:</label>
        <input type="text" id="ad" name="ad" required>
        <br><br>
        <label for="soyad">Soyad:</label>
        <input type="text" id="soyad" name="soyad" required>
        <br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br><br>
        <button type="submit">Ekle</button>
    </form>

    <!-- 2. Form: Kişi Arama -->
    <h2>Kişi Bul</h2>
    <form method="POST">
        <label for="arama">Ad:</label>
        <input type="text" id="arama" name="arama" required>
        <button type="submit">Bul</button>
    </form>

    <!-- Arama Sonucu -->
    <?php if ($bulunan): ?>
        <h3>Sonuç:</h3>
        <p>Soyad: <?php echo $bulunan["soyad"]; ?></p>
        <p>Email: <?php echo $bulunan["email"]; ?></p>
    <?php endif; ?>

</body>
</html>
