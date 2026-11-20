<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit;
}

include '../settings/connection.php';

$sql = 'SELECT id, firstname, lastname, email, mobile_phone, photo_url
        FROM users ORDER BY id';
$result = pg_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <main class="page">
        <h1>Lista de usuarios</h1>
        <p class="welcome">
            Bienvenido, <?php echo $_SESSION['firstname']; ?>
            | <a href="logout.php">Cerrar sesión</a>
        </p>

        <div class="table-wrap">
            <table>
        <tr>
            <th>Foto</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Correo</th>
            <th>Celular</th>
            <th>Editar</th>
        </tr>

        <?php while ($row = pg_fetch_assoc($result)) { ?>
        <tr>
            <td>
                <?php if ($row['photo_url'] != "") { ?>
                    <img class="photo" src="<?php echo $row['photo_url']; ?>" alt="Foto de usuario">
                <?php } else { ?>
                    Sin foto
                <?php } ?>
            </td>
            <td><?php echo $row['firstname']; ?></td>
            <td><?php echo $row['lastname']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['mobile_phone']; ?></td>
            <td>
                <a href="edit_users.php?id=<?php echo $row['id']; ?>">✏️</a>
            </td>
        </tr>
        <?php } ?>
            </table>
        </div>
    </main>
</body>
</html>