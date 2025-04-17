<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Jelszó visszaállítási kísérlet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #dee2e6;
        }
        .header {
            background-color: #ffd700;
            color: #000;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            text-align: center;
            margin: -20px -20px 20px -20px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .details {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .user-info {
            background-color: #f1f3f5;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ Jelszó visszaállítási kísérlet ⚠️</h1>
        </div>

        <div class="warning">
            <strong>Figyelem!</strong> Valaki megpróbálta visszaállítani egy webmester jelszavát a SeeNAV rendszerben.
        </div>

        <div class="details">
            <h3>A kísérlet részletei:</h3>
            <p><strong>Időpont:</strong> {{ now()->format('Y. m. d. H:i:s') }}</p>
            
            <div class="user-info">
                <h4>Érintett webmester:</h4>
                <ul>
                    <li><strong>Felhasználónév:</strong> {{ $targetUser->username }}</li>
                    <li><strong>Karakter név:</strong> {{ $targetUser->charactername }}</li>
                </ul>
            </div>

            <div class="user-info">
                <h4>Végrehajtó adatai:</h4>
                <ul>
                    <li><strong>Felhasználónév:</strong> {{ $initiator->username }}</li>
                    <li><strong>Karakter név:</strong> {{ $initiator->charactername }}</li>
                    <li><strong>Email:</strong> {{ $initiator->email }}</li>
                    <li><strong>IP cím:</strong> {{ $ipAddress }}</li>
                </ul>
            </div>
        </div>

        <p>Ha ezt a műveletet nem te kezdeményezted, kérjük, azonnal lépj kapcsolatba egy Webmesterrel!</p>

        <div class="footer">
            <p>Ez egy automatikus üzenet a SeeNAV rendszertől. Kérjük, ne válaszolj erre az e-mailre.</p>
            <p> SeeNAV - Minden jog fenntartva</p>
        </div>
    </div>
</body>
</html>
