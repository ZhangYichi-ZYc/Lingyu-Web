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
    $volunteer_id = $_POST['volunteer_id'];
    $activity_id = $_POST['activity_id'];

    $stmt = $pdo->prepare('INSERT INTO volunteer_activities (volunteer_id, activity_id) VALUES (?, ?)');
    $stmt->execute([$volunteer_id, $activity_id]);

    header('Location: dashboard.php');
    exit;
}

$volunteers = $pdo->query('SELECT * FROM volunteers')->fetchAll();
$activities = $pdo->query('SELECT * FROM activities')->fetchAll();
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <title>新增志愿者活动关联</title>
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
        <h1>志愿者活动关联</h1>
        <br>
        <form action="add_volunteer_activity.php" method="post">
            <div class="form-group">
                <label for="volunteer_id">志愿者:</label>
                <select id="volunteer_id" name="volunteer_id" required>
                    <?php foreach ($volunteers as $volunteer) : ?>
                        <option value="<?php echo $volunteer['volunteer_id']; ?>">
                            <?php echo htmlspecialchars($volunteer['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div><br>
            <div class="form-group">
                <label for="activity_id">活动:</label>
                <select id="activity_id" name="activity_id" required>
                    <?php foreach ($activities as $activity) : ?>
                        <option value="<?php echo $activity['activity_id']; ?>">
                            <?php echo htmlspecialchars($activity['activity_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select><br><br>
            </div>
            <button type="submit" class="btn">关联</button>
        </form>
    </div>
</body>

</html>