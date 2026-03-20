<?php
// Simple migration script: copy a user and their artist from SQLite -> MySQL
// Usage: php tools/migrate_user.php user@example.com

$email = $argv[1] ?? null;
if (!$email) {
    echo "Usage: php tools/migrate_user.php user@example.com\n";
    exit(1);
}

$projectRoot = dirname(__DIR__);
$sqlitePath = $projectRoot . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'database.sqlite';
if (!file_exists($sqlitePath)) {
    echo "SQLite DB not found at: $sqlitePath\n";
    exit(1);
}

try {
    $sqlite = new PDO('sqlite:' . $sqlitePath);
    $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $mysqlDsn = 'mysql:host=127.0.0.1;dbname=arcdatabase;charset=utf8mb4';
    $mysql = new PDO($mysqlDsn, 'root', '');
    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    echo "DB connection error: " . $e->getMessage() . "\n";
    exit(1);
}

// Fetch user from sqlite
$stmt = $sqlite->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
$stmt->execute([':email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    echo "No user found in SQLite with email: $email\n";
    exit(0);
}

// Check if user exists in MySQL
$check = $mysql->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
$check->execute([':email' => $email]);
$existing = $check->fetch(PDO::FETCH_ASSOC);
if ($existing) {
    echo "User already exists in MySQL with id: {$existing['id']}. Skipping user insert.\n";
    $newUserId = $existing['id'];
} else {
    // Get MySQL user columns
    $colsStmt = $mysql->query('SHOW COLUMNS FROM users');
    $colsInfo = $colsStmt->fetchAll(PDO::FETCH_ASSOC);
    $mysqlCols = array_column($colsInfo, 'Field');

    $insertData = [];
    foreach ($user as $k => $v) {
        if (in_array($k, $mysqlCols)) {
            $insertData[$k] = $v;
        }
    }
    // Do not copy primary id to avoid duplicate PK collisions; let MySQL assign a new id
    if (isset($insertData['id'])) {
        unset($insertData['id']);
    }

    // Ensure timestamps exist
    if (in_array('created_at', $mysqlCols) && !isset($insertData['created_at'])) {
        $insertData['created_at'] = date('Y-m-d H:i:s');
    }
    if (in_array('updated_at', $mysqlCols) && !isset($insertData['updated_at'])) {
        $insertData['updated_at'] = date('Y-m-d H:i:s');
    }

    $fields = array_keys($insertData);
    $placeholders = array_map(function ($f){ return ':' . $f; }, $fields);
    $sql = 'INSERT INTO users (' . implode(',', $fields) . ') VALUES (' . implode(',', $placeholders) . ')';
    $ins = $mysql->prepare($sql);
    foreach ($insertData as $k => $v) $ins->bindValue(':' . $k, $v);
    $ins->execute();
    $newUserId = $mysql->lastInsertId();
    echo "Inserted user into MySQL with id: $newUserId\n";
}

// Migrate artist(s) associated with this user in SQLite
$artistStmt = $sqlite->prepare('SELECT * FROM artists WHERE email = :email OR user_id = :uid');
$artistStmt->execute([':email' => $email, ':uid' => $user['id']]);
$artists = $artistStmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($artists as $artist) {
    // Skip if artist with same email exists in MySQL
    $chk = $mysql->prepare('SELECT id FROM artists WHERE email = :email LIMIT 1');
    $chk->execute([':email' => $artist['email']]);
    $exists = $chk->fetch(PDO::FETCH_ASSOC);
    if ($exists) {
        echo "Artist already exists in MySQL (email={$artist['email']}) — skipped.\n";
        continue;
    }

    // Determine MySQL artist columns
    $colsStmt = $mysql->query('SHOW COLUMNS FROM artists');
    $colsInfo = $colsStmt->fetchAll(PDO::FETCH_ASSOC);
    $mysqlCols = array_column($colsInfo, 'Field');

    $insertData = [];
    foreach ($artist as $k => $v) {
        if (in_array($k, $mysqlCols)) {
            $insertData[$k] = $v;
        }
    }
    // Avoid copying the primary key from SQLite
    if (isset($insertData['id'])) {
        unset($insertData['id']);
    }
    // Set user_id to new user id
    if (in_array('user_id', $mysqlCols)) {
        $insertData['user_id'] = $newUserId;
    }
    if (in_array('created_at', $mysqlCols) && !isset($insertData['created_at'])) {
        $insertData['created_at'] = date('Y-m-d H:i:s');
    }
    if (in_array('updated_at', $mysqlCols) && !isset($insertData['updated_at'])) {
        $insertData['updated_at'] = date('Y-m-d H:i:s');
    }

    $fields = array_keys($insertData);
    $placeholders = array_map(function ($f){ return ':' . $f; }, $fields);
    $sql = 'INSERT INTO artists (' . implode(',', $fields) . ') VALUES (' . implode(',', $placeholders) . ')';
    $ins = $mysql->prepare($sql);
    foreach ($insertData as $k => $v) $ins->bindValue(':' . $k, $v);
    $ins->execute();
    $newArtistId = $mysql->lastInsertId();
    echo "Inserted artist into MySQL with id: $newArtistId (email={$artist['email']})\n";
}

echo "Migration complete for $email\n";

exit(0);

