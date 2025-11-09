<?php
// SSE endpoint: emite cambios en config/detalles.json como eventos Server-Sent
ignore_user_abort(true);
set_time_limit(0);
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Access-Control-Allow-Origin: *');

$jsonPath = __DIR__ . '/../config/detalles.json';
$lastMtime = 0;

while (true) {
    clearstatcache(true, $jsonPath);
    if (!file_exists($jsonPath)) {
        echo "data: " . json_encode((object)[]) . "\n\n";
        @ob_flush(); @flush();
        sleep(2);
        continue;
    }
    $mtime = @filemtime($jsonPath);
    if ($mtime === false) $mtime = 0;
    if ($mtime !== $lastMtime) {
        $lastMtime = $mtime;
        $content = @file_get_contents($jsonPath);
        $detalles = json_decode($content, true);
        $map = [];
        if (is_array($detalles)) {
            foreach ($detalles as $d) {
                $nombre = trim(($d['nombre_docente'] ?? '') . ' ' . ($d['apellido_docente'] ?? ''));
                if ($nombre === '') continue;
                $estado = $d['estado_disponibilidad'] ?? ($d['estado'] ?? '');
                $aula = $d['aula'] ?? '';
                $map[$nombre] = ['estado' => $estado, 'aula' => $aula];
            }
        }
        echo "data: " . json_encode($map, JSON_UNESCAPED_UNICODE) . "\n\n";
        @ob_flush(); @flush();
    }
    // pequeño sleep para evitar carga alta
    sleep(2);
}
