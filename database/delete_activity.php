<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

$host = 'localhost';
$db = 'volunteer';
$user = 'volunteer';
$pass = 'wh8tC6kXsMWxemTL';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$activity_id = $_GET['id'];

$stmt = $pdo->prepare('DELETE FROM volunteer_activities WHERE activity_id = ?');
$stmt->execute([$activity_id]);

$stmt = $pdo->prepare('DELETE FROM activities WHERE activity_id = ?');
$stmt->execute([$activity_id]);

header('Location: dashboard.php');
exit;
