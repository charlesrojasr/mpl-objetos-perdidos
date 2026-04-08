<?php
include 'config.php';

$idiomaM = "es";

$primaryTable = "objetosperdidos_registros";
// ============================================
// EVITAR CORTE EN GROUP_CONCAT (IMPORTANTE)
// ============================================
$conn->query("SET SESSION group_concat_max_len = 1000000");
$appModulo = 'LISTA DE OBJETOS PERDIDOS';
// ============================================
// CAMPOS BD
// ============================================
$titulocampobd1 = 'id';
$titulocampobd2 = 'descripcion_objeto';
$titulocampobd3 = 'nombre_categoria';
$titulocampobd4 = 'lugar_referencia';

$titulocampobd5 = 'fecha_registro';
$titulocampobd6 = 'usuario_registro';

$titulocampobd7 = 'persona_registro';
$titulocampobd8 = 'tipo_persona';

$titulocampobd9 = 'acta_recepcion';
$titulocampobd10 = 'fecha_acta_recepcion';

$titulocampobd11 = 'persona_entrega';
$titulocampobd12 = 'fecha_entrega';
$titulocampobd13 = 'usuario_entrega';

$titulocampobd14 = 'correo_entrega';
$titulocampobd15 = 'telefono_entrega';

$titulocampobd16 = 'acta_entrega';
$titulocampobd17 = 'fecha_acta_entrega';

$titulocampobd18 = 'fotos_registro';
$titulocampobd19 = 'archivos_entrega';

$titulocampobd20 = 'estado_texto';


// ============================================
// TITULOS PARA EXPORTACION
// ============================================
$titulocampobd1P = 'ID';
$titulocampobd2P = 'Objeto';
$titulocampobd3P = 'Categoría';
$titulocampobd4P = 'Lugar';

$titulocampobd5P = 'Fecha Registro';
$titulocampobd6P = 'Usuario Registro';

$titulocampobd7P = 'Persona Registro';
$titulocampobd8P = 'Tipo Persona';

$titulocampobd9P = 'Acta Recepción';
$titulocampobd10P = 'Fecha Acta Recepción';

$titulocampobd11P = 'Persona Entrega';
$titulocampobd12P = 'Fecha Entrega';
$titulocampobd13P = 'Usuario Entrega';

$titulocampobd14P = 'Correo Entrega';
$titulocampobd15P = 'Teléfono Entrega';

$titulocampobd16P = 'Acta Entrega';
$titulocampobd17P = 'Fecha Acta Entrega';

$titulocampobd18P = 'Fotos Registro';
$titulocampobd19P = 'Archivos Entrega';

$titulocampobd20P = 'Estado';


// ============================================
// MODULOS
// ============================================
$modulo_add = "objetos_modal_add.php";
$modulo_edit = "objetos_modal_edit.php";


// ============================================
// SQL COMPLETO
// ============================================
$sql = "SELECT 
    r.id,

    r.descripcion_objeto,
    c.nombre_categoria,
    r.lugar_referencia,

    r.fecha_registro,
    ur.username AS usuario_registro,

    CONCAT(r.nombre, ' ', r.apellido_paterno, ' ', r.apellido_materno) AS persona_registro,
    r.tipo_persona,

    a.nombre_acta AS acta_recepcion,
    a.fecha_subida AS fecha_acta_recepcion,

    CONCAT(e.nombre, ' ', e.apellido_paterno, ' ', e.apellido_materno) AS persona_entrega,
    e.fecha_entrega,
    ue.username AS usuario_entrega,

    e.correo AS correo_entrega,
    e.numero_contacto AS telefono_entrega,

    ae.nombre_acta AS acta_entrega,
    ae.fecha_subida AS fecha_acta_entrega,

    -- 🔥 FOTOS (CONCATENADAS)
    (
        SELECT GROUP_CONCAT(f.nombre_archivo SEPARATOR ', ')
        FROM objetosperdidos_registro_fotos f
        WHERE f.registro_id = r.id
    ) AS fotos_registro,

    -- 🔥 ARCHIVOS ENTREGA (CONCATENADOS)
    (
        SELECT GROUP_CONCAT(fa.nombre_archivo SEPARATOR ', ')
        FROM objetosperdidos_entrega_archivos fa
        WHERE fa.entrega_id = e.id
    ) AS archivos_entrega,

    CASE 
        WHEN r.estado = '1' THEN 'Registrado'
        WHEN r.estado = '2' THEN 'Con Acta'
        WHEN r.estado = '4' THEN 'Entregado'
        WHEN r.estado = '5' THEN 'Cerrado'
        ELSE 'Desconocido'
    END AS estado_texto

FROM objetosperdidos_registros r

LEFT JOIN objetosperdidos_categorias c 
    ON r.categoria_id = c.id

LEFT JOIN objetosperdidos_users ur 
    ON r.user_id = ur.id

LEFT JOIN objetosperdidos_actas a 
    ON r.id = a.registro_id

LEFT JOIN objetosperdidos_entregas e 
    ON r.id = e.registro_id

LEFT JOIN objetosperdidos_users ue 
    ON e.user_id = ue.id

LEFT JOIN objetosperdidos_entrega_actas ae 
    ON e.id = ae.entrega_id

ORDER BY r.id DESC
";


// ============================================
// EJECUCION
// ============================================
$stmt = $conn->prepare($sql);
$stmt->execute();
$getAllRegistros = $stmt->get_result();
