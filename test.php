<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$key = env('GEMINI_API_KEY');
$response = Illuminate\Support\Facades\Http::withoutVerifying()->get("https://generativelanguage.googleapis.com/v1beta/models?key={$key}");
$models = $response->json('models');
if ($models) {
    foreach($models as $m) {
        if(strpos($m['name'], 'flash') !== false || strpos($m['name'], 'pro') !== false) {
            echo $m['name'] . "\n";
        }
    }
} else {
    echo "No models found or error.";
}
