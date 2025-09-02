<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitación para unirse a Xchool</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
        .info-box {
            background: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎓 Xchool</h1>
        <p>Plataforma Educativa</p>
    </div>
    
    <div class="content">
        <h2>¡Hola {{ $invitation->name }}!</h2>
        
        <p>Has sido invitado a unirte a <strong>Xchool</strong>, nuestra plataforma educativa.</p>
        
        <div class="info-box">
            <h3>📋 Detalles de la invitación:</h3>
            <ul>
                <li><strong>Usuario:</strong> {{ $invitation->name }}</li>
                <li><strong>Email:</strong> {{ $invitation->email }}</li>
                <li><strong>Rol:</strong> {{ $invitation->role->display_name }}</li>
                <li><strong>Invitado por:</strong> {{ $invitation->invitedBy->name }}</li>
                <li><strong>Expira:</strong> {{ $invitation->expires_at->format('d/m/Y H:i') }}</li>
            </ul>
        </div>
        
        <p>Para aceptar esta invitación y crear tu cuenta, haz clic en el botón de abajo:</p>
        
        <div style="text-align: center;">
            <a href="{{ $invitationUrl }}" class="button">
                ✅ Aceptar Invitación
            </a>
        </div>
        
        <p><strong>Importante:</strong> Esta invitación expira en 7 días. Si no la aceptas antes de esa fecha, tendrás que solicitar una nueva invitación.</p>
        
        <p>Una vez que aceptes la invitación, recibirás un email con tus credenciales de acceso.</p>
        
        <p>Si tienes alguna pregunta, no dudes en contactar al administrador del sistema.</p>
        
        <p>¡Esperamos verte pronto en Xchool!</p>
        
        <p>Saludos,<br>
        <strong>El equipo de Xchool</strong></p>
    </div>
    
    <div class="footer">
        <p>Este es un email automático, por favor no respondas a este mensaje.</p>
        <p>&copy; {{ date('Y') }} Xchool. Todos los derechos reservados.</p>
    </div>
</body>
</html>
