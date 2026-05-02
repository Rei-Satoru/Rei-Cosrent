<?php
require_once 'bootstrap/app.php';
use Illuminate\Support\Facades\DB;

$app = require_once 'bootstrap/app.php';

// Check catalog images
$catalogs = DB::table('data_katalog')->select('name', 'image')->get();
echo "=== DATA KATALOG ===\n";
foreach($catalogs as $cat) {
    echo "Name: {$cat->name}, Image: {$cat->image}\n";
}

// Check costume images
$costumes = DB::table('data_kostum')->select('nama_kostum', 'gambar')->limit(5)->get();
echo "\n=== DATA KOSTUM (5 items) ===\n";
foreach($costumes as $cos) {
    echo "Name: {$cos->nama_kostum}, Image: {$cos->gambar}\n";
}
?>
