<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pagamento PIX</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 80px;
        }

        #timer {
            font-size: 40px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <h1>Finalize seu pagamento via PIX</h1>
    <p>O pagamento está sendo processado...</p>

    <div id="timer">00:30</div>

    <script>
        let timeLeft = 30;

        const timerDiv = document.getElementById("timer");

        const interval = setInterval(() => {
            timeLeft--;

            // Atualiza a tela
            let seconds = timeLeft < 10 ? "0" + timeLeft : timeLeft;
            timerDiv.innerHTML = "00:" + seconds;

            // Quando chegar a zero → redireciona
            if (timeLeft <= 0) {
                clearInterval(interval);
                window.location.href = "/?success=1";
            }
        }, 1000);
    </script>

</body>
</html>
