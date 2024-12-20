<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Kisiler";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Bağlantı başarısız: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['ekle'])) {
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $email = $_POST['email'];

    $sql = "INSERT INTO kisi (ad, soyad, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $ad, $soyad, $email);

    if ($stmt->execute()) {
        echo "Kışi başarıyla eklendi.";
    } else {
        echo "Hata: " . $conn->error;
    }
    $stmt->close();
}


$sonuclar = [];
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['ara'])) {
    $arama = $_POST['arama'];

    $sql = "SELECT * FROM kisi WHERE ad LIKE ? OR soyad LIKE ? OR email LIKE ?";
    $stmt = $conn->prepare($sql);
    $arama_param = "%$arama%";
    $stmt->bind_param("sss", $arama_param, $arama_param, $arama_param);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $sonuclar[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kişi Yönetimi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        h1 {
            color: #0056b3;
        }
        form {
            background: #fff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background: #0056b3;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background: #004494;
        }
        table {
            border-collapse: collapse;
            width: 80%;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background: #0056b3;
            color: #fff;
        }
    </style>
</head>
<body>
    <h1>Kişi Ekle</h1>
    <form method="POST">
        <label for="ad">Ad:</label>
        <input type="text" name="ad" id="ad" required>
        <label for="soyad">Soyad:</label>
        <input type="text" name="soyad" id="soyad" required>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <button type="submit" name="ekle">Ekle</button>
    </form>

    <h1>Kişi Ara</h1>
    <form method="POST">
        <label for="arama">Arama:</label>
        <input type="text" name="arama" id="arama" required>
        <button type="submit" name="ara">Ara</button>
    </form>

    <?php if (!empty($sonuclar)) : ?>
        <h2>Arama Sonuçları</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Ad</th>
                <th>Soyad</th>
                <th>Email</th>
            </tr>
            <?php foreach ($sonuclar as $kisi) : ?>
                <tr>
                    <td><?= htmlspecialchars($kisi['id']) ?></td>
                    <td><?= htmlspecialchars($kisi['ad']) ?></td>
                    <td><?= htmlspecialchars($kisi['soyad']) ?></td>
                    <td><?= htmlspecialchars($kisi['email']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

</body>
</html>
