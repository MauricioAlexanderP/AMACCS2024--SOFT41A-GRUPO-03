<?php
require_once dirname(__DIR__) . '/config/cn.php';

if (!isset($_GET['carnet'])) {
    echo json_encode(["error" => "No se recibió carnet"]);
    exit;
}

$carnet = $_GET['carnet'];

$cn = new cn();
$sql = "SELECT apellido FROM alumno WHERE carnet = '$carnet' LIMIT 1";
$res = $cn->consulta($sql);

if ($res->num_rows > 0) {
    $fila = $res->fetch_assoc();
    echo json_encode(["apellido" => $fila["apellido"]]);
} else {
    echo json_encode(["apellido" => null]);
}
