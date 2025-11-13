<?php
// Escribe aquí la contraseña que quieras usar
$password_a_hashear = '123456';

// Esto genera el hash
$hash_generado = password_hash($password_a_hashear, PASSWORD_DEFAULT);

// --- Mostramos el resultado ---
echo "<h1>Generador de Hash</h1>";
echo "Hemos generado un hash para la contraseña (texto simple): <b>" . $password_a_hashear . "</b><br><br>";
echo "<h3>TU HASH CORRECTO:</h3>";
echo "<p>Copia y pega la siguiente línea completa en phpMyAdmin:</p>";

// Te lo pongo en un textarea para que sea fácil de copiar
echo '<textarea rows="3" cols="80" readonly style="font-size: 1.2rem; background-color: #eee; padding: 10px;">';
echo htmlspecialchars($hash_generado);
echo '</textarea>';

echo "<br><br><h3>Instrucciones:</h3>";
echo "<ol>";
echo "<li>Copia el hash de la caja de texto de arriba.</li>";
echo "<li>Ve a phpMyAdmin, tabla 'usuarios', edita el 'admin@correo.com'.</li>";
echo "<li>Pega este hash en el campo 'password' y guarda.</li>";
echo "<li>¡Prueba tu login!</li>";
echo "</ol>";
?>