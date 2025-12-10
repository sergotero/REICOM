<?php
require_once "./../../src/autoload.php";
require './../../src/libs/TCPDF/tcpdf.php';
session_start();

// Generamos los objetos necesarios
$bbdd = new MySQLBBDD();
$gestor = new Gestor();
$usuario = $_SESSION['usuario'];
$hoy = date('Y-m-d');
$fechaImpresion = date('d/m/Y');

// Estas fechas las necesitamos para poder hacer un cribado más adelante.
$today = date_create($hoy);

$hoy_literal = date_format($today, "D");
switch ($hoy_literal) {
    case "Mon":
        $hoy_literal = "Lunes";
        break;
    case "Tue":
        $hoy_literal = "Martes";
    break;
    case "Wed":
        $hoy_literal = "Miércoles";
    break;
    case "Thu":
        $hoy_literal = "Jueves";
    break;
    case "Fri":
        $hoy_literal = "Viernes";
    break;
    case "Sat":
        $hoy_literal = "Sábado";
    break;
    case "Sun":
        $hoy_literal = "Domingo";
    break;
}

$pdf = new TCPDF();

// =====================
// CONFIGURACIÓN INICIAL
// =====================
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($usuario->getEmail());
$pdf->SetTitle('Listado de alumnos');
$pdf->SetMargins(15, 15, 15);
$pdf->setFontSize(10);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->AddPage('P', 'A4');
$pdf->setFillColor(209,209,209);
$pdf->setLineStyle(['width'=>0.1]);

$filas_grupo = [];
$filas = '';
$grupos_faltas = [];
$cursos_faltas = [];

//Recuperamos los cursos y grupos en los que hay faltas con fecha de hoy
$resultado = $gestor->recuperaCursosGruposConFaltas($bbdd);
if($resultado){
    //Los filtramos porque estos valores van a verse "duplicados" (en realidad no lo son porque pertenecen a alumnos diferentes, pero la consulta no lo muestra)
    for ($i=0; $i < count($resultado); $i++) { 
        if(!in_array($resultado[$i]->curso, $cursos_faltas)){
            $cursos_faltas[] = $resultado[$i]->curso;
        }
        if(!in_array($resultado[$i]->grupo, $grupos_faltas))
        $grupos_faltas[] = $resultado[$i]->grupo;
    }
}

// Definimos los anchos fijos de columnas (en mm)
$anchoAlumno = 60;
$anchoCurso = 15;
$anchoGrupo = 15;
$anchoAlergias = 50;
$anchoActividades = 40;

try {

    // ======================================
    // RECORREMOS CADA CURSO
    // ======================================
    foreach ($cursos_faltas as $curso) {
        

        $pdf->SetFont('', '');
        $html = <<<MARCA
                <p style="text-align:right;">Fecha de impresión: {$fechaImpresion}</p>
                <h2 style="text-align:center;">Listado de alumnos</h2>
                <h3 style="text-align:right;">Curso: {$curso}</h3>
                <br>

        MARCA;
        $pdf->writeHTML($html);

        // ================================
        // CABECERA DEL GRUPO
        // ================================
        
        //Cambiamos el tipo de texto a negrita
        $pdf->SetFont('', 'B');
        // 1ª Columna
        $pdf->MultiCell($anchoCurso, 6, "Curso", 1, 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
        // 2ª Columna
        $pdf->MultiCell($anchoGrupo, 6, "Grupo", 1, 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
        // 3ª Columna
        $pdf->MultiCell($anchoAlumno, 6, "Apellidos y nombre", 1, 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
        // 4ª Columna
        $pdf->MultiCell($anchoAlergias, 6, "Alergias e intolerancias", 1, 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
        // 4ª Columna
        $pdf->MultiCell($anchoActividades, 6, "Actividades", 1, 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
        //Añadimos un salto de línea
        $pdf->Ln();


        // ======================================
        // RECORREMOS CADA GRUPO
        // ======================================
        foreach ($grupos_faltas as $grupo) {
            
            $resultado = $gestor->recuperaAlumnosCursoGrupo($bbdd, $curso, $grupo);

            if($resultado){
                
                $alumnado = Alumno::multicast($resultado);

                //Recorremos los alumnos
                foreach ($alumnado as $alumno) {
                    
                    //Recuperamos actividades
                    $actividades = $gestor->recuperaActividadesAlumno($bbdd, $alumno);
                    $actividades = Actividad::multicast($actividades);
                    if($actividades != null){
                        $alumno->setActividades($actividades);
                    }
                    $textoActividades = $alumno->getActividadDia($hoy_literal);
                    
                    // Recuperamos faltas
                    $faltas = Falta::multicast($gestor->recuperaFaltas($bbdd, $alumno));
            
                    if(count($faltas)>0){
                        $alumno->setFaltas($faltas);
                    }
            
                    // Verificamos faltas
                    if(($alumno->getFaltas() != null) && ($alumno->getFalta($hoy) != null) && ($alumno->getFalta($hoy)->getFecha() == $hoy)){

                        //Controla el fin de la página para evitar errores
                        if ($pdf->GetY() > 260) { // Ajusta según márgenes y tamaño de hoja
                            $pdf->AddPage();
                            // Redibuja la cabecera de tabla
                            $pdf->SetFont('', 'B');
                            $pdf->MultiCell($anchoCurso, 6, "Curso", 1, 'C', 1, 0);
                            $pdf->MultiCell($anchoGrupo, 6, "Grupo", 1, 'C', 1, 0);
                            $pdf->MultiCell($anchoAlumno, 6, "Apellidos y nombre", 1, 'C', 1, 0);
                            $pdf->MultiCell($anchoAlergias, 6, "Alergias e intolerancias", 1, 'C', 1, 0);
                            $pdf->MultiCell($anchoActividades, 6, "Actividades", 1, 'C', 1, 0);
                            $pdf->Ln();
                            $pdf->SetFont('', '');
                        }
                        
                        //Cambiamos el tipo de texto (a normal)
                        $pdf->SetFont('', '');
                        // 1ª Columna
                        $pdf->MultiCell($anchoCurso, 6, $alumno->getCurso(), 1, 'C', 0, 0, '', '', true, 0, false, true, 6, 'M');
                        // 2ª Columna
                        $pdf->MultiCell($anchoGrupo, 6, $alumno->getGrupo(), 1, 'C', 0, 0, '', '', true, 0, false, true, 6, 'M');
                        // 3ª Columna
                        $pdf->MultiCell($anchoAlumno, 6, $alumno->getApellido1() . " " .$alumno->getApellido2() . ", " . $alumno->getNombre(), 1, 'C', 0, 0, '', '', true, 0, false, true, 6, 'M');
                        // 4ª Columna
                        $pdf->MultiCell($anchoAlergias, 6, ($alumno->getAlergias())??'', 1, 'C', 0, 0, '', '', true, 0, false, true, 6, 'M', true);
                        // 4ª Columna
                        $pdf->MultiCell($anchoActividades, 6, $textoActividades, 1, 'C', 0, 0, '', '', true, 0, false, true, 6, 'M', true);
                        
                        //Añadimos un salto de línea
                        $pdf->Ln();
                        
                    }
                }
            }

        }
        
        $pdf->Ln();
        //Creamos una nueva página para el siguiente curso
        if($curso != end($cursos_faltas)){
            $pdf->AddPage();
        }

    }

    $pdf->Output('listado.pdf', 'I');

} catch (ConsultaAlumnosException $e) {
    header('Content-type: application/json');
    echo json_encode(['error'=> $e->getMessage()]);
    exit;
} catch(CreaAlumnoException $e){
    header('Content-type: application/json');
    echo json_encode(['error'=> $e->getMessage()]);
    exit;
}



