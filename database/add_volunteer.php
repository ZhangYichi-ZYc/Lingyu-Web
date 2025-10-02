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
  $name = $_POST['name'];
  $gender = $_POST['gender'];
  $birth_date = $_POST['birth_date'];
  $phone_number = $_POST['phone_number'];
  $notes = $_POST['notes'] ?? '';

  $stmt = $pdo->prepare('INSERT INTO volunteers (name, gender, birth_date, phone_number, notes) VALUES (?, ?, ?, ?, ?)');
  $stmt->execute([$name, $gender, $birth_date, $phone_number, $notes]);

  header('Location: dashboard.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta charset="UTF-8">
  <title>新增志愿者</title>
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
    <h1>新增志愿者</h1>
    <br />
    <form action="add_volunteer.php" method="post">
      <div class="form-group">
        <label for="name">姓名:</label>
        <input type="text" id="name" name="name" placeholder="在此输入姓名" required />
      </div><br />
      <div class="form-group gender-group">
        <label>性别：</label>
        <input type="radio" id="male" name="gender" value="男" />
        <label for="male">男</label>
        <input type="radio" id="female" name="gender" value="女" />
        <label for="female">女</label>
      </div><br />
      <div class="form-group">
        <label for="birth_date">出生日期:</label>
        <input type="date" id="birth_date" name="birth_date" required />
      </div><br />
      <div class="form-group">
        <label for="phone_number">手机号:</label>
        <input type="text" id="phone_number" name="phone_number" placeholder="在此输入手机号" required />
      </div><br />
      <div class="form-group">
        <label for="notes">备注:</label>
        <input type="text" id="notes" name="notes" placeholder="在此输入备注" />
      </div><br /><br />
      <button type="submit" class="btn">添加</button>
    </form>
  </div>
</body>

</html>