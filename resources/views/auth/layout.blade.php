<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ config('app.name', 'Streak Button') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
<style>
    body {
        background-color: #272643;
        color: #ffffff;
        font-family: 'Segoe UI', sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        margin: 0;
    }

    .neon-box {
        border: 2px solid #bae8e8;
        padding: 40px 32px;
        border-radius: 16px;
        box-shadow: 0 0 20px #2c698d;
        text-align: center;
        background-color: #1e1e2f;
        width: 100%;
        max-width: 400px;
    }

    .neon-box h1 {
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 24px;
        color: #bae8e8;
    }

    .neon-btn {
        display: inline-block;
        margin-top: 10px;
        padding: 14px 36px;
        font-size: 1rem;
        font-weight: bold;
        border-radius: 9999px;
        background: linear-gradient(45deg, #2c698d, #bae8e8);
        color: #ffffff;
        border: none;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-decoration: none;
    }

    .neon-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 0 15px #bae8e8;
    }

    .input {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: none;
        margin-bottom: 16px;
        font-size: 1rem;
        background-color: #e3f6f5;
        color: #000;
        box-sizing: border-box;
    }

    .input:focus {
        outline: none;
        box-shadow: 0 0 5px #bae8e8;
    }

    a {
        color: #bae8e8;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>

</head>
<body>
    <div class="neon-box">
        @yield('content')
    </div>
</body>
</html>
