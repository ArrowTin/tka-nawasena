<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Welcome</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-family: "Poppins", sans-serif;
        }

        .hero-title {
            font-size: 3.2rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #fff;
            letter-spacing: 3px;
            opacity: 0;
            transform: scale(0.7);
            animation: fadeScale 1.4s ease-out forwards;
        }

        @keyframes fadeScale {
            0% {
                opacity: 0;
                transform: scale(0.7) translateY(30px);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .subtitle {
            font-size: 1.2rem;
            opacity: 0;
            color: #fff;
            animation: fadeIn 2s ease-out forwards;
            animation-delay: 0.6s;
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        .card {
            background-color: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
        }
    </style>
</head>
<body>

<div class="container text-center">
    <div class="card">
        <h1 class="hero-title">Selamat Datang di TKA Nawasena</h1>
        <p class="subtitle mt-3">Platform Tes Kompetensi Akademik Modern</p>
    </div>
</div>

</body>
</html>
