<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Token de acceso</title>
    <!-- Tipografía Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f6f6f6;
            font-family: 'Poppins';
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

        .header h2 {
            color: #5b4cd0;
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }

        .content {
            padding: 20px 40px;
            font-size: 16px;
            line-height: 24px;
        }

        .code {
            font-size: 28px;
            letter-spacing: 8px;
            margin: 30px 0;
            font-weight: bold;
            color: #000000;
        }

        .note {
            font-size: 14px;
            color: #555555;
        }

        .footer {
            padding: 30px 40px;
            font-size: 12px;
            color: #666666;
            line-height: 18px;
        }

        .footer a {
            color: #5b4cd0;
            text-decoration: underline;
        }

        .divider {
            height: 1px;
            background: #ddd;
            border: none;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('storage/THUMAN.png') }}" alt="T-HÜMAN">
            <h2>Token de acceso</h2>
        </div>

        <div class="content">
            <p>
                Su código de acceso para firmar el contrato en su proceso de selección en <strong>T-HÜMAN</strong> ha sido generado.
                Tiene una validez de <strong>5 minutos</strong>, por lo que le recomendamos ingresarlo dentro de ese tiempo para completar el proceso.
            </p>

            <div class="code">{{ $code }}</div>

            <p class="note">
                Este código es confidencial. No lo compartas con nadie.
                Al continuar, acepta nuestras políticas de tratamiento de datos.
            </p>

            <hr class="divider">

            <div class="footer">
                Si usted no solicitó este código, puede ignorar este mensaje.<br><br>
                Para más información sobre cómo protegemos sus datos, consulte nuestras
                <a href="#">políticas de privacidad</a> o comuníquese con nuestro equipo de soporte
                <a href="mailto:soporte@ejemplo.com">Soporte@ejemplo.com</a>.<br><br>
                Gracias por confiar en T-HÜMAN.<br>
                <em>Conectando talentos, transformando futuro.</em>
            </div>
        </div>
    </div>
</body>
</html>
