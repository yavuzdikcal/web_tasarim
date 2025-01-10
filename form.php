<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metin Gönderme</title>
</head>
<body>
    <h2>Metin Gönderme Formu</h2>
    
    <form method="POST">
        <label for="user_text">Metin Yazın:</label><br>
        <textarea name="user_text" id="user_text" rows="4" cols="50"></textarea><br><br>
        <input type="submit" value="Gönder">
    </form>
    
    <?php
    // Form gönderildiğinde
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Kullanıcının yazdığı metni al
        $user_text = $_POST['user_text'];
        
        // Metni aynen aşağıda yazdır
        echo "<h3>Gönderdiğiniz Metin:</h3>";
        echo "<p>" . htmlspecialchars($user_text) . "</p>";
    }
    ?>

</body>
</html>
