<?php
ob_start();

include 'listarobjetos_config.php';

// ================= FILTRO IDS =================
$ids = $_POST['ids'] ?? '';
$ids = preg_replace('/[^0-9,]/', '', $ids);

$where = "";

if (!empty($ids)) {
    $where = "WHERE r.id IN ($ids)";
}

$where = "WHERE 1=1";

if (!empty($_GET['estado']))
  $where .= " AND r.estado = '{$_GET['estado']}'";

if (!empty($_GET['objeto']))
  $where .= " AND r.descripcion_objeto LIKE '%{$_GET['objeto']}%'";

if (!empty($_GET['categoria_id']))
  $where .= " AND r.categoria_id = '{$_GET['categoria_id']}'";

if (!empty($_GET['persona_registro']))
  $where .= " AND CONCAT(r.nombre,' ',r.apellido_paterno,' ',r.apellido_materno) LIKE '%{$_GET['persona_registro']}%'";

if (!empty($_GET['documento_persona_registro']))
  $where .= " AND r.nro_documento LIKE '%{$_GET['documento_persona_registro']}%'";

if (!empty($_GET['tipo_persona']))
  $where .= " AND r.tipo_persona = '{$_GET['tipo_persona']}'";

if (!empty($_GET['user_registro']))
  $where .= " AND r.user_id = '{$_GET['user_registro']}'";

if (!empty($_GET['fecha_ini']))
  $where .= " AND DATE(r.fecha_registro) >= '{$_GET['fecha_ini']}'";

if (!empty($_GET['fecha_fin']))
  $where .= " AND DATE(r.fecha_registro) <= '{$_GET['fecha_fin']}'";

if (!empty($_GET['acta_recepcion']))
  $where .= " AND a.nombre_acta LIKE '%{$_GET['acta_recepcion']}%'";

if (!empty($_GET['persona_entrega']))
  $where .= " AND CONCAT(e.nombre,' ',e.apellido_paterno,' ',e.apellido_materno) LIKE '%{$_GET['persona_entrega']}%'";

if (!empty($_GET['dni_entrega']))
  $where .= " AND e.nro_documento LIKE '%{$_GET['dni_entrega']}%'";

if (!empty($_GET['fecha_entrega_ini']))
  $where .= " AND DATE(e.fecha_entrega) >= '{$_GET['fecha_entrega_ini']}'";

if (!empty($_GET['fecha_entrega_fin']))
  $where .= " AND DATE(e.fecha_entrega) <= '{$_GET['fecha_entrega_fin']}'";

if (!empty($_GET['user_entrega']))
  $where .= " AND e.user_id = '{$_GET['user_entrega']}'";

if (!empty($_GET['acta_entrega']))
  $where .= " AND ae.nombre_acta LIKE '%{$_GET['acta_entrega']}%'";

// ================= SQL =================
$sql = "SELECT 
    r.id,

    CASE 
        WHEN r.estado = '1' THEN 'Registrado'
        WHEN r.estado = '2' THEN 'Con Acta'
        WHEN r.estado = '3' THEN 'Entregado'
        WHEN r.estado = '4' THEN 'Cerrado'
        ELSE 'Desconocido'
    END AS estado_texto,

    r.descripcion_objeto,
    c.nombre_categoria,
    r.lugar_referencia,

    CONCAT(r.nombre, ' ', r.apellido_paterno, ' ', r.apellido_materno) AS persona_registro,
    r.nro_documento AS documento_persona_registro,
    r.tipo_persona,
    CONCAT(upr.nombre, ' ', upr.apellido_paterno, ' ', upr.apellido_materno) AS usuario_registro_nombre,
    r.fecha_registro,


    a.nombre_acta AS acta_recepcion,
    a.fecha_subida AS fecha_acta_recepcion,

    CONCAT(e.nombre, ' ', e.apellido_paterno, ' ', e.apellido_materno) AS persona_entrega,
    e.nro_documento,
    e.correo,
    e.numero_contacto,
    e.direccion,
    e.fecha_entrega,

    CONCAT(up.nombre, ' ', up.apellido_paterno, ' ', up.apellido_materno) AS usuario_entrega_nombre,

    ae.nombre_acta AS acta_entrega,
    ae.fecha_subida AS fecha_acta_entrega

FROM objetosperdidos_registros r

LEFT JOIN objetosperdidos_categorias c ON r.categoria_id = c.id

LEFT JOIN objetosperdidos_actas a ON r.id = a.registro_id

LEFT JOIN objetosperdidos_entregas e ON r.id = e.registro_id

LEFT JOIN objetosperdidos_users u ON e.user_id = u.id

LEFT JOIN objetosperdidos_user_profile up ON u.id = up.user_id

-- 🔥 JOIN NUEVO (USUARIO REGISTRO)
LEFT JOIN objetosperdidos_users ur ON r.user_id = ur.id
LEFT JOIN objetosperdidos_user_profile upr ON ur.id = upr.user_id

LEFT JOIN objetosperdidos_entrega_actas ae ON e.id = ae.entrega_id

$where

ORDER BY r.id DESC";

$result = $conn->query($sql);

// 🔥 DEBUG SI FALLA
if (!$result) {
    die("Error SQL: " . $conn->error);
}

// ================= DATA =================
$data = [];

$data[] = [
    'ID',
    'Estado',
    'Objeto',
    'Categoría',
    'Lugar Referencia',
    'Persona Registro',
    'Tipo Persona',
    'Nro Documento',
    'Usuario Registro',
    'Fecha Registro',
    'Acta Recepción',
    'Fecha Acta',
    'Persona Recibe',
    'Nro Documento',
    'Correo',
    'Teléfono',
    'Dirección',
    'Fecha Entrega',
    'Usuario Entrega',
    'Acta Entrega',
    'Fecha Acta Entrega'
];

while ($row = $result->fetch_assoc()) {

    $data[] = [
        $row['id'],
        $row['estado_texto'],
        $row['descripcion_objeto'],
        $row['nombre_categoria'],
        $row['lugar_referencia'],

        $row['persona_registro'],
        $row['tipo_persona'],
        $row['documento_persona_registro'],
        
        $row['usuario_registro_nombre'],
        $row['fecha_registro'],
        

        $row['acta_recepcion'],
        $row['fecha_acta_recepcion'],

        $row['persona_entrega'],
        $row['nro_documento'],
        $row['correo'],
        $row['numero_contacto'],
        $row['direccion'],

        $row['fecha_entrega'],
        $row['usuario_entrega_nombre'],

        $row['acta_entrega'],
        $row['fecha_acta_entrega']
    ];
}

// ================= XML =================
function cell($v)
{
    $v = htmlspecialchars($v ?? '', ENT_XML1, 'UTF-8');
    return "<c t=\"inlineStr\"><is><t>$v</t></is></c>";
}

$rows = "";
foreach ($data as $r) {
    $rows .= "<row>";
    foreach ($r as $c) {
        $rows .= cell($c);
    }
    $rows .= "</row>";
}

// ================= XLSX =================
$sheetXML = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<worksheet xmlns=\"http://schemas.openxmlformats.org/spreadsheetml/2006/main\">
<sheetData>$rows</sheetData>
</worksheet>";

$workbookXML = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<workbook xmlns=\"http://schemas.openxmlformats.org/spreadsheetml/2006/main\"
xmlns:r=\"http://schemas.openxmlformats.org/officeDocument/2006/relationships\">
<sheets>
<sheet name=\"Datos\" sheetId=\"1\" r:id=\"rId1\"/>
</sheets>
</workbook>";

$relsRoot = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<Relationships xmlns=\"http://schemas.openxmlformats.org/package/2006/relationships\">
<Relationship Id=\"rId1\" Type=\"http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument\" Target=\"xl/workbook.xml\"/>
</Relationships>";

$relsWorkbook = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<Relationships xmlns=\"http://schemas.openxmlformats.org/package/2006/relationships\">
<Relationship Id=\"rId1\" Type=\"http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet\" Target=\"worksheets/sheet1.xml\"/>
</Relationships>";

$contentTypes = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<Types xmlns=\"http://schemas.openxmlformats.org/package/2006/content-types\">
<Default Extension=\"rels\" ContentType=\"application/vnd.openxmlformats-package.relationships+xml\"/>
<Default Extension=\"xml\" ContentType=\"application/xml\"/>
<Override PartName=\"/xl/workbook.xml\" ContentType=\"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml\"/>
<Override PartName=\"/xl/worksheets/sheet1.xml\" ContentType=\"application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml\"/>
</Types>";

// ================= ZIP =================
$tmp = tempnam(sys_get_temp_dir(), 'xlsx');

$zip = new ZipArchive();
$zip->open($tmp, ZipArchive::OVERWRITE);

$zip->addFromString('[Content_Types].xml', $contentTypes);
$zip->addFromString('_rels/.rels', $relsRoot);
$zip->addFromString('xl/workbook.xml', $workbookXML);
$zip->addFromString('xl/_rels/workbook.xml.rels', $relsWorkbook);
$zip->addFromString('xl/worksheets/sheet1.xml', $sheetXML);

$zip->close();

// ================= OUTPUT =================
ob_end_clean();

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=objetos_filtrados.xlsx");

readfile($tmp);
unlink($tmp);
exit;
