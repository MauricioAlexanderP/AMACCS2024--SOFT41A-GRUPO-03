<?php
// Script para regenerar config/detalles.json desde la base de datos
// Asegurar que REQUEST_METHOD exista para evitar warnings cuando se ejecuta por CLI
if (!isset($_SERVER['REQUEST_METHOD'])) {
    $_SERVER['REQUEST_METHOD'] = 'CLI';
}
require_once __DIR__ . '/../model/detalle.php';

try {
    $detalle = new Detalle();
    $ok = $detalle->generarJSON();
    $ruta = __DIR__ . '/../config/detalles.json';

    if ($ok && file_exists($ruta)) {
        $data = json_decode(file_get_contents($ruta), true);
        $count = is_array($data) ? count($data) : 0;
        echo "OK: detalles.json generado. Registros: {$count}\nRuta: {$ruta}\n";
        exit(0);
    } else {
        echo "ERROR: No se pudo generar detalles.json\n";
        exit(1);
    }
} catch (Throwable $e) {
    echo "EXCEPCION: " . $e->getMessage() . "\n";
    exit(2);
}
