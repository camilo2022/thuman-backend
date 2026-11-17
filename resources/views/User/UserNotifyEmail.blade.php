<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Recuperación de contraseña</title>
        <!-- Tipografía Poppins -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

        <style>
            body {
                margin: 0;
                padding: 0;
                background: #f6f6f6;
                font-family: 'Poppins', sans-serif;
                color: #333333;
            }

            .container {
                max-width: 600px;
                margin: 40px auto;
                background: #ffffff;
                border-radius: 8px;
                border: 1px solid #eee;
                text-align: center;
                overflow: hidden;
            }

            .header {
                background: linear-gradient(180deg, #f4f4ff 0%, #ffffff 100%);
                padding: 40px 20px 20px 20px;
            }

            .header img {
                width: 150px;
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img src="{{ $logo }}" alt="T-HÜMAN">
            </div>

            <div class="dynamic-content">
                {!! $content !!}
            </div>

        </div>
    </body>
</html>
