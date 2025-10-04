<?php

//Este código debe estar en un archivo independiente para que no se impriman caracteres, tags y demás código inesperado.

//Determinamos los campos que se van a cubrir (le hemos añadido un par de comentarios)
$headers = [['Nombre', 'Apellido1', 'Apellido2', 'Curso', 'Grupo', 'F_Nacimiento', 'Alergias', 'Tarifa']];

$nombreArchivo = 'plantilla_alumnos.csv';

header('Content-Type: text/csv; charset=UTF-8');
header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
//Esta línea le dice al navegador que verifique si hay una versión nueva antes de usar la que tiene en la cache (no-cache) y que si el archivo ha caducado, el servidor debe revalidarlo para poder usarlo (must-revalidate);
//No es una línea obligatoria pero se usa en archivos que cambian muy a menudo
header('Cache-Control: no-cache, must-revalidate');

$salida = fopen('php://output', 'w');

// Para que Excel lo abra correctamente con tildes y eñes, añadimos BOM UTF-8
fprintf($salida, chr(0xEF) . chr(0xBB) . chr(0xBF));

//Usamos un bucle foreach porque el método fputcsv() no está preparado para poder pasarles arrays multidimensionales.
foreach ($headers as $fila) {
    fputcsv($salida, $fila);
}

fclose($salida);

exit;