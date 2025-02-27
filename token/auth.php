<?php
include 'conexion.php'; // Conexion a la base de datos

session_start();  // Iniciar sesion para gestionar el token

// Funcion para generar un token y establecer su expiracion
function generarToken($userId, $conn) {
    $token = bin2hex(random_bytes(16)); // Genera un token aleatorio
    $expiracion = date("Y-m-d H:i:s", strtotime("+5 minutes")); // Establece expiracion a 5 minutos 

    // Actualiza el token y la expiracion en la base de datos
    $stmt = $conn->prepare("UPDATE usuarios SET token = ?, token_expiracion = ? WHERE id = ?");
    $stmt->execute([$token, $expiracion, $userId]);

    return $token;
}

// Mostrar el formulario para registrar o iniciar sesionn
function mostrarFormulario($mensaje = '') {
    echo "<div class='form-container'>";
    echo "<h2>$mensaje</h2>";
    echo "<form method='post'>
            <label>Usuario:</label>
            <input type='text' name='usuario' required><br>
            <label>Contraseña:</label>
            <input type='password' name='password' required><br>
            <button type='submit' name='action' value='login' class='btn'>Iniciar sesión</button>
            <button type='submit' name='action' value='register' class='btn'>Registrar</button>
          </form>";
    echo "</div>";
}

// Verificar si hay una accion en el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Comprobamos si la solicitud es de tipo POST (formulario enviado)
    $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';// Obtenemos el valor del campo 'usuario', o asignamos una cadena vacía si no esta presente
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    // para registrar un nuevo usuario
    if ($action === 'register') {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO usuarios (usuario, password) VALUES (?, ?)");

        try {
            $stmt->execute([$usuario, $passwordHash]);
            echo "<div class='success-message'> Te has registrado correctamente ! Ahora puedes iniciar sesión.</div>";
            mostrarFormulario();
        } catch (Exception $e) {
            echo "<div class='error-message'>Error: el usuario ya existe.</div>";
            mostrarFormulario("Intenta con un nombre de usuario diferente.");
        }
    }

    // Iniciar sesion y generar - verificar el token
    elseif ($action === 'login') {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Comprobar si ya hay un token valido
            if ($user['token'] && strtotime($user['token_expiracion']) > time()) {
                $_SESSION['token'] = $user['token']; // Almacenar el token en la sesión
                 // Mensaje de exito indicando que el usuario esta conectado
                echo "<div class='success-message'>Hola, " . $user['usuario'] . ", estás conectado.</div>";
            } else {
                // Generar un nuevo token si el actual esta expirado o no existe
                $token = generarToken($user['id'], $conn);
                $_SESSION['token'] = $token; // Almacenar el nuevo token en la sesion
                echo "<div class='success-message'>Hola, " . $user['usuario'] . ", estas conectado.</div>";
            }
        } else {
            echo "<div class='error-message'>Usuario o contraseña incorrectos.</div>";
            mostrarFormulario("Intenta iniciar sesión de nuevo .");
        }
    }
} else {
    //para verificar si hay un token activo en la sesion
    if (isset($_SESSION['token'])) {
        // Preparar la consulta para buscar el usuario con el token proporcionado y verificar que el token no haya expirado
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE token = ? AND token_expiracion > NOW()");
        $stmt->execute([$_SESSION['token']]);
        $user = $stmt->fetch();// para obtener el usuario correspondiente

        if ($user) {
            echo "<div class='success-message'>Hola, " . $user['usuario'] . ", estás conectado.</div>";
        } else {
            echo "<div class='error-message'>Tu sesión ha expirado, por favor inicia sesión otra vez .</div>";
            mostrarFormulario(); // Mostrar el formulario para iniciar sesion otra vez 
        }
    } else {
        mostrarFormulario("Por favor, inicia sesión o regístrate.");
    }
}
?>
<link rel="stylesheet" href="styles.css">
