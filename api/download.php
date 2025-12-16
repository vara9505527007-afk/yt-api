<?php
set_time_limit(0);

$YTDLP = "yt-dlp";
$FFMPEG = "ffmpeg";

$url = $_GET['url'] ?? '';
$height = (int)($_GET['height'] ?? 720);

if (!$url || !$height) {
    http_response_code(400);
    exit("Invalid parameters");
}

$file = sys_get_temp_dir() . "/yt_" . uniqid() . ".mp4";

$cmd = "$YTDLP --ffmpeg-location $FFMPEG "
    . "-f \"bv*[ext=mp4][height<={$height}]+ba[ext=m4a]/b[ext=mp4][height<={$height}]\" "
    . "--merge-output-format mp4 "
    . "-o \"$file\" "
    . escapeshellarg($url) . " 2>&1";

shell_exec($cmd);

if (!file_exists($file)) {
    http_response_code(500);
    exit("Download failed");
}

header("Content-Type: video/mp4");
header("Content-Disposition: attachment; filename=video_{$height}p.mp4");
header("Content-Length: " . filesize($file));

readfile($file);
unlink($file);
exit;
