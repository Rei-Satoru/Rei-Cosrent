<?php
// Safe backfill: link pengembalian.formulir_id when a single candidate formulir is found near the pengembalian created_at
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$db   = $_ENV['DB_DATABASE'] ?? 'rc_laravel';
$user = $_ENV['DB_USERNAME'] ?? 'root';
$pass = $_ENV['DB_PASSWORD'] ?? '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

$pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);

$rows = $pdo->query("SELECT id, created_at FROM pengembalian WHERE formulir_id IS NULL")->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $r) {
    $pid = (int)$r['id'];
    $created = $r['created_at'];
    echo "Processing pengembalian id=$pid created_at=$created\n";

    $stmt = $pdo->prepare(
        "SELECT id, email, nama_kostum, created_at, tanggal_pengembalian FROM formulir
         WHERE status IN ('diterima')
         AND created_at BETWEEN DATE_SUB(?, INTERVAL 7 DAY) AND DATE_ADD(?, INTERVAL 7 DAY)
         AND id NOT IN (SELECT formulir_id FROM pengembalian WHERE formulir_id IS NOT NULL)");
    $stmt->execute([$created, $created]);
    $candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = count($candidates);
    if ($count === 0) {
        echo "  No candidates found (0)\n";
        continue;
    }
    if ($count === 1) {
        $fid = (int)$candidates[0]['id'];
        echo "  Single candidate found: formulir_id=$fid (email={$candidates[0]['email']}, kostum={$candidates[0]['nama_kostum']})\n";
        $u = $pdo->prepare("UPDATE pengembalian SET formulir_id = ? WHERE id = ?");
        $u->execute([$fid, $pid]);
        echo "  Updated pengembalian.$pid -> formulir_id=$fid\n";
        continue;
    }
    echo "  Multiple candidates found ($count):\n";
    foreach ($candidates as $c) {
        echo "    id={$c['id']}, email={$c['email']}, nama_kostum={$c['nama_kostum']}, created_at={$c['created_at']}, tanggal_pengembalian={$c['tanggal_pengembalian']}\n";
    }
    echo "  Skipping update to avoid ambiguity.\n";
}

echo "Done.\n";
