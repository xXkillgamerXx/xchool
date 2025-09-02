<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tus credenciales de acceso a Xchool</title>
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
        .credentials-box {
            background: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎓 Xchool</h1>
        <p>Plataforma Educativa</p>
    </div>
    
    <div class="content">
        <h2>¡Bienvenido a Xchool, {{ $user->name }}!</h2>
        
        <p>Tu cuenta ha sido creada exitosamente. Aquí tienes tus credenciales de acceso para iniciar sesión:</p>
        
        <div class="credentials-box">
            <h3>🔑 Tus credenciales de acceso:</h3>
            <ul>
                <li><strong>Usuario:</strong> <code style="background: #f8f9fa; padding: 2px 6px; border-radius: 3px;">{{ $user->name }}</code></li>
                <li><strong>Email:</strong> {{ $user->email }}</li>
                <li><strong>Contraseña:</strong> <code style="background: #f8f9fa; padding: 2px 6px; border-radius: 3px;">{{ $password }}</code></li>
                <li><strong>Rol:</strong> {{ $user->role->display_name }}</li>
            </ul>
        </div>
        
        <div class="warning-box">
            <strong>⚠️ Importante:</strong> Usa tu <strong>Usuario</strong> y <strong>Contraseña</strong> para iniciar sesión. Por seguridad, te recomendamos cambiar tu contraseña después del primer login.
        </div>
        
        <p>Para acceder a tu cuenta, haz clic en el botón de abajo:</p>
        
        <div style="text-align: center;">
            <a href="{{ $loginUrl }}" class="button">
                🚀 Iniciar Sesión
            </a>
        </div>
        
        <h3>📱 ¿Qué puedes hacer ahora?</h3>
        <ul>
            <li>Iniciar sesión con tus credenciales</li>
            <li>Explorar tu dashboard personalizado</li>
            <li>Cambiar tu contraseña por una más segura</li>
            <li>Completar tu perfil de usuario</li>
        </ul>
        
        <p>Si tienes algún problema para acceder, contacta al administrador del sistema.</p>
        
        <p>¡Disfruta tu experiencia en Xchool!</p>
        
        <p>Saludos,<br>
        <strong>El equipo de Xchool</strong></p>
    </div>
    
    <div class="footer">
        <p>Este es un email automático, por favor no respondas a este mensaje.</p>
        <p>&copy; {{ date('Y') }} Xchool. Todos los derechos reservados.</p>
    </div>
</body>
</html>
