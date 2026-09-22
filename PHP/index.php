<?php
require_once "conexion.php";

$mensaje = "";

if (isset($_GET["mensaje"])) {
    $mensaje = $_GET["mensaje"];
}

$sql = "SELECT * FROM alumnado ORDER BY curso ASC, apellidos ASC, nombre ASC";
$resultado = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de alumnado - CF Somorrostro</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
</head>
<body>
    <main class="contenedor">
        <h1>Gestión de alumnado</h1>
        <p class="subtitulo">CF Somorrostro - Educación Secundaria Obligatoria</p>

        <?php if ($mensaje != "") { ?>
            <p class="mensaje"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php } ?>

        <section class="tarjeta">
            <h2>Matricular alumno/a</h2>

            <form action="guardarAlumno.php" method="POST">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" required>

                <label for="fecha_nacimiento">Fecha de nacimiento</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>

                <label for="curso">Curso</label>
                <select id="curso" name="curso" required>
                    <option value="">Selecciona un curso</option>
                    <option value="1 ESO">1º ESO</option>
                    <option value="2 ESO">2º ESO</option>
                    <option value="3 ESO">3º ESO</option>
                    <option value="4 ESO">4º ESO</option>
                </select>

                <label for="email">Email de Educamos</label>
                <input type="email" id="email" name="email" required>

                <label for="contrasena">Contraseña de Educamos</label>
                <input type="password" id="contrasena" name="contrasena" required>

                <button type="submit">Matricular</button>
            </form>
        </section>

        <section class="tarjeta tabla-contenedor">
            <h2>Alumnado matriculado</h2>

            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Nacimiento</th>
                        <th>Curso</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($resultado && $resultado->num_rows > 0) { ?>
                    <?php while ($alumno = $resultado->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($alumno["nombre"]); ?></td>
                            <td><?php echo htmlspecialchars($alumno["apellidos"]); ?></td>
                            <td><?php echo htmlspecialchars($alumno["fecha_nacimiento"]); ?></td>
                            <td><?php echo htmlspecialchars($alumno["curso"]); ?></td>
                            <td><?php echo htmlspecialchars($alumno["email"]); ?></td>
                            <td class="acciones">
                                <a class="editar" href="editarAlumno.php?id=<?php echo $alumno["id"]; ?>">Editar</a>
                                <a class="eliminar" href="eliminarAlumno.php?id=<?php echo $alumno["id"]; ?>"
                                   onclick="return confirm('¿Seguro que quieres eliminar este alumno?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="6">Todavía no hay alumnado matriculado.</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
