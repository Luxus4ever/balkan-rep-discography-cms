<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Stranica nije pronađena</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            height: 100vh;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            text-align: center;
        }

        .container {
            max-width: 500px;
        }

        h1 {
            font-size: 120px;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 15px;
        }

        p {
            font-size: 16px;
            margin-bottom: 25px;
            opacity: 0.9;
        }

        a {
            display: inline-block;
            padding: 12px 25px;
            background: #fff;
            color: #2a5298;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            transition: 0.3s;
        }

        a:hover {
            background: #f1f1f1;
            transform: translateY(-2px);
        }

        .small {
            margin-top: 20px;
            font-size: 14px;
            opacity: 0.7;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>404</h1>
        <h2>Ups! Stranica nije pronađena.</h2>
        <p>Stranica koju tražite možda je obrisana, preimenovana ili trenutno nije dostupna.</p>
        
        <a href="/sajtovi/albumi/">Vrati se na početnu</a>

        <div class="small">
            &copy; <?php echo date("Y"); ?> Diskografija.org
        </div>
    </div>

</body>
</html>
