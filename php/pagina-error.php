<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso Denegado</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #ff4e50, #f9d423);
            color: #fff;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            transition: opacity 1s ease;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 3rem 2rem;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 500px;
            animation: fadeIn 1s ease;
        }

        .icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            animation: bounce 1.5s infinite;
        }

        .alert {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .countdown {
            font-size: 1.1rem;
            margin-top: 1rem;
        }

        .countdown span {
            font-weight: bold;
            color: #ffd700;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @media (max-width: 600px) {
            .container {
                margin: 1rem;
                padding: 2rem 1rem;
            }
        }
    </style>
    <script>
        let seconds = 5;
        function countdown() {
            const display = document.getElementById("countdown");
            const body = document.body;
            const interval = setInterval(() => {
                seconds--;
                display.textContent = seconds;
                if (seconds <= 0) {
                    clearInterval(interval);
                    body.style.opacity = "0";
                    setTimeout(() => {
                        window.location.href = "../ConexionSQL/cerrar.php";
                    }, 1000);
                }
            }, 1000);
        }

        window.onload = countdown;
    </script>
</head>
<body>
    <div class="container">
        <div class="icon">🚫</div>
        <div class="alert">Acceso Denegado</div>
        <p>No tienes permisos para ver esta página.</p>
        <div class="countdown">Serás redirigido al inicio en <span id="countdown">5</span> segundos...</div>
    </div>
</body>
</html>
