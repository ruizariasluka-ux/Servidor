<?php
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");
    exit;
}

$nombre = trim($_POST["nombre"]);
$apellidos = trim($_POST["apellidos"]);
$fecha = $_POST["fecha_nacimiento"];
$curso = $_POST["curso"];
$email = trim($_POST["email"]);
$contrasena = $_POST["contrasena"];

$cursosValidos = ["1 ESO", "2 ESO", "3 ESO", "4 ESO"];

if ($nombre == "" || $apellidos == "" || $fecha == "" || $curso == "" || $email == "" || $contrasena == "") {
    header("Location: index.php?mensaje=" . urlencode("Todos los campos son obligatorios."));
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: index.php?mensaje=" . urlencode("El email no es válido."));
    exit;
}

if (!in_array($curso, $cursosValidos)) {
    header("Location: index.php?mensaje=" . urlencode("El curso seleccionado no es válido."));
    exit;
}

$consulta = $conexion->prepare("SELECT COUNT(*) AS total FROM alumnado WHERE curso = ?");
$consulta->bind_param("s", $curso);
$consulta->execute();
$total = $consulta->get_result()->fetch_assoc()["total"];

if ($total >= 25) {
    header("Location: index.php?mensaje=" . urlencode("Ese curso ya tiene 25 alumnos. No se pueden matricular más."));
    exit;
}

$hash = password_hash($contrasena, PASSWORD_DEFAULT);

$stmt = $conexion->prepare("INSERT INTO alumnado (nombre, apellidos, fecha_nacimiento, curso, email, contrasena) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $nombre, $apellidos, $fecha, $curso, $email, $hash);

if ($stmt->execute()) {
    header("Location: index.php?mensaje=" . urlencode("Alumno matriculado correctamente."));
} else {
    header("Location: index.php?mensaje=" . urlencode("No se ha podido matricular. Comprueba que el email no esté repetido."));
}
exit;
?>
