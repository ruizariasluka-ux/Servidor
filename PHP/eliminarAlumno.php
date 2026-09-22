<?php
require_once "conexion.php";

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id = (int) $_GET["id"];
    $stmt = $conexion->prepare("DELETE FROM alumnado WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: index.php?mensaje=" . urlencode("Alumno eliminado."));
exit;
?>
