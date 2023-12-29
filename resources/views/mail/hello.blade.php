<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email di Benvenuto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        p {
            color: #666;
        }

        .cta-button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #4caf50;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Benvenuto/a {{ $name }}</h1>
        <p>Grazie per esserti unito a noi. Siamo entusiasti di averti con noi.</p>
        <p>Inizia subito a esplorare tutto ciò che abbiamo da offrire.</p>
        <a href="#" class="cta-button">Esplora Ora</a>
    </div>
</body>
</html>
