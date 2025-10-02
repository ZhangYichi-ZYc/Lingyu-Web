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
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $birth_date = $_POST['birth_date'];
    $phone_number = $_POST['phone_number'];
    $notes = $_POST['notes'] ?? '';

    $stmt = $pdo->prepare('UPDATE volunteers SET name = ?, gender = ?, birth_date = ?, phone_number = ?, notes = ? WHERE volunteer_id = ?');
    $stmt->execute([$name, $gender, $birth_date, $phone_number, $notes, $volunteer_id]);

    header('Location: dashboard.php');
    exit;
} else {
    $volunteer_id = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM volunteers WHERE volunteer_id = ?');
    $stmt->execute([$volunteer_id]);
    $volunteer = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <title>编辑志愿者</title>
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
        <h1>编辑志愿者信息</h1>
        <br>
        <form action="edit_volunteer.php" method="post">
            <div class="form-group">
                <input type="hidden" name="volunteer_id" value="<?php echo htmlspecialchars($volunteer['volunteer_id']); ?>">
                <label for="name">姓名:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($volunteer['name']); ?>" required>
            </div>
            <br>
            <div class="form-group gender-group">
                <label>性别：</label>
                <input type="radio" id="male" name="gender" value="男" <?php if ($volunteer['gender'] == '男') echo 'checked'; ?> />
                <label for="male">男</label>
                <input type="radio" id="female" name="gender" value="女" <?php if ($volunteer['gender'] == '女') echo 'checked'; ?> />
                <label for="female">女</label>
            </div>
            <br>
            <div class="form-group">
                <label for="birth_date">出生日期:</label>
                <input type="date" id="birth_date" name="birth_date" value="<?php echo htmlspecialchars($volunteer['birth_date']); ?>" required>
            </div>
            <br>
            <div class="form-group">
                <label for="phone_number">手机号:</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($volunteer['phone_number']); ?>" required>
            </div>
            <br>
            <div class="form-group">
                <label for="notes">备注:</label>
                <input type="text" id="notes" name="notes" value="<?php echo htmlspecialchars($volunteer['notes'] ?? ''); ?>">
            </div>
            <br><br>
            <button type="submit" class="btn">提交</button>
        </form>
    </div>
</body>

</html>