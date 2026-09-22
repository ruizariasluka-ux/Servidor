<?php
require_once "conexion.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET["id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $apellidos = trim($_POST["apellidos"]);
    $fecha = $_POST["fecha_nacimiento"];
    $curso = $_POST["curso"];
    $email = trim($_POST["email"]);
    $cursosValidos = ["1 ESO", "2 ESO", "3 ESO", "4 ESO"];

    if ($nombre == "" || $apellidos == "" || $fecha == "" || $curso == "" || $email == "" ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) || !in_array($curso, $cursosValidos)) {
        header("Location: editarAlumno.php?id=$id&error=1");
        exit;
    }

    $actual = $conexion->prepare("SELECT curso FROM alumnado WHERE id = ?");
    $actual->bind_param("i", $id);
    $actual->execute();
    $filaActual = $actual->get_result()->fetch_assoc();

    if (!$filaActual) {
        header("Location: index.php");
        exit;
    }

    if ($filaActual["curso"] != $curso) {
        $contador = $conexion->prepare("SELECT COUNT(*) AS total FROM alumnado WHERE curso = ?");
        $contador->bind_param("s", $curso);
        $contador->execute();
        $total = $contador->get_result()->fetch_assoc()["total"];

        if ($total >= 25) {
            header("Location: index.php?mensaje=" . urlencode("No se puede cambiar: el curso elegido ya tiene 25 alumnos."));
            exit;
        }
    }

    $stmt = $conexion->prepare("UPDATE alumnado SET nombre=?, apellidos=?, fecha_nacimiento=?, curso=?, email=? WHERE id=?");
    $stmt->bind_param("sssssi", $nombre, $apellidos, $fecha, $curso, $email, $id);

    if ($stmt->execute()) {
        header("Location: index.php?mensaje=" . urlencode("Datos modificados correctamente."));
    } else {
        header("Location: index.php?mensaje=" . urlencode("No se pudieron modificar los datos."));
    }
    exit;
}

$stmt = $conexion->prepare("SELECT * FROM alumnado WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$alumno = $stmt->get_result()->fetch_assoc();

if (!$alumno) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar alumno</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
</head>
<body>
<main class="contenedor pequeno">
    <section class="tarjeta">
        <h1>Editar alumno/a</h1>

        <?php if (isset($_GET["error"])) { ?>
            <p class="mensaje">Revisa los datos introducidos.</p>
        <?php } ?>

        <form method="POST">
            <label>Nombre</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($alumno["nombre"]); ?>" required>

            <label>Apellidos</label>
            <input type="text" name="apellidos" value="<?php echo htmlspecialchars($alumno["apellidos"]); ?>" required>

            <label>Fecha de nacimiento</label>
            <input type="date" name="fecha_nacimiento" value="<?php echo htmlspecialchars($alumno["fecha_nacimiento"]); ?>" required>

            <label>Curso</label>
            <select name="curso" required>
                <?php
                $cursos = ["1 ESO", "2 ESO", "3 ESO", "4 ESO"];
                foreach ($cursos as $c) {
                    $seleccionado = $alumno["curso"] == $c ? "selected" : "";
                    echo "<option value="$c" $seleccionado>$c</option>";
                }
                ?>
            </select>

            <label>Email de Educamos</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($alumno["email"]); ?>" required>

            <button type="submit">Guardar cambios</button>
            <a class="volver" href="index.php">Volver</a>
        </form>
    </section>
</main>
</body>
</html>
