<?php
require_once "./../../src/autoload.php";
require './../../src/libs/TCPDF/tcpdf.php';
session_start();

// Generamos los objetos y parámetros necesarios
$bbdd = new MySQLBBDD();
$gestor = new Gestor();
$usuario = $_SESSION['usuario'];

$alumnado = [];
$cursosGrupos = [];
$html = '';
$filas = [];
$salto = "<br>";

/*
El código JS hace un fetch (POST) con JSON en el cuerpo de la petición, pero no llegarán a PHP como $_POST['curso'], sino como un flujo JSON. Por ello hay que usar $inputJSON = file_get_contents('php://input');
 */

// Leer el contenido raw de la petición
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true); // true devuelve array asociativo

$curso = $input['curso'] ?? '';
$mes   = intval($input['mes']) ?? '';
$ano   = $input['ano'] ?? '';
$mes_impreso = str_pad($mes, 2, '0', STR_PAD_LEFT);

try {
    //Recuperamos a todos los alumnos
    $alumnos = $gestor->recuperaAlumnosCurso($bbdd, $curso);
    if($alumnos){
        $alumnos = Alumno::multicast($alumnos);
    } else{
        $errores[] = [
            'codigo' => CodigosError::GENERICO_SIN_CODIGO,
            'mensaje' => 'No se han recuperado los alumnos de la base de datos'
        ];
    }

    foreach ($alumnos as $alumno) {
        //Recuperamos las faltas
        $faltas = $gestor->recuperaFaltasMesAno($bbdd, $alumno->getId(), $mes, $ano);
        if($faltas){
            $faltas = Falta::multicast($faltas);
            $alumno->setFaltas($faltas);
        }
    }

} catch (ConsultaAlumnosException $e) {
    $errores[] = [
        'codigo'   => $e->getCode(),
        'mensaje'  => $e->getMessage(),
        'archivo'  => $e->getFile(),
        'linea'    => $e->getLine(),
    ];
} catch (ConsultaFaltasException $e) {
    $errores[] = [
        'codigo'   => $e->getCode(),
        'mensaje'  => $e->getMessage(),
        'archivo'  => $e->getFile(),
        'linea'    => $e->getLine(),
    ];
}

/*
Por defecto, TCPDF trabaja en milímetros (mm).
Es decir:
    $w, $h, $x, $y, márgenes, etc. están en milímetros.
    El tamaño de página (A4, Letter, etc.) también se define en mm salvo que se cambie.

-----------------------------------
¿Cómo cambiar las unidades?
-----------------------------------
Cuando creas el objeto TCPDF, puedes elegir la unidad en el constructor:

    $pdf = new TCPDF($orientation='P', $unit='mm', $format='A4');

El segundo parámetro ($unit) puede ser:

    'mm' → milímetros (por defecto).
    'pt' → puntos tipográficos (1 pt = 1/72 pulgadas).
    'cm' → centímetros.
    'in' → pulgadas.
 */

$pdf = new TCPDF();

// =====================
// CONFIGURACIÓN INICIAL
// =====================
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($usuario->getEmail());
$pdf->SetTitle('Listado de faltas');
$pdf->SetMargins(15, 15, 15); // márgenes del documento (izquierda, arriba, derecha)
$pdf->setFontSize(10);
$pdf->setPrintHeader(false); // sin cabecera automática
$pdf->setPrintFooter(false); // sin pie de página automático
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->AddPage('L', 'A4');    // orientación Landscape (horizontal), tamaño A4
$pdf->setFillColor(209,209,209); //seleccionamos el color de fondo de las cabeceras
$pdf->setLineStyle(['width'=>0.1]);

/*
Explicación clave de MultiCell:

Parámetros principales:

MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)

1. $w (width): Anchura de la celda en milímetros. Si es 0, la celda se extiende hasta el margen derecho.
2. $h (height): Altura mínima de la celda (no máxima). El contenido puede hacer que la celda crezca en altura si el texto ocupa varias líneas.
3. $txt: El texto que se quiere imprimir. Puede ser texto plano o HTML simple (si $ishtml = true).
4. $border: Define los bordes de la celda. Valores posibles:
    0 → sin borde.
    1 → borde alrededor.
    "L", "T", "R", "B" → bordes individuales (Left, Top, Right, Bottom). Combinación posible: "LTB".
5. $align (horizontal alignment): Alineación horizontal del texto:
    'L' → izquierda.
    'C' → centrado.
    'R' → derecha.
    'J' → justificado.
6. $fill: Relleno de fondo.
    0 → sin relleno.
    1 → relleno con el color de fondo actual (SetFillColor).

7. $ln: Qué hacer con la posición del cursor después de imprimir la celda:
    0 → a la derecha.
    1 → al inicio de la siguiente línea.
    2 → debajo.

8. $x: Coordenada X donde colocar la celda (desde el margen izquierdo). Si se deja vacío (''), se usa la posición actual.
9. $y: Coordenada Y donde colocar la celda (desde el margen superior). Si se deja vacío (''), se usa la posición actual.
10. $reseth: Si true, reinicia el "height" interno usado para saltos de línea automáticos. Normalmente se deja true.
11. $stretch: Controla cómo se adapta el texto:
    0 → sin estiramiento.
    1 → ajusta ancho para que quepa en la celda.
    2 → ajusta espaciado de caracteres.
    3 → ajusta espaciado entre palabras.
    4 → forzar texto en una sola línea.

12. $ishtml:
    false → interpreta $txt como texto plano.
    true → interpreta $txt como HTML simple (admite <b>, <i>, <br>, etc.).

13. $autopadding: Si true, añade padding automático (espaciado interno entre texto y borde).

14. $maxh: Altura máxima permitida para la celda. Si el texto excede, se corta.

15. $valign (vertical alignment): Alineación vertical dentro de la celda:
    'T' → arriba (Top).
    'M' → medio (Middle).
    'B' → abajo (Bottom).

16. $fitcell: Si true, fuerza el texto a encajar dentro de la celda (sin desbordar).

EJEMPLOS EN: https://tcpdf.org/examples/
 */

try {

    $grupos  = $gestor->recuperaGrupos($bbdd);
    $tarifas = $gestor->recuperaTarifas($bbdd);
    $tarifas = Tarifa::multicast($tarifas);

    $html = "<h3 style='text-align: right;'>Faltas del {$mes_impreso}/{$ano}</h3><br>";
    $pdf->writeHTML($html);
    
    // ======================================
    // RECORREMOS CADA GRUPO DE ALUMNOS
    // ======================================
    foreach ($grupos as $grupo) {
        //Comprobamos que hay alumnos en un curso y grupo dado
        if(!$gestor->recuperaAlumnosCursoGrupo($bbdd, $curso, $grupo)){
            continue;
        } else{
            
            // Definimos los anchos fijos de columnas
            $anchoAlumno = 60;
            $anchoTarifa = 10;
            $anchoDia    = round((267 - $anchoAlumno - $anchoTarifa) / 31, 2); // ≈ 5.55 mm
    
            // Si no es la primera página, añadimos una nueva
            if ($pdf->getPage() > 1) {
                $pdf->AddPage('L', 'A4');
            }
    
            // ================================
            // CABECERA DEL GRUPO (curso + días)
            // ================================
            $pdf->SetFont('', 'B'); // negrita para cabecera
            
            // 1ª columna: Nombre del curso y grupo
            $pdf->MultiCell($anchoAlumno, 6, "{$curso} - {$grupo}", 1, 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
            
            // 2ª columna: Tarifa (abreviado como "T")
            $pdf->MultiCell($anchoTarifa, 6, "T", 1, 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
            
            // 31 columnas para los días
            for ($d = 1; $d <= 31; $d++) {
                $dia = str_pad($d, 2, "0", STR_PAD_LEFT);
                $pdf->MultiCell($anchoDia, 6, $dia, 1, 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
            }
    
            // Salto de línea al terminar la fila de cabecera
            $pdf->Ln();
    
            // ================================
            // FILAS DE ALUMNOS DEL GRUPO
            // ================================
            $pdf->SetFont('', ''); // texto normal

            // Altura estimada de fila y cabecera
            $alturaFila = 6;
            $alturaCabecera = 6;
            $margenInferior = PDF_MARGIN_BOTTOM;

            foreach ($alumnos as $alumno) {
                if ($alumno->getGrupo() !== $grupo) {
                    continue;
                }

                // Verifica si hay espacio suficiente para imprimir una fila + cabecera
                if ($pdf->GetY() > ($pdf->getPageHeight() - $margenInferior - ($alturaFila + $alturaCabecera))) {
                    // No hay espacio: creamos nueva página
                    $pdf->AddPage('L', 'A4');

                    // Redibujamos cabecera del grupo (idéntica a la primera)
                    $pdf->SetFont('', 'B');
                    $pdf->MultiCell($anchoAlumno, $alturaCabecera, "{$curso} - {$grupo}", 1, 'C', 1, 0);
                    $pdf->MultiCell($anchoTarifa, $alturaCabecera, "T", 1, 'C', 1, 0);
                    for ($d = 1; $d <= 31; $d++) {
                        $dia = str_pad($d, 2, "0", STR_PAD_LEFT);
                        $pdf->MultiCell($anchoDia, $alturaCabecera, $dia, 1, 'C', 1, 0);
                    }
                    $pdf->Ln();
                    $pdf->SetFont('', '');
                }

                // ==============================
                // Fila del alumno
                // ==============================
                $nombre = "{$alumno->getApellido1()} {$alumno->getApellido2()}, {$alumno->getNombre()}";
                $pdf->MultiCell($anchoAlumno, $alturaFila, $nombre, 1, 'L', 0, 0);

                $pdf->MultiCell($anchoTarifa, $alturaFila, $alumno->getTarifa()->getTarifa(), 1, 'C', 0, 0);

                $calendario_faltas = $alumno->getCalendario();
                foreach ($calendario_faltas as $dia => $falta) {
                    $marca = $falta != '' ? "X" : "";
                    $pdf->MultiCell($anchoDia, $alturaFila, $marca, 1, 'C', 0, 0);
                }

                $pdf->Ln();
            }
        }

    }

    // =======================
    // SALIDA FINAL DEL PDF
    // =======================
    $pdf->Output('faltasGantt.pdf', 'I');

} catch (ConsultaAlumnosException $e) {

    // Enviar JSON con error si se produce algún error
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}