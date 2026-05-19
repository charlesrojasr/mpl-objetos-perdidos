<?php
session_start();
include '../00_includes/conn.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    if ($username === '' || $password === '') {

        $error = 'Debe ingresar usuario y contraseña.';
    } else {

        $stmt = $conn->prepare("SELECT id, role_id, password, estado FROM objetosperdidos_users WHERE username = ? LIMIT 1");

        if (!$stmt) {

            $error = "Error de base de datos.";
        } else {

            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {

                $id = 0;
                $role_id = 0;
                $hash = '';
                $estado = 0;

                $stmt->bind_result($id, $role_id, $hash, $estado);

                if ($stmt->fetch()) {

                    if ($estado != 1) {

                        $error = "Usuario deshabilitado";
                    } else {

                        if (password_verify($password, $hash)) {

                            $_SESSION['user_id'] = $id;
                            $_SESSION['role_id'] = $role_id;

                            header('Location: ../m-modulos/index.php');
                            exit;
                        } else {

                            $error = "Contraseña incorrecta.";
                        }
                    }
                } else {

                    $error = "Error de base de datos.";
                }
            } else {

                $error = "Usuario no encontrado.";
            }

            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        input[type=text],
        input[type=password] {
            width: 100%;
            padding: 12px 40px 12px 20px;
            margin: 8px 0;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
        }

        button {
            background-color: #053A52;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            opacity: 0.8;
        }

        .imgcontainer {
            text-align: center;
            margin: 24px 0 12px 0;
        }

        img.avatar {
            width: 150px;
            border-radius: 50%;
        }

        .container {
            padding: 16px;
        }

        .modal {
            display: block;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            border: 1px solid #888;
            width: 55%;
        }
    </style>

</head>

<body>

    <?php if ($error !== ''): ?>
        <script>
            window.onload = function() {
                alert(<?php echo json_encode($error); ?>);
            };
        </script>
    <?php endif; ?>

    <div class="modal">

        <form class="modal-content" action="login.php" method="post">

            <div class="imgcontainer">
                <h3>OBJETOS PERDIDOS</h3>
                <img src="img/logo-mdpl.png" alt="Municipalidad de Pueblo Libre" class="avatar">
            </div>

            <div class="container">

                <label><b>Usuario</b></label>
                <input type="text" name="username" required>

                <label><b>Contraseña</b></label>

                <div class="password-container">
                    <input id="password" type="password" name="password" required>
                    <i class="fa fa-eye toggle-password" onclick="togglePassword()"></i>
                </div>

                <button type="submit">Ingresar</button>

            </div>

        </form>

    </div>

    <script>
        function togglePassword() {

            var password = document.getElementById("password");
            var icon = document.querySelector(".toggle-password");

            if (password.type === "password") {
                password.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                password.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }

        }
    </script>

</body>

</html>