<?php
// El password que escribimos en el formulario
$password_escrita = '123456';

// El hash que DEBERÍA estar en tu base de datos
$hash_de_la_bd = '$2y$10$oY.Y.43j.36P8k/j18eDk.xclL8m6N/jS.sJ2g8gJ2f5i0.k9.39K';

echo "<h2>Prueba de Hash Definitiva</h2>";
echo "Intentando verificar la contraseña (texto simple): <b>" . $password_escrita . "</b><br>";
echo "Contra el hash (de la BD): <b>" . $hash_de_la_bd . "</b><br><hr>";

// Esta es la función que está fallando
if (password_verify($password_escrita, $hash_de_la_bd)) {
    
    echo '<h1 style="color: green;">¡ÉXITO!</h1>';
    echo '<h3>La contraseña y el hash coinciden.</h3>';
    echo '<p>Si ves esto, tu PHP funciona perfectamente. El problema es 100% seguro que el hash en tu base de datos NO es el de arriba. Ve a phpMyAdmin, edita el usuario admin, borra el campo password y pega el hash de arriba.</p>';

} else {
    
    echo '<h1 style="color: red;">¡FALLA!</h1>';
    echo '<h3>La contraseña y el hash NO coinciden.</h3>';
    echo '<p>Si ves esto, el hash que te di es incorrecto o tu PHP tiene un problema. Lo solucionaremos de otra forma.</p>';
}

echo "<hr>Prueba con trim() (por si acaso):<br>";

// Prueba con trim() (como la que está en el código limpio)
if (password_verify(trim($password_escrita), trim($hash_de_la_bd))) {
     echo '<b style="color: green;">Éxito con trim() también.</b>';
} else {
     echo '<b style="color: red;">Falla con trim() también.</b>';
}

?>