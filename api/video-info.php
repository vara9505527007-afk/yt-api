<?php
header("Content-Type: application/json");
set_time_limit(0);

$YTDLP = "yt-dlp";
$FFMPEG = "ffmpeg";

$url = $_GET['url'] ?? '';

if (!$url) {
    echo json_encode(["success" => false, "message" => "URL required"]);
    exit;
}

$allowedHeights = [144, 240, 360, 480, 720, 1080, 1440, 2160];

$cmd = "$YTDLP --ffmpeg-location $FFMPEG --dump-single-json "
    . escapeshellarg($url) . " 2>&1";

$json = shell_exec($cmd);
$data = json_decode($json, true);

if (!is_array($data)) {
    echo json_encode(["success" => false, "message" => "Failed"]);
    exit;
}

$qualities = [];
foreach ($data['formats'] as $f) {
    if (!empty($f['height']) && $f['vcodec'] !== 'none') {
        if (in_array($f['height'], $allowedHeights)) {
            $qualities[$f['height']] = true;
        }
    }
}

$qualities = array_keys($qualities);
sort($qualities);

echo json_encode([
    "success" => true,
    "title" => $data['title'],
    "thumbnail" => $data['thumbnail'],
    "duration" => $data['duration'],
    "uploader" => $data['uploader'],
    "qualities" => $qualities
]);
