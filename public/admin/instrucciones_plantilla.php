<?php

$contenido = <<<TXT
INSTRUCCIONES DE USO DE LA PLANTILLA CSV
===============================================

1. Respete el uso de mayúsculas y minúsculas en todos los campos.
2. No cambie los encabezados de la plantilla.
3. Los valores válidos para el campo "Curso" son:
   - 4º Inf.
   - 5º Inf.
   - 6º Inf.
   - 1º Pri.
   - 2º Pri.
   - 3º Pri.
   - 4º Pri.
   - 5º Pri.
   - 6º Pri.
5. Los valores válidos para el campo "Grupo" son:
   - A
   - B
   - C
   - D
   - E
6. El campo "F_Nacimiento" debe tener el formato: AAAA-MM-DD (por ejemplo: 2014-03-27)
7. El campo "Alergias" se incluye en la platilla, pero no es obligatorio cubrirlo. Sin embargo, se recomienda dejarlo vacío o escribir "null" en aquellos casos en los que el alumno no tenga ninguna alergia y/o intolerancia.
8. Los valores válidos para el campo "tarifa" son los siguientes:
   - 0 (se corresponde con --> 0€ o "Gratuidade total")
   - 1 (se corresponde con --> 1€ o "Abono 1 Euro")
   - 2.5 (se corresponde con --> 2.5€ o "Abono 2.5 Euros")
   - 4.5 (se corresponde con --> 4.5€ o "Abono 4.5 Euros")
9. Asegúrese de que no haya celdas vacías antes de guardar y subir el archivo.

IMPORTACIÓN DE LA PLANTILLA EN EXCEL
===============================================

En caso de que nunca haya trabajado con un archivo en formato .csv, le ofrecemos los
siguientes consejos:

1. MÉTODO RECOMENDADO (IMPORTAR DESDE DATOS)
--------------------------------------------

Este método es el más fiable y evita la mayoría de errores de codificación, delimitación o encabezados.

PASOS:
------
1. Abre Excel (una hoja en blanco).
2. Ve a la pestaña "Datos".
3. Haz clic en "Obtener datos" (puede aparecer como "Obtener y transformar datos").
4. Selecciona: "Desde archivo" > "Desde texto/CSV".
5. Busca y selecciona el archivo `.csv` que deseas importar.
6. Se abrirá una vista previa. Asegúrate de que:
   - El **delimitador** está correctamente seleccionado (normalmente "Coma" o "Punto y coma").
   - El **conjunto de caracteres** esté bien configurado (por ejemplo: "65001: Unicode (UTF-8)" para acentos y símbolos especiales).
   - El nombre de las columnas aparecen correctamente en la primera fila (si no, actúa como se indica en la sección de problemas).
7. Haz clic en el botón "Cargar".
8. ¡Listo! Tus datos estarán organizados en la hoja de cálculo.


PROBLEMAS COMUNES Y SUS SOLUCIONES
==================================

PROBLEMA 1: Los nombres de las columnas no se cargan correctamente (es decir, el nombre que muestran es "Columna 1", "Columna 2", etc.
-----------------------------------------------------------------------------------------------------------------------
SOLUCIÓN:
- Si al importar, ves que la fila de cabeceras se trata como una fila más, cierra el Excel y sigue estos pasos:
1. Abre Excel (una hoja en blanco).
2. Ve a la pestaña "Datos".
3. Haz clic en "Obtener datos" (puede aparecer como "Obtener y transformar datos").
4. Selecciona: "Desde archivo" > "Desde texto/CSV".
5. Busca y selecciona el archivo `.csv` que deseas importar.
6. Se abrirá una vista previa, pero, a diferencia de la vez anterior, esta vez pulsarás en el botón de "Transformar datos".
7. Haz clic derecho sobre la primera fila y elige "Usar la primera fila como encabezados".
8. Pulsa sobre el botón de "Cerrar y cargar".

PROBLEMA 2: Todo aparece en una sola columna
----------------------------------------------
SOLUCIÓN:
- Esto suele ocurrir cuando Excel no detecta correctamente el delimitador.
- Usa el método "Desde texto/CSV" y selecciona el delimitador correcto (coma, punto y coma, tabulación...).
- Si abres directamente el archivo y ocurre esto, copia el contenido, pégalo en una hoja en blanco, y usa "Datos" > "Texto en columnas" para dividirlo manualmente.

PROBLEMA 3: Malos caracteres (� o símbolos raros)
----------------------------------------------------
SOLUCIÓN:
- Esto ocurre por una mala codificación. Usa el método recomendado ("Desde texto/CSV") y selecciona la codificación "65001: Unicode (UTF-8)".
- Si tu archivo está en ANSI u otra codificación, puedes convertirlo previamente con un editor de texto (como Notepad++), guardándolo como UTF-8.

PROBLEMA 4: Fechas o números se transforman (por ejemplo, 1/2 como 01-feb)
-----------------------------------------------------------------------------
SOLUCIÓN:
- Usa el método con asistente (heredado) y selecciona el tipo de dato de cada columna manualmente en el paso 3.
- Alternativamente, importa como texto y luego conviértelo con funciones de Excel si es necesario.

PROBLEMA 5: El archivo tiene separadores diferentes según el país
--------------------------------------------------------------------
SOLUCIÓN:
- En España y muchos países de Europa, el separador decimal es la coma (`,`), así que el separador de columnas en `.csv` suele ser el punto y coma (`;`).
- Asegúrate de seleccionar el delimitador correcto en el paso de importación.
- Puedes editar el `.csv` y hacer un reemplazo global (por ejemplo, cambiar `;` por `,`) si prefieres.



CONVERTIR FECHAS DE FORMATO DD-MM-AAAA A AAAA-MM-DD
====================================================

Este problema es común cuando importas archivos .CSV con fechas en formato europeo (DD-MM-AAAA) y necesitas convertirlas al formato ISO (AAAA-MM-DD), que es más adecuado para bases de datos o estandarización.

OPCIÓN 1: FORMATO PERSONALIZADO (CAMBIA SOLO LA VISUALIZACIÓN)
--------------------------------------------------------------
Si las fechas ya han sido correctamente reconocidas como fechas por Excel (es decir, se alinean a la derecha y puedes hacer operaciones con ellas), haz lo siguiente:

1. Selecciona la columna con fechas.
2. Haz clic derecho > "Formato de celdas".
3. En la pestaña "Número", selecciona "Personalizada".
4. En el campo de formato, escribe: aaaa-mm-dd
5. Pulsa Aceptar.

Esta opción solo cambia la forma en la que se muestran las fechas, no su valor interno.

OPCIÓN 2: CONVERTIR TEXTO A FECHA Y CAMBIAR EL FORMATO
-------------------------------------------------------
Si las fechas se importaron como **texto** (alineadas a la izquierda, o Excel no las reconoce como fechas válidas), necesitas transformarlas.

PASOS:
------
Supongamos que tienes una celda con la fecha `31-07-2025` (texto). Haz lo siguiente:

1. Supón que la fecha está en la celda A2.
2. En otra celda escribe esta fórmula:

=FECHA(DERECHA(A2;4);MEDIO(A2;4;2);IZQUIERDA(A2;2))
TXT;

// Nombre del archivo que se descargará
$nombreArchivo = "instrucciones.txt";

// Encabezados HTTP para indicar que se va a descargar un archivo de texto
header('Content-Type: text/plain; charset=UTF-8');
header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
header('Cache-Control: no-cache, must-revalidate');

// Enviar contenido al navegador
echo $contenido;
exit;
exit;