<?php
$envPath = __DIR__ . '/.env';
$lines = file($envPath);
$newLines = [];
$seenKeys = [];

foreach($lines as $line) {
    if(trim($line) === '' || strpos(trim($line), '#') === 0) {
        $newLines[] = $line;
        continue;
    }
    $parts = explode('=', $line, 2);
    $key = trim($parts[0]);
    if(in_array($key, $seenKeys) && in_array($key, ['BROADCAST_CONNECTION', 'REVERB_APP_ID', 'REVERB_APP_KEY', 'REVERB_APP_SECRET', 'REVERB_HOST', 'REVERB_PORT', 'REVERB_SCHEME', 'VITE_REVERB_APP_KEY', 'VITE_REVERB_HOST', 'VITE_REVERB_PORT', 'VITE_REVERB_SCHEME'])) {
        continue; // skip duplicate
    }
    $seenKeys[] = $key;
    $newLines[] = $line;
}

file_put_contents($envPath, implode("", $newLines));
echo "ENV cleaned.\n";