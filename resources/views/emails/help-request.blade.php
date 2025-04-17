<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: #2d3748;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f7fafc;
        }
        .header {
            background-color: #1a202c;
            padding: 24px;
            margin-bottom: 24px;
            border-radius: 8px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            background-color: #ffffff;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .user-data {
            background-color: #edf2f7;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 24px;
        }
        .user-data h3 {
            color: #2d3748;
            margin-top: 0;
            margin-bottom: 16px;
            font-size: 18px;
        }
        .user-data ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .user-data li {
            margin-bottom: 8px;
        }
        .problem {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 24px;
        }
        .problem h3 {
            color: #2d3748;
            margin-top: 0;
            margin-bottom: 16px;
            font-size: 18px;
        }
        .timestamp {
            color: #718096;
            font-size: 14px;
            margin-bottom: 24px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4299e1;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        .button:hover {
            background-color: #3182ce;
        }
        @media (max-width: 600px) {
            body {
                padding: 16px;
            }
            .header, .content, .user-data, .problem {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>2FA Segítségkérés - SeeNav</h1>
    </div>

    <div class="content">
        <p>Tisztelt Webmester!</p>

        <p>Új segítségkérés érkezett a SeeNav rendszer 2FA beállításával kapcsolatban.</p>

        <div class="user-data">
            <h3>🧑 Felhasználó adatai</h3>
            <ul>
                <li><strong>Név:</strong> {{ $user->charactername }}</li>
                <li><strong>Felhasználónév:</strong> {{ $user->username }}</li>
            </ul>
        </div>

        <div class="problem">
            <h3>⚠️ A probléma leírása</h3>
            <p>{{ $problem }}</p>
        </div>

        <div class="timestamp">
            <strong>Beérkezés időpontja:</strong> {{ now()->format('Y. m. d. H:i:s') }}
        </div>

        <p style="text-align: center;">
            <a href="{{ config('app.url') }}" class="button">
                Belépés a SeeNav rendszerbe →
            </a>
        </p>
    </div>
</body>
</html>
