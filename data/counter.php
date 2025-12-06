<?php
// Nur zählen, wenn die Anfrage von deiner Domain kommt
if (
    !isset($_SERVER['HTTP_REFERER']) ||
    strpos($_SERVER['HTTP_REFERER'], 'mzs.rocks') === false
) {
    // Transparenter Pixel zurück, aber NICHT zählen
    header('Content-Type: image/gif');
    echo base64_decode("R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==");
    exit;
}


// Counter-Datei außerhalb des Webroots
$file = '/config/counter/counter.txt';

// Sicherstellen, dass der Ordner existiert
$dir = dirname($file);
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

// Datei öffnen oder neu anlegen
$fp = fopen($file, 'c+');

if ($fp === false) {
    header('Content-Type: image/gif');
    echo base64_decode("R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==");
    exit;
}

// Lock gegen gleichzeitige Zugriffe
if (flock($fp, LOCK_EX)) {
    $size  = filesize($file);
    $count = $size > 0 ? (int)fread($fp, $size) : 0;
    $count++;

    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, (string)$count);
    fflush($fp);
    flock($fp, LOCK_UN);
}

fclose($fp);

// Transparentes 1x1 GIF als Antwort
header('Content-Type: image/gif');
echo base64_decode("R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==");
