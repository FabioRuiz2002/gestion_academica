<?php
/*
 * Archivo temporal: generar_hash.php
 * Úsalo para generar un hash válido para la contraseña "123".
 */

$password_plana = "123";
$hash_nuevo = password_hash($password_plana, PASSWORD_DEFAULT);

echo "<h3>Hash Generado para la contraseña '123'</h3>";
echo "<p>Copia esta línea completa (incluyendo el $2y$) y pégala en la columna 'password' de tu usuario en phpMyAdmin:</p>";
echo "<hr>";
echo "<strong>" . $hash_nuevo . "</strong>";
echo "<hr>";
echo "<p style='color:red;'>¡IMPORTANTE! Borra este archivo (generar_hash.php) cuando termines.</p>";
?>