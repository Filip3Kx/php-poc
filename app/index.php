<?php
$customVar = getenv('MY_CUSTOM_VAR');
$redisHost = getenv('REDIS_HOST');
$redisPort = getenv('REDIS_PORT') ?: 6379;
$mysqlHost = getenv('MYSQL_HOST');
$mysqlPort = getenv('MYSQL_PORT') ?: 3306;
$mysqlDatabase = getenv('MYSQL_DATABASE');
$mysqlUser = getenv('MYSQL_USER');
$mysqlPassword = getenv('MYSQL_PASSWORD');

$redisStatus = 'Redis configuration not found';
if ($redisHost) {
    try {
        $redis = new Redis();
        $redis->connect($redisHost, (int) $redisPort, 1);
        $redisStatus = 'Connected to Redis: ' . htmlspecialchars($redisHost, ENT_QUOTES);
        $redis->close();
    } catch (Throwable $e) {
        $redisStatus = 'Redis connection failed: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}

$mysqlStatus = 'MySQL configuration not found';
if ($mysqlHost && $mysqlDatabase && $mysqlUser) {
    try {
        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $mysqlHost, $mysqlPort, $mysqlDatabase);
        $pdo = new PDO($dsn, $mysqlUser, $mysqlPassword, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $pdo->query('SELECT 1');
        $mysqlStatus = sprintf('Connected to MySQL database %s at %s:%s', htmlspecialchars($mysqlDatabase, ENT_QUOTES), htmlspecialchars($mysqlHost, ENT_QUOTES), htmlspecialchars($mysqlPort, ENT_QUOTES));
    } catch (Throwable $e) {
        $mysqlStatus = 'MySQL connection failed: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}

echo '<h1>Environment Variable Test: ' . htmlspecialchars($customVar ?: 'Not Found', ENT_QUOTES) . '</h1>';
echo '<ul>' . PHP_EOL;
echo '<li>' . htmlspecialchars($redisStatus, ENT_QUOTES) . '</li>' . PHP_EOL;
echo '<li>' . htmlspecialchars($mysqlStatus, ENT_QUOTES) . '</li>' . PHP_EOL;
echo '</ul>' . PHP_EOL;

phpinfo();
