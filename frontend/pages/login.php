<?php
require_once __DIR__ . '/../../backend/core/session.php';
require_once __DIR__ . '/../../backend/config/database.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    if (empty($email) || empty($contrasena)) {
        $error = 'Por favor completa todos los campos';
    } else {
        try {
            $stmt = $conexion->prepare('SELECT * FROM usuarios WHERE email = :email');
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_email'] = $usuario['email'];
                header('Location: dashboard.php'); 
                exit();
            } else {
                $error = 'Email o contraseña incorrectos';
            }
        } catch (PDOException $e) {
            $error = 'Error en la base de datos: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Veterinaria Royal Pets</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
    <style>
    body {
        background: linear-gradient(120deg,#e5e7fb,#98f7e5); min-height:100vh;
        font-family:'Montserrat',Arial,sans-serif; display:flex; justify-content:center; align-items:center;
    }
    .login-container {
        width:340px; max-width:90vw; background:#fff; border-radius:22px; box-shadow:0 5px 28px #6740cf22;
        padding:3rem 2.4rem; text-align:center; margin:auto;
    }
    .login-header h1 { color:#6740cf; font-size:2em; font-weight:900; margin-bottom:0.15em;}
    .login-header p { color:#a5a4b9; margin-bottom:2em;}
    .error-message {
        background: #ffecec; color: #e03b3b; border-radius:8px; border-left:5px solid #ff7d7e; font-size:1em;
        padding:13px 10px; margin-bottom:22px; text-align:left;
    }
    .form-group {margin-bottom:18px; text-align:left;}
    label {font-weight:700; color:#555;display:block;margin-bottom:6px;}
    input[type="email"], input[type="password"] {
        width:99%; padding:13px 10px; border:1.5px solid #ddd; border-radius:7px; font-size:1.08em; background:#f9fafd;
        box-sizing:border-box; outline:none; transition:border-color .21s; margin-bottom:8px;
    }
    input[type="email"]:focus, input[type="password"]:focus {border-color:#2ad2c9;}
    button[type="submit"] {
        width:100%;background:linear-gradient(90deg,#2ad2c9,#6740cf); color:#fff; border:none;
        border-radius:7px; padding:13px 0; font-size:1.17em; font-weight:bold; cursor:pointer;
        margin-top:12px; box-shadow:0 2px 10px #2ad2c926;transition:.18s;
    }
    button[type="submit"]:hover {
        background: linear-gradient(90deg,#6740cf,#2ad2c9);
    }
    .back-link {margin-top:32px;}
    .back-link a { color:#6740cf; text-decoration:none; font-size:1em;transition:color .15s;}
    .back-link a:hover { color:#25d366; text-decoration:underline;}
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>🐾 Royal Pets</h1>
            <p>Acceso solo administración</p>
        </div>
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email de Admin:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <div class="back-link">
            <a href="../../public/index.php">← Volver al inicio</a>
        </div>
    </div>
</body>
</html>
