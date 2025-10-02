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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activity_name = $_POST['activity_name'];
    $activity_date = $_POST['activity_date'];
    $location = $_POST['location'];

    $stmt = $pdo->prepare('INSERT INTO activities (activity_name, activity_date, location) VALUES (?, ?, ?)');
    $stmt->execute([$activity_name, $activity_date, $location]);

    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <title>新增活动</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" sizes="16x16" href="../img/icons/favicon-16x16.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="../img/icons/favicon-32x32.png" />
    <link rel="shortcut icon" type="image/x-icon" href="../img/icons/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="../img/icons/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="192x192" href="../img/icons/android-chrome-192x192.png" />
    <link rel="icon" type="image/png" sizes="512x512" href="../img/icons/android-chrome-512x512.png" />
</head>

<body>
    <div class="container">
        <h1>新增活动</h1>
        <br>
        <form action="add_activity.php" method="post">
            <div class="form-group">
                <label for="activity_name">活动名称:</label>
                <input type="text" id="activity_name" name="activity_name" required>
            </div>
            <br>
            <div class="form-group">
                <label for="activity_date">活动日期:</label>
                <input type="date" id="activity_date" name="activity_date" required>
            </div>
            <br>
            <div class="form-group">
                <label for="location">地点:</label>
                <input type="text" id="location" name="location" required>
            </div>
            <br><br>
            <button type="submit" class="btn">添加</button>
        </form>
    </div>
</body>

</html>