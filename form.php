<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metin Gönderme</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 400px;
            text-align: center;
        }

        h2 {
            color: #5f6368;
        }

        label {
            font-size: 1.1em;
            margin-bottom: 10px;
            display: block;
        }

        textarea {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            border-radius: 4px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            resize: vertical;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .result {
            background-color: #f1f1f1;
            border-radius: 4px;
            padding: 15px;
            margin-top: 20px;
            text-align: left;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Metin Gönderme Formu</h2>
        
        <form method="POST">
            <label for="user_text">Metin Yazın:</label>
            <textarea name="user_text" id="user_text" rows="4"></textarea><br><br>
            <input type="submit" value="Gönder">
        </form>
        
        <?php
        // Form gönderildiğinde
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Kullanıcının yazdığı metni al
            $user_text = $_POST['user_text'];
            
            // Metni aynen aşağıda yazdır
            echo "<div class='result'>";
            echo "<h3>Gönderdiğiniz Metin:</h3>";
            echo "<p>" . htmlspecialchars($user_text) . "</p>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>
